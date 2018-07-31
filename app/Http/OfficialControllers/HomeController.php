<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 22/5/2018
 * Time: 11:03 AM
 */

namespace App\Http\OfficialControllers;


use App\Foundation\Lib\WechatShareHandle;
use App\Models\OfficialActivityUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Repositories\StoreUserRepository;


class HomeController extends BaseController
{



    function index()
    {


        // $this->app->customer_service->message('hello')->to('oAAhsxJ2uugzr1JoiZSYL-c6v-P4')->send();

        $user = wechatOauthUser();

        $drawUser = OfficialActivityUser::isJoin($user[ 'openid' ]);
        $activity = $this->activity;

        //参加过活动的通知，直接跳到个人中心
        if ($drawUser[ 'draw_number_count' ]) {
            return redirect()->route("officialAccount.user.numberCenter", [ 'official_activity_id' => $activity ]);
        }



        $share = $activity->share_settings->first();


        $shareTimeLine = WechatShareHandle::shareOnTimeLine(
            $share->title,
            route('officialAccount.showDrawArticle', [ "token" => $drawUser[ 'openid' ], 'url' => $share->link_url, 'official_activity_id' => $activity ]),
            asset($share->cover_img),
            $activity
        );

        return view("official_front.home.index", compact("shareTimeLine", "drawUser", 'activity'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 获取用户电话号码
     */
    public function getPhone( Request $request,StoreUserRepository $repository)
    {

        if ($request->ajax()) {
            $reg = "/^((13|14|15|17|18)[0-9]{1}\d{8})$/";

            if (!$request->phone || !preg_match($reg, $request->phone)) {
                return $this->error([ "message" => "您的电话号码不正确" ], 502);
            }

            if (OfficialActivityUser::isJoin($request->phone)) {
                return $this->error([ "message" => "此号码已经参加过了" ], 502);
            }

            $wxUserInfo = wechatOauthUser();
             $wxUserInfo['phone']=$request->phone;

            $user=$repository->storeUserByWebMobile($wxUserInfo);


            //参加活动
            if (OfficialActivityUser::attendDraw($user, $this->activity)) {

                $subscribe = $this->app->user->get($wxUserInfo[ "openid" ]);

                //等于1的时候代表订阅过
                if (isset($subscribe[ "subscribe" ]) && !$subscribe[ "subscribe" ]) {

                    return $this->success([ "message" => "success", "code" => "unsubscribe" ], 200);
                }

                return $this->success([ "message" => "success" ], 200);
            }
            else {
                return $this->error([ "message" => "你的使用姿势不太对" ], 502);
            }

        }
        return $this->error([ "message" => "你的使用姿势不太对" ], 502);

    }


}