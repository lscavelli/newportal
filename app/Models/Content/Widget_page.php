<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Widget_page extends Model
{
    protected $table = 'widgets_pages';

    protected $fillable = array(
        'widget_id', 'page_id', 'template', 'position', 'comunication',
        'title', 'css', 'js', 'setting', 'frame', 'name'
    );

    public function widget() {
        return $this->belongsTo('App\Models\Content\Widget');
    }
}