<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = array(
        'name', 'status_id','code','parent_id','type_id','country_id','city_id','user_id','username'
    );

    public function users() {
        return $this->belongsToMany('App\Models\User','users_organizations','organization_id','user_id');
    }

    public function parent() {
        return $this->belongsTo('\App\Models\Organization', 'parent_id');
    }

    public function children() {
        return $this->hasMany('\App\Models\Organization', 'parent_id');
    }
}
