<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Plan
 */
class Plan extends Model
{
    use SoftDeletes;

    public const TYPE_TO_ENTO = 'ento';
    public const TYPE_TO_EМTO = 'emto';
    public const TYPE_TO_TO1 = 'to1';
    public const TYPE_TO_TO2 = 'to2';

    private static $typesTo = [
        self::TYPE_TO_ENTO => 'ЕНТО',
        self::TYPE_TO_EМTO => 'ЕМТО',
        self::TYPE_TO_TO1 => 'ТО-1',
        self::TYPE_TO_TO2 => 'ТО-2',
    ];
    
    protected $fillable = [
        'name',
        'equipment_id',
        'service_id',
        'crash_id',
        'periodicity',
        'type_to',
        'type_operation',
        'recommendation',
        'start_date',
        'next_execution_date',
        'task_result',
        'last_date_from_checklist',
        'comment_no_result',
        'reason',
        'operations_not_done',
    ];

    /**
     * @return array
     */
    public static function getTypesTo(): array
    {
        return self::$typesTo;
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getVerbalTypeTo(string $type): string
    {
        return self::$typesTo[$type];
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'service_id');
    }

}