<?php

namespace App\Providers;

use App\Models\ExperienceBooking;
use App\Observer\Experience\ExperienceRoomBookingObserver;
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


//        if (app()->environment([ 'test', 'local', 'develop' ])) {
////            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
//        }
//
//        if ($this->app->environment() !== 'production') {
////            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
////            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
//        }
    }
}
