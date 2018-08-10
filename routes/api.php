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
    Route::post('/customer/refreshToken', "LoginController@refreshToken");


    Route::group(
        [ 'middleware' => 'auth.api' ], function() {


        Route::post('/customer/logout', 'LoginController@logout')->name('customer.logout');

        Route::post('/room/list', 'RoomController@roomList')->name('room.list');
        Route::post('/room/detail/{room_id}', 'RoomController@roomDetail')->name('room.detail');
        Route::post('/room/questions', 'RoomController@question');
        //评论列表
        Route::post('/comment/list/{room_id}', 'CommentController@commentList')->name('room.comment.list');
        Route::get('/article/process', "ArticleController@index");
        Route::get('/article/list', "ArticleController@list");
        Route::get('/article/detail/{id}/', "ArticleController@show");

        //房间不可订日期
        Route::post('/booking/room_checkin_disable/{room_id}', 'BookingController@RoomCheckinDisableBy')->name('room.booking.roomCheckinDisable');
        //房间不可订日期
        Route::post('/booking/room_checkout_disable/{room_id}', 'BookingController@RoomCheckoutDisableBy')->name('room.booking.roomCheckoutDisable');
        //剩余可订房间
        Route::post('/booking/room_left/{room_id}', 'BookingController@leftCheckinRoom')->name('room.booking.leftRoom');

        Route::post('/booking/orderTotalFee', 'BookingController@orderTotalFee')->name('room.booking.orderTotal');

        //创建订单
        Route::post('/booking/createBookingOrder', 'BookingController@createBookingOrder')->name('room.booking.create');

        Route::post('/booking/repay', 'BookingController@repay');

        //订单列表
        Route::post('/booking/orderList', 'BookingController@orderList')->name('room.booking.orderList');

        //订单详情
        Route::post('/booking/orderDetail/{booking_id}/{type?}', 'BookingController@orderDetail')->name('room.booking.orderList');


        //添加评论
        Route::post('/comment/add/{booking_id}/{type?}', 'CommentController@addComment')->name('room.comment.add');
        //显示评论框
        Route::post('/comment/rooms/{booking_id}/{type?}', 'CommentController@getBookingRooms')->name('room.comment.rooms');


        //时间选择器初始化
        Route::post('/booking/calendarInit', 'BookingController@calendarInit')->name('booking.calendarInit');


        //更改订单状态
        Route::get('/booking/changeOrderStatus/booking_id/{booking_id}/status/{status}/form_id/{form_id?}', 'BookingController@orderStatusToChange')->name('room.booking.changeStatus');


        //发送模板通知
        Route::post('/tpl/sendPayTpl', 'WechatTemplateController@sendPayTpl')->name('tpl.sendPayTpl');

        //测试通知
        Route::get('/tpl/changeBookingOrder', 'BookingController@changeBookingOrder');

        //用户资料
        Route::post('/user/userInfo', 'UserController@userInfo');

    }
    );

    //支付回调
    Route::post('/mini/callback/{booking_id}', 'BookingController@miniNotifyCallback')->name('mini.callback');


}
);

Route::group(
    [ 'namespace' => 'Art', 'middleware' => 'api' ], function() {

    Route::post('art/miniLogin', "LoginController@miniLogin");
    Route::post('art/refreshToken', "LoginController@refreshToken");
    //艺术展示

    Route::group(
        [ 'middleware' => 'auth.api' ], function() {

        Route::resource('art_show', 'ArtShowController', [ 'only' => [ 'index', 'show' ] ]);
        Route::get('art_show/comment/{art_show}', 'ArtShowController@getArtShowComment');
        //分享
        Route::get('art_show/share/{art_show}', 'ArtShowController@shareArtShow');
        //评论
        Route::resource('art_comment', 'CommentController', [ 'only' => [ 'store', 'destroy' ] ]);
        Route::post('art_comment_list/{art_show}', 'CommentController@commentList');
        Route::post('art_comment_detail/{art_comment}', 'CommentController@commentDetail');
        //点赞
        Route::resource('art_like', 'LikesController', [ 'only' => [ 'store' ] ]);

        //收藏列表
        Route::resource('art_collection', 'CollectionController', [ 'only' => [ 'index' ] ]);
        //收藏
        Route::post('art_collection/store/{art_show}', 'CollectionController@store');

        Route::resource('art_suggestion', 'SuggestionController', [ 'only' => [ 'store' ] ]);

        //用户资料
        Route::post('art_user/userInfo', 'UserController@userInfo');
        Route::post('art_user/unReadMsg', 'UserController@unReadMsg');
    }
    );
}
);

/**
 * 茶舍空间
 */
Route::group(
    [ 'namespace' => 'Tearoom','prefix'=>'tearoom' ], function() {
    //小程序登录
    Route::post('/miniLogin', 'LoginController@miniLogin')->name('tearoom.mini.login');
    Route::post('/refreshToken', "LoginController@refreshToken");
    Route::group(
        [ 'middleware' =>'auth.api' ], function() {
        //待修改
        Route::get('/', 'TearoomController@index')->name('tearoom.index');
        //房间时段价格
        Route::get('/time_price/{tearoom}', 'TearoomController@timePrice')->name('tearoom.timePrice');

        //房间价格一览表
        Route::get('/price_chart', 'TearoomController@priceChart')->name('tearoom.index.priceChart');
        //时间段
        Route::post('/schedule', 'ScheduleController@index')->name('tearoom.schedule.index');
        //创建订单
        Route::post('/booking/create', 'BookingController@store')->name('tearoom.booking.create');
    }
    );

}
);