<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accept extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'equipment_id',
        'checklist',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    
}
