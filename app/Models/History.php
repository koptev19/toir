<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\History
 */
class History extends Model
{
    use SoftDeletes;

    public const SOURCE_PLAN_DATE = 'Планирование';
    public const SOURCE_REPORT_DATE = 'Отчет';
    public const SOURCE_CHANGE_DATE = 'Перенос дат';
    public const SOURCE_CRASH = 'Авария';
    public const SOURCE_SERVICE = 'Заявка на ремонт';
    public const SOURCE_ADD_OPERATION = 'Добавление операции';
	public const SOURCE_DOWNTIME = 'Простой';
    public const SOURCE_TMC = 'Списание ТМЦ';

    public const DOWNTIME_TYPE_CRASH = 'crash';
    public const DOWNTIME_TYPE_REPAIR = 'repair';
    public const DOWNTIME_TYPE_WORKS = 'works';
    public const DOWNTIME_TYPE_UNDEFINED = 'undefined';

    protected $fillable = [
    ];

    protected $casts = [
        'planned_date' => 'date',
    ];

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

    /**
     * @return string
     */
    public function getDateAttribute()
    {
        return $this->planned_date ? $this->planned_date->format('d.m.Y') : '';
    }

    /**
     * @return string
     */
    public function getDateDatabaseAttribute()
    {
        return $this->planned_date ? $this->planned_date->format('Y-m-d') : '';
    }

    /**
     * @return string
     */
    public function getDowntimeTypeAttribute()
    {
        if ($this->reason === Operation::REASON_CRASH) {
            return self::DOWNTIME_TYPE_CRASH;
        } elseif ($this->source === self::SOURCE_REPORT_DATE) {
            return self::DOWNTIME_TYPE_WORKS;
        } else {
            return self::DOWNTIME_TYPE_UNDEFINED;
        }
    }

}