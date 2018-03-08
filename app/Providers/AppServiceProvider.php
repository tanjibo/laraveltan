<?php

namespace App\Providers;

use App\Models\ArtShow;
use App\Models\ArtShowComment;
use App\Models\ArtShowSuggestion;
use App\Models\ExperienceArticle;
use App\Models\ExperienceBooking;
use App\Models\TearoomBooking;
use App\Observer\Art\ArtShowCommentLikeObserver;
use App\Observer\Art\ArtShowCommentObserver;
use App\Observer\Art\ArtShowObserver;
use App\Observer\Art\ArtShowSuggestionObserver;
use App\Observer\Experience\ArticleObserver;
use App\Observer\Experience\BookingApiObserver;
use App\Observer\Experience\BookingBackendObserver;
use App\Observer\Tearoom\BookingObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
       \App\Models\Backend\ExperienceBooking::observe(BookingBackendObserver::class);
       ExperienceBooking::observe(BookingApiObserver::class);
       ExperienceArticle::observe(ArticleObserver::class);
        Carbon::setLocale("zh");

        //观察者模式
        ArtShowComment::observe(ArtShowCommentObserver::class);
        TearoomBooking::observe(BookingObserver::class);
        ArtShowSuggestion::observe(ArtShowSuggestionObserver::class);
        ArtShow::observe(ArtShowObserver::class);

        Schema::defaultStringLength(191);
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
