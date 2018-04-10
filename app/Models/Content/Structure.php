<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;


class Structure extends Model
{
    protected $table = 'structure';

    protected $fillable = array(
        'name', 'description', 'content', 'color', 'service_id',
        'status_id', 'user_id', 'username',
    );

    /**
     * 1-m rest. la lista dei modelli
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function models() {
        return $this->hasMany('App\Models\Content\Modelli', 'structure_id');
    }

    /**
     * m-1 - rest. il servizio della struttura corrente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service() {
        return $this->belongsTo(Service::class);
    }



}
