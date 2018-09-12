<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social_auth extends Model
{
    protected $table = 'social_auth';

    protected $fillable = array(
        'user_id', 'provider','provider_id', 'avatar'
    );
}