<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = array(
        'name', 'class', 'color', 'content'
    );

    /**
     * m-m
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vocabularies() {
        return $this->belongsToMany('App\Models\Content\Vocabulary','vocabularies_services');
    }

    /**
     * 1-m - rest. la lista delle strutture
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function structures() {
        return $this->hasMany('App\Models\Content\Structure');
    }
}
