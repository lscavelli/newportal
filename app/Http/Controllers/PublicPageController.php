<?php

namespace App\Http\Controllers;

use App\Providers\WidgetServiceProvider;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Services\Theme;
use App\Services\Widget;


class PublicPageController extends Controller {

    private $rp;
    //private $mWidget;

    public function __construct()  {
        $this->middleware('web');
        //RepositoryInterface $rp, Widget $mWidget
        //$this->rp = $rp->setModel('App\Models\Content\Page');
        //$this->mWidget = ; //$mWidget->setRepository($rp);
    }

    public function getPage(Theme $theme, $uri=null) {

        //$page = $this->rp->where('slug',$uri)->where('status_id',1)->first();
        $page = app()->make('widget')->getPage();
        if (!$page) app()->abort(404, 'Pagina non trovata');
        if ($page->type_id===1 && !empty($page->url)) {
            return redirect($page->url);
        }
        //if ($page->private && !Auth::check()) abort(403, 'La pagina è riservata');
        $themePage = $page->theme ?: config('newportal.theme-default');
        $layout = $page->layout ?: config('newportal.layout-default');
        $theme->setTheme($themePage)->setLayout($layout,$page->toArray());
        $listWidgets = $page->widgets;
        // ricavo le widgets associate alla pagina
        foreach ($listWidgets as $widget) {
            $data = $setting = $widget->pivot->toArray();
            $data['title'] = ($widget->pivot->title) ?: $widget->title;
            $data['template'] = $widget->pivot->template ?: config('newportal.partial-default');
            $data['setting'] = (!empty($widget->pivot->setting)) ? json_decode($widget->pivot->setting, true) : [];
            $setting = array_merge($setting,$data['setting']); unset($setting['setting']);
            $data['content'] = app()->widget->run($widget->init,$theme,$widget->path,$setting);
            // se priva di contenuti ( $data['content'] == null)
            // verifica l'impostazione della widget se deve essere visualizzata comunque
            // TODO:  INSERIRE NEL SETTING DELLA PORTLET - VISUALIZZA ANCHE SENZA CONTENUTI
            if (!$data['content'] && !auth()->check()) continue;
            if (empty($data['content'])) $data['content'] = view('ui.widget')->with(['widget'=>$widget]);
            $theme->addWidget($data);
        }
        // aggiungo gli asset dei temi
        return $theme->render();

        //\View::exists($layout_dir.$layout_name)
        //return view($layout_dir.$layout_name)->with($data);
    }
}
