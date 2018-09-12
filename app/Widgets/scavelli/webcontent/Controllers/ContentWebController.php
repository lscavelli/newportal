<?php

namespace App\Widgets\scavelli\webcontent\Controllers;

use App\Services\listGenerates;
use App\Models\Content\Content;
use App\Models\Content\Modelli;
use App\Models\Content\Structure;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content\Service;

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
     * @param $widget
     * @param Request $request
     * @return string
     */
    public function configWidget($widget, Request $request) {
        //$viewWidget = app_path("Widgets\\".$widget->path).'\\'.'views';
        //View()->addLocation($viewWidget);

        $service = $this->rp->setModel(Service::class)->where('class',Content::class)->first();
        $this->params['listStructure'] = $this->rp->setModel('App\Models\Content\Structure')->where('service_id',$service->id)->where('status_id',1)->pluck()->toArray();
        $structure_id = ($request->has('structure_id')) ? $request->structure_id : key($this->params['listStructure']);

        // ricavo la lista dei modelli riferiti alla struttura_id
        $this->params['listModels'] = $this->rp->setModel(Structure::class)->find($structure_id)->models->where('type_id',1)->pluck('name','id')->toArray();
        $listContent = $this->rp->setModel(Content::class)->where('status_id',1)->where('structure_id',$structure_id)->with('model')->get()->toArray();

        $this->params['modelContent'] = null;
        if (!empty($widget->pivot->setting)) {
            $prf = json_decode($widget->pivot->setting, true);
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
            if (isset($prf['modelWidgetId'])) {
                $this->params['modelWidgetId'] = $prf['modelWidgetId'];
            }
            if (isset($prf['modelWidget'])) {
                $this->params['modelWidget'] = $prf['modelWidget'];
            }
        }
        $this->params['widget'] = $widget;

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