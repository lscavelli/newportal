<?php

namespace App\Http\Controllers;

use App\Services\listGenerates;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;

class PermissionController extends Controller {

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Permission')->setSearchFields(['name','slug','description']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:permissions';
        if ($onUpdate) { $filter = Rule::unique('permissions')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'required|min:2|max:80',
            //'slug' => ['required','min:2','max:100','regex:/^[a-z0-9_.]+$/',$filter]
        ]);
    }

    /**
     * Visualizza la lista dei permessi, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $permissions = $this->repo->paginate($request);
        $list->setPagination($permissions);
        return view('users.listPermission')->with(compact('permissions','list'));
    }

    /**
     * Mostra il form per la creazione di un nuovo permesso
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $permission = new Permission();
        return view('users.editPermission')->with(compact('permission'));
    }

    /**
     * Salva il permesso nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $this->validator($data)->validate();
        $this->repo->create($data);
        return redirect('admin/permissions')->withSuccess('Permesso creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del permesso
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $permission = $this->repo->find($id);
        return view('users.editPermission')->with(compact('permission'));
    }

    /**
     * Aggiorna i dati del permesso nel DB
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)  {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        if ($this->repo->update($id,$data)) {
            return redirect('admin/permissions')->withSuccess('Permesso aggiornato correttamente');
        }
    }

    /**
     * Cancella il permesso - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)   {
        if ($this->repo->delete($id)) {
            return redirect()->back()->withSuccess('Permesso cancellato correttamente');
        }
    }

    /**
     * Restituisce la lista dei ruoli a cui è stato assegnato il permesso
     * @param $permissionId
     * @return mixed
     */
    public function listRoles($permissionId)  {
        return $this->repo->find($permissionId)->roles;
    }

    /**
     * Restituisce la lista dei gruppi a cui è stato assegnato il permesso
     * @param $permissionId
     * @return mixed
     */
    public function listGroups($permissionId)  {
        return $this->repo->find($permissionId)->groups;
    }

    /**
     * Restituisce la lista degli utenti a cui è stato assegnato il permesso
     * @param $permissionId
     * @return mixed
     */
    public function listUsers($permissionId)  {
        return $this->repo->find($permissionId)->users;
    }

    /**
     * Mostra il profilo del singolo permesso
     * @param $id
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id, Request $request, listGenerates $list) {
        $permission = $this->repo->find($id);
        $pag['nexid'] = $this->repo->next($id);
        $pag['preid'] = $this->repo->prev($id);
        $listUsers = new listGenerates($this->repo->paginateArray($permission->listUsers()->toArray(),10,$request->page_a,'page_a'));
        $listRoles = new listGenerates($this->repo->paginateArray($this->listRoles($id)->toArray(),10,$request->page_b,'page_b'));
        $listGroups = new listGenerates($this->repo->paginateArray($permission->listGroups()->toArray(),10,$request->page_c,'page_c'));
        return view('users.profilePermission')->with(compact('permission','listUsers','listRoles','listGroups','pag'));
    }
}
