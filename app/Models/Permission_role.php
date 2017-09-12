<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_role extends Model
{
    protected $table = 'permissions_roles';

    protected $fillable = array(
        'permission_id', 'role_id'
    );
}
