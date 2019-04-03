<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use App\Services\ThemeException;
use Illuminate\Support\Facades\View;
use PragmaRX\Google2FALaravel\Support\Authenticator;


// dal template loadcss()
// setTheme($theme)->setLayout(index.php,$arg)->render();

class Theme {

    protected $files;
    protected $theme;
    protected $layout;
    protected $_excss = [];
    protected $_exjs = [];
    protected $_js;
    protected $_css;
    protected $arguments = array();
    protected $frames = array();
    private   $namespaceLayout;
    protected $config = array();
    public $page;


    public function __construct(Filesystem $files) {
        $this->files = $files;
    }

    private function isAuthorized() {
        return (auth()->check() && auth()->user()->can('widget-control'));
    }

    public function getThemeName() {
        return $this->theme;
    }
    public function getLayoutName() {
        return $this->layout;
    }

    private function getPathThemes() {
        return public_path().'/'.config('newportal.theme-dir');
    }

    public function getPathLayouts($theme=null) {
        if (is_null($theme)) $theme = $this->theme;
        return $this->getPathThemes().'/'.$theme.'/'.config('newportal.themeSubDir.layout');
    }

    public function getPathPartials($theme=null) {
        if (is_null($theme)) $theme = $this->theme;
        return $this->getPathThemes().'/'.$theme.'/'.config('newportal.themeSubDir.partial');
    }

    private function getPathAsset() {
        return $this->getPathThemes().'/'.$this->theme.'/'.config('newportal.themeSubDir.asset').'/';
    }

    public function themeExists($theme) {
        return is_dir($this->getPathThemes().'/'.$theme);
    }

    public function layoutExists($layout) {
        return View()->exists($layout);
    }

    /**
     * Imposta il tema
     * @param $theme
     * @return $this
     */
    public function setTheme($theme) {
        if (!$this->themeExists($theme)) {
            throw new ThemeException("Il tema \"$theme\" non esiste.");
        }
        $this->theme = $theme;
        View()->addLocation($this->getPathThemes().'/'.$theme);
        View()->addNamespace($theme,  $this->getPathThemes().'/'.$theme);
        return $this;
    }

    /**
     * Imposta il layout
     * @param $layout
     * @param array $args
     * @return $this
     */
    public function setLayout($layout, $args = array()) {
        $this->namespaceLayout = $this->theme.'::'.config('newportal.themeSubDir.layout').'.'.$layout;
        if (!$this->layoutExists($this->namespaceLayout)) {
            throw new ThemeException("Il layout \"$layout\" non è statto trovato");
        }
        $this->layout = $layout;
        if (isset($args['name'])) $args['title'] = $args['name'];
        $this->setArguments($args);
        return $this;
    }

    /**
     * imposta la var arguments utilizzati dal metodo get() per Meta Tag page
     * @param $args
     */
    public function setArguments($args) {
        if (is_array($args) && count($args)>0)
            $this->arguments = array_merge($this->arguments,$args);
    }

    public function removeAsset() {
        return $this;
    }

    /**
     * restituisce la lista dei temi come array
     * @return array
     */
    public function listThemes() {
        $path = $this->getPathThemes();
        if (!is_dir($path)) {
            throw new ThemeException("la cartella dei temi non esiste");
        }
        $themes = [];
        $dirPath = $this->files->directories($path);
        foreach ($dirPath as $dir) {
            $dir = basename($dir);
            $themes[$dir] = $dir;
        }
        return $themes;
    }

    /**
     * restituisce la lista dei template parziali come array
     * @return array
     */
    public function listPartials($theme=null) {
        $path = $this->getPathPartials($theme);
        if (!is_dir($path)) {
            throw new ThemeException("la directory dei template parziali non esiste");
        }
        $files = $this->files->files($path);
        $layouts = [];
        foreach ($files as $file) {
            $file = substr(basename($file), 0, -10); //remove .blade.php
            $layouts[$file] = $file;
        }
        return $layouts;
    }

    /**
     * restituisce la lista dei layout presenti nel tema
     * @return array
     */
    public function listLayouts($theme=null) {
        $path = $this->getPathLayouts($theme);
        //if (!is_dir($path)) {
        //    throw new ThemeException("la directory dei layouts non esiste");
        //}
        $files = $this->files->files($path);
        $layouts = [];
        foreach ($files as $file) {
            $file = substr(basename($file), 0, -10); //remove .blade.php
            $layouts[$file] = $file;
        }
        return $layouts;
    }

    public function addCss($source) {
        $this->addAsset('css', $source);
    }

    public function addJs($source,$position='body') {
        $this->addAsset('js', $source, $position);
    }

    public function addAsset($type, $source, $position='body') {
        switch ($type) {
            case 'javascript' :
            case 'js' :
            case 'script' : {
                if (!isset($this->_js[$position])) $this->_js[$position] = null;
                $this->_js[$position] .=  $source. "\n";
            } break;
            case 'css' :
            case 'style' :
            $this->_css .= $source. "\n"; break;
        }
    }

    public function url($uri) {
        if (preg_match('#^http|//:#', $uri)) {
            return $uri;
        }
        if (file_exists($this->getPathAsset().$uri)) {
            return asset(config('newportal.theme-dir').'/'.$this->theme.'/'.config('newportal.themeSubDir.asset').'/'.$uri);
        }
        return $this->getPathAsset().$uri;
    }


    /**
     * Imposta Asset esterni
     * @param $file
     * @param $type
     * @param $index
     */
    private function addExAsset($file,$type,$index) {
        $file = trim($file);
        $pathAsset = $this->getPathAsset();
        $dir = $pathAsset."$type/";
        if (file_exists($dir.$file)) {
            $file = $this->asset("$type/".$file);
        } elseif (file_exists($pathAsset.$file)) {
            $file = $this->asset($file);
        }
        if (!empty($file)) {
            $type = '_ex'.$type;
            //if (!isset($this->$type[$index])) $this->$type[$index] = NULL;
            if (!isset($this->$type[$index])) $this->$type[$index] = [];
            if (!in_array($file,$this->$type[$index])) $this->$type[$index][] = $file;
        }
    }

    /**
     * Wrapper to addExAsset con js
     * @param $file
     * @param string $position
     */
    public function addExJs($file,$position="body") {
        $this->addExAsset($file,'js',$position);
    }

    /**
     * Wrapper to addExAsset con css
     * @param $file
     * @param string $media
     */
    public function addExCss($file,$media="screen") {
        $this->addExAsset($file,'css',$media);
    }

    /**
     * Svuota la variabile _excss
     * @param string $media
     */
    function emptyExCss($media="all") {
        if ($media=="all") {
            unset($this->_excss);
        } else {
            unset($this->_excss[$media]);
        }
    }

    // da capire come trasferire arguments
    public function render() {
        if (!View()->exists($this->namespaceLayout)) {
            throw new ThemeException("Il Layout \"$this->layout\" non esiste.");
        }
        return View::make($this->namespaceLayout, [
            'theme' => $this
        ])->render();
    }

    /**
     * aggiunge una widget alla proprietà $this->frames
     * @param $data
     * @return $this
     */
    public function addWidget($data) {
        //View::addLocation('/additional/path/to/search/in');
        $pathTemplate = $this->theme.'::'.config('newportal.themeSubDir.partial').'.'.$data['template'];
        if (!View()->exists($pathTemplate)) {
            throw new ThemeException("Il template \"{$data['template']}\" non è stato trovato.");
        }
        if (empty($data['position'])) $data['position'] = 100;
        if (!empty($data['css'])) $this->addCss($data['css']);
        if (!empty($data['js'])) $this->addJs($data['js']);
        $content = View::make($pathTemplate, $data)->render();
        if ($this->isAuthorized()) {
            $div = "<div
            id = 'ctrl_{$data["id"]}'
            class='fld-extensive";
            if (count($data['setting'])<1 and $data['comunication']!=1) $div .= " noset";
            $div .= "'
            data-pivotid='{$data["id"]}'
            data-pageid='{$data["page_id"]}'
            data-frame='{$data["frame"]}'
            >$content</div>";
            $content = $div;
        }
        $this->frames[$data['frame']][$data['position']][] = $content;
        return $this;
    }

    /**
     * restituisce l'array del config o il valore della chiave passata come argomento
     * @param null $key
     * @return array|mixed
     * @throws ThemeException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function config($key = null) {
        if (count($this->config)<1) {
            $configPath = $this->getPathThemes().'/'.$this->theme.'/config.php';
            if (!file_exists($configPath)) {
                throw new ThemeException("Il file di Config \"$configPath\" non è stato trovato.");
            }
            $this->config = $this->files->getRequire($configPath);
        }
        return is_null($key) ? $this->config : array_get($this->config, $key);
    }

    public function listFramesOfLayout($layout=null) {
        $frames = $this->config('master');
        if (!is_null($layout)) {
            $framesLayout = $this->config($layout);
            $framesLayout = is_array($framesLayout) ? $framesLayout : [];
            $frames = array_merge($frames,$framesLayout);
        }
        return $frames;
    }

    /**
     * carica i css da template
     * @return null|string
     */
    public function loadCSS() {
        $css = array();

        if(sizeof($this->_excss) > 0) {
            foreach ($this->_excss as $media=>$value){
                foreach ($value as $href){
                    $css[] = "<link rel=\"stylesheet\" media=\"$media\" type=\"text/css\" href=\"$href\" />";
                }
            }
        }
        if(isset($this->_css)) {
            $css[] = "<style type=\"text/css\">";
            $css[] = "<!--";
            $css[] = $this->_css;
            $css[] = "-->";
            $css[] = "</style>";
            $this->_css = null;
        }

        if(count($css)>0) {
            unset($this->_excss,$this->_css);
            return implode("\n", $css)."\n";
        } else {
            return null;
        }
    }

    public function style() {
        return $this->loadCSS();
    }

    /**
     * carica i js da template
     * @return null|string
     */
    public function loadJS($position='body') {
        $js = array();

        if(isset($this->_exjs[$position]) && sizeof($this->_exjs[$position]) > 0) {
            foreach ($this->_exjs[$position] as $src){
                $js[] = "<script type=\"text/javascript\" src=\"$src\"></script>";
            }
        }

        if(isset($this->_js[$position])) {
            $js[] = "<script type='text/javascript'>";
            $js[] = "/* <![CDATA[ */";
            $js[] = $this->_js[$position];
            $js[] = "/* ]]> */";
            $js[] = "</script>";
            $this->_js[$position] = null;
        }

        if(count($js)>0) {
            //$js[] = "<noscript><meta http-equiv=\"refresh\" content=\"7; url=index.php?page=schede&id=10\"></noscript>";
            unset($this->_exjs,$this->_js);
            return implode("\n", $js)."\n";
        } else {
            return null;
        }
    }

    public function js($position='body') {
        return $this->loadJS($position);
    }

    /**
     * restituisce il content corrispondente alla chiave - per template
     * @param $key
     * @return mixed
     */
    public function get($key, $default=null) {
        return array_get($this->arguments, $key, $default);
    }

    /**
     * restituisce i contents disponibili nella proprietà frames - da template
     * @param $frame
     * @return mixed
     */
    public function getFrame($frame) {
        $widgets = null;
        if (!empty($frame) and key_exists($frame,$this->frames)) {
            ksort($this->frames[$frame]);
            $widgets = null; $and = "";
            foreach ($this->frames[$frame] as $items) {
                $widgets .= $and . implode("\r\n",$items); $and = "\r\n";
            }
        }
        if ($this->isAuthorized()) {$widgets = "<div class='droppedArea' data-frame='$frame' data-page='{$this->arguments["id"]}'>$widgets</div>";}
        return $widgets;
    }

    /**
     * Verifica che l'utente con 2fa abilitato sia autenticato in 2fa
     * @return bool
     * @throws \Exception
     */
    public function check2fa() {
        if (array_get(cache('settings'), '2fa_activation')) {
            $authenticator = app(Authenticator::class)->boot(request());
            if ($authenticator->isAuthenticated()) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * imposta la pagina corrente
     * @param $page
     */
    public function setPageCurrent($page) {
        $this->page = $page;
    }

    /**
     * chiama il metodo passato come argomento della classe $class
     * @param $class
     * @param $method
     * @return mixed
     */
    public function call($class,$method) {
        if (class_exists($class)) {
            return app()->make($class)->$method();
        }
    }

}
