<?php

namespace app\Services;

use Illuminate\Support\Facades\View;


class Breadcrumb {

    private $breadcrumbs = [];
    private $separator = " / ";
    public $tcrumb = [];
    public $showTitle = true;
    public $urlHome ='/admin/dashboard';


    public function __construct($bc=null,$href=null) {
        if (!empty($bc)) $this->add($bc,$href);
        $this->tcrumb['title'] = "Newportal";
    }

    /**
     * @param $crumb
     * @param null $href
     * @return $this
     */
    public function add($crumb,$href=null) {
        if (!empty($crumb)) {
            $breadcrumb = (is_array($crumb)) ? $crumb : [$crumb => $href];
            if (is_array($crumb)) {
                $href = $crumb[1];
                $crumb = $crumb[0];
            }
            if (empty($href)) $this->tcrumb['title'] = $crumb;
            $this->breadcrumbs = array_merge($this->breadcrumbs,$breadcrumb);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function removeAll() {
        $this->breadcrumbs = [];
        return $this;
    }

    public function setSeparator($separator)  {
        if (!empty($separator)) {
            $this->separator = $separator;
        }
        return $this;
    }

    public function count() {
        return count($this->breadcrumbs);
    }

    public function isEmpty() {
        return $this->count() === 0;
    }

    public function getCrumbs() {
        return $this->breadcrumbs;
    }

    public function getSeparator() {
        return $this->separator;
    }

    public function showTitle($show=true) {
        $this->showTitle = $show;
        return $this;
    }

    public function setTcrumb($desc, $title= null) {
        $this->tcrumb['desc'] = $desc;
        if (!empty($title)) {
            $this->tcrumb['title'] = $title;
        }
        return $this;
    }

    /**
     * @param null $viewp
     * @return mixed
     */
    public function render($viewp = null) {
        $view = "ui.breadcrumb";
        if (!empty($viewp)) $view = $viewp;
        return View::make($view, [
            'list' => $this
        ])->render();
    }

    public function getUrlHome() {
        return url($this->urlHome);
    }

    public function setPage($page) {
        $crumbs = [];
        foreach ( $page->getAncestors() as $key => $node ) {
            $parent = '/';
            if ( $key > 0) {
                $parent = $crumbs[$key-1]['url'] . $this->separator;
            }
            $crumbs[] = [
                'title' => $node->crumb,
                'url'   => $parent . $node->crumb,
            ];
        }
        // Current page
        $crumbs[] = [
            'title' => $page->title,
            'url'   => end($crumbs)['url'] . $this->separator . $page->crumb,
        ];
        return $crumbs;
    }



}