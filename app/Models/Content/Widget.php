<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $fillable = array(
        'name', 'init', 'description', 'type_id', 'title', 'status_id',
        'author', 'path', 'container', 'revision', 'date', 'structure_id', 'service'
    );

    public function pages() {
        return $this->belongsToMany('App\Models\Content\Page','widgets_pages')
            ->withPivot('frame');
    }

}
