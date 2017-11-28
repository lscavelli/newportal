<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Cache;

class siteMap
{
    private $property = [];

    public function __construct(array $data = [],$value = null)
    {
        $this->set($data,$value);
        $this->property['items'] = [];
    }

    /**
     * Restituisce il contenuto di Site Map
     */
    public function getSiteMap()
    {
        if (Cache::has('site-map')) {
            return Cache::get('site-map');
        }

        $siteMap = $this->render($view = null);
        Cache::add('site-map', $siteMap, 120);
        return $siteMap;
    }

    public function addItem($url,$lastmod=null,$changefreq=null,$priority=null) {
        $url = htmlspecialchars(strip_tags($url), ENT_COMPAT, 'UTF-8');
        if (in_array($url,data_get($this->property['items'],'*.url'))) return;
        $obj = new \stdClass();
        $obj->url = $url;
        $obj->lastmod = $lastmod;
        $obj->changefreq = $changefreq;
        $obj->priority = $priority;
        $this->add($obj);
    }

    private function add($item) {
        $this->property['items'][] = $item;
    }

    public function get($key)
    {
        return array_get($this->property,$key);
    }

    public function set($data,$value=null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->property[$key] = $value;
            }
        } elseif(is_string($data) and !is_null($value)) {
            $this->property[$data] = $value;
        }
        return $this;
    }

    public function render($view = null) {
        $view = !is_null($view) ? "ui.sitemap.".$view : "ui.sitemap.sitemap";
        return View()->make($view, [
            'sitemap' => $this,
        ])->render();
    }
}
