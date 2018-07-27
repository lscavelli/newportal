<?php

namespace App\Libraries;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Validator;
use Exception;
use ZipArchive;

class WidgetManage {

    private $fs;
    private $request;
    private $config = [];
    private $fileConfig = null;
    private $tempContainer = null;
    const EXTENSION = 'ptle';

    public function __construct(Request $request, Filesystem $fileSystem) {
        $this->validator($request->all())->validate();
        $this->request = $request;
        $this->fs = $fileSystem;
    }

    private function validator(array $data)   {
        return Validator::make($data, [
            'fileWidget' => 'sometimes|file|mimetypes:application/zip,application/octet-stream|max:10240',
        ]);
    }

    /**
     * Upload della widget
     * @param $rp
     * @throws Exception
     */
    public function uploadWidget($rp) {
        // Prende il file dalla request
        $file = $this->getArhive();
        // Verifica l'estensione
        $this->checkExtension($file);
        // Prende nota del path delle widgets
        $path = $this->getDir();
        // Sposta il file nel Path delle Widget
        $newFile = $file->move($path, $file);
        // Estrae il contenuto dal file compresso
        $this->extractArchive($newFile->getPathname(),$path);
        // Verifico se esiste la dir assets ed eventualmente
        // sposto il contenuto nella dir public
        $this->assetPublisher();
        // Carico la widget nel db se non esiste
        foreach ($this->config() as $init=>$widget) {
            if (!$rp->findBy(['init'=>$init])) {
                $widget['init'] = $init;
                $widget['type_id'] = $widget['type'];
                unset($widget['type']);
                $rp->create($widget);
            }
        }
    }

    /**
     * Copia la dir asset nella cartella pubblica
     */
    private function assetPublisher() {
        $assetOrigin = $this->getDir().$this->getPathConfig().'/assets';
        if ($this->fs->exists($assetOrigin)) {
            $assetDest = $this->getPublicPath($this->getPathConfig());
            $this->fs->copyDirectory($assetOrigin,$assetDest);
        }
    }

    /**
     * Estrae il contenuto dal file compresso
     * @param $file
     * @param $extractTo
     * @throws Exception
     */
    private function extractArchive($file,$extractTo) {
        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE ) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                if (pathinfo($zip->getNameIndex($i))['basename']=="config.php") {
                    $this->fileConfig = $this->getDir().$zip->getNameIndex($i);
                    $zip->extractTo($extractTo);
                    $this->config();
                    break;
                }
            }
            $zip->close();
            $this->fs->delete($file);
        } else {
            throw new Exception("Extract Archive Fallito");
        }
    }

    /**
     * verifica l'estensione del file
     * @param $file
     * @throws Exception
     */
    private function checkExtension($file) {
        $extension = $file->getClientOriginalExtension();
        if ($extension != self::EXTENSION) {
            throw new Exception("Estensione del file non accettata");
        }
    }

    /**
     * Restituisce le impostazioni del file config della Widget
     * @param null $key
     * @return array|mixed
     * @throws Exception
     */
    public function config($key = null) {
        if (count($this->config)<1) {
            if (!file_exists($this->fileConfig)) {
                throw new Exception("Il file di Config \"$this->fileConfig\" non è stato trovato.");
            }
            $this->config = $this->fs->getRequire($this->fileConfig);
        }
        return is_null($key) ? $this->config['widgets'] : array_get($this->config['widgets'], $key);
    }

    /**
     * restituisce il file caricato dal modulo
     * @return array|\Illuminate\Http\UploadedFile|null
     */
    private function getArhive() {
        return $this->request->file('fileWidget');
    }

    /**
     * Restituisce il path delle widgets
     * @return string
     */
    private function getDir() {
        return sprintf("%s/%s/", app_path(), config('newportal.widgets.namespace'));
    }

    /**
     * Restituisce il path della dir public completo del contenitore delle widgets
     * @param null $path
     * @return string
     */
    private function getPublicPath($path=null){
        return sprintf("%s/%s/", public_path(), strtolower(config('newportal.widgets.namespace'))).$path;
    }

    private function getTempContainer() {
        if (empty($this->tempContainer)) {
            $this->tempContainer = sprintf("%s", md5(str_random() . time()));
        }
        return $this->tempContainer;
    }

    /**
     * restituisce il path impostato nel config della widget
     * @param null $path
     * @return mixed
     * @throws Exception
     */
    private function getPathConfig($path=null) {
        $pathCons = null;
        if (!is_null($path)){
            $pathCons =  $path;
        } elseif (count($this->config)>0) {
            $pathCons = array_first($this->config())['path'];
        }
        return str_replace("\\","/", $pathCons);
    }

    /**
     * Disinstalla la widget
     * @param $id
     * @param $rp
     * @return bool
     * @throws Exception
     */
    public function uninstallWidget($id, $rp) {

        // Cancello dal Db le widget aventi il path simile a quella passata come argomento
        // e' possibile uploadare più widget
        $widget = $rp->find($id);
        $rp->getModel()->where('path',$widget->path)->delete();

        // Cancello le Dir delle widget
        $path = $this->getPathConfig($widget->path);
        $this->fs->deleteDirectory($this->getDir().$path);
        $this->fs->deleteDirectory($this->getPublicPath($path));

        // Cancello le dir del vendor se priva di pacchetti e contenuti
        $vendorDir = explode('/',$path)[0];
        $masterDir = $this->getDir().$vendorDir;
        if (is_dir($masterDir) && count($this->fs->allFiles($masterDir))<1) {
            $this->fs->deleteDirectory($masterDir);
        }
        $masterPublicDir = $this->getPublicPath($vendorDir);
        if (is_dir($masterPublicDir) && count($this->fs->allFiles($masterPublicDir))<1) {
            $this->fs->deleteDirectory($masterPublicDir);
        }
        return true;
    }
}