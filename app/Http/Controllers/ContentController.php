<?php

namespace App\Http\Controllers;

use App\Libraries\listGenerates;
use App\Models\Content\Structure;
use App\Models\Content\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Validator;
use App\Models\Content\Content;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;
use App\Libraries\FormGenerates;
use App\Models\Content\Service;
use App\Libraries\Images;
use Exception;
use Illuminate\Support\Facades\Log;

class ContentController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Content')->setSearchFields(['name','description','content']);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
            return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255',
            'content' => 'sometimes|required'
        ]);
    }

    /**
     * Visualizza la lista dei contenuti, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list, $structureId=null) {
        $nameStructure = null;
        if (!empty($structureId)) {
            $content = $this->rp->where('structure_id',$structureId)->orderBy('id','DESC')->paginate($request);
            $nameStructure = $this->rp->setModel(new Structure())->find($structureId)->name;
        } else
            $content = $this->rp->paginate($request);
        $list->setModel($content);
        $structures = $this->rp->setModel(new Structure())->where('type_id',2)->where('status_id',1)->pluck();
        $listStructure = [];
        foreach($structures as $key=>$val) {
            $listStructure[url('admin/content/create/'.$key)] = $val;
        }
        return view('content.listContent')->with(compact('content','list','listStructure','nameStructure'));
    }

    /**
     * Mostra il form per la creazione del content
     * @return \Illuminate\Contracts\View\View
     */
    public function create($structureId=null)   {
        if (empty($structureId)) $structureId = 1; //structure default
        $structure = $this->rp->setModel(new Structure())->find($structureId);
        $form = new FormGenerates($structure->content);
        $content = new Content(); $action = "ContentController@store";
        return view('content.editContent')->with(compact('content','action','form','structureId'));
    }

    /**
     * Salva il contenuto nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) {
        $data = $this->jsonData($request);
        $data['user_id'] = \Auth::user()->id; $data['username'] = \Auth::user()->username;
        $this->validator($data)->validate();
        $this->rp->create($data);
        return redirect()->route('content')->withSuccess('Contenuto creato correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento del contenuto web
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $content = $this->rp->find($id);
        $structure = $this->rp->setModel(new Structure())->find($content->structure_id);
        $form = new FormGenerates($structure->content,$content->content);
        $action = ["ContentController@update",$id];
        return view('content.editContent')->with(compact('content','action','form'));
    }

    public function editWrapper($structureid,$id) {
        return $this->edit($id);
    }

    /**
     * Aggiorna i dati nel DB
     * @param $id
     * @param Request $request
     * @return $this
     */
    public function update($id, Request $request)  {
        $data = $this->jsonData($request);
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect()->route('content')->withSuccess('Contenuto aggiornato correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * predispone i dati json da salvare in content
     * @param $request
     * @return mixed
     */
    public function jsonData($request)  {
        $dataJson = json_encode(array_except($request->all(), ['_token','name','structureId']));
        $data['name'] = $request->name;
        $data['slug'] = (!empty($request->slug)) ? $request->slug : $request->name;
        $data['content'] = $dataJson;
        if ($request->has('structureId')) $data['structure_id'] = $request->structureId;
        return $data;
    }

    /**
     * Cancella il contenuto - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        $content = $this->rp->find($id);
        if ($content->tags()->count()>0) $this->rp->detach($content->tags(), $content->tags()->pluck('id'));
        if ($content->categories()->count()>0) $this->rp->detach($content->categories(), $content->categories()->pluck('id'));
        if ($content->comments()->count()>0) $content->comments()->delete();
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Contenuto web cancellato correttamente');
        }
        return redirect()->back();
    }

    /**
     * Visualizza il form delle categorie e tags
     * @param $id
     * @return mixed
     */
    public function categorization($id) {
        $content = $this->rp->find($id);
        $action = ["ContentController@otherUpdate",$id];
        $tags = $this->rp->setModel('App\Models\Content\Tag')->pluck();
        // =======================
        $vocabularies = $this->listVocabularies();
        // =======================
        $cats = $this->rp->setModel('App\Models\Content\Category');
        $categories = $cats->pluck();

        return view('content.editContentCategorization')->with( compact('content','action','tags','categories','categoryJson','vocabularies'));
    }

    /**
     * Visualizza il form dell'estratto
     * @param $id
     * @return mixed
     */
    public function extract($id) {
        $content = $this->rp->find($id);
        $action = ["ContentController@otherUpdate",$id];
        return view('content.editContentExtract')->with( compact('content','action'));
    }

    /**
     * Salva altri campi nel db
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function otherUpdate($id, Images $image, Request $request) {
        $content = $this->rp->find($id);
        $data = $request->all();
        $image->setPath(config('newportal.path_upload_imgwc'));
        $check =$this->checkUseImage($content->image);
        if ($request->file('image')) {
            $canc = null; if($check) $canc = $content->image;
            $data['image'] = $image->uploadImage($canc, 331, 200)[0];
        } elseif($request->has('setImageDefault') or (isset($request->urlImage) && !$request->has('urlImage'))) {
            dd('ALT');
            $data['image'] = null;
            // se non viene utilizzato da un altro contenuto cancello l'immagine
            if ($check) $image->delFile($content->image);
        } elseif($request->has('urlImage')) {
            // se diversa image old e non è utilizzata da un altro contenuto la cancello
            if (($request->urlImage!=$content->image) && $check) $image->delFile($content->image);
            // se urlImage non contiene http o https e il file non esiste allora $data['image'] = null;
            if (!starts_with($request->urlImage, ['http','https']) &&
                !$image->fileExists($request->urlImage)) {
                $data['image'] = null;
            } else {
                $data['image'] = $request->urlImage;
            }
        }

        $this->rp->update($id, $data);
        if ($request->has('saveCategory')) $this->saveCat($content,$request);
        return redirect()->route('content')->withSuccess('Contenuto aggiornato correttamente');
    }

    private function saveCat($content,$request) {
        if (isset($request->tags)) {
            $content->tags()->sync($request->tags);
        } elseif ($request->has('saveCategory')) {
            $content->tags()->sync([]);
        }

        $content->categories()->detach();
        foreach($this->listVocabularies() as $vocabulary) {
            $itemCats = "categories".$vocabulary->id;
            if (isset($request->$itemCats)) {
                $content->categories()->attach($request->$itemCats,['vocabulary_id'=>$vocabulary->id]);
            }
        }
    }

    public function listCategoryJson($vocabulary_id,$default=null) {
        if ($default) $default = explode(',',$default);
        //$content = $this->rp->find($content_id);
        $allCategory = $this->rp->setModel('App\Models\Content\Category')->where('vocabulary_id',$vocabulary_id)->whereNull('parent_id')->get();
        return $this->getJsonCategory($allCategory,$default);
        //$content->categories;
        //or (is_null($default) and !is_null($categories) and ($categories->contains('id', $val->id)))
    }

    private function getJsonCategory($collect,$default=null) {
        static $jd;
        $jd .= '['; $and = "";
        foreach ($collect as $key =>$val) {
            $jd .= $and .'{"item":{';
            $jd .= '"id":"'.$val->id.'",';
            $jd .= '"label":"'.$val->name.'",';
            if (is_array($default) and in_array($val->id,$default)) {
                $jd .= '"checked":true}';
            } else {
                $jd .= '"checked":false}';
            }

            if ($val->children()->count()>0) {
                $jd .= ',"children": ';
                $this->getJsonCategory($val->children, $default);
            }
            $jd .="}";
            $and = ",";
        }
        $jd .= "]";
        return $jd;
    }

    private function listVocabularies() {
        $service = $this->rp->setModel(Service::class)->where('class',Content::class)->first();
        return $service->vocabularies;
    }

    private function checkUseImage($image) {
        return($this->rp->findBy(['image'=>$image]));
    }

    /**
     * Visualizza il form per settare il modello
     * @param $id
     * @return mixed
     */
    public function model($id) {
        $content = $this->rp->find($id);
        $action = ["ContentController@otherUpdate",$id];
        $structure = $this->rp->setModel('App\Models\Content\Structure')->find($content->structure_id);
        $structure_name = $structure->name;
        $listModels = [0=>""]; $listModels += $structure->models->where('type_id',1)->pluck('name','id')->toArray();
        return view('content.editContentModel')->with( compact('content','action','listModels','structure_name'));
    }

}
