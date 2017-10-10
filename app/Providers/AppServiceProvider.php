<?php

namespace App\Providers;

use App\Models\ExperienceBooking;
use App\Observer\Front\ExperienceRoomBookingObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //订单监听
        ExperienceBooking::observe(ExperienceRoomBookingObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->environment() == 'develop') {
            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
        }
    }
}
