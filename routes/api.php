<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


/**
 * 小程序api
 */
Route::group(
    [ 'namespace' => 'Experience', 'middleware' => 'api' ], function() {

    //小程序登录
    Route::post('/customer/miniLogin', 'LoginController@miniLogin')->name('customer.mini.login');

    //房间列表
    Route::post('/room/list', 'ExperienceRoomController@roomList')->name('room.list');

    Route::group(
        [ 'middleware' => App::environment() == 'local' ?: 'auth.api' ], function() {

        Route::post('/customer/logout', 'LoginController@logout')->name('customer.logout');

        //房间详情
        Route::post('/room/detail/{room_id}', 'ExperienceRoomController@roomDetail')->name('room.detail');

        //房间常见问题
        Route::post('/room/questions', 'ExperienceRoomController@question');


        //房间不可订日期
        Route::post('/booking/room_checkin_disable/{room_id}', 'ExperienceRoomBookingController@RoomCheckinDisableBy')->name('room.booking.roomCheckinDisable');
        //房间不可订日期
        Route::post('/booking/room_checkout_disable/{room_id}', 'ExperienceRoomBookingController@RoomCheckoutDisableBy')->name('room.booking.roomCheckoutDisable');
        //剩余可订房间
        Route::post('/booking/room_left/{room_id}', 'ExperienceRoomBookingController@leftCheckinRoom')->name('room.booking.leftRoom');

        Route::post('/booking/orderTotalFee', 'ExperienceRoomBookingController@orderTotalFee')->name('room.booking.orderTotal');

        //创建订单
        Route::post('/booking/createBookingOrder', 'ExperienceRoomBookingController@createBookingOrder')->name('room.booking.create');

        Route::post('/booking/repay', 'ExperienceRoomBookingController@repay');

        //订单列表
        Route::post('/booking/orderList', 'ExperienceRoomBookingController@orderList')->name('room.booking.orderList');

        //订单详情
        Route::post('/booking/orderDetail/{booking_id}/{type?}', 'ExperienceRoomBookingController@orderDetail')->name('room.booking.orderList');

        //评论列表
        Route::post('/comment/list/{room_id}', 'ExperienceBookingCommentController@commentList')->name('room.comment.list');

        //添加评论
        Route::post('/comment/add/{booking_id}/{type?}', 'ExperienceBookingCommentController@addComment')->name('room.comment.add');
        //显示评论框
        Route::post('/comment/rooms/{booking_id}/{type?}', 'ExperienceBookingCommentController@getBookingRooms')->name('room.comment.rooms');




        //时间选择器初始化
        Route::post('/booking/calendarInit', 'ExperienceRoomBookingController@calendarInit')->name('booking.calendarInit');


        //更改订单状态
        Route::get('/booking/changeOrderStatus/booking_id/{booking_id}/status/{status}/form_id/{form_id?}', 'ExperienceRoomBookingController@orderStatusToChange')->name('room.booking.changeStatus');


        //发送模板通知
        Route::post('/tpl/sendPayTpl', 'WechatTemplateController@sendPayTpl')->name('tpl.sendPayTpl');

        //测试通知
        Route::get('/tpl/changeBookingOrder', 'ExperienceRoomBookingController@changeBookingOrder');

        //用户资料
        Route::post('/user/userInfo', 'UserController@userInfo');
    }
    );

    //支付回调
    Route::post('/mini/callback/{booking_id}', 'ExperienceRoomBookingController@miniNotifyCallback')->name('mini.callback');


}
);

Route::group(['namespace'=>'Art', 'middleware' => 'api' ],function(){

    Route::post('art/miniLogin',"LoginController@miniLogin");

    Route::group(
        [ 'middleware' => App::environment() == 'local' ?: 'auth.api' ], function() {

        //艺术展示
         Route::resource('art_show','ArtShowController',['only'=>['index','show']]);
         //评论
         Route::resource('art_comment','CommentController',['only'=>['store','destroy','index']]);
         //点赞
        Route::resource('art_comment_like','LikesController',['only'=>['store','index']]);
        //收藏
        Route::resource('art_collection','CollectionController',['only'=>['store','index']]);

        //用户资料
       Route::post('art_user/userInfo', 'UserController@userInfo');
       Route::post('art_user/unReadMsg', 'UserController@unReadMsg');
    }
    );
});