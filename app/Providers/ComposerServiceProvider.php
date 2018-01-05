<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Repositories\RepositoryInterface;
use App\Models\Content\Page;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View()->share('breadcrumb', new \App\Libraries\breadcrumb());
        View()->share('composer', new \App\Libraries\composerView());
        View()->share('navigation', new \App\Libraries\navigation());
        View()->share('cspages', $this->app
            ->make(RepositoryInterface::class)
            ->setModel(Page::class)
            ->where('status_id',1)
            ->limit(4)
            ->orderby('id', 'desc')
            ->get());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
