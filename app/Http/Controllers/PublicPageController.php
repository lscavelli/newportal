<?php

namespace App\Http\Controllers;

use App\Providers\PortletServiceProvider;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Theme;
use App\Libraries\Portlet;


class PublicPageController extends Controller {

    private $rp;
    //private $mPortlet;

    public function __construct()  {
        //RepositoryInterface $rp, Portlet $mPortlet
        //$this->rp = $rp->setModel('App\Models\Content\Page');
        //$this->mPortlet = ; //$mPortlet->setRepository($rp);
    }

    public function getPage(Theme $theme, $uri=null) {

        //$page = $this->rp->where('slug',$uri)->where('status_id',1)->first();
        $page = app()->make('portlet')->getPage();
        if (!$page) app()->abort(404, 'Pagina non trovata');
        //if ($page->private && !Auth::check()) abort(403, 'La pagina è riservata');
        $themePage = $page->theme ?: config('newportal.theme-default');
        $layout = $page->layout ?: config('newportal.layout-default');
        $theme->setTheme($themePage)->setLayout($layout,$page->toArray());
        $listPortlets = $page->portlets;
        // ricavo le portlets associate alla pagina
        foreach ($listPortlets as $portlet) {
            $data = $setting = $portlet->pivot->toArray();
            $data['title'] = ($portlet->pivot->title) ?: $portlet->title;
            $data['template'] = $portlet->pivot->template ?: config('newportal.partial-default');
            $data['setting'] = (!empty($portlet->pivot->setting)) ? json_decode($portlet->pivot->setting, true) : [];
            $setting = array_merge($setting,$data['setting']); unset($setting['setting']);
            $data['content'] = app()->portlet->run($portlet->init,$theme,$portlet->path,$setting);
            $theme->addPortlet($data);
        }
        // aggiungo gli asset dei temi
        return $theme->render();

        //\View::exists($layout_dir.$layout_name)
        //return view($layout_dir.$layout_name)->with($data);
    }
}
