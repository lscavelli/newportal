<?php

namespace App\Widgets\scavelli\navigationcat\Controllers;

use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content\Content;
use App\Models\Content\Service;


class categoryController extends Controller
{

    private $rp;


    public function __construct(RepositoryInterface $rp) {
        $this->rp = $rp->setModel('App\Models\Content\Vocabulary');
    }

    /**
     * Mostra il web form per la configurazione della widget
     * @param $widget
     * @param Request $request
     * @return string
     */
    public function configWidget($widget, Request $request) {
        $conf = ['inpage'=>'','ord'=>0,'dir'=>0,'service'=>'','comunication'=>$widget->pivot->comunication];
        if(!empty($widget->pivot->setting)) $conf = array_merge($conf,json_decode($widget->pivot->setting, true));


        $pages = $this->rp->setModel('App\Models\Content\Page')->where('status_id',1)->orderBy('name')->pluck('name','slug')->toArray();
        $services = $this->rp->setModel('App\Models\Content\Service')->where('id',1)->pluck('name','class')->toArray();

        // definizione delle categorie
        //===============================================
        $class = Content::class;
        if (!empty($conf['service'])) $class = $conf['service'];
        $vocabularies = $this->listVocabularies($class)->pluck('name','id')->toArray();

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


        return view('navigationcat::preferences')->with(compact(
            'services',
            'pages',
            'vocabularies',
            'conf',
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
        return  ['ord'=>['Inserimento','Titolo','Data di Creazione','Data di Modifica'],'dir'=>['Ascendente','Discendente']];
    }

}