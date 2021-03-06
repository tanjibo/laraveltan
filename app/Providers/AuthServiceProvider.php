<?php

namespace App\Providers;

use App\Foundation\Auth\LrssEloquentUserProvider;
use App\Models\Api\TearoomBooking;
use App\Models\ExperienceBooking;
use App\Policies\ExperienceRoomBookingPolicy;
use App\Policies\TearoomBookingPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Horizon\Horizon;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies
        = [
            //  'App\Model' => 'App\Policies\ModelPolicy',
            ExperienceBooking::class => ExperienceRoomBookingPolicy::class,
            TearoomBooking::class => TearoomBookingPolicy::class,
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
        Horizon::auth(
            function( Request $request ) {
                //只能在公司访问
                $arr = [ '1.119.40.2', '127.0.0.1' ];
                return in_array($request->getClientIp(), $arr) ? true : false;
            }
        );

        Passport::routes(
            function( RouteRegistrar $router ) {
                //对于密码授权的方式只要这几个路由就可以了
                $router->forAccessTokens();
            }, [ 'domain' => config('app.api_domain') ]
        );

        Passport::tokensExpireIn(Carbon::now()->addDays(30));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(31));


    }
}
