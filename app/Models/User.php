<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

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
        'country_id', 'city_id', 'confirmation_token', 'email_verified_at',
        'google2fa_secret'

    ];

    /**
     * Attributi che dovrebbero essere nascosti.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'google2fa_secret'
    ];

    /**
     * Attriobuti che si desidera non assegnare
     * @var array
     */
    //protected $guarded = []

    protected $casts = [
        'created_at' => 'date:d/m/Y', //'datetime:Y-m-d H:00'
        'data_nascita' => 'date:d/m/Y',
    ];

    /**
     * return the name attribute calculated
     * @return string
     */
    public function getNameAttribute() {
        return sprintf("%s %s",$this->getAttributeValue('nome'),$this->getAttributeValue('cognome'));
    }

    /**
     * verifiche che l'utente sia attivo
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
     * restituisce i commenti sui post dell'utente
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() {
        return $this->hasMany('App\Models\Content\Comment','user_id');
    }

    /**
     * definisco la mappa dei permessi complessivi posseduti dall'utente
     * @return mixed|null
     */
    public function listPermissions() {
        if (!is_null($this->listPermissions)) return $this->listPermissions;

        // considero i permessi assegnati direttamente all'utente
        $this->listPermissions = $this->permissions;

        // considero i permessi dei ruoli assegnati all'utente
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

    /**
     * definisco la mappa dei ruoli complessivi posseduti dall'utente
     * @return mixed|null
     */
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

    /**
     * Verifica se si possiedono i permessi passati come argomento
     * @param $permissions
     * @return bool
     */
    public function hasPermission($permissions) {
        if (is_null($this->listPermissions)) $this->listPermissions();
        return $this->hasPerm($permissions);
    }

    /**
     * Verifica se si possiedono i ruoli passati in argomento
     * @param $roles
     * @return mixed
     */
    public function hasRole($roles) {
        $roles = is_array($roles) ? $roles : [$roles];
        $roles = array_push($roles, config('newportal.super_admin'));
        return $this->listRoles()->contains('slug', $roles);
        //if ($this->listRoles()->where('slug',$role)->first() { return true; }
    }

    /**
     * Verifica se si possiede il ruolo di super Amministratore della piattaforma
     * @return bool
     */
    public function isAdmin() {
        $superAdmin = config('newportal.super_admin');
        foreach ($this->listRoles() as $role) {
            if ($role->slug === $superAdmin) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verifica se si possiede il permesso di gestore degli utenti
     * @return bool
     */
    public function isUserManager() {
        if ($this->isAdmin()) {
            return true;
        }
        return $this->hasPermission('user-manager');
    }

    public function level() {
        return ($role = $this->listRoles()->sortByDesc('level')->first()) ? $role->level : 0;
    }

    /**
     * Verifica privatamente se si possiedono i permessi in argomento
     * @param $permissions
     * @return bool
     */
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

    /**
     * Invia il link per il reset della password - override
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Scope confirmation_token
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTokenVerification($query,$token)
    {
        return $query->where('confirmation_token', $token);
    }

    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setGoogle2faSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = !is_null($value) ? encrypt($value): null;
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        return !empty($value) ? decrypt($value) : null;
    }

}
