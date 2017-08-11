<?php

namespace App\Http\Controllers;

use App\Api\Sms;
use Illuminate\Http\Request;

class SendEmailController extends Controller
{
    /**
     * 发送通知短信
     */
    function sendNotifyEmail(){
        $params = [
            'customer' =>'tanjibo',
            'checkin'  => '2017-08-1',
            'checkout' => '2017-08-3',
            'price'    => '32',
            'mobile'   => 15067256154,
            'room'     => '好',
            'sex'         =>'女士',
            'user_mobile' =>18610729170
        ];

        // 用户短信通知
        $template = Sms::template(Sms::TYPE_EXPERIENCE_BOOKING_WITH_USER, $params);
        Sms::send('', $template, Sms::TYPE_EXPERIENCE_BOOKING_WITH_USER);

    }
}
