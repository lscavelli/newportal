<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\Repository;
use App\Services\listGenerates;
use App\Repositories\RepositoryInterface;


/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Role')->setSearchFields(['name','slug','level','description']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:roles';
        if ($onUpdate) { $filter = Rule::unique('roles')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'required|min:2|max:80',
            //'slug' => ['required','min:2','max:100','regex:/^[a-z0-9_.]+$/',$filter]
        ]);
    }

    /**
     * Visualizza la lista dei ruoli, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list)   {
        $roles = $this->repo->paginate($request);
        $list->setModel($roles);
        return view('users.listRole', compact('roles','list'));
    }

    /**
     * Mostra il form per la creazione del nuovo ruolo
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $role = new Role();
        return view('users.editRole', compact('role'));
    }

    /**
     * Salva il ruolo nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $this->validator($data)->validate();
        $this->repo->create($data);
        return redirect('admin/roles')->withSuccess('Ruolo creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del ruolo
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id, Request $request) {
        $role = $this->repo->find($id);
        return view('users.editRole', compact('role'));
    }

    /**
     * Aggiorna i dati del ruolo nel DB
     * @param $id
     * @param Request $request
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)  {
        $data = $request->all();
        $data['id'] = $id;
        $this->validator($data,true)->validate();
        if ($this->repo->update($id,$data)) {
            return redirect('admin/roles')->withSuccess('Ruolo aggiornato correttamente');
        }
    }

    /**
     * Cancella il ruolo - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->repo->delete($id)) {
            return redirect()->back()->withSuccess('Ruolo cancellato correttamente');
        }
    }

    /**
     * Visualizzo la pagina per l'assegnazione dei permessi ai ruoli
     * @param $roleId
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignPerm($roleId, Request $request, listGenerates $list) {
        $role = $this->repo->find($roleId);
        $pag['nexid'] = $this->repo->next($roleId);
        $pag['preid'] = $this->repo->prev($roleId);
        // --- Permessi Assegnati
        $permAss = $this->listPermissions($roleId);
        $permAssArray = $permAss->toArray();
        $permissionAss = $this->repo->paginateArray($permAssArray,4,$request->page_a,'page_a');
        // --- Permessi ancora disponibili
        $permDispArray = $this->repo->setModel(new Permission())->all()->diff($permAss)->toArray();
        $permissionDis = $this->repo->paginateArray($permDispArray,4,$request->page_b,'page_b');
        return view('users.assignPermRole', compact('permissionAss','permissionDis','role','pag','list'));
    }

    /**
     * Assegna uno o più permessi al Ruolo
     * @param $roleId
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPerm($roleId, $permissions)  {
        $role = $this->repo->find($roleId);
        $permissionArray = is_array($permissions) ? $permissions : [$permissions];
        foreach ($permissionArray as $permId) {
            $this->repo->attach($role->permissions(),$permId);
        }
        return redirect()->back();
    }

    /**
     * Restituisce la lista dei permessi contenuti nel ruolo
     * @param $roleId
     * @return mixed
     */
    public function listPermissions($roleId)  {
        return $this->repo->find($roleId)->permissions;
    }

    /**
     * elimina uno o più permessi dal ruolo
     * @param $roleId
     * @param $permissions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delPerm($roleId, $permissions)  {
        $permissionArray = is_array($permissions) ? $permissions : [$permissions];
        $role = $this->repo->find($roleId);
        foreach ($permissionArray as $permId) {
            $this->repo->detach($role->permissions(),$permId);
        }
        return redirect()->back();
    }

    /**
     * svuota il ruolo
     * @param $roleId
     */
    public function delAllPerm($roleId) {
        $this->repo->detach($this->repo->find($roleId)->permissions());
    }

    /**
     * svuota tutti i ruoli
     */
    public function delAllPermFromAllRoles() {
        $roles = $this->repo->all();
        foreach ($roles as $role) {
            $this->delAllPerm($role->id);
        }
    }

    /**
     * restituisce il numero dei permessi contenuti nel ruolo
     * @param $roleId
     * @return array
     */
    public function countPerm($roleId)  {
        return $this->repo->find($roleId)->permissions()->count();
    }

    /**
     * Restituisce la lista dei gruppi a cui è stato assegnato il ruolo
     * @param $Id
     * @return mixed
     */
    public function listGroups($Id)  {
        return $this->repo->find($Id)->groups;
    }

    /**
     * Restituisce la lista degli utenti a cui è stato assegnato il ruolo
     * @param $Id
     * @return mixed
     */
    public function listUsers($Id)  {
        return $this->repo->find($Id)->users;
    }

    /**
     * Mostra il profilo del singolo ruolo
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Request $request) {
        $role = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        $listUsers = new listGenerates($this->repo->paginateArray($role->listUsers()->toArray(),10,$request->page_a,'page_a'));
        $listGroups = new listGenerates($this->repo->paginateArray($this->listGroups($id)->toArray(),10,$request->page_b,'page_b'));
        $listPermissions = new listGenerates($this->repo->paginateArray($this->listPermissions($id)->toArray(),10,$request->page_c,'page_c'));
        return view('users.profileRole', compact('role','listUsers','listGroups','listPermissions','pag'));
    }
}
