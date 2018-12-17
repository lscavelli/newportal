<?php

namespace App\Http\Controllers\Content;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Content\Category;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Services\listGenerates;

/**
 * Class CategoryController
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\Category')->setSearchFields(['id','name']);
    }

    /**
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            'name' => 'required|min:2|max:255'
        ]);
    }

    /**
     * Visualizza la lista delle categorie, eventualmente filtrata
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list, $vocabulary_id=null)   {
        if ($vocabulary_id) {
            $categories = $this->rp->where('vocabulary_id',$vocabulary_id)->paginate($request);
        } else {
            $vocabulary_name = null;
            $categories = $this->rp->paginate($request);
        }
        $list->setPagination($categories);

        if ($vocabulary_id) $vocabulary_name = $this->rp->setModel('App\Models\Content\Vocabulary')->find($vocabulary_id)->name;
        return view('content.listCategories')->with(compact('categories','list','vocabulary_name'));
    }

    /**
     * Mostra il form per la creazione di una nuova Categoria
     * @return \Illuminate\Contracts\View\View
     */
    public function create($vocabulary_id) {
        $category = new Category(); $action = "Content\\CategoryController@store";
        $selectCat = $this->rp->optionsSel(null,['vocabulary_id'=>$vocabulary_id]);
        $vocabulary = $this->rp->setModel('App\Models\Content\Vocabulary')->find($vocabulary_id);
        return view('content.editCategory')->with(compact('category','action','selectCat','vocabulary'));
    }

    /**
     * Salva la categoria nel database dopo aver validato i dati
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)   {
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ? $data['parent_id'] : null;
        $this->validator($data)->validate();
        $this->rp->create($data);
        return redirect()->route('categories',['vocabulary_id'=>$request['vocabulary_id']])->withSuccess('Categoria creata correttamente.');
    }

    /**
     * Mostra il form per l'aggiornamento della Categoria
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($vid,$id) {
        $category = $this->rp->find($id);
        $selectCat = $this->rp->optionsSel($id,['vocabulary_id'=>$category->vocabulary_id]);
        $action = ["Content\\CategoryController@update",$id];
        $vocabulary = $this->rp->setModel('App\Models\Content\Vocabulary')->find($category->vocabulary_id);
        return view('content.editCategory')->with(compact('category','action','selectCat','vocabulary'));
    }

    /**
     * Aggiorna i dati della Categoria nel DB
     * @param $id
     * @param Request $request
     * @return $this
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)  {
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] ?: null;
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect()->route('categories',['vocabulary_id'=>$request['vocabulary_id']])->withSuccess('Categoria aggiornata correttamente');
        }
        return redirect()->back()->withErrors('Si è verificato un  errore');
    }

    /**
     * Cancella la categoria - chiede conferma prima della cancellazione
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)  {
        if ($this->rp->delete($id)) {
            return redirect()->back()->withSuccess('Categoria cancellata correttamente');
        }
        return redirect()->back();
    }

    /**
     * Visualizzo la pagina per l'assegnazione delle sottocategorie
     * @param $categoryId
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function assignSubcat($categoryId, Request $request, listGenerates $list) {
        $category = $this->rp->find($categoryId);
        $vocabulary = ['vocabulary_id'=>$category->vocabulary_id];
        $pag['nexid'] = $this->rp->next($categoryId,$vocabulary);
        $pag['preid'] = $this->rp->prev($categoryId,$vocabulary);
        // --- Assegnati
        $ass = $this->listSubcat($categoryId);
        $assArray = $ass->toArray();
        $subcatAss = $this->rp->paginateArray($assArray,4,$request->page_a,'page_a');
        // --- Ancora disponibili
        if (!is_null($category->parent_id)) {
            $parentRoot = $this->rp->getParentRoot($category->parent_id);
            $this->rp->where('id','<>', $parentRoot->id);
        }
        $this->rp->where('id','<>',$categoryId);
        $dispArray = $this->rp->whereNull('parent_id')->where('vocabulary_id',$category->vocabulary_id)->get()->diff($ass)->toArray();
        $subcatDis = $this->rp->paginateArray($dispArray,4,$request->page_b,'page_b');
        $vocabulary = $this->rp->setModel('App\Models\Content\Vocabulary')->find($category->vocabulary_id);
        return view('content.assignSubCategory')->with(compact('subcatAss','subcatDis','category','pag','list','vocabulary'));
    }

    /**
     * Assegna una o più Categorie
     * @param $categoryId
     * @param $subcatId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addSubcat($categoryId, $subcatId)  {
        $subcat = $this->rp->find($subcatId);
        $category = $this->rp->find($categoryId);
        //$category->children()->save($filial); // imposta il figlio
        //$filial->parent()->associate($category); // imposta il genitore
        //$filial->save();
        $this->rp->associate($subcat->parent(),$category);
        return redirect()->back();
    }

    /**
     * Restituisce la lista delle Categorie children
     * @param $categoryId
     * @return mixed
     */
    public function listSubcat($categoryId)  {
        return $this->rp->find($categoryId)->children;
    }

    /**
     * Elimina il parent dalla subcat
     * @param $subcatId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delSubcat($subcatId)  {
        $subcat = $this->rp->find($subcatId);
        $this->rp->dissociate($subcat->parent());
        return redirect()->back();
    }

    /**
     * restituisce il numero delle sottocategorie
     * @param $categoryId
     * @return array
     */
    public function countSubcat($categoryId)  {
        return $this->rp->find($categoryId)->children()->count();
    }

    /**
     * Mostra il profilo dell'Categoria
     * @param $Id
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function profile($Id, Request $request) {
        $category = $this->rp->find($Id);
        $vocabulary = ['vocabulary_id'=>$category->vocabulary_id];
        $pag['nexid'] = $this->rp->next($Id,$vocabulary);
        $pag['preid'] = $this->rp->prev($Id,$vocabulary);
        $listSubcat = new listGenerates($this->rp->paginateArray($this->listSubcat($Id)->toArray(),10,$request->page_a,'page_a'));
        $graphorg = $this->rp->whereNull('parent_id')->where('vocabulary_id',$category->vocabulary_id)->get();
        $titleGraph = "Rappresentazione grafica delle categorie";
        $vocabulary = $this->rp->setModel('App\Models\Content\Vocabulary')->find($category->vocabulary_id);
        return view('content.profileCategory')->with(compact('category','listSubcat','pag','titleGraph','graphorg','vocabulary'));
    }


}
