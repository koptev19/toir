<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * @param string $name
     * @return Setting
     */
    public static function getByName(string $name): Setting
    {
        return self::whereName($name)->first();
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getValueByName(string $name): ?string
    {
        $setting = self::getByName($name);
        return $setting ? $setting->VALUE : null;
    }
}
