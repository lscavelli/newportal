<?php

namespace App\Http\Controllers;

use App\Services\listGenerates;
use App\Models\Content\Structure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;
use App\Models\Content\Service;

class StructureController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Structure')->setSearchFields(['name','description','content']);
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
        $list->setModel($this->rp->paginate($request));
        $optionsSel = $this->rp->setModel(Service::class)->pluck()->mapWithKeys(function ($val, $key) {
            return ["structure/service/".$key."/create" => $val];
        });
        if(count($optionsSel)<1) {
            return redirect('admin/dashboard')->withErrors('Non ci sono servizi attivi.');
        }
        return view('content.listStructure', compact('list','optionsSel'));
    }

    /**
     * Mostra il form per la creazione delle strutture
     * @return \Illuminate\Contracts\View\View
     */
    public function create($service_id)   {
        $structure = new Structure();
        $service = $this->rp->setModel(Service::class)->where('id',$service_id)->first();
        return view('content.editStructure', compact('structure','service'));
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
        $data['user_id'] = auth()->user()->id; $data['username'] = auth()->user()->username; // content structure
        $this->rp->create($data);
        return redirect('admin/structure')->withSuccess('Strutura creata correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento della struttura
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $structure = $this->rp->find($id);
        $service = $structure->service;
        return view('content.editStructure', compact('structure','service'));
    }

    /**
     * Aggiorna i dati nel DB
     * @param $id
     * @param Request $request
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)  {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('admin/structure')->withSuccess('Struttura aggiornata correttamente');
        }
    }

    /**
     * Cancella la struttura - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Struttura cancellata correttamente');
        }
        return redirect()->back();
    }

}
