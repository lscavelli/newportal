<?php

namespace App\Portlets\scavelli\contentlist\Controllers;

use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content\Content;
use App\Models\Content\Service;


class assetController extends Controller
{

    private $rp;


    public function __construct(RepositoryInterface $rp) {
        $this->rp = $rp->setModel('App\Models\Content\Content');
    }

    /**
     * Mostra il web form per la configurazione della portlet
     * @param $portlet
     * @param Request $request
     * @return string
     */
    public function configPortlet($portlet, Request $request) {
        $conf = ['inpage'=>'','viewList'=>'','scrolling'=>'','ord'=>0,'dir'=>0,'service'=>'','structure_id'=>0,'model_id'=>0,'comunication'=>$portlet->pivot->comunication];
        if(!empty($portlet->pivot->setting)) $conf = array_merge($conf,json_decode($portlet->pivot->setting, true));

        // definizione della lista dei modelli
        //===============================================
        $models = null;
        $services = $this->rp->setModel('App\Models\Content\Service')->where('id',1)->pluck('name','class')->toArray();
        $structures = $this->rp->setModel('App\Models\Content\Structure')->where('type_id',2)->where('status_id',1);
        if (!empty($conf['structure_id'])) {
            $structure = $this->rp->getModel()->find($conf['structure_id']);
        } elseif($structures->count()>0) {
            $structure = $structures->first();
        }

        $structures = $structures->pluck('name','id')->toArray();
        if (isset($structure))
            $models = $structure->models->where('type_id',2)->pluck('name','id')->toArray();
        //===============================================

        $pages = $this->rp->setModel('App\Models\Content\Page')->where('status_id',1)->orderBy('name')->pluck('name','slug')->toArray();

        // definizione dei tags
        //===============================================
        $tags = $this->rp->setModel('App\Models\Content\Tag')->pluck();
        $tags_reg = "";
        if (!empty($conf['tags'])) {
            $and = "";
            foreach($conf['tags'] as $val) {
                $tags_reg .= $and.$val['tag'];
                $and =",";
            }
        }

        // definizione delle categorie
        //===============================================
        $class = Content::class;
        if (!empty($conf['service'])) $class = $conf['service'];
        $vocabularies = $this->listVocabularies($class);

        $cats_reg = "";
        if (!empty($conf['categories'])) {
            $and = "";
            foreach($conf['categories'] as $val) {
                $cats_reg .= $and.$val['category'];
                $and =",";
            }
        }
        //===============================================

        $selectOrder = $this->selectOrder();

        return view('contentlist::preferences')->with(compact(
            'services',
            'structures',
            'models',
            'pages',
            'tags',
            'vocabularies',
            'conf',
            'portlet',
            'tags_reg',
            'cats_reg',
            'selectOrder'
        ));
    }

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