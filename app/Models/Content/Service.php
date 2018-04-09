<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = array(
        'name', 'class', 'color', 'content'
    );

    public function vocabularies() {
        return $this->belongsToMany('App\Models\Content\Vocabulary','vocabularies_services');
    }
}
