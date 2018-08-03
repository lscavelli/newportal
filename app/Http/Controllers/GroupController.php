<?php

namespace App\Http\Controllers;

use App\Services\listGenerates;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User_group;
use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;

/**
 * Class groupController
 * @package App\Http\Controllers
 */
class GroupController extends Controller
{
    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Group')->setSearchFields(['name','slug','description']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:groups';
        if ($onUpdate) { $filter = Rule::unique('groups')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'required|min:2|max:80',
            //'slug' => ['required','min:2','max:100','regex:/^[a-z0-9_.]+$/',$filter]
        ]);
    }

    /**
     * Visualizza la lista dei gruppi, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request,User_group $user_group)   {
        $groups = $this->repo->paginate($request);
        $list = new \App\Services\listGenerates($groups);
        $user_group = $this->repo->get($user_group); // for column count user
        return view('users.listGroup', compact('groups','list','user_group'));
    }

    /**
     * Mostra il form per la creazione del nuovo gruppo
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $group = new Group();
        return view('users.editGroup', compact('group'));
    }

    /**
     * Salva il gruppo nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $this->validator($data)->validate();
        $this->repo->create($data);
        return redirect('admin/groups')->withSuccess('Gruppo creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del gruppo
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $group = $this->repo->find($id);
        return view('users.editGroup', compact('group'));
    }

    /**
     * Aggiorna i dati del gruppo nel DB
     * @param $id
     * @param Request $request
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $data['id'] = $id;
        $this->validator($data,true)->validate();
        if ($this->repo->update($id,$data)) {
            return redirect('admin/groups')->withSuccess('Gruppo aggiornato correttamente');
        }
    }

    /**
     * Cancella il gruppo - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->repo->delete($id)) {
            return redirect('admin/groups')->withSuccess('Gruppo cancellato correttamente');
        }
    }

    /**
     * Visualizzo la pagina per l'assegnazione degli utenti ai gruppi
     * @param $groupId
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function assignUser($groupId, Request $request, listGenerates $list) {
        $group = $this->repo->find($groupId);
        $pag['nexid'] = $this->repo->next($groupId);
        $pag['preid'] = $this->repo->prev($groupId);
        // --- Utenti Assegnati
        $ass = $this->listUsers($groupId);
        $usersAssArray = $ass->toArray();
        $usersAss = $this->repo->paginateArray($usersAssArray,5,$request->page_a,'page_a');
        // --- Utenti ancora disponibili
        $usersDisArray = $this->repo->setModel(new User())->all()->diff($ass)->toArray();
        $usersDis = $this->repo->paginateArray($usersDisArray,5,$request->page_b,'page_b');

        return view('users.assignUserGroup', compact('usersAss','usersDis','group','pag','list'));
    }

    /**
     * Assegna uno o più utenti al gruppo
     * @param $groupId
     * @param $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUser($groupId, $users)  {
        $group = $this->repo->find($groupId);
        $usersArray = is_array($users) ? $users : [$users];
        foreach ($usersArray as $userId) {
            $this->repo->attach($group->users(),$userId);
        }
        return redirect()->back();
    }

    /**
     * Restituisce la lista degli utenti assegnati al gruppo
     * @param $groupId
     * @return mixed
     */
    public function listUsers($groupId)  {
        return $this->repo->find($groupId)->users;
    }

    /**
     * elimina uno o più utenti dal gruppo
     * @param $groupId
     * @param $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delUser($groupId, $users)  {
        $usersArray = is_array($users) ? $users : [$users];
        $group = $this->repo->find($groupId);
        foreach ($usersArray as $userId) {
            $this->repo->detach($group->users(),$userId);
        }
        return redirect()->back();
    }

    /**
     * rimuove tutti gli utenti
     * @param $groupId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delAllUsers($groupId) {
        $this->repo->detach($this->repo->find($groupId)->users());
        return redirect()->back();
    }

    /**
     * svuota tutti i gruppi
     */
    public function delAllUsersFromAllGroups() {
        $groups = $this->repo->all();
        foreach ($groups as $group) {
            $this->delAllUsers($group->id);
        }
    }

    /**
     * restituisce il numero degli utenti assegnati al gruppo
     * @param $groupId
     * @return array
     */
    public function countUsers($groupId)  {
        return $this->repo->find($groupId)->users()->count();
    }

    /**
     * Visualizzo la pagina per l'assegnazione dei permessi
     * @param $Id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignPerm($Id, Request $request, listGenerates $list) {
        $group = $this->repo->find($Id);
        $pag['nexid'] = $this->repo->next($Id);
        $pag['preid'] = $this->repo->prev($Id);
        // --- Permessi Assegnati
        $permAss = $this->listPermissions($Id);
        $permAssArray = $permAss->toArray();
        $permissionAss = $this->repo->paginateArray($permAssArray,4,$request->page_a,'page_a');
        // --- Permessi ancora disponibili
        $permDispArray = $this->repo->setModel(new Permission())->all()->diff($permAss)->toArray();
        $permissionDis = $this->repo->paginateArray($permDispArray,4,$request->page_b,'page_b');
        return view('users.assignPermGroup', compact('permissionAss','permissionDis','group','pag','list'));
    }

    /**
     * Restituisce la lista dei permessi assegnati
     * @param $Id
     * @return mixed
     */
    public function listPermissions($Id)  {
        return $this->repo->find($Id)->permissions;
    }

    /**
     * Assegna uno o più permessi
     * @param $Id
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPerm($Id, $permissions)  {
        $group = $this->repo->find($Id);
        $permissionArray = is_array($permissions) ? $permissions : [$permissions];
        foreach ($permissionArray as $permId) {
            $this->repo->attach($group->permissions(),$permId);
        }
        return redirect()->back();
    }

    /**
     * elimina uno o più permessi
     * @param $Id
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delPerm($Id, $permissions)  {
        $permissionArray = is_array($permissions) ? $permissions : [$permissions];
        $group = $this->repo->find($Id);
        foreach ($permissionArray as $permId) {
            $this->repo->detach($group->permissions(),$permId);
        }
        return redirect()->back();
    }

    /**
     * Visualizzo la pagina per l'assegnazione dei ruoli
     * @param $Id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignRole($Id, Request $request, listGenerates $list) {
        $group = $this->repo->find($Id);
        $pag['nexid'] = $this->repo->next($Id);
        $pag['preid'] = $this->repo->prev($Id);
        // --- Ruoli Assegnati
        $ass = $this->listRoles($Id);
        $roleAssArray = $ass->toArray();
        $roleAss = $this->repo->paginateArray($roleAssArray,4,$request->page_a,'page_a');
        // --- Ruoli ancora disponibili
        $roleDispArray = $this->repo->setModel(new Role())->all()->diff($ass)->toArray();
        $roleDis = $this->repo->paginateArray($roleDispArray,4,$request->page_b,'page_b');
        return view('users.assignRoleGroup', compact('roleAss','roleDis','group','pag','list'));
    }

    /**
     * Restituisce la lista dei ruoli assegnati
     * @param $Id
     * @return mixed
     */
    public function listRoles($Id)  {
        return $this->repo->find($Id)->roles;
    }

    /**
     * Assegna uno o più ruoli
     * @param $Id
     * @param $roles
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addRole($Id, $roles)  {
        $group = $this->repo->find($Id);
        $roleArray = is_array($roles) ? $roles : [$roles];
        foreach ($roleArray as $roleId) {
            $this->repo->attach($group->roles(),$roleId);
        }
        return redirect()->back();
    }

    /**
     * elimina uno o più ruoli
     * @param $Id
     * @param $roles
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delRole($Id, $roles)  {
        $roleArray = is_array($roles) ? $roles : [$roles];
        $group = $this->repo->find($Id);
        foreach ($roleArray as $roleId) {
            $this->repo->detach($group->roles(),$roleId);
        }
        return redirect()->back();
    }

    /**
     * Mostra il profilo del gruppo
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id, Request $request) {
        $group = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        $listUsers = new listGenerates($this->repo->paginateArray($this->listUsers($id)->toArray(),10,$request->page_a,'page_a'));
        $listRoles = new listGenerates($this->repo->paginateArray($this->listRoles($id)->toArray(),10,$request->page_b,'page_b'));
        $listPermissions = new listGenerates($this->repo->paginateArray($group->listPermissions()->toArray(),10,$request->page_c,'page_c'));
        return view('users.profileGroup', compact('group','listUsers','listRoles','listPermissions','pag'));
    }
}