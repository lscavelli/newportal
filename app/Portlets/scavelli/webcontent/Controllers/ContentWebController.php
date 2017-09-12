<?php

namespace App\Portlets\scavelli\webcontent\Controllers;

use App\Libraries\listGenerates;
use App\Models\Content\Content;
use App\Models\Content\Page;
use App\Models\Content\Structure;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ContentWebController extends Controller
{

    private $repo;

    /**
     * ContentWebController constructor.
     * @param RepositoryInterface $rp
     */
    public function __construct(RepositoryInterface $rp) {
        //$this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Content\Content')->setSearchFields(['name', 'description', 'content']);
    }


    /**
     * Restituisce la lista dei modelli in formato Json relativi alla struttura del web content
     * passato come argomento
     * @param $id
     * @return mixed
     */
    public function listModels($id) {
        $content = $this->repo->find($id);
        $structure = $this->repo->setModel(new Structure())->find($content->structure_id);
        return json_encode($structure->models()->where('type_id',1)->pluck('name','id')->toArray());
    }

    /**
     * Mostra il web form per la selezione del web content e dei modelli corrispondenti
     * @param $portlet
     * @param Request $request
     * @return string
     */
    public function configPortlet($portlet, Request $request) {
        //$viewPortlet = app_path("Portlets\\".$portlet->path).'\\'.'views';
        //View()->addLocation($viewPortlet);

        $listStructure = $this->repo->setModel('App\Models\Content\Structure')->where('type_id',2)->where('status_id',1)->pluck()->toArray();
        $structure_id = ($request->has('structure_id')) ? $request->structure_id : key($listStructure);
        $content = new Content;
        $listContent = $this->repo->setModel($content)->where('status_id',1)->where('structure_id',$structure_id)->get()->toArray();
        $listModels = []; $modelId = null;
        if (!empty($portlet->pivot->setting)) {
            $prf = json_decode($portlet->pivot->setting, true);
            if (isset($prf['content_id'])) {
                $content = $this->repo->setModel('App\Models\Content\Content')->find($prf['content_id']);
                $modelId = (!empty($prf['model_id'])) ? $prf['model_id'] : $content->model_id;
                $listModels = $this->repo->setModel('App\Models\Content\Structure')->find($content->structure_id)->models->where('type_id',1)->pluck('name','id')->toArray();
            }
        }

        $list = new listGenerates($this->repo->paginateArray($listContent,10,$request->page_a,'page_a'));
        return view('webcontent::preferences')->with(compact(
            'listStructure',
            'list',
            'listModels',
            'modelId',
            'content',
            'portlet'
        ));
    }

}