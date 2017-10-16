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
*/
/**
 * 后台api,暂时废弃
 */
Route::group(
    [ 'namespace' => 'Admin', 'middleware' => 'api' ], function() {

    Route::post('/user/login', 'LoginController@login')->name('user.login');


    Route::group(
        [ 'middleware' => 'auth.api_admin' ], function() {
        Route::get('/hello', 'HelloController@hello')->name('hello');
        //订单搜索
        Route::get('/experiencebooking/search', 'ExperienceBookingController@search')->name('experiencebooking.search');
    }
    );

}
);

/**
 * 小程序api
 */
Route::group(
    [ 'namespace' => 'Front', 'middleware' => 'api' ], function() {

    Route::post('/customer/login', 'LoginController@login')->name('customer.login');

    Route::post('/customer/retoken', 'LoginController@refreshToken')->name('customer.refreshToken');
    //小程序登录
    Route::post('/customer/miniLogin', 'LoginController@miniLogin')->name('customer.mini.login');

    //房间列表
    Route::post('/room/list', 'ExperienceRoomController@roomList')->name('room.list');

    Route::group(
       [ 'middleware' => App::environment()=='develop'?:'auth.api_front' ], function() {
        Route::post('/customer/logout', 'LoginController@logout')->name('customer.logout');


        //房间详情
        Route::post('/room/detail/{room_id}', 'ExperienceRoomController@roomDetail')->name('room.detail');

        //房间不可订日期
        Route::post('/booking/room_checkin_disable/{room_id}', 'ExperienceRoomBookingController@RoomCheckinDisableBy')->name('room.booking.roomCheckinDisable');
        //房间不可订日期
        Route::post('/booking/room_checkout_disable/{room_id}', 'ExperienceRoomBookingController@RoomCheckoutDisableBy')->name('room.booking.roomCheckoutDisable');
        //剩余可订房间
        Route::post('/booking/room_left/{room_id}', 'ExperienceRoomBookingController@leftCheckinRoom')->name('room.booking.leftRoom');

        Route::post('/booking/orderTotalFee', 'ExperienceRoomBookingController@orderTotalFee')->name('room.booking.orderTotal');

        Route::post('/booking/createBookingOrder', 'ExperienceRoomBookingController@createBookingOrder')->name('room.booking.create');
        //更改订单状态
        Route::post('/booking/changeOrderStatus/{booking_id}', 'ExperienceRoomBookingController@orderStatusToChange')->name('room.booking.changeStatus');

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

        //显示评论框
        Route::post('/comment/upload', 'ExperienceBookingCommentController@upload')->name('room.comment.upload');

        //山云荟
        Route::post('/specialRoom/disabledDate/{room_id}', 'ExperienceBookingSanAndXingController@RoomCheckinDisableBy')->name('specialRoom.disabledDate');

        //山云荟每个时间段
        Route::post('/specialRoom/getQuanTum', 'ExperienceBookingSanAndXingController@getQuanTum')->name('specialRoom.getQuanTum');

        //山云荟总价格
        Route::post('/specialRoom/totalFee', 'ExperienceBookingSanAndXingController@totalFee')->name('specialRoom.totalFee');

        //创建特殊订单
        Route::post('/specialRoom/booking', 'ExperienceBookingSanAndXingController@createSpecialOrder')->name('specialRoom.booking');

        //时间选择器初始化
        Route::post('/booking/calendarInit', 'ExperienceRoomBookingController@calendarInit')->name('booking.calendarInit');
    }
    );



}
);