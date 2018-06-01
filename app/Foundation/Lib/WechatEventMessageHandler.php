<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 25/5/2018
 * Time: 1:39 PM
 */

namespace App\Foundation\Lib;


use App\Models\OfficialAccountDefaultSetting;
use App\Models\OfficialActivity;
use App\Models\OfficialActivityUser;

use App\Models\OfficialActivityShareRelation;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Support\Facades\Log;


class WechatEventMessageHandler implements EventHandlerInterface
{

    static protected $params;

    public function handle( $message = null )
    {
        //  Log::info($message);
//       array (
//        'ToUserName' => 'gh_0fd853d2e21a',
//        'FromUserName' => 'oAAhsxJ2uugzr1JoiZSYL-c6v-P4',
//        'CreateTime' => '1527227820',
//        'MsgType' => 'event',
//        'Event' => 'SCAN',
//        'EventKey' => '{"expire_seconds":604800,"action_name":"QR_STR_SCENE","action_info":{"scene":{"scene_str":165}}}',
//        'Ticket' => 'gQF98DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyMnlCbzBEMDhkSTQxOW1jZmhyY2sAAgRWowdbAwQA6QcA',
//    )

        $eventType = strtolower($message[ "Event" ]);
        //说明是开发生成的场景值二维码

        if (in_array($eventType, [ 'click', 'scan', 'subscribe', 'unsubscribe' ])) {

            return call_user_func_array([ static::class, $eventType ], [ $message ]);

        }


    }

    public function click( $message )
    {
        $data = explode(":", $message[ 'EventKey' ]);

        $user = OfficialActivityUser::query()->withoutGlobalScopes()->where("open_id", $message[ 'FromUserName' ])->where('official_activity_id', $data[ 1 ])->first();

        if (!$user || !$user->draw_number_count) {

            return new Text("你需要先参加完活动才可生成海报哟");
        }

        if ($user->poster_media_id) {

            return new Image($user->poster_media_id);
        }
        else {

            return new Text("您的海报正在紧张的生产中....");
        }

    }

    /**
     *订阅
     */
    protected static function subscribe( $message )
    {
       Log::info($message);
        if ($message[ 'EventKey' ]) {

            static::addShareUserRelation($message);
        }

        //默认设置
        $default = OfficialAccountDefaultSetting::officialAccountHasDefaultSetting();
        $sayInfo = $default->default_welcome;


        if ($model = OfficialActivity::activeActivity()) {

            $homeUrl = "<a href='" . makeActivityMixUrl("officialAccount.home", $model) . "'>点击进入</a>";
            $sayInfo = str_replace([ '{:activity_name}', "{:activity_home}" ], [ $model->name, $homeUrl ], $model->default_welcome);
        }


        return new Text(str_replace([ '<br/>', "<br/>" ], [ "\r\n" ], $sayInfo));
    }


    protected static function unsubscribe( $message )
    {

    }

    /**
     * @param $message
     * @return Image|Text
     */
    private static function scan( $message )
    {
        $data = static::parseParams($message);

        request()->offsetSet("official_activity_id", $data[ 'official_activity_id' ]);


        if ($message[ 'FromUserName' ] == $data[ 'scene_str' ]) {

            $user = OfficialActivityUser::query()->with('user')->where("open_id", $message[ 'FromUserName' ])->first();


            if ($user->poster_media_id) {

                return new Image($user->poster_media_id);
            }
            else {

                return new Text("您的海报正在紧张的生产中....");
            }
        }

        static::addShareUserRelation($message);
    }


    private static function addShareUserRelation( $message )
    {
        $data                           = static::parseParams($message);
        request()->official_activity_id = $data[ 'official_activity_id' ];
        OfficialActivityShareRelation::add($message[ 'FromUserName' ], $data[ 'scene_str' ]);
    }

    private static function parseParams( $message )
    {
        //{"scene":{"scene_str":"ohJajxKAue5qiLgaEkgB_4nUx7Xg","official_activity_id":1}}}
        Log::error($message);
        return json_decode($message[ 'EventKey' ], true)[ 'action_info' ][ 'scene' ];
    }

}