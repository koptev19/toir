<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    public const TYPE_WORKSHOP = 'workshop';
    public const TYPE_LINE = 'line';
    public const TYPE_MECHANISM = 'mechanism';
    public const TYPE_NODE = 'node';
    public const TYPE_DETAIL = 'detail';

    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'type',
        'name',
        'level',
        'workshop_id',
        'line_id',
        'mechanic_id',
    ];

    public static function getTypes(): array
    {
        return [
            self::TYPE_WORKSHOP => 'Цех',
            self::TYPE_LINE => 'Линия',
            self::TYPE_MECHANISM => 'Механизм',
            self::TYPE_NODE => 'Узел',
            self::TYPE_DETAIL => 'Деталь',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Equipment::class, 'parent_id');
    }

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Equipment::class, 'parent_id');
    }

    /**
     * @return array
     */
    public function allParentsId(): array
    {
        $parentsId = [];

        if($this->parent) {
            $parentsId = $this->parent->allParentsId();
            $parentsId[] = $this->parent_id;
        }

        return $parentsId;
    }

    /**
     * @return bool
     */
    public function isLine(): bool
    {
        return $this->type === Equipment::TYPE_LINE;
    }

    /**
     * @return bool
     */
    public function isWorkshop(): bool
    {
        return $this->type === Equipment::TYPE_WORKSHOP;
    }

    public function getHtmlClassAttribute()
    {
		$classes = [
            Equipment::TYPE_WORKSHOP =>"text-body link-dark",
            Equipment::TYPE_LINE =>"text-danger link-danger",
            Equipment::TYPE_MECHANISM =>"text-primary link-primary",
            Equipment::TYPE_NODE=>"text-success link-success",
            Equipment::TYPE_DETAIL=>"text-info link-info"
        ];

        return $classes[$this->type];
    }

}
