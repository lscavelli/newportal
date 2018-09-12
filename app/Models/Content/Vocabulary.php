<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $table = 'vocabularies';

    protected $fillable = array(
        'name', 'description'
    );

    /**
     * 1-m - rest. la lista delle categorie
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories() {
        return $this->hasMany('App\Models\Content\Category','vocabulary_id');
    }

    /**
     * m-m rest. la lista dei servizi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services() {
        return $this->belongsToMany('App\Models\Content\Service','vocabularies_services')
            ->withPivot('type_order','type_dir','required')
            ->withTimestamps();
    }
}
