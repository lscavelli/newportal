<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface RepositoryInterface {

    public function setModel($model);
    public function setSearchFields($fields);
    public function getModel();
    public function all();
    public function find($id,$md=null);
    public function findBy(array $criteria, $oper = "=");
    public function findByEmail($email);
    public function findBySlug($slug);
    public function delete($id,$md=null);
    public function create(array $item);
    public function update($id, array $item);
    public function count();
    public function countByStatus($stato);
    public function getPerPage(Request $request);
    public function paginate(Request $request, $namePage='page');
    public function paginateArraySearch(Request $request, $namePage='page');
    public function next($id);
    public function prev($id);
    public function paginateArray($items,$perPage,$pageStart=1,$namePage='page');
    public function get($model=null, array $fields=['*']);
    public function where($column, $operator = null, $value = null, $boolean = 'and');
    public function whereNull($column, $boolean = 'and', $not = false);
    public function whereNotIn($column, $values, $boolean = 'and');
    public function getParentRoot($parent_id);
    public function getIdDescendants($id);
    public function getRoots();
    public function isRoot();
    public function isChild();
    public function pluck($column='name', $key = 'id', $md=null);
    public function attach($related, $id);
    public function detach($related, $id=null);
    public function associate($related, $parent);
    public function dissociate($related);
    public function orderBy($column, $direction = 'asc');
    public function first($columns);
    public function increment(string $column, int $amount = 1, array $extra = array());
    public function decrement(string $column, int $amount = 1, array $extra = array());
    public function filter(array $criteria, $oper = "=");
    public function resetModel();
}