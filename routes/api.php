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

Route::middleware('auth:api')->get(
    '/user', function( Request $request ) {
    return $request->user();
}
)
;

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

Route::group(
    [ 'namespace' => 'Front', 'middleware' => 'api' ], function() {
    Route::post('/customer/login', 'LoginController@login')->name('customer.login');
    Route::post('/customer/logout', 'LoginController@logout')->name('customer.logout');

}
);