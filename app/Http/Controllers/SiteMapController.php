<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface;
use App\Services\siteMap;
use App\Models\Content\Page;


class SiteMapController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $repo)  {
        $this->middleware('web');
        $this->rp = $repo;

    }

    public function sitemap(siteMap $siteMap)
    {
        $pages = $this->rp->setModel(Page::class)
            ->where('sitemap', 1)
            ->where('status_id', 1)
            ->whereNull('parent_id')
            ->get();

        $this->setMap($pages,$siteMap);

        $map = $siteMap->getSiteMap();

        return response($map)
            ->header('Content-type', 'text/xml');
    }

    /**
     * per ogni pagina estraggo le widget di tipo AssetP
     */
    private function setMap($pages,$siteMap) {
        foreach ($pages as $key=>$page)
        {
            if (count($page->children)) {
                $this->setMap($page->children,$siteMap);
            } else {
                $siteMap->addItem(url($page->slug), $page->updated_at, 'daily');
            }
            foreach ($page->widgets as $widget)
            {
                if ($widget->init == "contentList") {
                    $setting = (!empty($widget->pivot->setting)) ? json_decode($widget->pivot->setting, true) : [];
                    if (!empty($setting['sitemap'])) {
                        $url = (!empty($setting['inpage'])) ?  url($setting['inpage']) : $page->slug;
                        $contents = app()->widget->run($widget->init, null, $widget->path, $setting);
                        foreach ($contents as $content)
                        {
                            $siteMap->addItem(url($url . "/" . $content->slug), $page->updated_at, 'weekly','0.9');
                        }
                    }
                }
            }
        }

    }

}
