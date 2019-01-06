<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    /**
     * @var array
     */
    protected $criteria = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    public $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request->all();
    }

    public function addCriterion(Array $criteria=[], $reset = false) {
        if ($reset) $this->criteria = [];
        $this->criteria = array_merge($this->criteria,$criteria);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = camel_case($field);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }
        return $this->builder;
    }

    /**
     * @return array
     */
    protected function fields(): array
    {
        return array_filter(
            array_map('trim', array_merge($this->request,$this->criteria))
        );
    }
}
