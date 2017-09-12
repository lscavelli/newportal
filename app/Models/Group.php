<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    private $listPermissions = null;

    protected $table = 'groups';

    protected $fillable = array(
        'name', 'slug', 'description'
    );

    public function users()    {
        return $this->belongsToMany('App\Models\User','users_groups');
    }
    public function roles() {
        return $this->belongsToMany('App\Models\Role','roles_groups');
    }
    public function permissions() {
        return $this->belongsToMany('App\Models\Permission','permissions_groups');
    }

    public function listPermissions() {
        if (!is_null($this->listPermissions)) return $this->listPermissions;
        /* considero i permessi assegnati direttamente al gruppo */
        $this->listPermissions = $this->permissions;
        /* considero i permessi assegnati ai ruoli del gruppo */
        foreach ($this->roles as $role) {
            $this->listPermissions = $this->listPermissions->merge($role->permissions);
        }
        return $this->listPermissions;
    }
}
