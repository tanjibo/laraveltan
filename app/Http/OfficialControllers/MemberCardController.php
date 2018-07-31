<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 18/7/2018
 * Time: 2:05 PM
 */

namespace App\Http\OfficialControllers;


use App\Exceptions\InternalException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Repositories\MemberCardRepository;

class MemberCardController extends Controller
{

    protected $app;
    protected $card;
    protected $repository;

    public function __construct( MemberCardRepository $repository )
    {
        $this->app        = app('wechat.official_account');
        $this->card       = $this->app->card;
        $this->repository = $repository;

    }

    public function index()
    {
        //用户是否开卡
       // dd(wechatOauthUser());
        $url = $this->repository->getActivateFormUrl();
        return redirect()->away($url);
//        $cardId = cache(config('app.member_card_cache_key'));
//        $cards  = [
//            [ 'card_id' => $cardId],
//        ];
//        $json   = $this->card->jssdk->assign($cards); // 返回 json 格式
//         $config=json_decode($json,true)[0]['cardExt'];
//        return view('official_front.member_card.index', [ 'cardId' => $cardId, 'code' => '', 'json' => $json,'config'=>$config]);
    }

    /**
     * 创建会员卡
     */
    public function addCard()
    {
        $this->repository->addMemberCard();
    }


    public function setWhiteList()
    {
        $result = $this->card->setTestWhitelistByName([ 'tantanjibo','new88918425' ]);

    }

    public function dispatchCard()
    {
        $this->repository->dispatchCard();
    }

    /**
     *会员卡回调地址
     */
    public function wxActivateAfterSubmitUrlCallback( Request $request )
    {

        $result = $this->repository->activateMemberCardAndUpdateInfo($request->all());
        if (!$result[ 'errcode' ]) {
            return redirect(route('officialAccount.memberCard.index'));
        }
        else {
            throw new InternalException("激活会员卡出现错误:" . $result[ 'errmsg' ]);
        }
    }


}