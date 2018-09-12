<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = array(
        'setting_key', 'setting_value'
    );
}
