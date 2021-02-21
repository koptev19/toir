<?php

namespace App\Providers;

use App\Contracts\DowntimeContract;
use App\Services\DowntimeService;
use Illuminate\Support\ServiceProvider;

/**
 * Class DowntimeServiceProvider
 * @package App\Providers
 */
class DowntimeServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(DowntimeContract::class, function () {
            return new DowntimeService();
        });
    }

    public function provides()
    {
        return [DowntimeContract::class];
    }
}