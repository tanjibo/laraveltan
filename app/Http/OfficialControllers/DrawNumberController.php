<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 22/5/2018
 * Time: 3:59 PM
 */

namespace App\Http\OfficialControllers;

use App\Foundation\Lib\PostImgCombine;
use App\Foundation\Lib\WechatShareHandle;
use App\Models\OfficialActivityUser;
use App\Models\User;
use App\Models\WechatDrawActiveUser;
use Intervention\Image\Facades\Image;


/**
 * Class UserController
 * @package App\Http\WechatControllers
 * 用户抽奖中心
 */
class DrawNumberController extends BaseController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 个人抽奖码中心
     */
    public function numberCenter()
    {
        $wx            = wechatOauthUser();
        $activity      = $this->activity;
        $model         = User::query()->with('draw_number')->where("open_id", $wx[ 'openid' ])->first();

        $share         = $activity->share_settings->first();
        $shareFriendJs = WechatShareHandle::shareFriend(
            $share->title,
            $share->desc,
            route(
                "officialAccount.showDrawArticle",
                [ "token" => $wx[ 'openid' ], 'url' => $share->link_url, 'official_activity_id' => $activity ]
            ), asset($share->cover_img)
        );

        return view('official_front.number.numberCenter', compact("model", "shareFriendJs", 'activity'));
    }

    /**
     * 排行榜
     */
    public function numberList()
    {
        $ranking = OfficialActivityUser::ranking();
        $activity=$this->activity;
        return view("official_front.number.list", compact("ranking",'activity'));
    }


    public function poster()
    {
        $activity = $this->activity;
        if (request()->wantsJson()) {
            $wx     = wechatOauthUser();
            $wxUser = OfficialActivityUser::query()->with("user")->where('open_id', $wx[ 'openid' ])->first();
            if (!$wxUser->poster_url) {
                $_tmp = PostImgCombine::makePoster($wxUser->user, $activity);
                $wxUser->fill($_tmp)->save();

                return $this->success([ "message" => 'success', 'url' => $wxUser->poster_url ]);
            }
            else {
                return $this->success([ "message" => 'success', 'url' => $wxUser->poster_url ]);
            }
        }
        else {
            return view('official_front.number.poster', compact('activity'));
        }
    }

}