<?php

namespace App\Listeners;

use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
use UniSharp\LaravelFilemanager\Events\ImageWasDeleted;
use UniSharp\LaravelFilemanager\Events\ImageWasRenamed;
use UniSharp\LaravelFilemanager\Events\ImageIsUploading;
use UniSharp\LaravelFilemanager\Events\FolderWasRenamed;
use App\Models\Content\File;
use Illuminate\Filesystem\Filesystem;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Storage;

class FileEventsSubscriber {

    public $fs;
    private $rp;
    private $storage;

    public function __construct(Filesystem $fs, RepositoryInterface $rp)
    {
        $this->fs = $fs;
        $this->rp = $rp->setModel(File::class);
        $this->storage =  Storage::disk(config('lfm.disk'))->getDriver()->getAdapter()->getPathPrefix();
    }

    /**
     * Rinomina il path dei file
     * @param FolderWasRenamed $event
     */
    public function onFolderWasRenamed(FolderWasRenamed $event)
    {
        $oldFolderPath = str_replace($this->storage, "", $event->oldPath());
        $newFolderPath = str_replace($this->storage, "", $event->newPath());
        //info($oldFolderPath);
        $this->rp->getModel()->where('path', $oldFolderPath)->update(['path'=>$newFolderPath]);
    }

    /**
     * dovrebbe impedire l'upload - da verificare
     * @return bool
     */
    public function onImageIsUploading()
    {
        if (!auth()->guard('web')->check()) {
            return false;
        }
    }

    /**
     * Rinomina l'immagine nel db dopo che è stata rinominata fisicamente
     * @param ImageWasRenamed $event
     */
    public function onImageWasRenamed(ImageWasRenamed $event)
    {
        $oldFilePath = str_replace($this->storage, "", $event->oldPath());
        $newFilePath = str_replace($this->storage, "", $event->newPath());
        $file = $this->rp
            ->where('path', $this->getDirname($oldFilePath))
            ->where('file_name', $this->getFile($event->oldPath()))
            ->first();
        if ($file) $this->rp->update($file->id,[
                    'path' => $this->getDirname($newFilePath),
                    'file_name' => $this->getFile($event->newPath()),
                    ]);
    }

    /**
     * Cancella il file dal db dopo che è stato fisicamente eliminato
     * @param ImageWasDeleted $event
     */
    public function onImageWasDeleted(ImageWasDeleted $event)
    {
        $filePath = str_replace($this->storage, "", $event->path());
        $file = $this->rp
            ->where('path', $this->getDirname($filePath))
            ->where('file_name', $this->getFile($event->path()))
            ->first();
        if ($file) $this->rp->delete($file->id);
    }

    /**
     * Crea il file nel database dopo che è stato caricato
     * @param ImageWasUploaded $event
     */
    public function onImageWasUploaded(ImageWasUploaded $event)
    {
        $filePath = str_replace($this->storage, "", $event->path());
        $file = $this->getFile($event->path());
        $this->rp->create([
            'path' => $this->getDirname($filePath),
            'name' => $file,
            'slug' => $this->rp->makeSlug($file),
            'file_name' => $file,
            'mime_type' => $this->getMimeType($event->path()),
            'extension' => $this->getExtension($event->path()),
            'size' => $this->getSize($event->path()),
            'user_id'=> auth()->user()->id,
            'username'=> auth()->user()->username
        ]);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = 'App\Listeners\FileEventsSubscriber';
        $events->listen(ImageWasUploaded::class, "{$class}@onImageWasUploaded");
        $events->listen(ImageWasDeleted::class, "{$class}@onImageWasDeleted");
        $events->listen(ImageWasRenamed::class, "{$class}@onImageWasRenamed");
        $events->listen(ImageIsUploading::class, "{$class}@onImageIsUploading");
        $events->listen(FolderWasRenamed::class, "{$class}@onFolderWasRenamed");
    }

    /**
     * restituisce il nome del file
     * @param $filePath
     * @return string
     */
    public function getFile($filePath) {
        return $this->fs->basename($filePath);
    }

    /**
     * restituisce il MimeType
     * @param $filePath
     * @return string
     */
    public function getMimeType($filePath) {
        return $this->fs->mimeType($filePath);
    }

    /**
     * restituisce la dimensione del file
     * @param $filePath
     * @return string
     */
    public function getSize($filePath) {
        return $this->fs->size($filePath);
    }

    /**
     * restituisce la base della dir
     * @param $filePath
     * @return string
     */
    public function getDirname($filePath) {
        return $this->fs->dirname($filePath);
    }

    /**
     * restituisce il suffisso del file es. .pdf
     * @param $filePath
     * @return string
     */
    public function getExtension($filePath) {
        return strtolower($this->fs->extension($filePath));
    }

}
