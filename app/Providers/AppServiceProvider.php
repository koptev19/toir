<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        /**
         * ObServices
         */
        \App\Models\AcceptHistory::observe(\App\Observers\AcceptHistoryObserver::class);
        \App\Models\Equipment::observe(\App\Observers\EquipmentObserver::class);
        \App\Models\Line::observe(\App\Observers\LineObserver::class);
        \App\Models\Workshop::observe(\App\Observers\WorkshopObserver::class);
    }
}
