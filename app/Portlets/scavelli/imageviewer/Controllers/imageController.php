<?php

namespace App\Portlets\scavelli\imageviewer\Controllers;

use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Content\File;
use App\Models\Content\Service;

class imageController extends Controller
{

    private $rp;
    public $conf;
    public $pages;
    public $tags;
    public $vocabularies;
    public $portlet;
    public $tags_reg;
    public $cats_reg;
    public $selectOrder;
    public $listView;
    public $models;
    public $structures;



    public function __construct(RepositoryInterface $rp) {
        $this->rp = $rp->setModel(File::class);
        $this->conf = [];
        $this->models = $this->tags_reg = $this->cats_reg = null;
    }

    public function viewFile($slug)
    {
        $file = $this->rp->findBySlug($slug);
        return response()->file(public_path($file->getPath()));
    }

    /**
     * Mostra il web form per la configurazione della portlet
     * @param $portlet
     * @param $documentList
     * @return $this
     */
    public function configPortlet($portlet, $documentList) {
        $default = ['listView'=>'','scrolling'=>'','ord'=>0,'dir'=>0,'structure_id'=>0,'model_id'=>0,'comunication'=>$portlet->pivot->comunication];
        if(!empty($portlet->pivot->setting)) $this->conf = array_merge($default,json_decode($portlet->pivot->setting, true));


        // definizione della lista dei modelli
        //===============================================
        $service = $this->rp->setModel(Service::class)->where('class',File::class)->first();
        $structures = $this->rp->setModel('App\Models\Content\Structure')->where('service_id',$service->id)->where('status_id',1)->get();

        if (!empty($this->get('structure_id'))) {
            $structure = $this->rp->getModel()->find($this->get('structure_id'));
        } elseif($structures->count()>0) {
            $structure = $structures->first();
        }
        $this->structures = $structures->pluck('name','id')->toArray();

        if (isset($structure))
            $this->models = $structure->models->where('type_id',2)->pluck('name','id')->toArray();
        //===============================================


        $this->pages = $this->rp->setModel('App\Models\Content\Page')->where('status_id',1)->orderBy('name')->pluck('name','slug')->toArray();

        // definizione dei tags
        //===============================================
        $this->tags = $this->rp->setModel('App\Models\Content\Tag')->pluck();
        if (!empty($this->get('tags'))) {
            $and = "";
            foreach($this->get('tags') as $val) {
                $this->tags_reg .= $and.$val['tag'];
                $and =",";
            }
        }

        // definizione delle categorie
        //===============================================
        $this->vocabularies = $this->listVocabularies(File::class);

        if (!empty($this->get('categories'))) {
            $and = "";
            foreach($this->get('categories') as $val) {
                $this->cats_reg .= $and.$val['category'];
                $and =",";
            }
        }
        //===============================================

        $this->selectOrder = $this->selectOrder();
        $this->listView = $documentList->listView();

        return view('imageviewer::preferences')->with(['cList' => $this]);
    }

    /*
     *
     */
    private function listVocabularies($class) {
        $service = $this->rp->setModel(Service::class)->where('class',$class)->first();
        return $service->vocabularies;
    }

    /**
     * @return array
     * Definisco i valori dei campi select ord e dir
     */
    private function selectOrder() {
        return  ['ord'=>['Inserimento','Titolo','Data di Creazione','Data di Modifica','Visite'],'dir'=>['Ascendente','Discendente']];
    }

    public function get($key,$default=null) {
        return array_get($this->conf, $key, $default);
    }

    /**
     * Restituisce la lista dei modelli in formato Json relativi alla struttura passata come argomento
     * @param $id
     * @return mixed
     */
    public function listModels($id) {
        $structure = $this->rp->setModel('App\Models\Content\Structure')->find($id);
        return json_encode($structure->models->where('type_id',2)->pluck('name','id')->toArray());
    }

}