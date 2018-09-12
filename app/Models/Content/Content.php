<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'webcontent';

    protected $fillable = array(
        'name', 'slug', 'description', 'content', 'structure_id',
        'model_id', 'status_id', 'displaydate', 'expirationdate', 'inevidence', 'expirationdate_evidence',
        'user_id', 'username', 'hidden_', 'image'
    );

    /**
     * restituisce i tags assegnati al content
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags() {
        return $this->morphToMany('App\Models\Content\Tag', 'taggable');
    }

    /**
     * restituisce tutte le categorie del content
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories() {
        return $this->morphToMany('App\Models\Content\Category', 'categorized')->withPivot('vocabulary_id');
    }

    /**
     * restituisce il modello assegnato al content
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model() {
        return $this->belongsTo('App\Models\Content\Modelli');
    }

    /**
     * restituisce l'immagine associata al content
     * @return mixed|string
     */
    public function getImage() {
        if (! $this->image ) {
            return asset('img/webcontent.jpg');
        } elseif (starts_with($this->image,['http','https'])) {
            return $this->image;
        } else {
            return asset(config('newportal.path_upload_imgwc')."/".$this->image);
        }
    }

    /**
     * restituisce l'user che ha prodotto il content
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * restituisce i commenti associati al content
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments() {
        return $this->morphMany('App\Models\Content\Comment','commentable');
    }

}
