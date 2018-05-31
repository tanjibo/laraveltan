<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 24/5/2018
 * Time: 12:04 PM
 */


namespace App\Http\OfficialControllers;

//分享
use App\Jobs\WechatPoster;
use App\Models\OfficialAccountDefaultSetting;
use App\Models\OfficialActivityUser;
use App\Models\User;
use App\Models\WechatDrawActiveUser;
use App\Models\OfficialActivityShareRelation;
use App\Models\OfficialActivityNumber;

class ShareController extends BaseController
{

    //分享到朋友圈
    public function shareTimeLine()
    {

        //获得抽奖码
        $wxUser = wechatOauthUser();
        $draw   = OfficialActivityUser::isJoin($wxUser[ 'openid' ]);
        if (isset($draw) && $draw->draw_number_count) {
            return false;
        }

        $user = User::query()->where("open_id", $wxUser[ "openid" ])->first();
        //说明是分享过来的

        $childDraw = [
            "open_id"              => $wxUser[ 'openid' ],
            "draw_number"          => str_random(6),
            'official_activity_id' => $this->activity->id,
        ];

        OfficialActivityNumber::query()->create($childDraw);
        OfficialActivityUser::AddDrawCount($wxUser[ 'openid' ]);


        $share = OfficialActivityShareRelation::query()->where('open_id', $wxUser[ 'openid' ])->first();
        if (isset($share) && $share->parent_open_id) {
            $code       = str_random(6);
            $parentDraw = [
                'open_id'              => $share->parent_open_id,
                "children_open_id"     => $share->open_id,
                "draw_number"          => $code,
                'official_activity_id' => $this->activity->id,
            ];

            $numberList    = makeActivityMixUrl('officialAccount.user.numberList', $this->activity);
            $userCenterUrl = makeActivityMixUrl('officialAccount.user.numberCenter', $this->activity);

            $this->sayToUser($wxUser[ 'nickname' ], $code, $userCenterUrl, $numberList, $share->parent_open_id);

            OfficialActivityNumber::query()->create($parentDraw);
            OfficialActivityUser::AddDrawCount($share->parent_open_id);


        }

         return redirect()->route("officialAccount.user.numberCenter",['official_activity_id'=>$this->activity]);
        //异步生成队列
        //  WechatPoster::dispatch($user);
    }


    /**
     * @param $friend
     * @param $code
     * @param $userCenterUrl
     * @param $numberList
     * @param $toOpenId
     * 向用户发送推送
     */
    private function sayToUser( $friend, $code, $userCenterUrl, $numberList, $toOpenId )
    {

        $toReplace    = [
            $friend, "[{$this->activity->name}]", $code, "<a href='{$userCenterUrl}'>点击此处</a>", "<a href='{$numberList}'>点击此处</a>",
        ];
        $defaultModel = OfficialAccountDefaultSetting::officialAccountHasDefaultSetting();

        $message = $this->activity->be_recommend_welcome ?: $defaultModel->be_recommend_welcome;

        $message = str_replace([ "{:friend}", "{:name}", "{:code}", "{:userCenterUrl}", "{:numberList}" ], $toReplace, $message);

        $message=str_replace([ '<br/>', "\n" ], [ "\r\n" ], $message);

        $this->app->customer_service->message($message)->to($toOpenId)->send();
    }


}