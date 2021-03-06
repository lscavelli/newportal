<?php

namespace App\Http\Controllers\Content;

use App\Services\listGenerates;
use App\Models\Content\Modelli;
use App\Models\Content\Structure;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\FormGenerates;
use App\Services\Helpers;
use Illuminate\Support\Facades\File;


    class ModelliController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Modelli')->setSearchFields(['name','description','content']);
    }

    /**
     * @param array $data
     * @param bool $onUpdate
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data,$onUpdate=false)   {
        $filter = 'unique:modelli';
        if ($onUpdate) { $filter = Rule::unique('modelli')->ignore($data['id']);}
        return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255',
        ]);
    }

    /**
     * Visualizza la lista dei modelli x struttura, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index($id, Request $request, listGenerates $list) {
        $structure = $this->rp->setModel(Structure::class)->find($id);
        $list->setPagination($this->rp->paginateArray($structure->models->toArray(),10,$request->page_a,'page_a'));
        return view('content.listModels')->with(compact('list','structure'));
    }

    /**
     * Mostra il form per la creazione del modello
     * @return \Illuminate\Contracts\View\View
     */
    public function create($strutturaId) {
        $modello = new Modelli(); $action = "Content\\ModelliController@store";
        $structure = $this->rp->setModel(Structure::class)->find($strutturaId);
        $listVariable = $this->listVariable($structure);
        $listWidgets = $this->rp->setModel('App\Models\Content\Widget')->where('service',$structure->service->class)->pluck();
        return view('content.editModel')->with(compact('modello','action','structure','listVariable','listWidgets'));
    }

    /**
     * Salva il modello dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $data = $request->all();
        $this->validator($data)->validate();
        $this->rp->create($data);
        return redirect()->route('models',['structure_id' => $request->structure_id])->withSuccess('Modello creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del Modello
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($structureId, $id) {
        $modello = $this->rp->find($id); $action = ["Content\\ModelliController@update",$id];
        $structure = $this->rp->setModel(new Structure())->find($structureId);
        $listVariable = $this->listVariable($structure);
        $listWidgets = $this->rp->setModel('App\Models\Content\Widget')->where('service',$structure->service->class)->pluck();
        $templates = ($modello->widget_id) ? $this->listView($modello->widget_id, false) : [];
        return view('content.editModel')->with(compact('modello','action','structure','listVariable','listWidgets','templates'));
    }

        /**
         * Aggiorna i dati nel DBm $id
         * @param Request $request
         * @return $this
         * @throws \Illuminate\Validation\ValidationException
         */
    public function update($id, Request $request)  {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data,true)->validate();
        if (is_null($data['widget_id'])) $data['template'] = null;
        if ($this->rp->update($id,$data)) {
            return redirect()->route('models',['structure_id' => $request->structure_id])->withSuccess('Modello aggiornato correttamente');
        }
    }

    /**
     * Cancella il modello - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($structureid,$id)  {
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Modello cancellato correttamente');
        }
        return redirect()->back();
    }

    private function listVariable($structure) {
        $listVariable = [
            ""=>"",
            "np_title"=>"Titolo",
            "np_data_creazione"=>"Data di creazione",
            "np_data_modifica"=>"Data di modifica",
            "np_categories"=>"Categorie",
            "np_tags"=>"Tag",
            "np_author_username"=>"Autore - username",
            "np_author_name"=>"Autore - name",
            "np_author_id"=>"Autore - id",
            "np_page"=>"Pagina corrente",
            "np_description"=>"Descrizione"];
        if(!empty($structure->service->content)) {
            $intService = json_decode($structure->service->content, true);
            $listVariable += $intService['varmodelli'];
        }
        $lv =(new FormGenerates($structure->content))->listLabel();
        $lv = array_map(function ($k,$v) { return array("np".str_replace("-","",$k)=>$v);},array_keys($lv),array_values($lv));
        $listVariable += array_collapse($lv);
        return $listVariable;
    }

    /**
     * duplica il modello
     * @param $id
     * @param Helpers $helpers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicates($id,Helpers $helpers) {
        $model = $this->rp->find($id);
        $clone = $model->replicate();
        $clone->name = $model->name."-".$helpers->makeCode();
        $clone->save();
        return redirect()->back();
    }

    /**
     * mostra le view (list) contenute nella directory view della widget
     * @return array
     * @throws ThemeException
     */
    public function listView($id,$json=true) {
        $widget = $this->rp->setModel('App\Models\Content\Widget')->find($id);
        $path = str_replace('\\', '/' ,$widget->path);
        $path = app_path().'/'.config("newportal.widgets.namespace").'/'.$path."/views";

        if (!is_dir($path)) {
            throw new ThemeException("la directory dei template non esiste");
        }
        $files = File::allFiles($path);
        $view = [""=>""];
        foreach ($files as $file) {
            $file = substr(basename($file), 0, -10); //remove .blade.php
            if (starts_with($file,'list')) {
                $view[$file] = $file;
            }
        }
        if ($json) return json_encode( $view );
        return $view;
    }

}
