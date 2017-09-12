<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;


class Structure extends Model
{
    protected $table = 'structure';

    protected $fillable = array(
        'name', 'description', 'content', 'color', 'type_id',
        'status_id', 'user_id', 'username',
    );

    public function models() {
        return $this->hasMany('App\Models\Content\Modelli', 'structure_id');
    }

}
