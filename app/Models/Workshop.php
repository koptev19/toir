<?php

namespace App\Models;

use App\Scopes\WorkshopScope;

class Workshop extends Equipment
{

    protected $table = "equipment";

    protected $attributes = [
        'type'  => Equipment::TYPE_WORKSHOP,
        'level' => 1,
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new WorkshopScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lines()
    {
        return $this->hasMany(Line::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
