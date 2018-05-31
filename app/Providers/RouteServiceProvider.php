<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace        = 'App\Http\Controllers';
    protected $api_namespace    = 'App\Http\ApiControllers';
    protected $official_front_namespace = 'App\Http\OfficialControllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapWechatRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group(
            [
                'domain'     => config('app.admin_domain'),
                'middleware' => 'web',
                'namespace'  => $this->namespace,
            ], function( $router ) {
            require base_path('routes/web.php');
        }
        );
    }

    protected function mapWechatRoutes()
    {
        Route::group(
            [
                'domain'     => config('app.official_domain'),
                'middleware' => 'web',
                'namespace'  => $this->official_front_namespace,
            ], function( $router ) {
            require base_path('routes/official.php');
        }
        );
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group(
            [
                'prefix'=>'api',
                'domain'     => config('app.api_domain'),
                'middleware' => 'api',
                'namespace'  => $this->api_namespace,
            ], function( $router ) {
            require base_path('routes/api.php');
        }
        );
//        Route::prefix('api')
//             ->middleware('api')
//             ->domain(config("app.api_domain"))
//             ->namespace($this->api_namespace)
//             ->group(base_path('routes/api.php'))
//        ;
    }
}
