<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = array(
        'name', 'order', 'icon', 'uri', 'parent_id'
    );
}
