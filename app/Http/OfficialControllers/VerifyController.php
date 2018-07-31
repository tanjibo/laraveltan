<?php


namespace App\Http\OfficialControllers;

use App\Foundation\Lib\WechatEventMessageHandler;
use App\Foundation\Lib\WechatTextMessageHandler;
use App\Http\Controllers\Controller;
use App\Models\OfficialActivityShareRelation;
use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Http\Request;



class VerifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $app = app('wechat.official_account');
        $app->server->push(WechatTextMessageHandler::class, Message::TEXT);
        //事件类型
        $app->server->push(WechatEventMessageHandler::class, Message::EVENT);

        return $app->server->serve();
    }


    /**
     * @param Request $request
     * 分享文章地址
     */
    public function showDrawArticle( Request $request )
    {

        $data = wechatOauthUser();
        if ($request->token) {
            OfficialActivityShareRelation::add($data[ 'openid' ], $request->token);
        }
        app('session')->forget('wechat.oauth_user.default');
        header("Location:{$request->url}");

    }

    public function gateway()
    {

        return view("official_front.gate",compact('json'));
    }





}
