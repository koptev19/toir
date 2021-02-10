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
        'short_name',
        'manager_id',
        'inventory_number',
        'enter_date',
        'description',
        'photo_id',
        'sketch_id',
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

    protected $casts = [
        'enter_date' => 'date',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photo()
    {
        return $this->belongsTo(File::class, 'photo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sketch()
    {
        return $this->belongsTo(File::class, 'sketch_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function documents()
    {
        return $this->belongsToMany(File::class, 'equipments_documents', 'equipment_id', 'document_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Equipment::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function works()
    {
        return $this->hasMany(Work::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans()
    {
        return $this->hasMany(Plan::class);
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

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getEnterDateFormattedAttribute()
    {
        return $this->enter_date ? $this->enter_date->format('d.m.Y') : '';
    }

    /**
     * @return string
     */
    public function getFullPathAttribute()
    {
        return ($this->parent_id ? $this->parent->full_path . ' / ' : '') . $this->name;
    }

    /**
     * @return string
     */
    public function getLinePathAttribute()
    {
        return ($this->line_id ? $this->line->name . ' / ' : '') . $this->name;
    }

}
