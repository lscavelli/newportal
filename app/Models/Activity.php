<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //const UPDATED_AT = null; incompatible with laravel 5.5

    protected $table = 'activity';

    protected $fillable = array(
        'user_id', 'description', 'ip_address', 'user_agent'
    );

    /**
     * funzione inversa - restituisce l'utente che ha generato l'attività
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
