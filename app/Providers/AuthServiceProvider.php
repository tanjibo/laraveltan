<?php

namespace App\Providers;

use App\Foundation\Auth\LrssEloquentUserProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        $this->app['auth']->provider('lrss-eloquent',function($app,$config){
            return new LrssEloquentUserProvider($this->app['hash'],$config['model']);
        });

        Passport::routes(function (RouteRegistrar $router) {
            //对于密码授权的方式只要这几个路由就可以了
            $router->forAccessTokens();
        });

//        Passport::tokensExpireIn(Carbon::now()->addDays(15));
//
//        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));


    }
}
