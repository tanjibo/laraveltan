<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 18/7/2018
 * Time: 5:28 PM
 */

namespace Repositories;


use App\Exceptions\InternalException;
use App\Foundation\Lib\WechatTools;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * Class MemberCardRepository
 * @package Repositories
 * 会员卡
 */
class MemberCardRepository
{
    protected $app;
    protected $card;

    public function __construct()
    {
        $this->app  = app('wechat.official_account');
        $this->card = $this->app->card;
    }

    /**
     * 创建会员卡
     */
    public function addMemberCard()
    {

        $attributes = json_decode($this->makeCardMustData('card.json'), true);
        //背景图
        if (!$bg = cache('member_card_bg')) {
            $bg = $this->getMaterial(storage_path('app/public/card_bg.png'));
            Cache::forever('member_card_bg', $bg);
        }


        //logo
        if (!$logo = cache('member_card_logo')) {
            $logo = $this->getMaterial(storage_path('app/public/card_logo.jpg'));
            Cache::forever('member_card_logo', $logo);
        }


        if ($bg && $logo) {
            $attributes[ 'background_pic_url' ]      = $bg;
            $attributes[ 'base_info' ][ 'logo_url' ] = $logo;
        }
        $attributes[ 'wx_activate_after_submit_url' ] = route('officialAccount.memberCard.wxActivateAfterSubmitUrlCallback');

        //生成会员卡
        $result = $this->card->create('member_card', $attributes);

        if (isset($result[ 'card_id' ])) {
            //设置开卡字段
            $this->setActivateUserForm($result[ 'card_id' ]);
            Cache::forever('member_card', $result[ 'card_id' ]);
            return $result[ 'card_id' ];
        }

        throw new InternalException('创建会员卡失败:'.$result['errmsg']);
    }


    private function makeCardMustData( string $val )
    {
        return Storage::disk('public')->get($val);
    }

    private function getMaterial( $url )
    {
        $result = $this->app->material->uploadImage($url);

        return isset($result[ 'url' ]) ? $result[ 'url' ] : '';

    }


    public function setWhiteList()
    {
        return $this->card->setTestWhitelistByName([ 'tantanjibo' ]);

    }

    public function dispatchCard()
    {
        //$this->getActivateFormUrl();
        // exit;
        //二维码方式领取二维码
        if (app()->environment('local')) {

            $cardId = $this->addMemberCard();
        }
        else {
            $cardId = \cache(config('app.member_card_cache_key')) ?: $this->addMemberCard();
        }

        $result = $this->qrCard($cardId);

        if (!$result[ 'errcode' ]) {
            header("Location:{$result[ 'show_qrcode_url']}");
        }

    }


    /**
     * 二维码方式
     */
    protected function qrCard( $card_id )
    {
        $cards = [
            'action_name' => 'QR_CARD',
            'action_info' => [
                'card' => [
                    'card_id'        => $card_id,
                    'is_unique_code' => false,
                    'outer_id'       => "card_id:" . $card_id,
                ],
            ],
        ];

        return $this->card->createQrCode($cards);

    }

    private function setActivateUserForm( $cardId )
    {
        $settings = [
            'required_form' => [
                'common_field_id_list' => [
                    'USER_FORM_INFO_FLAG_MOBILE',
                    'USER_FORM_INFO_FLAG_NAME',
                ],
            ],
            'optional_form'        => [
                'common_field_id_list' => [
                    'USER_FORM_INFO_FLAG_EMAIL',
                    'USER_FORM_INFO_FLAG_SEX',
                    'USER_FORM_INFO_FLAG_BIRTHDAY',
                ],
            ],
        ];

        //省略判断
       $this->card->member_card->setActivationForm($cardId, $settings);
    }


    /**
     * @param array $data
     * 激活会员卡
     */
    public function activateMemberCardAndUpdateInfo( array $data )
    {
        $code = $this->decryptCode($data[ 'encrypt_code' ]);

        //获取临时信息
        $wxUserFillFormInfo = $this->getActivateTempInfo($data);

        $memberCardUserInfo = $this->card->member_card->getUser($data[ 'card_id' ], $code);

        $willAddUserData = array_merge($wxUserFillFormInfo, $memberCardUserInfo, wechatOauthUser());

        //添加用户
        $userData = User::addUserByMemberCard($willAddUserData);

        return $this->activateMemberCard($code, $userData);
    }

    /**
     * @param $encryptCode
     * @return mixed
     * @throws InternalErrorException
     * 解码code
     */
    private function decryptCode( $encryptCode )
    {
        $codeArr = $this->card->code->decrypt($encryptCode);
        if ($codeArr[ 'errcode' ]) {
            throw new InternalErrorException('解码code出现错误:' . $codeArr[ 'errmsg' ]);
        }
        return $codeArr[ 'code' ];
    }

    /**
     * 支持开发者根据activate_ticket获取到用户填写的信息
     * @param array $data
     * @return array
     * @throws InternalErrorException
     */
    private function getActivateTempInfo( array $data )
    {

        $result = $this->post(
            '/activatetempinfo/get', [
                                       'activate_ticket' => $data[ 'activate_ticket' ],
                                   ]
        );

        if ($result[ 'errcode' ]) {
            throw new InternalErrorException("获取用户信息出错:" . $result[ 'errmsg' ]);
        }

        return collect(current($result[ 'info' ]))->mapWithKeys(
            function( $item ) {

                $key = strtolower(substr(strrchr($item[ 'name' ], "_"), 1));
                switch ( $key ) {
                    case "sex":
                        return [ 'gender' => $item[ 'value' ] == '男' ? 1 : 2 ];
                    case "name":
                        return [ 'username' => $item[ 'value' ] ];
                }

                return [ $key => $item[ 'value' ] ];
            }
        )->toArray()
            ;


    }


    public function activateMemberCard( $code, $userInfo )
    {
        $info = [
            'membership_number'        => $userInfo->mobile, //会员卡编号，由开发者填入，作为序列号显示在用户的卡包里。可与Code码保持等值。
            'code'                     => $code, //创建会员卡时获取的初始code。
            'activate_begin_time'      => '1397577600', //激活后的有效起始时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式
            'activate_end_time'        => strtotime('2038-12-29'), //激活后的有效截至时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式。
            'init_bonus'               => $userInfo->surplus_credit, //初始积分，不填为0。
            'init_balance'             => $userInfo->balance * 100, //初始余额，不填为0。
            'init_custom_field_value1' => User::credit2level($userInfo->surplus_credit)[ 'name' ], //创建时字段custom_field1定义类型的初始值，限制为4个汉字，12字节。
        ];

        return $this->card->member_card->activate($info);

    }


    public function getActivateFormUrl()
    {
        $cardId = \cache(config('app.member_card_cache_key'));
        if (!$cardId) throw new InternalErrorException("暂无会员卡");

        $data = [ 'card_id' => $cardId, 'outer_str' => 'card_id:' . $cardId ];

        $result = $this->post('/activate/geturl', $data);

        if ($result[ 'errcode' ]) {
            throw new InternalErrorException("获取开卡地址出现错误");
        }
        return $result[ 'url' ];

    }

    public function accessToken()
    {
        return $this->app->access_token->getToken()[ 'access_token' ];
    }


    private function post( $url, $data )
    {
        $client = new Client();

        $url = 'https://api.weixin.qq.com/card/membercard' . $url . '?access_token=' . $this->accessToken();

        $result = $client->post($url, [ 'json' => $data ]);

        if ($result->getStatusCode() == Response::HTTP_OK) {

            return json_decode($result->getBody()->getContents(), true);
        }
        throw new InternalException('获取开卡组件接口出错');
    }

}