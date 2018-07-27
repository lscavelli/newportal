<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use App\Repositories\RepositoryInterface;
use App\Libraries\Widget;

class WidgetServiceProvider extends ServiceProvider
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
        $widget = $this->app->make('widget');
        $dir = app_path().'/'.config("newportal.widgets.namespace").'/';

        try {
            if(is_dir($dir) and (\Illuminate\Support\Facades\Schema::hasTable('widgets'))) {
                foreach ($widget->listPageWidgets() as $widget) {
                    $arrayName = explode("\\", $widget->path);
                    $nameAuthor = reset($arrayName);
                    $nameWidget = next($arrayName);
                    $path = $dir . $nameAuthor."/".$nameWidget;

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
                    if (File::isDirectory($path . '/views')) $this->loadViewsFrom($path . '/views', $nameWidget);
                    if (File::isDirectory($path . '/translations')) $this->loadTranslationsFrom($path . '/translations', $nameWidget);
                    if (File::isDirectory($path . '/assets')) {
                        //$this->publishes([
                        //    $path . '/assets' => sprintf("%s/%s/", public_path(), strtolower(config('newportal.widgets.namespace'))).$nameAuthor."/".$nameWidget,
                        //], 'public');
                    }

                }
            }
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
        $this->app->singleton('widget', function() {
            return new Widget($this->app->make(RepositoryInterface::class));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['widget'];
    }

    protected function configPath()
    {
        //return __DIR__ . '/../config/xxx.php';
    }
}
