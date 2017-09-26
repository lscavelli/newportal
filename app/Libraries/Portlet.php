<?php

namespace App\Libraries;

use ReflectionClass;
use App\Repositories\RepositoryInterface;
use App\Models\Content\Page;
use Illuminate\Support\Facades\Auth;

class Portlet {

    public $portlets = [];
    public $path = null;
    public $rp = null;
    public $page = null;

    public function __construct(RepositoryInterface $rp)  {
        $this->rp = $rp;
    }

    public function setPath($path=null) {
        if (!empty($path)) $this->path = '\\'.$path;
    }

    public function setRepository($rp) {
        $this->rp = $rp;
        return $this;
    }

    public function getNamespace() {
        return config('newportal.portlets.namespace').$this->path;
    }

    //public function getDirTemplate() {
        //return app_path($this->getNamespace()).'/'.'views';
    //}

    public function run($className,$theme,$path=null,$params=[]) {
        $this->setPath($path);
        $className = 'App\\'.$this->getNamespace().'\\'.$className;
        if (class_exists($className)) {
            if (!$instance = array_get($this->portlets, $className)) {
                $reflector = new ReflectionClass($className);
                if (!$reflector->isInstantiable()) {
                    throw new PortletException("La Portlet [$className] non è istanziabile.");
                }
                $instance = $reflector->newInstance($this->rp,$theme);
                array_set($this->portlets, $className, $instance);
            }
            //$instance->setDirTemplate($this->getDirTemplate());
            $instance->setConfig($params);
            $instance->init();
            $content = $instance->getContent();
            $instance->inizializeConf();
            return $content;
        }
        return null;
    }

    public function getIstances() {
        return $this->portlets;
    }

    public function listPagePortlets() {
        $page = $this->getPage();
        if ($page and !Auth::check()) return $page->portlets;
        //$arr = explode("/",\Request::path());
        //if (is_array($arr) and in_array('configPortlet',$arr) and is_numeric(end($arr))) {
        //    $this->rp->setModel('App\Models\Content\Portlet_page');
        //    $pivot = $this->rp->find(end($arr));
        //    return [$pivot->portlet];
        //}
        //return [];
        $this->rp->setModel('App\Models\Content\Portlet');
        return $this->rp->where('status_id',1)->get();
    }

    public function getPage() {
        if (empty($this->page)) {
            $uri = \Request::segment(1); //path();
            $this->rp->setModel(Page::class);
            $this->page = $this->rp->where('slug',$uri)->where('status_id',1)->first();
        }
        return $this->page;
    }

}