<?php

namespace App\Widgets;

use App\Repositories\RepositoryInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use App\Libraries\Theme;
use ReflectionClass;
use Illuminate\Support\Facades\File;

abstract class abstractWidget {

    public $config = [];
    public $theme;
    protected $request;
    protected $rp;
    protected $view;

    public function __construct(RepositoryInterface $rp, Theme $theme=null, array $config = []) {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
        $this->rp = $rp;
        $this->request = \Request::instance();
        $this->theme = $theme;
    }

    public function getInitConfig() {
        $configFile = $this->getPathClass().'/config.php';
        if (file_exists($configFile)) {
            $this->setConfig(File::getRequire($configFile));
        }
    }

    //public function setDirTemplate($pathWidget) {
    //View()->addLocation($pathWidget);
    //}

    public function setConfig(array $config) {
        $this->config = array_merge($this->config, $config);
    }

    public function setKey($key, $value) {
        $this->config[$key] = $value;
    }

    public function config($key=null) {
        return !is_null($key) ? array_get($this->config,$key) : $this->config;
    }

    public function inizializeConf() {
        $this->config = [];
    }

    abstract public function init();

    abstract public function configWidget($widget);

    abstract public function render();

    protected function applyModel($model, $__data) {

        $__data = array_map(function ($k,$v) {
            return array("np".str_replace("-","",$k)=>$v);
        },array_keys($__data),array_values($__data));

        $__data = array_collapse($__data);
        //dd($__data);
        $__data['__env'] = app(\Illuminate\View\Factory::class);
        $__php = \Blade::compileString($model);

        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }
        return ltrim(ob_get_clean());
        //$__data['__env'] = app(\Illuminate\View\Factory::class);*/
    }

    /**
     * restituisce il path al file chiamante della widget corrente
     * @return string
     */
    public function getPath() {
        return strtolower(str_replace(app_path(),'',$this->getPathClass()))."/";
    }

    private function getPathClass() {
        return dirname((new ReflectionClass(static::class))->getFileName());
    }

    /**
     * Imposta i meta tag della pagina corrente
     * @param $metaTag
     */
    protected function setConfigTheme(array $metaTag) {
        if (count($metaTag)>0) $this->theme->setArguments($metaTag);
    }

    /**
     * mostra le view (list) contenute nella directory view della widget
     * @return array
     * @throws ThemeException
     */
    public function listView() {
        $path = $this->getPathClass()."/views";
        if (!is_dir($path)) {
            throw new ThemeException("la directory dei template non esiste");
        }
        $files = File::allFiles($path);
        $view = [""=>""];
        foreach ($files as $file) {
            $file = substr(basename($file), 0, -10); //remove .blade.php
            if (starts_with($file,'list')) {
                $view[$file] = $file;
            }
        }
        return $view;
    }


}