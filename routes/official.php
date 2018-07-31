<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 21/5/2018
 * Time: 12:50 PM
 */
Route::prefix('official_account')->group(
    function() {
        //微信公众号和自己服务器对接
        Route::any('/verify', 'VerifyController@index')->name('officialAccount.verify');
        Route::any('/img', 'VerifyController@coverImg');

        Route::middleware("wechat.oauth:snsapi_base")->match([ 'post', 'get' ], "/showDrawArticle", "verifyController@showDrawArticle")->name('officialAccount.showDrawArticle');


        Route::group(

            [ 'middleware' => 'wechat.oauth.lrss:snsapi_userinfo' ], function() {
            //首页
            Route::any('/home', "HomeController@index")->name('officialAccount.home');
            Route::any('/gateway', "VerifyController@gateway")->name('officialAccount.gateway');
            //获取电话号码
            Route::post('/getPhone', "HomeController@getPhone")->name('officialAccount.getPhone');
            //分享朋友圈
            Route::match([ 'get', 'post' ], '/shareTimeLine', "ShareController@shareTimeLine")->name('officialAccount.shareTimeLine');

            //个人抽奖码中心
            Route::get('/user/center', "DrawNumberController@numberCenter")->name("officialAccount.user.numberCenter");
            //排行榜
            Route::get('/user/numberList', "DrawNumberController@numberList")->name("officialAccount.user.numberList");
            //生成海报
            Route::match([ 'get', 'post' ], '/user/poster', "DrawNumberController@poster")->name("officialAccount.user.poster");


        }
        );

        //---------------------------微信卡券-----------------------
        Route::prefix('member_card')->group(
            function() {
                Route::get('/add_card', 'MemberCardController@addCard')->name('officialAccount.memberCard.addCard');
                Route::get('/set_white_list', 'MemberCardController@setWhiteList')->name('officialAccount.memberCard.setWhiteList');
                Route::get('/dispatch_card', 'MemberCardController@dispatchCard')->name('officialAccount.memberCard.dispatchCard');
                //条转型开卡组件 商户回调网页
                Route::get('/wx_activate_after_submit_url_callback', 'MemberCardController@wxActivateAfterSubmitUrlCallback')->name('officialAccount.memberCard.wxActivateAfterSubmitUrlCallback');
                //加入会员入口
                Route::middleware('wechat.oauth.lrss:snsapi_userinfo' )->get('/index', 'MemberCardController@index')->name('officialAccount.memberCard.index');
            }
        )
        ;


    }
)
;