<?php

namespace App\Providers;

use App\Contracts\UploadContract;
use App\Services\UploadService;
use Illuminate\Support\ServiceProvider;

/**
 * Class UploadServiceProvider
 * @package App\Providers
 */
class UploadServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(UploadContract::class, function () {
            return new UploadService();
        });
    }

    public function provides()
    {
        return [UploadContract::class];
    }
}