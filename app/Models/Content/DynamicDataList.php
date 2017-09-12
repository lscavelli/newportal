<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class DynamicDataList extends Model
{
    protected $table = 'dynamicdatalist';

    protected $fillable = array(
        'name', 'description', 'structure_id',
        'status_id', 'user_id', 'username'
    );

    public function content() {
        return $this->hasMany('\App\Models\Content\DynamicDataListContent','dynamicdatalist_id');
    }

    public function parent() {
        return $this->hasMany('\App\Models\Content\Structure','id','structure_id');
    }

}
