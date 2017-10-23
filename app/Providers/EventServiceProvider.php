<?php

namespace App\Providers;

use App\Listeners\QueryListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //发送通知
        'App\Events\SendNotificationEvent' => [
            'App\Listeners\SendNotificationEventListener',
        ],
        'Illuminate\Database\Events\QueryExecuted'=>[
            QueryListener::class
        ],
        'App\Events\RefundFailNotificationEvent' => [
            'App\Listeners\RefundFailNotificationEventListener',
        ],

//        'Laravel\Passport\Events\AccessTokenCreated' => [
//            'App\Listeners\Auth\RevokeOldTokens',
//        ],
//        'Laravel\Passport\Events\RefreshTokenCreated' => [
//            'App\Listeners\Auth\PruneOldTokens',
//        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
