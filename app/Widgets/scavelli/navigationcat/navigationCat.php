<?php

namespace app\Widgets\scavelli\navigationcat;

use App\Widgets\abstractWidget as Widget;
use App\Services\navigation;
use App\Widgets\scavelli\navigationcat\Controllers\categoryController;

class navigationCat extends Widget {

    private $menu;

    public function init() {
        $this->rp->setModel('App\Models\Content\Category');

        $this->theme->addExCss($this->getPath().'css/menucat.css');
        $this->theme->addExJs($this->getPath().'js/menucat.js');
    }

    public function render() {
        // dal config sarà possibile impostare uno o più vocabolari - ad esempio voc. 1
        // e la pagina di visualizzazione dei contenuti

        $builder = $this->rp->getModel()->whereNull('parent_id');

        // ordered
        if (!empty($this->config['ord'])) {
            $ord = ['id','name','created_at','updated_at'];
            $dir = ['asc','desc'];
            if (!isset($this->config['dir'])) $this->config['dir'] = 0;
            $builder = $builder->orderBy($ord[$this->config['ord']], $dir[$this->config['dir']]);
        }

        $vocabularies = null;
        if (!empty($this->config['comunication']) and $this->request->has('vocabulary')) {
            //Attenzione: nel setting viene salvato come categories ma in realtà sono vocabularies
            $vocabularies = ['categories'=>['category'=>$this->request->vocabulary]];
        } elseif (!empty($this->config['categories'])) {
            $vocabularies = $this->config['categories'];
        }

        if ($vocabularies) {
            $or = "where";
            foreach ($vocabularies as $key=>$vocabulary) {
                if ($key!=0) $or = "orWhere";
                $builder = $builder->$or('vocabulary_id',$vocabulary['category']);
            }
        }

        $categories = $builder->get();
        if ($categories->count()<1) return;
        $nav = new navigation();
        foreach($categories as $category) {
            $this->menu = [$category->name=>['class'=>'treeview','icon'=>'fa-laptop']];
            if (count($category->children)) {
                $this->menu[$category->name]['submenu'] = $this->submenu($category->children);
            } else {
                $url = (!empty($this->config['inpage'])) ?  url($this->config['inpage']) : url()->current();
                $this->menu[$category->name]['url'] = $url.'?'.http_build_query(['category'=>$category->id]);
            }
            $nav->add($this->menu);
        }
        //dd($nav->getNav());
        $title = $this->config['title'];
        return view('navigationcat::navCatGrey')->with(compact('nav','title'))->render();
    }

    public function submenu($categories) {
        static $arr = [];
        foreach($categories as $category) {
            //$arr += [$category->name=>$category->id];
            $url = (!empty($this->config['inpage'])) ?  url($this->config['inpage']) : url()->current();
            $arr += [$category->name=>$url.'?'.http_build_query(['category'=>$category->id])];
            if (count($category->children)) {
                $arr[$category->name]['submenu'] = $this->submenu($category->children);
            }
        }
        return $arr ;
    }

    public function configWidget($widget) {
        return (new categoryController($this->rp))->configWidget($widget, $this->request);
    }
}