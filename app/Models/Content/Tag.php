<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = array(
        'id', 'name'
    );

    public function webcontent() {
        return $this->morphedByMany('App\Models\Content\Content', 'taggable');
    }

}
