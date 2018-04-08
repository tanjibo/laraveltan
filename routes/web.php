<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("login", "LoginController@showLoginForm");
Route::post("login", "LoginController@login")->name('login');
Route::get('logout', "LoginController@logout")->name('logout');

/**
 * 安吉
 */

Route::middleware([ "auth" ])->group(
    function() {

        Route::get('home', "HomeController@home")->name('home');

        //-----------------------用户---------------------------
        Route::resource('user', 'UserController');
        Route::post('user/index_api', 'UserController@indexApi')->name('user.index_api');
        Route::match([ 'post', 'get' ], 'user/give_permissions/{user}', 'UserController@givePermissions')->name('user.give_permissions');
        Route::match([ 'post', 'get' ], 'user/user_all_order/{user}', 'UserController@userAllOrder')->name('user.user_all_order');

        //-----------------------权限---------------------------
        Route::resource('permission', 'PermissionsController');
        Route::post('permission/mass_destroy', 'PermissionsController@massDestroy')->name('permission.mass_destroy');

        //-----------------------角色---------------------------
        Route::resource('roles', 'RolesController');
        Route::post('roles/mass_destroy', 'RolesController@massDestroy')->name('roles.mass_destroy');


        //-----------------------通知---------------------------
        Route::resource('notification', 'NotificationController', [ 'only' => [ "index" ] ]);

        //上传图片
        Route::any('upload/qiniu', 'UploadController@uploadToQiniuBy')->name('upload.qiniu');


        Route::namespace('Experience')->group(
            function() {
                //-----------------------安吉房间---------------------------
                Route::resource('experience_rooms', 'RoomController');
                Route::post('experience_rooms/makePrice/{experience_room}', 'RoomController@makePrice')->name('experience_rooms.makePrice');
                Route::match([ 'post', 'get' ], 'experience_rooms/lockDate/{experience_room}', 'RoomController@lockDate')->name('experience_rooms.lockDate');
                //生成锁定时间
                Route::post('experience_rooms/makeDate', 'RoomController@makeDate')->name('experience_rooms.makeDate');
                //-----------------------安吉房间订单---------------------------
                Route::resource('experience_bookings', 'BookingController');
                Route::post('experience_bookings/index_api', 'BookingController@indexApi')->name('experience_bookings.index_api');
                //更改订单状态
                Route::post('experience_bookings/changeStatus/{experience_booking}', 'BookingController@changeStatus')->name('experience_bookings.changeStatus');
                //编辑备注
                Route::post('experience_bookings/editRequirements/{experience_booking}', 'BookingController@editRequirements')->name('experience_bookings.editRequirements');
                //初始化日期
                Route::post('experience_bookings/calendarInit/{room_id}', 'BookingController@calendarInit')->name('experience_bookings.calendarInit');
                //剩余的房子
                Route::post('experience_bookings/leftCheckinRoom/{experience_room}', 'BookingController@leftCheckinRoom')->name('experience_bookings.leftCheckinRoom');

                //-----------------------评论---------------------------
                Route::resource('experience_comments', 'CommentController');
                Route::post('experience_comments/index_api', 'CommentController@indexApi')->name('experience_comments.index_api');
                Route::post('experience_comment_reply/{experience_comment}', 'CommentController@reply')->name('experience_comments.reply');

                //-----------------------设置---------------------------
                Route::resource('experience_settings', 'SettingsController');
                //----------------------三舍改造过程-----------------------------
                Route::resource('experience_article', 'ArticleController');

                Route::resource('experience_more_article', 'MoreArticleController');

                Route::resource('experience_wechat_fetcher', 'WechatFetcherController');

                Route::resource("experience_send_sm","SendSmController");


            }
        )
        ;

        //艺术展品-----------------------------------------------
        Route::namespace('Art')->group(
            function() {
                Route::resource('art', 'ArtShowController');
                Route::post('art/index_api', 'ArtShowController@indexApi')->name('art.index_api');
                Route::resource('art_comment', 'CommentController');
                Route::post('art_comment/index_api', 'CommentController@indexApi')->name('art_comment.index_api');

                Route::resource('art_comment_like', 'LikesController', [ 'only' => [ "store" ] ]);
                Route::post('art_show_collect/{art_show}','ArtShowCollectController@store')->name('art_show_collect.store');

                Route::resource('art_suggestion', 'SuggestionController',['only'=>['index','destroy']]);
                Route::post('art_suggestion/index_api', 'SuggestionController@indexApi')->name('art_suggestion.index_api');
                Route::post('art_suggestion/reply/{art_suggestion}','SuggestionController@reply')->name('art_suggestion.reply');
            }
        )
        ;

        //-------------------------茶舍房间---------------------------
        Route::namespace('Tearoom')->group(
            function() {

                Route::resource('tearoom', 'TearoomController');
                Route::match([ 'post', 'get' ], 'tearoom/lockDate/{tearoom}', 'TearoomController@lockDate')->name('tearoom.lockDate');
                //生成锁定时间
                Route::post('tearoom/makeDate', 'TearoomController@makeDate')->name('tearoom.makeDate');
                Route::post('tearoom/initGetLockDate/{tearoom}', 'TearoomController@initGetLockDate')->name('tearoom.initGetLockDate');


//-------------------------茶舍房间订单---------------------------
                Route::resource('tearoom_booking', 'BookingController');
                Route::post('tearoom_booking/index_api', 'BookingController@indexApi')->name('tearoom_booking.index_api');
                Route::post('tearoom_booking/getInitTimeTable', 'BookingController@getInitTimeTable')->name('tearoom_booking.getInitTimeTable');

                //编辑备注
                Route::post('tearoom_booking/editRequirements/{booking}', 'BookingController@editRequirements')->name('tearoom_booking.editRequirements');

                Route::post('tearoom_booking/changeStatus/{booking}', 'BookingController@changeStatus')->name('tearoom_booking.changeStatus');
            }
        )
        ;


    }
)
;


