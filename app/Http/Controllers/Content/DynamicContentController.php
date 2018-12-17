<?php

namespace App\Http\Controllers\Content;

use App\Services\listGenerates;
use App\Models\Content\DynamicDataList;
use App\Models\Content\DynamicDataListContent;
use Illuminate\Http\Request;
use App\Repositories\RepositoryInterface;
use App\Services\FormGenerates;
use App\Http\Controllers\Controller;

class DynamicContentController extends Controller {

    private $repo;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->repo = $rp->setModel('App\Models\Content\DynamicDataListContent')->setSearchFields(['content']);
    }

    /**
     * Visualizza la lista dei dati dinamici, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index($id,Request $request, listGenerates $list) {
        $datalist = $this->repo->setModel(new DynamicDataList())->find($id);
        $structure = $datalist->parent()->first()->content;
        //$listContent = json_decode('['.$datalist->content->implode('content',', ').']',true);
        $dlsorted = $datalist->content->sortByDesc('id');
        $jsontemp = [];
        foreach ($dlsorted as $collect) {
            $contentFinal = array_merge(['id'=>$collect->id],json_decode('['.$collect->content.']',true)[0]);
            $jsontemp[] = $contentFinal;
        }
        $dataPaginate = $this->repo->paginateArray($jsontemp,4,$request->page_a,'page_a');
        $formObj = new FormGenerates($structure);
        $listLabel = $formObj->listLabel();
        $listType = $formObj->listType();
        $sortFields = array_keys($listLabel);
        $list->setPagination($dataPaginate)->columns($listLabel)->sortFields($sortFields);
        foreach($sortFields as $val) {
            $list->customizes($val,function($row) use($val,$listType){
                if ($listType[$val]=='date') {
                    return \Carbon\Carbon::parse($row[$val])->format('d/m/Y');
                } else {
                    return str_limit(strip_tags($row[$val]), 100);
                }
            });
        }
        return view('content.listDynamicContent')->with(compact('list','datalist'));
    }

    /**
     * Mostra il form per la creazione del content
     * @return \Illuminate\Contracts\View\View
     */
    public function create($id)   {
        $dataList = $this->repo->setModel(new DynamicDataList())->find($id);
        $structureContent = $dataList->parent()->first()->content;
        $form = new FormGenerates($structureContent);
        $content = new DynamicDataListContent(); $url = url('admin/ddl/content/store');
        return view('content.editDynamicContent')->with(compact('content','url','form','dataList'));
    }

    /**
     * Salva il contenuto nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {
        $data = $this->jsonData($request);
        $data['user_id'] = \Auth::user()->id; $data['username'] = \Auth::user()->username;
        $this->repo->create($data);
        return redirect()->route('ddlcontent',['ddl_id' => $request->dynamicdatalist_id])->withSuccess('Contenuto dinamico creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del contenuto web
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($dataList_id,$id) {
        $content = $this->repo->find($id);
        $ddlId = $content->dynamicdatalist_id;
        $dataList = $this->repo->setModel(new DynamicDataList())->find($ddlId);
        $structureContent = $dataList->parent()->first()->content;
        $form = new FormGenerates($structureContent,$content->content);
        $url = url('admin/ddl/content/update',$id);
        return \View::make('content.editDynamicContent', compact('content','url','form','dataList'));
    }

    /**
     * Aggiorna i dati nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $this->jsonData($request);
        if ($this->repo->update($id,$data)) {
            return redirect()->route('ddlcontent',['ddl_id' => $request->dynamicdatalist_id])->withSuccess('Contenuto dinamico aggiornato correttamente.');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * predispone i dati json da salvare in content
     * @param $request
     * @return mixed
     */
    public function jsonData($request)  {
        $dataJson = json_encode(array_except($request->all(), ['_token','dynamicdatalist_id']));
        $data['content'] = $dataJson;
        if ($request->has('dynamicdatalist_id')) $data['dynamicdatalist_id'] = $request->dynamicdatalist_id;
        return $data;
    }

    /**
     * Cancella il contenuto - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($ddl_id,$id)  {
        if ($this->repo->delete($id)) {
            return redirect()->back()->withSuccess('Contenuto web cancellato correttamente');
        }
        return redirect()->back();
    }

}
