<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use App\Repositories\RepositoryInterface;
use App\Libraries\Portlet;

class PortletServiceProvider extends ServiceProvider
{

    /**
    * Indicates if loading of the provider is deferred.
    * @var bool
    */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $portlet = $this->app->make('portlet');
        $dir = app_path().'/'.config("newportal.portlets.namespace").'/';
        /*if(is_dir($dir) and (\Illuminate\Support\Facades\Schema::hasTable('portlets'))) {
            foreach ($portlet->listPagePortlets() as $portlet) {
                $arrayName = explode("\\", $portlet->path);
                $nameAuthor = reset($arrayName);
                $namePortlet = next($arrayName);
                $path = $dir . $nameAuthor."/".$namePortlet;

                if (!File::isDirectory($path)) continue;
                if (!$this->app->routesAreCached()) {

                    $routes = [
                        $path . '/routes/web.php',
                        $path . '/routes/console.php',
                        $path . '/routes/api.php',
                        $path . '/routes/channels.php',
                    ];
                    foreach ($routes as $route) {
                        if (File::exists($route)) $this->loadRoutesFrom($route);
                    }
                }
                if (File::exists($path . '/helper.php')) include_once $path . '/helper.php';
                if (File::isDirectory($path . '/views')) $this->loadViewsFrom($path . '/views', $namePortlet);
                if (File::isDirectory($path . '/translations')) $this->loadTranslationsFrom($path . '/translations', $namePortlet);
                if (File::isDirectory($path . '/assets')) {
                    //$this->publishes([
                    //    $path . '/assets' => sprintf("%s/%s/", public_path(), strtolower(config('newportal.portlets.namespace'))).$nameAuthor."/".$namePortlet,
                    //], 'public');
                }

            }
        }*/
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('portlet', function() {
            return new Portlet($this->app->make(RepositoryInterface::class));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['portlet'];
    }

    protected function configPath()
    {
        //return __DIR__ . '/../config/xxx.php';
    }
}
