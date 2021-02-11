<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Operation
 */
class Operation extends Model
{
    use SoftDeletes;

    public const REASON_INSTRUCTION = 'instruction';
    public const REASON_VIEW = 'view';
    public const REASON_CRASH = 'crash';
	public const REASON_DOWNTIME = 'downtime';

    public const SOURCE_GROUP_PLAN_DATE = 'plan_date';
    public const SOURCE_GROUP_REPORT_DATE = 'report_date';
    public const SOURCE_GROUP_INDEX = 'index';
    public const SOURCE_GROUP_SERVICE_REQUIEST = 'service_requiest';
    public const SOURCE_GROUP_CRASH = 'crash';
	public const SOURCE_GROUP_DOWNTIME = 'downtime';

    public const TYPE_REPAIR = 'repair';
    public const TYPE_REPLACEMENT = 'replacement';
    public const TYPE_TO = 'to';
    public const TYPE_OTHER = 'other';

    protected $fillable = [
        'name',
        'equipment_id',
        'service_id',
    ];

    private static $types = [
        self::TYPE_REPAIR => 'Ремонт',
        self::TYPE_REPLACEMENT => 'Замена',
        self::TYPE_TO => 'ТО',
        self::TYPE_OTHER => 'Прочие работы',
    ];

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return self::$types;
    }

    /**
     * @param string $reason
     * @return string
     */
    public static function verbalReason(string $reason): string
    {
        switch ($reason) {
            case self::REASON_INSTRUCTION:
                return "Инструкция по эксплуатации оборудования";
                break;
            case self::REASON_VIEW:
                return "Осмотр оборудования";
                break;
            case self::REASON_CRASH:
                return "Авария";
                break;
			case self::REASON_DOWNTIME:
                return "Простой";
                break;
            default:
                return "";
        }
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getVerbalType(string $type): string
    {
        return self::$types[$type];
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