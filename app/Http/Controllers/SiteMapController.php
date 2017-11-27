<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryInterface;
use App\Libraries\siteMap;
use App\Models\Content\Page;


class SiteMapController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $repo)  {
        $this->middleware('web');
        $this->rp = $repo;

    }

    public function sitemap(siteMap $siteMap) {
        //$url = trim(url(), '/') . '/';
        $pages = $this->rp->setModel(Page::class)
            ->where('hidden_',0)
            ->where('status_id',1)
            ->get();

        /**
         * per ogni pagina estraggo le portlet di tipo AssetP
         */
        foreach ($pages as $page) {
            foreach ($page->portlets as $portlet);
                if ($portlet->init=="contentList") {
                    $setting = (!empty($portlet->pivot->setting)) ? json_decode($portlet->pivot->setting, true) : [];
                    if (!empty($setting['sitemap'])) {

                    }
                }
                continue;
        }
        $map = $siteMap->getSiteMap();

        return response($map)
            ->header('Content-type', 'text/xml');
    }
}
