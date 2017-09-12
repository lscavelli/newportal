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

    public function tags() {
        return $this->morphToMany('App\Models\Content\Tag', 'taggable');
    }

    public function categories() {
        return $this->morphToMany('App\Models\Content\Category', 'categorized')->withPivot('vocabulary_id');
    }

    public function model() {
        return $this->belongsTo('App\Models\Content\Modelli');
    }

    public function getImage() {
        if (! $this->image ) {
            return asset('img/webcontent.jpg');
        }
        return asset(config('newportal.path_upload_imgwc')."/".$this->image);
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

}
