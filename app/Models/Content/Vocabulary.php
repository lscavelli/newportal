<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $table = 'vocabularies';

    protected $fillable = array(
        'name', 'description'
    );

    public function categories() {
        return $this->hasMany('App\Models\Content\Category','vocabulary_id');
    }

    public function services() {
        return $this->belongsToMany('App\Models\Content\Service','vocabularies_services')
            ->withPivot('type_order','type_dir','required')
            ->withTimestamps();
    }
}
