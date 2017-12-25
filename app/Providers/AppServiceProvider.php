<?php

namespace App\Providers;

use App\Models\ArtShowComment;
use App\Models\ArtShowCommentLike;
use App\Models\ExperienceBooking;
use App\Models\TearoomBooking;
use App\Observer\Art\ArtShowCommentLikeObserver;
use App\Observer\Art\ArtShowCommentObserver;
use App\Observer\Experience\ExperienceRoomBookingObserver;
use App\Observer\Tearoom\BookingObserver;
use Carbon\Carbon;
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
        Carbon::setLocale("zh");

        //观察者模式
        ArtShowComment::observe(ArtShowCommentObserver::class);
        ArtShowCommentLike::observe(ArtShowCommentLikeObserver::class);
        TearoomBooking::observe(BookingObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


        if (app()->environment([ 'test', 'local', 'develop' ])) {
            $this->app->register(\Reliese\Coders\CodersServiceProvider::class);
        }

        if ($this->app->environment() !== 'production') {
            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
        }
    }
}
