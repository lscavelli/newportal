<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_group extends Model
{
    protected $table = 'users_groups';

    protected $fillable = array(
        'user_id', 'group_id'
    );
}
