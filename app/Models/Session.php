<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';
    public $incrementing = false; // quando l'id non è incrementale
    protected $attributes = ['name'];

    protected $fillable = array(
        'user_id','ip_address','user_agent','last_activity'
    );

    /**
     * return the name attribute calculated
     * @return string
     */
    public function getNameAttribute() {
        return "Sessione utente ".$this->getAttributeValue('user_id');
    }
}
