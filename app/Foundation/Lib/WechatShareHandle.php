<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 24/5/2018
 * Time: 5:20 PM
 */

namespace App\Foundation\Lib;


class WechatShareHandle
{

    /**
     * @param $title标题
     * @param $desc描述
     * @param $link地址
     * @param $imgUrl图片地址
     * @param string $callback 回调
     * @return string
     *
     */
    public static function shareFriend( $title, $desc, $link, $imgUrl, $activity='' )
    {
        $str
            = <<<END
        wx.ready(function () {
            wx.hideAllNonBaseMenuItem();
            wx.showMenuItems({
                menuList: ["menuItem:share:appMessage"] // 要显示的菜单项，所有menu项见附录3
            });

            wx.onMenuShareAppMessage({
               // title: '这是分享给朋友的', // 分享标题
               title:'{$title}',
               // desc: '分享才有意思', // 分享描述
               desc:'{$desc}',
               link:'{$link}',
              //  link: '{{route("officialAccount.showDrawArticle")}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
              imgUrl:'{$imgUrl}',
               // imgUrl: 'http://blog.ngrok.liaorusanshe.com/0.jpeg', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
// 用户点击了分享后执行的回调函数
              
            }
            });
        })
END;
        return $str;
    }


    public static function shareOnTimeLine( $title, $link, $imgurl,$activity)
    {
        $callback = route("officialAccount.shareTimeLine",['official_activity_id'=>$activity]);

        $str
                  = <<<str


  wx.ready(function () {
        wx.hideAllNonBaseMenuItem();
        wx.showMenuItems({
            menuList: ["menuItem:share:timeline"] // 要显示的菜单项，所有menu项见附录3
        });
       
     wx.onMenuShareTimeline({
            //title: '这是分享到朋友圈的', // 分享标题
            title:'{$title}',
           // link: "{{route('officialAccount.showDrawArticle')}}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            link:'{$link}',
           // imgUrl: 'http://blog.ngrok.liaorusanshe.com/0.jpeg', // 分享图标
            imgUrl:'{$imgurl}',
            success:function(res) {
           swal({ 
                      title: "分享成功🎉！",
                      text: "正在跳转。", 
                      timer: 4000, 
                      showConfirmButton: false 
                    })
           
            setTimeout(function(){
             window.location.href="{$callback}"
        },1500)
                            

            },
        });
    })
str;
        return $str;
    }

}