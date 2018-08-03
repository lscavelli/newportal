<?php

namespace app\Services;

use Illuminate\Support\Facades\View;

class Navigation {

    public $items = [];
    public $prefix = null;


    public function __construct($nav=null,$value=null) {
        if (!empty($nav)) $this->add($nav,$value);
    }

    /**
     * @param $nav
     * @param null $value
     * @return $this
     */
    public function add($nav,$value=null) {
        if (!empty($nav)) {
            $nav = (is_array($nav)) ? $nav : [$nav => $value];
            $this->items = array_merge($this->items, $nav);
        }
        return $this;
    }

    /**
     * Svuota il navigatore
     * @return $this
     */
    public function removeAll() {
        $this->items = [];
        return $this;
    }

    /**
     * restituisce il numero delle voci
     * @return int
     */
    public function count() {
        return count($this->items);
    }

    /**
     * verifica se il navigatore è vuoto
     * @return bool
     */
    public function isEmpty() {
        return $this->count() === 0;
    }

    /**
     * Restituisce tutto il navigatore o il ramo passato come argomento
     * @param null $nav
     * @return array
     */
    public function getNav($nav=null) {
        return (!empty($nav)) ? $this->items[$nav] : $this->items;
    }

    /**
     * @param null $viewp
     * @return mixed
     */
    public function render($viewp = null) {
        $view = "ui.navigation";
        if (!empty($viewp)) $view = $viewp;
        $return = View::make($view, [
            'nav' => $this
        ])->render();
        $this->removeAll();
        return $return;
    }

    /**
     * verifica se la voce contiene il parametro url
     * @param $item
     * @return bool
     */
    public function hasUrl($item) {
        return isset($item['url']);
    }

    /**
     * Aggiunge un prefisso da anteporre all'url
     * @param $prefix
     * @return $this
     */
    public function prefix($prefix){
        if (!empty($prefix)) {
            $this->prefix = "/".$prefix;
        }
        return $this;
    }

    /**
     * Verifica se una voce del navigator è selezionata
     * @param $item
     * @return bool
     */
    public function isSelected($item) {
        if (is_string($item)) $item = ['url'=>$item];

        if (isset($item['submenu'])) {
            return $this->checkChildrenActive($item['submenu']);
        }
        if (isset($item['url'])) {
            return $this->checkUrl($item['url']);
        }
        return false;
    }

    /**
     * Controlla se il fullUrl corrisponde al modello selezionato
     * @param $url
     * @return bool
     */
    private function checkUrl($url) {
        //return str_is(url($this->prefix.$url).'*',\Request::fullUrl());
        if (\Request::has('category') and $cat=strstr($url,'category=')){
            return \Request::input('category') == explode("=",$cat)[1];
        } elseif (\Request::has('tag') and $tag=strstr($url,'tag=')){
            return \Request::input('tag') == explode("=",$tag)[1];
        }
        $slash = null; $segment = ($this->prefix) ? 2 : 1;
        if (starts_with($url, '/')) $slash = "/";
        return $url === $slash.\Request::segment($segment);
    }

    /** Controlla se esiste un discendente selezionato (attivo anche il parent)
     * @param $items
     * @return bool
     */
    private function checkChildrenActive($items) {
        foreach ($items as $item) {
            if ($this->isSelected($item)) {
                return true;
            }
        }
        return false;
    }



}