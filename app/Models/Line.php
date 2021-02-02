<?php

namespace App\Models;

use App\Scopes\LineScope;

class Line extends Equipment
{
    protected $table = "equipment";

    protected $attributes = [
        'type'  => Equipment::TYPE_LINE,
        'level' => 2,
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new LineScope);
    }

}
