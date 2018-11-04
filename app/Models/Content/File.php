<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = array(
        'name', 'slug', 'path', 'description', 'file_name', 'mime_type', 'size', 'position',
        'user_id', 'username', 'hits', 'status_id', 'extension'
    );

    /**
     * restituisce i tags assegnati al file
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags() {
        return $this->morphToMany('App\Models\Content\Tag', 'taggable');
    }

    /**
     * restituisce tutte le categorie assegnate al file
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories() {
        return $this->morphToMany('App\Models\Content\Category', 'categorized')->withPivot('vocabulary_id');
    }

    /**
     * restituisce l'user che ha prodotto il file
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Verifica se il file è di tipo immagine
     * @return bool
     */
    public function isImage() {
        if(substr($this->mime_type, 0, 5) == 'image') {
            return true;
        }
    }

    /**
     * Restituisce l'URL del file
     * @return string
     */
    public function getUrl() {
        //return $this->path."/".$this->file_name;
        return Storage::url($this->path."/".$this->file_name);
    }

    /**
     * Restituisce il fullpath del file
     * @return string
     */
    public function getPath() {
        return Storage::disk(config('newportal.disk'))->getDriver()->getAdapter()->getPathPrefix().$this->path;
    }

    /**
     * restituisce l'icona del file
     * @return string
     */
    public function getIcon() {
        return config('lfm.file_icon_array.' . $this->extension) ?: 'fa-file';
    }

}
