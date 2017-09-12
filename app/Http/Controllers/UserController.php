<?php

namespace App\Http\Controllers;

use App\Libraries\listGenerates;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use Illuminate\Validation\Rule;
use App\Libraries\Images;
use App\Models\Data\Country;
use App\Models\Data\City;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller {

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\User')->setSearchFields([
            'nome',
            'cognome',
            'note',
            'email']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:users'; $required = 'required|';
        if ($onUpdate) { $required = ''; $filter = Rule::unique('users')->ignore($data['id']);}
        return Validator::make($data, [
            'nome' => 'sometimes|required|min:3|max:255',
            'cognome' => 'sometimes|required|min:3|max:255',
            'email' => ['sometimes','required','email','max:255',$filter],
            'password' => $required.'sometimes|min:8|confirmed',
            'username' => ['sometimes','regex:/^[A-Za-z0-9_.]+$/','min:3','max:255',$filter],
        ]);
    }

    /**
     * Visualizza la lista degli utenti, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $users = $this->repo->paginate($request);
        $list->setModel($users);
        return \View::make('users.list', compact('users','list'));
    }

    /**
     * Mostra il form per l'aggiornamento dell'utente
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $user = $this->repo->find($id);
        $action = ["UserController@update", $id];
        $numOrgs = $this->listOrganizations($user)->count();
        $numGroups = $this->listGroups($user)->count();
        $countries = $this->repo->setModel(new Country)->orderBy('name')->pluck()->toArray();
        $cityOptions = [];
        if (!empty($user->city_id)) {
            $cityOptions = $this->repo->setModel(new City)->where("id", "=", $user->city_id)->pluck()->toArray();
        }
        return \View::make('users.edit', compact('user','action','numOrgs','numGroups','countries','cityOptions'));
    }

    /**
     * Aggiorna i dati dell'utente nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        if (!empty($data['data_nascita'])) {
            $data['data_nascita'] =Carbon::createFromFormat('d/m/Y', $data['data_nascita']);
        } else {$data['data_nascita'] = null;}
        if (!empty($data['password'])) $data['password'] = bcrypt($data['password']);
        if ($this->repo->update($id,$data)) {
            return redirect()->back()->withSuccess('Utente aggiornato correttamente');
        } else {
            return redirect()->back()->withErrors('Si è verificato un  errore');
        }
    }

    /**
     * Mostra il form per la creazione del nuovo utente
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $user = new User(); $action = "UserController@store"; $cityOptions = [];
        $countries = $this->repo->setModel(new Country)->orderBy('name')->pluck()->toArray();
        return \View::make('users.edit', compact('user','action','countries','cityOptions'));
    }

    /**
     * Salva l'utente nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['password'] = bcrypt($data['password']);
        $this->repo->create($data);
        return redirect()->route('users')->withSuccess('Utente creato correttamente.');
    }

    /**
     * Cancella l'utente - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)   {
        if ($this->repo->delete($id)) {
            return redirect()->back()->withSuccess('Utente cancellato correttamente');
        }
        return redirect()->back();
    }

    /**
     * Restituisce la lista delle attività (singolo utente o generale)
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function listActivity(Request $request, $id = null, $page='page_a') {
        $queryBuilder = null;
        $this->repo->setModel('\App\Models\Activity')->setSearchFields('description');
        if (!is_null($id)) $this->repo->where('user_id','=',$id);
        $activity = $this->repo->paginateArraySearch($request,$page);
        return $activity;
    }

    /**
     * Visualizza la lista delle attività
     * @param Request $request
     * @param listGenerates $list
     * @param null $id
     * @return \Illuminate\Contracts\View\View
     */
    public function showActivity(Request $request, listGenerates $list, $id = null) {
        $nameUser = null;
        if (!is_null($id)) $nameUser = $this->repo->find($id)->name;
        $activity = $this->listActivity($request,$id);
        $list->setModel($activity); $list->setPrefixPage('_a');
        return view('users.activity')->with(compact('activity','list','nameUser'));
    }

    /**
     * restituisce la lista delle sessioni attive
     * @param Request $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listSessions(Request $request, $id = null, $page='page_a') {
        $lifetime = 300; // 5 min.
        $this->repo->setModel('\App\Models\Session')->where('last_activity', '>=', Carbon::now()->getTimestamp() - $lifetime);
        if (!is_null($id)) $this->repo->where('user_id','=',$id);
        return $this->repo->paginateArraySearch($request,$page);
    }

    /**
     * visualizza la lista delle sessioni attive
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function showSessions(Request $request, listGenerates $list, $id = null) {
        $sessions = $this->listSessions($request,$id);
        $list->setModel($sessions); $list->setPrefixPage('_a');
        return \View::make('users.sessions', compact('sessions','list'));
    }

    /**
     * Rimuove la sessione selezionata
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sessionDestroy($id,$session_id=null) {
        if (!empty($session_id)) $id = $session_id;
        $modelSession = new \App\Models\Session;
        //$user_id = $this->repo->find($id,$modelSession)->user_id;
        if ($this->repo->delete($id,$modelSession)) {
            //la colonna non viene cancellata perchè è inserita fra i campi hidden del model
            // per motivi di sicurezza.
            //$this->repo->update($user_id, ['remember_token' => null]);
            return redirect()->back()->withSuccess('Sessione cancellata correttamente');
        }
        return redirect()->back();
    }

    /**
     * Visualizzo la pagina per l'assegnazione dei permessi
     * @param $id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignPerm($id, Request $request, listGenerates $list) {
        $user = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        // --- Permessi Assegnati
        $ass = $this->listPermissions($user);
        $permAssArray = $ass->toArray();
        $permissionAss = $this->repo->paginateArray($permAssArray,4,$request->page_a,'page_a');
        // --- Permessi ancora disponibili
        $permDispArray = $this->repo->setModel(new Permission())->all()->diff($ass)->toArray();
        $permissionDis = $this->repo->paginateArray($permDispArray,4,$request->page_b,'page_b');
        return \View::make('users.assignPermUser', compact('permissionAss','permissionDis','user','pag','list'));
    }

    /**
     * Restituisce la lista dei permessi assegnati
     * @param $user
     * @return mixed
     */
    public function listPermissions($user)  {
        return $user->permissions;
    }

    /**
     * Assegna uno o più permessi
     * @param $id
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPerm($id, $permissions)  {
        $user = $this->repo->find($id);
        $permissionArray = is_array($permissions) ? $permissions : [$permissions];
        foreach ($permissionArray as $permId) {
            $this->repo->attach($user->permissions(),$permId);
        }
        return redirect()->back();
    }

    /**
     * elimina uno o più permessi
     * @param $id
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delPerm($id, $permissions)  {
        $permissionArray = is_array($permissions) ? $permissions : [$permissions];
        $user = $this->repo->find($id);
        foreach ($permissionArray as $permId) {
            $this->repo->detach($user->permissions(),$permId);
        }
        return redirect()->back();
    }

    /**
     * Visualizzo la pagina per l'assegnazione dei ruoli
     * @param $id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignRole($id, Request $request, listGenerates $list) {
        $user = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        // --- Ruoli Assegnati
        $ass = $this->listRoles($user);
        $roleAssArray = $ass->toArray();
        $roleAss = $this->repo->paginateArray($roleAssArray,4,$request->page_a,'page_a');
        // --- Ruoli ancora disponibili
        $roleDispArray = $this->repo->setModel(new Role())->all()->diff($ass)->toArray();
        $roleDis = $this->repo->paginateArray($roleDispArray,4,$request->page_b,'page_b');
        return \View::make('users.assignRoleUser', compact('roleAss','roleDis','user','pag','list'));
    }

    /**
     * Restituisce la lista dei ruoli assegnati
     * @param $user
     * @return mixed
     */
    public function listRoles($user)  {
        return $user->roles;
    }

    /**
     * Assegna uno o più ruoli
     * @param $id
     * @param $roles
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addRole($id, $roles)  {
        $user = $this->repo->find($id);
        $roleArray = is_array($roles) ? $roles : [$roles];
        foreach ($roleArray as $roleId) {
            $this->repo->attach($user->roles(),$roleId);
        }
        return redirect()->back();
    }

    /**
     * elimina uno o più ruoli
     * @param $id
     * @param $roles
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delRole($id, $roles)  {
        $roleArray = is_array($roles) ? $roles : [$roles];
        $user = $this->repo->find($id);
        foreach ($roleArray as $roleId) {
            $this->repo->detach($user->roles(),$roleId);
        }
        return redirect()->back();
    }

    /**
     * Lista dei gruppi di cui fa parte l'utente
     * @param $user
     * @return mixed
     */
    public function listGroups($user) {
        return $user->groups;
    }

    /**
     * Lista delle organizzazioni di cui fa parte l'utente
     * @param $user
     * @return mixed
     */
    public function listOrganizations($user) {
        return $user->organizations;
    }

    /**
     * Mostra il profilo dell'utente
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function profile($id, Request $request) {
        $user = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        $listGroups = new listGenerates($this->repo->paginateArray($this->listGroups($user)->toArray(),10,$request->page_a,'page_a'));
        $listOrganizations = new listGenerates($this->repo->paginateArray($this->listOrganizations($user)->toArray(),10,$request->page_b,'page_b'));
        $listRoles = new listGenerates($this->repo->paginateArray($user->listRoles()->toArray(),10,$request->page_c,'page_c'));
        $listPermissions = new listGenerates($this->repo->paginateArray($user->listPermissions()->toArray(),10,$request->page_d,'page_d'));
        $listActivity = (new listGenerates($this->listActivity($request, $id, 'page_e')))->setPrefixPage('_e');
        $listSessions = (new listGenerates($this->listSessions($request, $id, 'page_f')))->setPrefixPage('_f');
        return \View::make('users.profile', compact(
            'user',
            'listGroups',
            'listOrganizations',
            'listRoles',
            'listPermissions',
            'pag',
            'listActivity',
            'listSessions'));
    }

    /**
     * Imposta l'avatar dell'User
     * @param $id
     * @param Images $avatar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setAvatar($id, Images $avatar, Request $request) {
        $user = $this->repo->find($id);
        $data['avatar'] = null;
        if ($request->has('setAvatarDefaut')) {
            $avatar->delFile($user->avatar);
        }
        if ($request->file('image')) {
            $data['avatar'] = $avatar->uploadImage($user->avatar)[0];
        }
        $this->repo->update($id,$data);
        return back()->withSuccess('Avatar caricato correttamente');
    }

    public function citiesRemoteData(Request $request) {
        //$array[0] = ['id'=>134,'text'=>'prova generale'];
        //$array[1] = ['id'=>135,'text'=>'altra prova'];
        //return '{"items": '. json_encode($array). '}';

        $q = $request->input('q');
        if (empty($q)) return '{"items": []}';
        $list = [];
        $cities = $this->repo->setModel(new City)
                    ->where('name','like',"%$q%")
                    ->orderBy('name')->get();

        foreach ($cities as $key => $value) {
            $list[$key]['id'] = $value->id;
            $list[$key]['text'] = $value->name;
        }

        return '{"items": '. json_encode($list) . '}';
    }

}
