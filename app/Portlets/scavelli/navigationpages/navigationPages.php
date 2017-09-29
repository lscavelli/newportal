<?php

namespace app\Portlets\scavelli\navigationpages;

use App\Portlets\abstractPortlet as Portlet;
use App\Libraries\navigation;
use App\Portlets\scavelli\navigationpages\Controllers\pageController;

class navigationPages extends Portlet {

    private $menu;

    public function init() {
        $this->rp->setModel('App\Models\Content\Page');

        $this->theme->addExCss($this->getPath().'css/navpages.css');//menupages.css
        $this->theme->addExJs($this->getPath().'js/navpages.js');
    }

    public function getContent() {

        $builder = $this->rp->getModel()->where('status_id',1);
        if ($this->config('page')) {
            $id = $this->rp->findBySlug($this->config('page'))->id;
            $builder = $builder->where('parent_id',$id);
        } else {
            $builder = $builder->whereNull('parent_id');
        }
        $builder = $builder->where('hidden_',0);
        $pages = $builder->get();

        $nav = new navigation();
        foreach($pages as $page) {
            $this->menu = [$page->name=>['class'=>'treeview','icon'=>'fa-laptop']];
            if (count($page->children)) {
                $this->menu[$page->name]['submenu'] = $this->submenu($page->children);
            } else {
                if ($page->type_id===1 && !empty($page->url) && starts_with($page->url,["http","https"])) {
                    $this->menu[$page->name]['external_link'] = 1;
                }
                $this->menu[$page->name]['url'] = url($page->slug);
            }
            $nav->add($this->menu);
        }
        //dd($nav->getNav());
        $title = $this->config['title'];
        return view('navigationpages::navPagesGrey')->with(compact('nav','title'));
    }

    public function submenu($pages) {
        //static $arr;
        $arr = [];
        foreach($pages as $page) {
            if (count($page->children)>0) {
                $arr[$page->name]['submenu'] = $this->submenu($page->children);
            } else {
                $arr[$page->name] = $page->slug;
            }
        }
        return $arr;
    }

    public function configPortlet($portlet) {
        return (new pageController($this->rp))->configPortlet($portlet, $this->theme);
    }
}