<?php

namespace App\Providers;

use App\Models\ExperienceBooking;
use App\Models\ExperienceSpecialRoomBooking;
use App\Models\ExperienceSpecialRoomBookingXinyuege;
use App\Observer\Front\ExperienceRoomBookingObserver;
use App\Observer\Front\ExperienceRoomBookingShanObserver;
use App\Observer\Front\ExperienceRoomBookingXingObserver;
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
        ExperienceSpecialRoomBooking::observe(ExperienceRoomBookingShanObserver::class);
        ExperienceSpecialRoomBookingXinyuege::observe(ExperienceRoomBookingXingObserver::class);
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
