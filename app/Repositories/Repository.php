<?php

namespace app\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\RepositoryException;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use App\Events\Created;
use App\Events\Updated;
use App\Events\Deleted;
use App\Events\Assigned;
use App\Events\Removed;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\RepositoryInterface;


class Repository implements RepositoryInterface {

    protected $model;
    protected $originalModel;
    protected $searchFields;

    public function setModel($model)  {
        if (is_string($model)) $model = app($model); // \App::make - ex.. 'App\Models\Content\Tag'
        if (!$model instanceof EloquentModel) {
            throw new RepositoryException("Class $model dev'essere una istanza di Illuminate\\Database\\Eloquent\\Model");
        }
        $this->model = $this->originalModel = $model;
        return $this;
    }

    public function setSearchFields($fields) {
        if (!empty($fields)) {
            $this->searchFields = is_array($fields) ? $fields : [$fields];
        }
        return $this;
    }

    /**
     * Restituisce il Model
     * @return mixed
     */
    public function getModel() {
        return $this->originalModel;
    }

    /**
     * Restituisce tutte le istanze del modello
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()  {
        return $this->model->all();
    }

    /**
     * Cerca e restituisce una singola istanza del modello
     * @param $id
     * @return mixed
     */
    public function find($id,$md=null) {
        $model = $this->model;
        if ($md) $model = $md;
        return $model->findOrFail($id);
    }

    /**
     * Cerca e restituisce il record che risponde ai criteri impostati
     * @param array $criteria
     * @param string $oper
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findBy(array $criteria, $oper = "=")  {
        $items = $this->model->query();
        foreach ($criteria as $key => $value) {
            $items->where($key, $oper, $value);
        }
        return $items->first();
    }

    /**
     * Cerca e restituisce l'istanza per email
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)  {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Cerca e restituisce l'istanza per slug
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug)  {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Cancella l'elemento corrispondente all'id passato come parametro
     * @param $id
     * @return mixed
     */
    public function delete($id,$md=null) {
        $model = $this->model;
        if ($md) $model = $md;
        $item = $model->find($id);
        $result = $item->delete();
        event(new Deleted($item));
        return $result;
    }

    /**
     * Crea una istanza con i dati passati in argomento
     * @param array $item
     * @return static
     */
    public function create(array $item)  {
        if (array_key_exists('slug',$item)) {
            $item['slug'] =  $this->makeSlug($item['slug']);
            //$this->checkSlug($item);
        }
        $result = $this->model->create($item);
        event(new Created($result));
        return $result;
    }

    /**
     * Aggiorna l'elemento con i dati passati in argomento
     * @param $id
     * @param array $item
     * @return mixed
     */
    public function update($id, array $item)  {
        $ele = $this->model->find($id);
        if (array_key_exists('slug',$item)) {
            $item['slug'] =  $this->makeSlug($item['slug'],$id);
            //$this->checkSlug($item,true);
        }
        $result = $ele->update($item);
        event(new Updated($ele));
        return $result;
    }

    /**
     * return a URL friendly "slug"
     * @param $slug
     * @param int $id
     * @return string
     * @throws \Exception
     */
    private function makeSlug($slug,$id=0) {
        if(empty($slug)) {
            $slug = \Request::has('name')? \Request::input('name'):'';
        }
        $slug = str_slug($slug, "-");
        $allSlugs = $this->getModel()->where('slug', 'like', $slug.'%')->where('id', '<>', $id)->get(['slug']);
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }
        $i=1;
        // ripete finché non trova un valore libero;
        while (true) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
            $i++;
        }
    }

    /**
     * Verifica che lo slug non sia duplicato
     * @param $item
     * @param bool $onUpdate
     * @return string
     */
    private function checkSlug($item,$onUpdate=false) {
        $table = $this->model->getTable();
        $filter = 'unique:'.$table;
        if ($onUpdate) { $filter = Rule::unique($table)->ignore($item['id']);}
        Validator::make($item, ['slug'=>$filter])->validate();
    }

    /**
     * Restituisce il numero degli elementi
     * @return mixed
     */
    public function count() {
        return $this->model->count();
    }

    /**
     * Restituisce il numero degli elementi per stato
     * @param $stato
     * @return mixed
     */
    public function countByStatus($stato) {
        return $this->model->where('status_id', $stato)->count();
    }

    /**
     * Restituisce il numero di record da visualizzare per pagina
     * @param Request $request
     * @return bool|int
     */
    public function getPerPage(Request $request) {
        if ($request->has('xpage')) {
            return $request->xpage;
        }
        return 5; // NOTE: in config page
    }

    /**
     * Restituisce la lista degli elementi filtrati per keys
     * @param Request $request
     * @param string $namePage
     * @return mixed
     */
    public function paginate(Request $request, $namePage='page') {
        $this->preparePagination($request);
        $perPage = $this->getPerPage($request);
        return $this->model->paginate($perPage,['*'],$namePage,$request->$namePage);
    }

    /**
     * Restituisce la lista degli elementi paginata
     * @param Request $request
     * @param string $namePage
     * @return LengthAwarePaginator
     */
    public function paginateArraySearch(Request $request, $namePage='page') {
        $this->preparePagination($request);
        $perPage = $this->getPerPage($request);
        return $this->paginateArray($this->model->get()->toArray(),$perPage,$request->$namePage,$namePage);
    }

    /**
     * Restituisce la lista degli elementi filtrati per keys
     * @param Request $request
     * @return EloquentModel
     */
    private function preparePagination(Request $request) {

        if ($this->model instanceof EloquentModel) {
            $this->model = $this->model->query();
        }
        if ($request->has('selectedSort')) {
            $sortField[$request->input('selectedSort')] = $request->input('sortDirection');
        } else {
            $sortField['id'] = 'desc';
        }
        foreach ($sortField as $name => $dir) {
            $this->model->orderBy($name, $dir);
        }

        $q = $request->input('keySearch');
        if (count($this->searchFields)>0 && isset($q) && !empty($q)) {
            $this->model->where(function ($item) use($q) {
                $i =1;
                foreach ($this->searchFields as $field) {
                    if ($i==1) $item->where($field, "like", "%{$q}%");
                    if ($i>1) $item->orWhere($field, 'like', "%{$q}%");
                    $i++;
                }
            });
        }
    }

    /**
     * restituisce l'id successivo
     * @param $id
     * @return mixed
     */
    public function next($id,$filters=null) {
        $model = $model1 = $this->model;
        if ($filters and is_array($filters)) {
            foreach ($filters as $key=>$val) {
                $model = $model->where($key, $val);
                $model1 = $model1->where($key, $val);
            }
        }
        $next = $model->where('id', '>', $id)->orderBy('id','asc')->first();
        if(!$next)
            $next = $model1->orderBy('id','asc')->first();
        return $next;
    }

    /**
     * restituisce l'Id precedente
     * @param $id
     * @return mixed
     */
    public function prev($id,$filters=null) {
        $model = $model1 = $this->model;
        if ($filters and is_array($filters)) {
            foreach ($filters as $key=>$val) {
                $model = $model->where($key, $val);
                $model1 = $model1->where($key, $val);
            }
        }
        $prev = $model->where('id', '<', $id)->orderBy('id','desc')->first();
        if(!$prev)
            $prev = $model1->orderBy('id','desc')->first();
        return $prev;
    }

    /**
     * Paginazione per l'Array
     * @param $items
     * @param $perPage
     * @param int $pageStart
     * @param string $namePage
     * @return LengthAwarePaginator
     */
    public function paginateArray($items,$perPage=5,$pageStart=1,$namePage='page') {
        if (empty($pageStart)) $pageStart=1;
        $offSet = ($pageStart * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);
        return new LengthAwarePaginator(
            $itemsForCurrentPage,
            count($items),
            $perPage,
            LengthAwarePaginator::resolveCurrentPage($namePage),
            array('path' => LengthAwarePaginator::resolveCurrentPath(), 'pageName' => $namePage));
    }

    public function get($model=null, array $fields=['*']) {
        if (is_null($model)) $model = $this->model;
        return $model->get($fields);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and') {
        $this->model = $this->model->where($column, $operator, $value, $boolean);
        return $this;
    }

    public function whereNull($column, $boolean = 'and', $not = false) {
        $this->model = $this->model->whereNull($column, $boolean, $not);
        return $this;
    }

    public function whereNotIn($column, $values, $boolean = 'and') {
        $this->model = $this->model->whereNotIn($column, $values, $boolean);
        return $this;
    }

    public function getParentRoot($parent_id) {
        $parent = $this->model->where('id',$parent_id)->first();
        if (!is_null($parent->parent_id)) {
            return $this->getParentRoot($parent->parent_id);
        }
        return $parent;
    }

    public function getIdDescendants($id) {
        static $idDesc;
        $childrens = $this->model->where('parent_id',$id)->get();
        if (count($childrens)>0) {
            foreach($childrens as $children) {
                $idDesc[] = $children->id;
                $this->getIdDescendants($children->id);
            }
        }
        return $idDesc;
    }

    public function getRoots() {
        return $this->model->newQuery()->whereNull('parent_id');
    }

    public function isRoot() {
        // is_null($this->getParentId());
    }

    public function isChild() {
        //return !$this->isRoot();
    }

    public function pluck($column='name', $key = 'id', $md=null) {
        $model = $this->model;
        if ($md) $model = $md;
        return $model->pluck($column, $key);
    }

    public function attach($related, $id, array $other=[]) {
        $related->attach($id,$other);
        $parent = $related->getParent();
        $relatedModel = $related->getRelated();
        if ($id instanceof EloquentModel) $id = $id->id;
        $children = $relatedModel->find($id);
        event(new Assigned($parent,$children));
    }

    public function detach($related, $id=null) {
        $parent = $related->getParent();
        $relatedModel = $related->getRelated();
        $children = $relatedModel;
        if (!is_null($id)) {
            $children = $relatedModel->find($id);
        }
        $related->detach($id);
        event(new Removed($parent,$children));
    }

    public function associate($related, $parent) {
        $related->associate($parent);
        $children = $related->getParent();
        $children->save();
        event(new Assigned($parent,$children));
    }

    public function dissociate($related) {
        $children = $related->getParent();
        $parentModel = $related->getRelated();
        $parent = $parentModel->find($children->parent_id);
        $related->dissociate();
        $children->save();
        event(new Removed($parent,$children));
    }

    public function orderBy($column, $direction = 'asc') {
        $this->model = $this->model->orderBy($column,$direction);
        return $this;
    }

    /**
     * Valorizza gli options per il select di Edit e Create
     * @param $id
     * @return array
     */
    public function optionsSel($id=null,$filters=null) {
        if (!is_null($id)) {
            // Escludo tutti i discendenti
            $IdDescendants = $this->getIdDescendants($id);
            if (count($IdDescendants)>0) {
                $this->whereNotIn('id',$IdDescendants);
            }
            $this->where('id','<>',$id);
        }
        if ($filters and is_array($filters)) {
            foreach ($filters as $key=>$val) {
                $this->where($key, $val);
            }
        }
        return [null=>null] + $this->pluck()->toArray();
    }

    /**
     * Restituisce la prima istanza del modello
     * @param array $columns
     * @return mixed
     */
    public function first($columns = ['*']) {
        return $this->model->first($columns);
    }

    public function increment($column, $amount = 1, array $extra = array()) {
        return $this->model->increment($column,$amount,$extra);
    }

    public function decrement($column, $amount = 1, array $extra = array()) {
        return $this->model->decrement($column,$amount,$extra);
    }

    public function filter(array $criteria, $oper = "=")  {
        foreach ($criteria as $key => $value) {
            $this->model = $this->model->where($key, $oper, $value);
        }
        return $this;
    }

    public function resetModel() {
        $this->model = $this->originalModel;
        return $this;
    }

    public function with($relations) {
        $this->model = $this->model->with($relations);
        return $this;
    }
}