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

    public function delFile($file) {
        if (empty($file)) return;
        $filepath = sprintf("%s/%s", $this->getPath() , $file);
        if ($this->fs->exists($filepath))  $this->fs->delete($filepath);
    }

    public function setPath($dir) {
        $this->path = sprintf("%s/%s", public_path(), $dir);
    }

    public function getPath() {
        return $this->path;
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