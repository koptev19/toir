<?php

namespace App\Models;

use App\Helpers\TimeHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Worktime
 */
class Worktime extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'worker_id',
        'operation_id',
        'action',
        'time_from',
        'time_to',
        'group',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function getWorkTimeAttribute()
    {
        return $this->time_from . ' - ' . $this->time_to;

    }

}