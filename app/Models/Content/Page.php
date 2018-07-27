<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = array(
        'name', 'slug', 'description', 'type_id', 'parent_id',
        'keywords', 'title', 'robots', 'css', 'javascript', 'hidden_',
        'status_id', 'layout', 'theme', 'user_id', 'username', 'url', 'sitemap'
    );

    public function parent() {
        return $this->belongsTo('App\Models\Content\Page', 'parent_id');
    }

    public function children() {
        return $this->hasMany('App\Models\Content\Page', 'parent_id');
    }

    public function widgets()    {
        return $this->belongsToMany('App\Models\Content\Widget','widgets_pages')
            ->withPivot('id','frame', 'template', 'position', 'comunication', 'title', 'css', 'js', 'setting')
            ->withTimestamps();
    }

    public function resources() {
        return $this->belongsToMany('App\Models\Content\Widget_page','widgets_pages','page_id','id');
    }

}
