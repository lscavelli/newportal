<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = array(
        'name', 'path', 'description', 'file_name', 'mime_type', 'size', 'position',
        'user_id', 'username', 'hits', 'status_id'
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

}
