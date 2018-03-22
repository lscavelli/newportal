<?php

namespace App\Listeners;

use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;
use Unisharp\Laravelfilemanager\Events\ImageIsDeleting;
use App\Models\Content\File;
use Illuminate\Filesystem\Filesystem;
use App\Repositories\RepositoryInterface;

class FileEventsSubscriber {

    public $fs;
    private $rp;

    public function __construct(Filesystem $fs, RepositoryInterface $rp)
    {
        $this->fs = $fs;
        $this->rp = $rp->setModel(File::class);
    }

    public function onImageIsDeleting(ImageIsDeleting $event)
    {
        $filePath = str_replace(public_path(), "", $event->path());
        $file = $this->rp->where('path', $filePath)->first();
        if ($file) {
            $this->rp->delete($file->id);
        }
    }

    public function onImageWasUploaded(ImageWasUploaded $event)
    {
        $filePath = str_replace(public_path(), "", $event->path());
        $this->rp->create([
            'path' => $filePath,
            'name' => $filePath,
            'file_name' => $this->getFile($event->path()),
            'mime_type' => $this->getMimeType($event->path()),
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
        $events->listen(ImageIsDeleting::class, "{$class}@onImageIsDeleting");
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

}