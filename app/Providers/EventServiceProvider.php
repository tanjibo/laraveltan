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
        //发送安吉用户下单通知
        'App\Events\SendNotificationEvent' => [
            'App\Listeners\SendNotificationEventListener',
        ],
        //发送安吉后台下单通知
        'App\Events\SendExperienceBackendNotificationEvent' => [
            'App\Listeners\SendExperienceBackendNotificationEventListener',
        ],
        //发送茶社后台下单通知
        'App\Events\SendTearoomBackendNotificationEvent' => [
            'App\Listeners\SendTearoomBackendNotificationEventListener',
        ],
        //发送茶社后台下单通知
        'App\Events\TearoomRefundFailNotificationEvent' => [
            'App\Listeners\TearoomRefundFailNotificationEventListener',
        ],
          //中式空间
        'App\Events\ArtShowNotificationEvent' => [
            'App\Listeners\ArtShowNotificationEventListener',
        ],

        'Illuminate\Database\Events\QueryExecuted'=>[
            QueryListener::class
        ],
        'App\Events\RefundFailNotificationEvent' => [
            'App\Listeners\RefundFailNotificationEventListener',
        ],

        'Laravel\Passport\Events\AccessTokenCreated' => [
            'App\Listeners\Auth\RevokeOldTokens',
        ],
        'Laravel\Passport\Events\RefreshTokenCreated' => [
            'App\Listeners\Auth\PruneOldTokens',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
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
