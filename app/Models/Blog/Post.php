<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = array(
        'name', 'slug', 'summary', 'content', 'seen', 'status_id', 'user_id', 'username'
    );

    /**
     * restituisce i tags assegnati al post
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags() {
        return $this->morphToMany('App\Models\Content\Tag', 'taggable');
    }

    /**
     * restituisce tutte le categorie del POST
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories() {
        return $this->morphToMany('App\Models\Content\Category', 'categorized');
    }

    /**
     * restituisce tutti i commenti del post
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments() {
        return $this->morphMany('App\Models\Blog\Comment','commentable');
    }

}
