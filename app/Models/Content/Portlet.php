<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Portlet extends Model
{
    protected $table = 'portlets';

    protected $fillable = array(
        'name', 'init', 'description', 'type_id', 'title', 'status_id',
        'author', 'path', 'container', 'revision', 'date'
    );

    public function pages() {
        return $this->belongsToMany('App\Models\Content\Page','portlets_pages')
            ->withPivot('frame');
    }

}
