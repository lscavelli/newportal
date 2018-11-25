<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;


class Modelli extends Model
{
    protected $table = 'models';

    protected $fillable = array(
        'name', 'description', 'content', 'type_id', 'structure_id',
        'widget_id', 'template'
    );

    public function parent() {
        return $this->belongsTo('App\Models\Content\Structure', 'structure_id');
    }

    public function contents() {
        return $this->hasMany('App\Models\Content\Content', 'model_id'); //chiave esterna
    }
}
