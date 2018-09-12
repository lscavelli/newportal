<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    private $listUsers = null;
    private $listGroups = null;

    protected $table = 'permissions';

    protected $fillable = array(
        'name', 'slug', 'description'
    );

    public function roles()    {
        return $this->belongsToMany('App\Models\Role','permissions_roles');
    }

    public function groups()    {
        return $this->belongsToMany('App\Models\Group','permissions_groups');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User','permissions_users');
    }

    /**
     * Restituisso la lista complessiva degli utenti a cui risulta assegnato il permesso
     * direttamente, tramite ruoli, tramite gruppi o tramite ruoli assegnati a gruppi
     * @return mixed|null
     */
    public function listUsers() {
        if (!is_null($this->listUsers)) return $this->listUsers;
        // considero gli utenti a cui è stato assegnato direttamente il permesso
        $this->listUsers = $this->users;
        // considero gli utenti a cui è stato assegnato il permesso tramite ruolo
        foreach ($this->roles as $role) {
            $this->listUsers = $this->listUsers->merge($role->users);
        }
        foreach ($this->groups as $group) {
            // considero gli utenti del gruppo a cui è stato assegnato il permessso
            $this->listUsers = $this->listUsers->merge($group->users);
            // considero gli utenti dei gruppi a cui è stato assegnato il ruolo con il permesso
            foreach ($group->roles as $role) {
                $this->listUsers = $this->listUsers->merge($role->users);
            }
        }
        return $this->listUsers;
    }

    /**
     * Restituisso la lista complessiva dei gruppi a cui risulta assegnato il permesso
     * direttamente o tramite ruoli.
     * @return mixed|null
     */
    public function listGroups() {
        if (!is_null($this->listGroups)) return $this->listGroups;
        // considero i gruppi a cui è stato assegnato direttamente il permesso
        $this->listGroups = $this->groups;
        // considero i gruppi a cui è stato assegnato il permesso tramite ruolo
        foreach ($this->roles as $role) {
            $this->listGroups = $this->listGroups->merge($role->groups);
        }
        return $this->listGroups;
    }
}
