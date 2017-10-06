<?php

namespace App\Libraries;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Validator;
use Intervention\Image\ImageManager;

class Images {

    private $fs;
    private $request;
    private $imageManager;
    private $path = null;

    public function __construct(Request $request, Filesystem $fileSystem,ImageManager $imageManager) {
        $this->validator($request->all())->validate();
        $this->request = $request;
        $this->fs = $fileSystem;
        $this->imageManager = $imageManager;
        $this->setPath(config('newportal.path_upload_user'));
    }

    private function validator(array $data)   {
        return Validator::make($data, [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    public function uploadImage($oldfile=null,$w=160,$h=160) {
        $this->delFile($oldfile);
        $file = $this->getFile('image');
        $nameFile = $this->makeName($file);
        $newFile = $file->move($this->getPath(), $nameFile);
        $this->resizeImage($nameFile,$w,$h);
        return [$nameFile, $newFile];
    }

    private function getFile($formdata) {
        return $this->request->file($formdata);
    }

    /**
     * cancella il file - se non contiente il path lo aggiunge
     * @param $file
     */
    public function delFile($file) {
        if (empty($file)) return;
        if ($this->fileExists($file))  $this->fs->delete($file);
    }

    /**
     * imposto il path delle immagini
     * @param $dir
     */
    public function setPath($dir) {
        $this->path = sprintf("%s/%s", public_path(), $dir);
    }

    /**
     * restituisco il path completo delle immagini
     * @return null
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * verifico che il file completo di path esista
     * @param $file
     * @return bool
     */
    public function fileExists($file) {
        $file = sprintf("%s/%s", $this->getPath() , $file);
        return ($this->fs->exists($file));
    }

    private function makeName($file) {
        return sprintf("%s.%s", md5(str_random() . time()),
            $file->getClientOriginalExtension());
    }

    private function resizeImage($filename,$w,$h) {
        $file = sprintf("%s/%s", $this->getPath(), $filename);
        $this->imageManager->make($file)->fit($w,$h, function ($constraint) {
            //$constraint->aspectRatio();
            $constraint->upsize();
        })->save();
    }
}