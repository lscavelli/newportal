<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = array(
        'name', 'code', 'parent_id','vocabulary_id', 'color'
    );

    public function parent() {
        return $this->belongsTo('\App\Models\Content\Category', 'parent_id');
    }

    public function children() {
        return $this->hasMany('\App\Models\Content\Category', 'parent_id');
    }

    public function webcontent() {
        return $this->morphedByMany('App\Models\Content\Content', 'categorized');
    }

    public function vocabulary() {
        return $this->belongsTo('\App\Models\Content\Vocabulary', 'vocabulary_id');
    }

    /**
     * restituisce il colore
     *
     * @param  string  $value
     * @return string
     */
    public function getColorAttribute($value)
    {
        return empty($value) ? '#16813D' : $value;
    }
}
