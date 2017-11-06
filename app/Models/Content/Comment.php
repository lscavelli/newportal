<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = array(
        'name', 'content', 'user_id', 'email', 'author_ip', 'author', 'approved',
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
