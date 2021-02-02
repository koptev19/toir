<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stop extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'workshop_id',
        'line_id',
        'creash_id',
        'date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workshop()
    {
        return $this->belongsTo(Equipment::class, 'workshop_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Equipment::class, 'line_id');
    }

}
