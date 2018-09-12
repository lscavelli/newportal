<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    private $listUsers = null;
    protected $table = 'roles';

    protected $fillable = array(
        'name', 'slug', 'description', 'level'
    );

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission','permissions_roles');
    }
    public function users() {
        return $this->belongsToMany('App\Models\User','roles_users');
    }
    public function groups()    {
        return $this->belongsToMany('App\Models\Group','roles_groups');
    }

    /**
     * Restituisso la lista complessiva degli utenti a cui risulta assegnato il ruolo
     * direttamente o tramite gruppo.
     * @return mixed|null
     */
    public function listUsers() {
        if (!is_null($this->listUsers)) return $this->listUsers;
        // considero gli utenti a cui è stato assegnato direttamente il ruolo
        $this->listUsers = $this->users;
        // considero gli utenti a cui è stato assegnato il ruolo tramite  il gruppo
        foreach ($this->groups as $group) {
            $this->listUsers = $this->listUsers->merge($group->users);
        }
        return $this->listUsers;
    }

    /**
     * Verifica se sono assegnati uno o almeno uno dei permessi passati come parametro
     * @param $permissions
     * @return bool
     */
    public function hasPerm($permissions)  {
        if (is_string($permissions)) {
            return $this->permissions->contains('slug',$permissions);
        }
        if ($permissions instanceof Permission) {
            return $this->permissions->contains('id', $permissions->id);
        }
        if (is_array($permissions)) {
            foreach ($permissions as $perm) {
                if ($this->hasPerm($perm)) {
                    return true;
                }
            }
        }
        return (bool) $permissions->intersect($this->permissions)->count();
    }

    /**
     * wrapper a hasPerm
     * @param $permissions
     * @return bool
     */
    public function hasAnyPerm($permissions)  {
        return $this->hasPerm($permissions);
    }

    /**
     * Verifica se sono assegnati tutti i permessi passati come parametro
     * @param $permissions
     * @return bool
     */
    public function hasAllPerm($permissions) {
        if (is_string($permissions)) {
            return $this->permissions->contains('slug', $permissions);
        }
        if ($permissions instanceof Permission) {
            return $this->permissions->contains('id', $permissions->id);
        }
        $permissions = collect()->make($permissions)->map(function ($perm) {
            return $perm instanceof Permission ? $perm->slug : $perm;
        });
        return $permissions->intersect($this->permissions->pluck('slug')) == $permissions;
    }
}
