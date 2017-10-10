<?php

namespace App\Listeners;

use App\Events\SendNotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendNotificationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendNotificationEvent  $event
     * @return void
     */
    public function handle(SendNotificationEvent $event)
    {

//        $template = SMSModel::template(SMSModel::TYPE_EXPERIENCE_CANCEL_WITH_USER, $params);
//        SMSModel::send($booking->mobile, $template, SMSModel::TYPE_EXPERIENCE_CANCEL_WITH_USER);
//
//        // 运营短信通知
//        $mobile = \Helper::config('notify_mobile')->experience;
//        $template = SMSModel::template(SMSModel::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR, $params);
//        SMSModel::send($mobile, $template, SMSModel::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR);
//
//        //发送微信通知
//        self::wechatNotify($mobile,$params,SMSModel::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR);
    }
}
