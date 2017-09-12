<?php

namespace App\Portlets\scavelli\navigationpages\Controllers;

use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use App\Libraries\Theme;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

class pageController extends Controller
{

    private $rp;


    public function __construct(RepositoryInterface $rp) {
        $this->rp = $rp->setModel('App\Models\Content\Page');
    }

    /**
     * Mostra il web form per la configurazione della portlet
     * @param $portlet
     * @param Theme $theme
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function configPortlet($portlet, Theme $theme) {
        $conf = ['ord'=>0,'dir'=>0,'layout'=>'','theme'=>'','page'=>'','comunication'=>$portlet->pivot->comunication];
        if(!empty($portlet->pivot->setting)) $conf = array_merge($conf,json_decode($portlet->pivot->setting, true));

        $pages = [''=>''];
        $pages += $this->rp->where('status_id',1)->whereNull('parent_id')->orderBy('name')->pluck('name','slug')->toArray();

        $themes = $theme->listThemes();

        // definizione dei layouts
        // =====================================================
        $layouts = [''=>'']; $tm = null;
        if (!empty($conf['theme'])) {
            $tm = $conf['theme'];
        } elseif(count($themes)>0) {
            $tm = config('newportal.theme-default');
        }
        if ($tm) $layouts += $theme->setTheme($tm)->listlayouts();
        // =====================================================

        $selectOrder = $this->selectOrder();

        return view('navigationpages::preferences')->with(compact(
            'pages',
            'themes',
            'layouts',
            'conf',
            'selectOrder'
        ));
    }

    /**
     * @return array
     * Definisco i valori dei campi select ord e dir
     */
    private function selectOrder() {
        return  ['ord'=>['Inserimento','Titolo','Data di Creazione','Data di Modifica'],'dir'=>['Ascendente','Discendente']];
    }

    /**
     * Restituisce la lista dei layout in formato Json relativi al tema passato come argomento
     * @param $theme
     * @return string
     */
    public function listLayout($theme) {
        $data = [''=>''];
        $themeObj = new Theme(new Filesystem());
        // Controllo l'esistenza della dir prima di valorizzare data
        // è necessario per la presenza di un eccezione nella funzione listLayouts
        $path = $themeObj->getPathLayouts($theme);
        if (is_dir($path)) $data += $themeObj->listLayouts($theme);
        return response()->json($data);
    }

}