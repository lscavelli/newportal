<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    private $listPermissions = null;
    private $listRoles = null;
    //protected $attributes = ['name'];

    /**
     * Attributi che si desidera assegnare.
     * @var array
     */
    protected $fillable = [
        'nome','cognome','email', 'password', 'username', 'indirizzo',
        'data_nascita', 'note', 'telefono', 'status_id', 'avatar',
        'country_id', 'city_id'
    ];

    /**
     * Attributi che dovrebbero essere nascosti.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Attriobuti che si desidera non assegnare
     * @var array
     */
    //protected $guarded = []

    /**
     * return the name attribute calculated
     * @return string
     */
    public function getNameAttribute() {
        return sprintf("%s %s",$this->getAttributeValue('nome'),$this->getAttributeValue('cognome'));
    }

    /**
     * verifiche che l'utente sia attivo
     * @return bool
     */
    public function isActive() {
       //return $this->status_id == 1;
    }

    /**
     * restituisce i gruppi dell'utente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()    {
        return $this->belongsToMany('App\Models\Group','users_groups');
    }

    /**
     * restituisce le organizzazioni di cui fa parte l'utente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizations()    {
        return $this->belongsToMany('App\Models\Organization','users_organizations');
    }

    /**
     * restituisce i ruoli collegati all'utente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role','roles_users');
    }

    /**
     * restituisce i permessi collegati all'utente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions() {
        return $this->belongsToMany('App\Models\Permission','permissions_users');
    }

    /**
     * restituisce le attività collegate all'utente
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities() {
        return $this->hasMany('App\Models\Activity','user_id');
    }

    /**
     * restituisce i commenti sui post degli utenti
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() {
        return $this->hasMany('App\Models\Blog\Comment','user_id');
    }

    public function listPermissions() {
        if (!is_null($this->listPermissions)) return $this->listPermissions;
        // considero i permessi assegnati direttamente all'utente
        $this->listPermissions = $this->permissions;
        // considero i permessi assegnati ai ruoli dell'utente
        foreach ($this->roles as $role) {
            $this->listPermissions = $this->listPermissions->merge($role->permissions);
        }
        foreach ($this->groups as $group) {
            // considero i permessi assegnati ai gruppi dell'utente
            $this->listPermissions = $this->listPermissions->merge($group->permissions);
            // considero i permessi assegnati ai ruoli dei gruppi dell'utente
            foreach ($group->roles as $role) {
                $this->listPermissions = $this->listPermissions->merge($role->permissions);
            }
        }
        return $this->listPermissions;
    }

    public function listRoles() {
        if (!is_null($this->listRoles)) return $this->listRoles;
        // considero i ruoli assegnati direttamente all'utente
        $this->listRoles = $this->roles;
        // considero i ruoli assegnati ai gruppi dell'utente
        foreach ($this->groups as $group) {
            $this->listRoles = $this->listRoles->merge($group->roles);
        }
        return $this->listRoles;
    }

    public function hasPermission($permissions) {
        if (is_null($this->listPermissions)) $this->listPermissions();
        return $this->hasPerm($permissions);
    }

    public function hasRole($roles) {
        $roles = is_array($roles) ? $roles : [$roles];
        $roles = array_push($roles, config('newportal.super_admin'));
        return $this->listRoles()->contains('slug', $roles);
        //if ($this->listRoles()->where('slug',$role)->first() { return true; }
    }

    public function isAdmin() {
        $superAdmin = config('newportal.super_admin');
        foreach ($this->listRoles() as $role) {
            if ($role->slug === $superAdmin) {
                return true;
            }
        }
        return false;
    }

    public function level() {
        return ($role = $this->listRoles()->sortByDesc('level')->first()) ? $role->level : 0;
    }

    private function hasPerm($permissions)  {
        if (is_string($permissions)) {
            return $this->listPermissions->contains('slug',$permissions);
        }
        if ($permissions instanceof Permission) {
            return $this->listPermissions->contains('slug', $permissions->slug);
        }
        if (is_array($permissions)) {
            foreach ($permissions as $perm) {
                if ($this->hasPerm($perm)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getAvatar() {
        $image = config('newportal.path_upload_user')."/".$this->avatar;
        $imagePath = sprintf("%s/%s", public_path(), $image);
        if (! $this->avatar or !file_exists($imagePath)) {
            return asset('img/avatar.png');
        }
        return asset($image);
    }

    /**
     * restituisce l'user sociale registrato al portale
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialUser()
    {
        return $this->hasOne(Social_auth::class, 'user_id');
    }

}
