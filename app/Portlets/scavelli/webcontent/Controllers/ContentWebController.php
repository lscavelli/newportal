<?php

namespace App\Portlets\scavelli\webcontent\Controllers;

use App\Libraries\listGenerates;
use App\Models\Content\Content;
use App\Models\Content\Modelli;
use App\Models\Content\Page;
use App\Models\Content\Structure;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ContentWebController extends Controller
{

    private $rp;
    public $params;

    /**
     * ContentWebController constructor.
     * @param RepositoryInterface $rp
     */
    public function __construct(RepositoryInterface $rp) {
        //$this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Content')->setSearchFields(['name', 'description', 'content']);
        $this->params = [];
    }


    /**
     * Restituisce la lista dei modelli in formato Json relativi alla struttura del web content
     * passato come argomento
     * @param $id
     * @return mixed
     */
    public function listModels($id) {
        $content = $this->rp->find($id);
        $structure = $this->rp->setModel(new Structure())->find($content->structure_id);
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

        $this->params['listStructure'] = $this->rp->setModel('App\Models\Content\Structure')->where('type_id',2)->where('status_id',1)->pluck()->toArray();
        $structure_id = ($request->has('structure_id')) ? $request->structure_id : key($this->params['listStructure']);

        // ricavo la lista dei modelli riferiti alla struttura_id
        $this->params['listModels'] = $this->rp->setModel(Structure::class)->find($structure_id)->models->where('type_id',1)->pluck('name','id')->toArray();
        $listContent = $this->rp->setModel(Content::class)->where('status_id',1)->where('structure_id',$structure_id)->with('model')->get()->toArray();

        $this->params['modelContent'] = null;
        if (!empty($portlet->pivot->setting)) {
            $prf = json_decode($portlet->pivot->setting, true);
            $this->params['modelId'] = (!empty($prf['model_id'])) ? $prf['model_id'] : null;
            if (isset($prf['content_id'])) {
                if ($content = $this->rp->setModel(Content::class)->find($prf['content_id'])) {
                    $this->params['content_id'] = $content->id;
                    $this->params['content_name'] = $content->name;
                    if(!empty($prf['modelContent'])) {
                        $this->params['modelContent'] = $prf['modelContent'];
                    } else {
                        if ($content->model_id) {
                            $modelContent = $this->rp->setModel(Modelli::class)->find($content->model_id);
                            $this->params['modelContent'] = $modelContent->name;
                        }
                    }
                }
            }
            if (isset($prf['modelPortletId'])) {
                $this->params['modelPortletId'] = $prf['modelPortletId'];
            }
            if (isset($prf['modelPortlet'])) {
                $this->params['modelPortlet'] = $prf['modelPortlet'];
            }
        }
        $this->params['portlet'] = $portlet;

        // imposto la variabile social sharing, comments, sethits, syntax
        if (isset($prf['socialshare'])) $this->params['socialshare'] = $prf['socialshare'];
        if (isset($prf['activecomments'])) $this->params['activecomments'] = $prf['activecomments'];
        if (isset($prf['sethits'])) $this->params['sethits'] = $prf['sethits'];
        if (isset($prf['syntax'])) $this->params['syntax'] = $prf['syntax'];

        $list = new listGenerates($this->rp->paginateArray($listContent,10,$request->page_a,'page_a'));
        return view('webcontent::preferences')->with([
            'list' => $list,
            'webContent' => $this
        ]);
    }

    public function get($key) {
        return array_get($this->params, $key);
    }

}