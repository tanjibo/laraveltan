<?php

namespace App\Providers;

use App\Foundation\Auth\LrssEloquentUserProvider;
use App\Models\ExperienceBooking;
use App\Policies\ExperienceRoomBookingPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Horizon\Horizon;
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
      //  'App\Model' => 'App\Policies\ModelPolicy',
        ExperienceBooking::class=>ExperienceRoomBookingPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * 队列访问
         */
        Horizon::auth(function(Request $request){
            //只能在公司访问
            $arr=['1.119.40.2','127.0.0.1'];
            return in_array($request->getClientIp(),$arr)?true:false;
        });


        $this->app['auth']->provider('lrss-eloquent',function($app,$config){
            return new LrssEloquentUserProvider($this->app['hash'],$config['model']);
        });

        Passport::routes(function (RouteRegistrar $router) {
            //对于密码授权的方式只要这几个路由就可以了
            $router->forAccessTokens(); 
        });


        Passport::tokensExpireIn(Carbon::now());

        Passport::refreshTokensExpireIn(Carbon::now());


    }
}
