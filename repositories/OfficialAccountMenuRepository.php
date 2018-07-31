<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/7/2018
 * Time: 2:39 PM
 */

namespace Repositories;


use App\Models\OfficialAccountDefaultSetting;

class OfficialAccountMenuRepository
{
    protected $defaultSetting;

    function __construct()
    {
        $this->defaultSetting = $this->getDefaultSetting();
        $this->setMiniProgramAppId();
    }

    /**
     * @return mixed
     * 后台默认设置
     */
    private function getDefaultSetting()
    {

        $defaultSetting = OfficialAccountDefaultSetting::officialAccountHasDefaultSetting();

        preg_match('/<pre.*>([\s\S]*?)<\/pre>/', $defaultSetting->menu_json, $match);

        $str = html_entity_decode($match[ 1 ]);

        $str = str_replace([ "<span class=hljs-string>", "<span class=\"hljs-string\">", "</span>", "<span class=\"hljs-symbol\">", "<span class=\"hljs-comment\">" ], [ "" ], $str);

        return eval("return $str;");
    }

    /**
     * 设置小程序appId  默认设置读取出来config函数没有起作用
     */
    public function setMiniProgramAppId()
    {
        //app_id
        $this->defaultSetting[ 1 ][ 'sub_button' ][ 0 ][ 'appid' ] = config("minilrss.default.appid");
    }

    /**
     * @return mixed
     *  重置菜单栏
     */
    public function reset()
    {
        return $this->make();
    }


    /**
     * @return mixed
     * 生成菜单栏
     */
    private function make()
    {
        $result = app("wechat.official_account")->menu->create($this->defaultSetting);
        return $result;
    }


    /**
     * @param $activity
     * @return mixed
     * 免费住活动菜单栏
     */
   public function makeMenu( $activity )
    {
        $data = [
            [
                "type" => "view",
                "name" => '抽奖码排行榜',
                'url'  => makeActivityMixUrl('officialAccount.user.numberList', $activity),
            ],
            [
                "type" => "click",
                "name" => '获取推广海报',
                "key"  => 'official_activity_post_envent:' . $activity->id,
            ],
            [
                "type" => "view",
                "name" => $activity->name,
                "url"  => makeActivityMixUrl('officialAccount.home', $activity),
            ],
        ];

        //代表开启
        if (!isset(request()->close)) {
            $this->defaultSetting[ 1 ][ 'sub_button' ] = array_merge($this->defaultSetting[ 1 ][ 'sub_button' ], $data);
        }

        return $this->make();
    }


}