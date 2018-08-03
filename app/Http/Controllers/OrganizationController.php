<?php

namespace App\Http\Controllers;

use App\Models\User_organization;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;
use App\Services\listGenerates;
use App\Models\User;

/**
 * Class OrganizationController
 * @package App\Http\Controllers
 */
class OrganizationController extends Controller
{
    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Organization')->setSearchFields(['id','name']);
    }

    /**
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            'name' => 'required|min:2|max:255'
        ]);
    }

    /**
     * Visualizza la lista delle organizzazioni, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list, User_organization $user_organization)   {
        $organizations = $this->repo->paginate($request);
        $list->setModel($organizations);
        $user_organization = $this->repo->get($user_organization);
        return view('users.listOrganization', compact('organizations','list','user_organization'));
    }

    /**
     * Mostra il form per la creazione di una nuova Organizzazione
     * @return \Illuminate\Contracts\View\View
     */
    public function create() {
        $organization = new Organization(); $action = "OrganizationController@store";
        $selectOrg = $this->repo->optionsSel();
        return view('users.editOrganization', compact('organization','action','selectOrg'));
    }

    /**
     * Salva l'organizzazione nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ? $data['parent_id'] : null;
        $data['user_id'] = auth()->user()->id; $data['username'] = auth()->user()->username;
        $this->validator($data)->validate();
        $this->repo->create($data);
        return redirect('admin/organizations')->withSuccess('Organizzazione creata correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento dell'organizzazione
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $organization = $this->repo->find($id);
        $selectOrg = $this->repo->optionsSel($id);
        return view('users.editOrganization', compact('organization','selectOrg'));
    }

    /**
     * Aggiorna i dati dell'organizzazione nel DB
     * @param $id
     * @param Request $request
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $data['id'] = $id;
        $data['parent_id'] = $data['parent_id'] ?: null;
        $this->validator($data,true)->validate();
        if ($this->repo->update($id,$data)) {
            return redirect('admin/organizations')->withSuccess('Organizzazione aggiornata correttamente');
        }
    }

    /**
     * Cancella l'irganizzazione - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->repo->delete($id)) {
            return redirect('admin/organizations')->withSuccess('Organizzazione cancellata correttamente');
        }
    }

    /**
     * Visualizzo la pagina per l'assegnazione delle filiali
     * @param $organizationId
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignFilial($organizationId, Request $request, listGenerates $list) {
        $organization = $this->repo->find($organizationId);
        $pag['nexid'] = $this->repo->next($organizationId);
        $pag['preid'] = $this->repo->prev($organizationId);
        // --- Assegnati
        $ass = $this->listFilial($organizationId);
        $assArray = $ass->toArray();
        $filialAss = $this->repo->paginateArray($assArray,4,$request->page_a,'page_a');
        // --- Ancora disponibili
        if (!is_null($organization->parent_id)) {
            $parentRoot = $this->repo->getParentRoot($organization->parent_id);
            $this->repo->where('id','<>', $parentRoot->id);
        }
        $this->repo->where('id','<>',$organizationId);
        $dispArray = $this->repo->whereNull('parent_id')->get()->diff($ass)->toArray();
        $filialDis = $this->repo->paginateArray($dispArray,4,$request->page_b,'page_b');
        return view('users.assignFilialOrganization', compact('filialAss','filialDis','organization','pag','list'));
    }

    /**
     * Assegna una o più organizzazioni
     * @param $organizationId
     * @param $filialId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFilial($organizationId, $filialId)  {
        $filial = $this->repo->find($filialId);
        $organization = $this->repo->find($organizationId);
        //$organization->children()->save($filial); // imposta il figlio
        //$filial->parent()->associate($organization); // imposta il genitore
        //$filial->save();
        $this->repo->associate($filial->parent(),$organization);
        return redirect()->back();
    }

    /**
     * Restituisce la lista delle organizzazioni children
     * @param $organizationId
     * @return mixed
     */
    public function listFilial($organizationId)  {
        return $this->repo->find($organizationId)->children;
    }

    /**
     * Elimina il parent dalla filiale
     * @param $filialId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delFilial($filialId)  {
        $filial = $this->repo->find($filialId);
        $this->repo->dissociate($filial->parent());
        return redirect()->back();
    }

    /**
     * Visualizzo la pagina per l'assegnazione degli utenti
     * @param $organizationId
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function assignUser($organizationId, Request $request, listGenerates $list) {
        $organization = $this->repo->find($organizationId);
        $pag['nexid'] = $this->repo->next($organizationId);
        $pag['preid'] = $this->repo->prev($organizationId);
        // --- Utenti Assegnati
        $ass = $this->listUsers($organizationId);
        $usersAssArray = $ass->toArray();
        $usersAss = $this->repo->paginateArray($usersAssArray,10,$request->page_a,'page_a');
        // --- Utenti ancora disponibili
        $usersDisArray = $this->repo->setModel(new User())->all()->diff($ass)->toArray();
        $usersDis = $this->repo->paginateArray($usersDisArray,10,$request->page_b,'page_b');

        return view('users.assignUserOrganization', compact('usersAss','usersDis','organization','pag','list'));
    }

    /**
     * Assegna uno o più utenti all'organizzazione
     * @param $organizationId
     * @param $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUser($organizationId, $users)  {
        $organization = $this->repo->find($organizationId);
        $usersArray = is_array($users) ? $users : [$users];
        foreach ($usersArray as $userId) {
            $this->repo->attach($organization->users(),$userId);
        }
        return redirect()->back();
    }

    /**
     * Restituisce la lista degli utenti assegnati all'organizzazione
     * @param $organizationId
     * @return mixed
     */
    public function listUsers($organizationId)  {
        return $this->repo->find($organizationId)->users;
    }

    /**
     * Elimina uno o più utenti dall'organizzazione
     * @param $organizationId
     * @param $users
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delUser($organizationId, $users)  {
        $usersArray = is_array($users) ? $users : [$users];
        $organization = $this->repo->find($organizationId);
        foreach ($usersArray as $userId) {
            $this->repo->detach($organization->users(),$userId);
        }
        return redirect()->back();
    }

    /**
     * svuota l'organizzazione dagli utenti
     * @param $organizationId
     */
    public function delAllUsers($organizationId) {
        $this->repo->detach($this->repo->find($organizationId)->users());
    }

    /**
     * svuota tutte le organizzazioni dagli utenti
     */
    public function delAllUsersFromAllOrganizations() {
        $organizations = $this->repo->all();
        foreach ($organizations as $organization) {
            $this->delAllUsers($organization->id);
        }
    }

    /**
     * restituisce il numero degli utenti assegnati all'organizzazione
     * @param $organizationId
     * @return array
     */
    public function countUsers($organizationId)  {
        return $this->repo->find($organizationId)->users()->count();
    }

    /**
     * Mostra il profilo dell'organizzazione
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id, Request $request) {
        $organization = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        $listUsers = new listGenerates($this->repo->paginateArray($this->listUsers($id)->toArray(),10,$request->page_a,'page_a'));
        $listFilials = new listGenerates($this->repo->paginateArray($this->listFilial($id)->toArray(),10,$request->page_b,'page_b'));
        $graphorg = $this->repo->whereNull('parent_id')->get();
        $titleGraph = "Rappresentazione grafica dell'organizzazione";
        return view('users.profileOrganization', compact('organization','listUsers','listFilials','pag','titleGraph','graphorg'));
    }

    public function treeViewOrg() {
        $organizations = $this->repo->whereNull('parent_id')->get();
        $title = "Rappresentazione grafica dell'organizzazione";
        return view('users.treeViewOrganization', compact('organizations','title'));
    }


}
