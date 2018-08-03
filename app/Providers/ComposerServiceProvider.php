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
        View()->share('breadcrumb', new \App\Services\breadcrumb());
        View()->share('composer', new \App\Services\composerView());
        View()->share('navigation', new \App\Services\navigation());
        try {
            View()->share('cspages', $this->app
                ->make(RepositoryInterface::class)
                ->setModel(Page::class)
                ->where('status_id',1)
                ->limit(4)
                ->orderby('id', 'desc')
                ->get());
        } catch (\Exception $e) {
            //
        }
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
