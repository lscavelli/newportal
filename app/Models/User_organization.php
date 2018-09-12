<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_organization extends Model
{
    protected $table = 'users_organizations';

    protected $fillable = array(
        'organization_id', 'user_id'
    );
}
