<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class DynamicDataListContent extends Model
{
    protected $table = 'dynamicdatalist_content';

    protected $fillable = array(
        'content', 'dynamicdatalist_id',
        'user_id', 'username'
    );

    public function parent() {
        return $this->belongsTo('\App\Models\Content\DynamicDataList');
    }

}
