<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 25/5/2018
 * Time: 1:30 PM
 */

namespace App\Foundation\Lib;


use App\Models\OfficialAccountDefaultSetting;
use App\Models\OfficialActivity;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;


/**
 * Class WechatTextMessageHandler
 * @package App\Foundation\Lib
 * 文本回复消息
 */
class WechatTextMessageHandler implements EventHandlerInterface
{
    public function handle( $message = null )
    {
        $model = OfficialActivity::activeActivity();
        if ($model) {
            return $model->auto_reply_welcome;
        }
        $model = OfficialAccountDefaultSetting::officialAccountHasDefaultSetting();
        return $model->auto_reply_welcome;
    }
}