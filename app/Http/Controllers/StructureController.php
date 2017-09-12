<?php

namespace App\Http\Controllers;

use App\Libraries\listGenerates;
use App\Models\Content\Structure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;

class StructureController extends Controller {

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Content\Structure')->setSearchFields(['name','description','content']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:structure';
        if ($onUpdate) { $filter = Rule::unique('structure')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255',
        ]);
    }

    /**
     * Visualizza la lista delle strutture, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $list->setModel($this->repo->paginate($request));
        return \View::make('content.listStructure', compact('list'));
    }

    /**
     * Mostra il form per la creazione delle strutture
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $structure = new Structure(); $action = "StructureController@store";
        return \View::make('content.editStructure', compact('structure','action'));
    }

    /**
     * Salva la struttura nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['user_id'] = \Auth::user()->id; $data['username'] = \Auth::user()->username; $data['type_id'] = 2; // content structure
        $this->repo->create($data);
        return redirect()->route('structure')->withSuccess('Strutura creata correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento della struttura
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $structure = $this->repo->find($id); $action = ["StructureController@update",$id];
        return \View::make('content.editStructure', compact('structure','action'));
    }

    /**
     * Aggiorna i dati nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        if ($this->repo->update($id,$data)) {
            return redirect()->route('structure')->withSuccess('Struttura aggiornata correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * Cancella la struttura - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->repo->delete($id)) {
            return redirect()->back()->withSuccess('Struttura cancellata correttamente');
        }
        return redirect()->back();
    }

}
