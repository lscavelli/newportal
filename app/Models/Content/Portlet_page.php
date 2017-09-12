<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Portlet_page extends Model
{
    protected $table = 'portlets_pages';

    protected $fillable = array(
        'portlet_id', 'page_id', 'template', 'position', 'comunication',
        'title', 'css', 'js', 'setting', 'frame', 'name'
    );

    public function portlet() {
        return $this->belongsTo('App\Models\Content\Portlet');
    }
}