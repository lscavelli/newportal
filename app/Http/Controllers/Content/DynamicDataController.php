<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Libraries\listGenerates;
use App\Models\Content\DynamicDataList;
use App\Models\Content\Structure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;

class DynamicDataController extends Controller {

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Content\DynamicDataList')->setSearchFields(['name','description']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:dynamicdatalist';
        if ($onUpdate) { $filter = Rule::unique('dynamicdatalist')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255',
            'structure_id'=> 'required',
        ]);
    }

    /**
     * Visualizza la lista dei dati, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $list->setModel($this->repo->paginate($request));
        return \View::make('content.listDynamicData', compact('list'));
    }

    /**
     * Mostra il form per la creazione dei DDL
     * @return \Illuminate\Contracts\View\View
     */
    public function create()   {
        $dynamicData = new DynamicDataList(); $action = "Content\\DynamicDataController@store"; $structureOptions = [];
        return \View::make('content.editDynamicData', compact('dynamicData','action','structureOptions'));
    }

    /**
     * Salva i dati nel database dopo averli validati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['user_id'] = \Auth::user()->id; $data['username'] = \Auth::user()->username; $data['type_id'] = 2; // content structure
        $this->repo->create($data);
        return redirect()->route('ddl')->withSuccess('Strutura creata correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento dei dati
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $dynamicData = $this->repo->find($id);
        $structureOptions = [];
        if (!empty($dynamicData->structure_id)) $structureOptions = $this->repo->setModel(new Structure)->where("id", "=", $dynamicData->structure_id)->pluck()->toArray();
        $action = ["Content\\DynamicDataController@update",$id];
        return \View::make('content.editDynamicData', compact('dynamicData','action','structureOptions'));
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
            return redirect()->route('ddl')->withSuccess('La lista è stata aggiornata correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * Cancella il record - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->repo->delete($id)) {
            return redirect()->back()->withSuccess('Lista cancellata correttamente');
        }
        return redirect()->back();
    }

    public function structureRemoteData(Request $request) {
        //$array[0] = ['id'=>134,'text'=>'prova generale'];
        //$array[1] = ['id'=>135,'text'=>'altra prova'];
        //return '{"items": '. json_encode($array). '}';

        $q = $request->input('q');
        if (empty($q)) return '{"items": []}';
        $list = [];
        $structure = $this->repo->setModel(new Structure)
            ->where('name','like',"%$q%")
            ->orderBy('name')->get();

        foreach ($structure as $key => $value) {
            $list[$key]['id'] = $value->id;
            $list[$key]['text'] = $value->name;
        }

        return '{"items": '. json_encode($list) . '}';
    }

}
