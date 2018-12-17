<?php

namespace app\Services;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;


class listGenerates
{

    public $columns = [];
    public $labels = [];
    public $attributes = [];
    public $customize = [];
    public $actions = [];
    public $paginator;
    public $sortFields = [];
    public $prefix_ = null;
    public $labelsList = [];
    public $showActions = true;
    public $showButtonNew = true;
    public $showAll = true;
    public $showSearch = true;
    public $showXpage = true;
    public $prefixPage = null;
    public $urlDelete = null;
    public $splitButtons = [];
    public $showActionsDefault = true;

    public function __construct(LengthAwarePaginator $paginator=null) {
        if ($paginator) $this->setPagination($paginator);
        $this->urlDelete = url(\Request::path());
    }
    public function setPagination(LengthAwarePaginator $paginator) {
        $this->paginator = $paginator;
        return $this;
    }
    public function columns(array $columns = []) {
        $this->columns = $columns;
        return $this;
    }
    public function labels(array $labels = []) {
        $this->labels = $labels;
        return $this;
    }
    public function setPrefix($prefix=null) {
        $this->prefix_ = $prefix;
        return $this;
    }
    public function setPrefixPage($prefix=null) {
        $this->prefixPage = $prefix;
        return $this;
    }
    public function sortFields(array $sortFields = []) {
        $this->sortFields = $sortFields;
        return $this;
    }
    public function customizes($column, Closure $closure) {
        $this->customize[$column] = $closure;
        return $this;
    }
    public function showActions($actions=true) {
        $this->showActions = $actions;
        return $this;
    }
    public function showActionsDefault($actions=true) {
        $this->showActionsDefault = $actions;
        return $this;
    }
    public function actions(array $actions = []) {
        $this->actions = array_merge($this->actions,$actions);
        return $this;
    }

    public function onlyActions(array $actions = []) {
        $this->actions = array_merge($this->actions,$actions);
        $this->showActionsDefault(false);
        return $this;
    }

    public function showButtonNew($showB=true, $path=null) {
        $this->showButtonNew = $showB;
        if (!is_null($path)) {
            if(\Request::path()==$path) {
                $this->showButtonNew = false;
            }
        }
        return $this;
    }
    public function showAll($show=true) {
        $this->showAll = $show;
        return $this;
    }
    public function showSearch($show=true) {
        $this->showSearch = $show;
        return $this;
    }
    public function showXpage($show=true) {
        $this->showXpage = $show;
        return $this;
    }
    public function tableAttributes(array $attributes = []) {
        $this->attributes = $this->toAttributes($attributes);
        return $this;
    }
    public function getRowAttributes($row) {
        // da sistemare
        return null;
    }
    public function getCellAttributes($column, $row = null) {
        // da sistemare
        return null;
    }

    public function getUrlDelete() {
        return $this->urlDelete;
    }

    public function setUrlDelete($path) {
        if (!empty($path)) $this->urlDelete = url($path);
        return $this;
    }

    public function addSplitButtons($options,$showButtonNew=true) {
        $this->showButtonNew($showButtonNew);
        $this->splitButtons = $options;
        return $this;
    }

    /**
     * restituisce il numero degli elementi x pagina
     * @return mixed
     */
    public function count() {
        $count = $this->paginator->count();
        return ($count<1 ? null: $count);
    }
    /**
     * restituisce il numero totale degli elementi
     * @return mixed
     */
    public function total() {
        $total = $this->paginator->total();
        return ($total<1 ? null: $total);
    }

    public function render($viewp = null) {
        $this->setLabelsList();
        $view = "ui.listGenerates";
        if (!empty($viewp)) $view = $viewp;
        return View::make($view, [
            'list' => $this,
            'model' => $this->paginator
        ])->render();
    }

    private function toAttributes(array $attributes = []) {
        $output = null;
        if (count($attributes) > 0) {
            foreach ($attributes as $key => $value) {
                $output .= sprintf('%s="%s"', $key, $value);
            }
        }
        return $output;
    }
    private function setLabelsList() {

        $tempArray = [];
        foreach ($this->columns as $key=>$column) {
            if (is_numeric($key)) {
                $tempArray[$column] = $column;
                $key = $column;
            } else $tempArray[$key] = $column;

            if(count($this->labels)>0 && array_key_exists($key, $this->labels)) {
                $value_label = $this->labels[$key];
            } else $value_label = $column;

            $icon = null;
            $this->labelsList[$key]['attributes'] = null;
            $numSortFields = count($this->sortFields);
            // se "sortFields" non ha campi li considero tutti
            if ($this->showAll and (($numSortFields<1) or ($numSortFields>0 && in_array($key,$this->sortFields)))) {
                $paramSort = $this->setSortFields($key);
                $this->labelsList[$key]['attributes'] = $paramSort[1];
                $icon = $paramSort[0];
            }
            $this->labelsList[$key]['labels'] = ucwords(str_replace('_', ' ', $value_label)) . $icon;
        }
        $this->columns = $tempArray;
    }

    private function setSortFields($key) {
        $pfx_ = null;
        if(!empty($this->prefix_)) {
            $pfx_ = $this->prefix_;
        }
        $selectedSort = \Request::input($pfx_.'selectedSort');
        $sortDirection = \Request::input($pfx_.'sortDirection');

        $iconDir = 'glyphicon glyphicon-sort';
        $classDir = 'sort-column';
        $sortDir = 'asc';
        if ($selectedSort==$key) {
            $sortDir = ( $sortDirection == 'asc' ? 'desc' : 'asc');
            $classDir = ( $sortDirection == 'asc' ? 'sort-column sort-desc' : 'sort-column sort-asc');
            $iconDir = ( $sortDirection == 'asc' ? 'glyphicon glyphicon-sort-by-alphabet' : 'glyphicon glyphicon-sort-by-alphabet-alt');
        }
        $arraylink = array_except(\Request::all(), ['_token']);
        $arraylink = array_merge($arraylink,[$pfx_.'selectedSort'=>$key,$pfx_.'sortDirection'=>$sortDir]);
        $link = \Request::url() . "?". http_build_query($arraylink);
        $icon = ($iconDir ? link_to($link,'',['class'=>"{$iconDir} pull-right"]) : null);
        $classth = ($classDir ? "class=\"$classDir\"" : null);
        return [$icon,$classth];
    }

    public function appends(Array $data) {
        $this->paginator->appends($data);
        return $this;
    }

    /**
     * Aggiunge l'anchor tag #foo
     * @param $hf
     * @return $this
     */
    public function fragment($hf) {
        $this->paginator->fragment($hf);
        return $this;
    }
}
