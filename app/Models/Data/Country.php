<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;

    protected $fillable = array(
        'code','name','capital','isoNumeric','continentName','continentCode'
    );
}
