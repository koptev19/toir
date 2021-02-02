<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Models\Line;
use App\Models\Workshop;
use App\Observers\EquipmentObserver;
use App\Observers\LineObserver;
use App\Observers\WorkshopObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Equipment::observe(EquipmentObserver::class);
        Workshop::observe(WorkshopObserver::class);
        Line::observe(LineObserver::class);
    }
}
