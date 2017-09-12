<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = array(
        'name', 'content', 'user_id',
    );

    /**
     * restituisce l'autore del commento
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    /**
     * restituisce tutti i models commentati
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable() {
        return $this->morphTo();
    }
}
