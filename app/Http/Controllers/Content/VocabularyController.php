<?php

namespace App\Http\Controllers\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Content\Vocabulary;
use Illuminate\Support\Collection;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Services\listGenerates;

/**
 * Class VocabularyController
 * @package App\Http\Controllers
 */
class VocabularyController extends Controller
{
    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Vocabulary')->setSearchFields(['id','name','description']);
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
     * Visualizza la lista delle vocabolari, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list)   {
        $Vocabularies = $this->rp->paginate($request);
        $list->setModel($Vocabularies);
        return view('content.listVocabularies')->with(compact('Vocabularies','list'));
    }

    /**
     * Mostra il form per la creazione di un nuovo vocabolario
     * @return \Illuminate\Contracts\View\View
     */
    public function create() {
        $vocabulary = new Vocabulary();
        $defaults = [['id'=>0,'pivot'=>['type_order'=>0,'type_dir'=>0,'required'=>1]]];
        $selectord = $this->selectOrder();
        $services = ["all"=>'Tutti i servizi'];
        $servicesList = $this->listServices();
        if (is_array( $servicesList) && count( $servicesList)>0) {
            $services += $servicesList;
            return view('content.editVocabulary')->with(compact('vocabulary','selectord','services','defaults'));
        } else {
            return redirect('admin/vocabularies')->withErrors('Non ci sono servizi attivi.');
        }
    }

    /**
     * Salva il vocabolario nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $this->validator($data)->validate();
        $vocabulary = $this->rp->create($data);
        $this->insertParamService($data,$vocabulary);
        return redirect('admin/vocabularies')->withSuccess('Vocabolario creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $vocabulary = $this->rp->find($id);
        $defaults = $vocabulary->services->toArray();
        $selectord = $this->selectOrder();
        $services = ["all"=>'Tutti i servizi'];
        $services += $this->listServices();
        return view('content.editVocabulary')->with(compact('vocabulary','selectord','services','defaults'));
    }

    /**
     * Aggiorna i dati nel DB
     * @param $id
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            $vocabulary = $this->rp->find($id);
            $this->delParamService($vocabulary);
            $this->insertParamService($data,$vocabulary);
            return redirect('admin/vocabularies')->withSuccess('Vocabolario aggiornato correttamente');
        }
    }

    /**
     * Cancella la riga - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Vocabolario cancellato correttamente');
        }
        return redirect()->back();
    }

    /**
     * @return array
     * Definisco i valori dei select
     */
    private function selectOrder() {
        return  ['ord'=>['Titolo','Data di Creazione','Data di Modifica'],'dir'=>['Ascendente','Discendente'],'req'=>['Si','No']];
    }

    /**
     * @return mixed
     * restituisce la lista dei servizi attivi in array
     */
    private function listServices() {
        return $this->rp->setModel('App\Models\Content\Service')->pluck()->toArray();
    }

    /**
     * restituisce le impostazioni di "All Service" in caso sia selezionato
     * @param $data
     * @return array
     */
    private function getAllParamService($data) {
        for($i=0; $i<count($data['services']); $i++){
            if ($data['services'][$i]=="all") {
                return [
                    'type_order'=>$data['type_order'][$i],
                    'type_dir'=>$data['type_dir'][$i],
                    'required'=>$data['required'][$i],
                ];
            }
        }
    }

    /**
     * inserisce nella tabella pivot i dati necessari
     * @param $data
     * @param $vocabulary
     */
    private function insertParamService($data,$vocabulary) {
        $setAll = null;
        if (in_array("all",$data['services'])) {
            $setAll = $this->getAllParamService($data);
            $data['services'] = array_keys($this->listServices());
        }
        for($i=0; $i<count($data['services']); $i++){
            if ($setAll) {
                $setting = $setAll;
            } else {
                $setting = [
                    'type_order'=>$data['type_order'][$i],
                    'type_dir'=>$data['type_dir'][$i],
                    'required'=>$data['required'][$i],
                ];
            }
            $this->rp->attach($vocabulary->services(),$data['services'][$i],$setting);
        }
    }

    /**
     * cancella i dati della pivot
     * @param $vocabulary
     */
    private function delParamService($vocabulary) {
        $this->rp->detach($vocabulary->services());
    }




}
