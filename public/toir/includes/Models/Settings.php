<?php

class Settings extends ToirModel
{

    public $table = 'settings';

    protected $modify = [];

    /**
     * @param string $name
     * @return Settings
     */
    public static function getByName(string $name): Settings
    {
        return self::filter(['NAME' => $name])->first();
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