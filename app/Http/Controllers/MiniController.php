<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MiniController extends Controller
{
    public function miniServe()
    {
        Log::info('request arrived.');
        $app = app('wechat.mini_program');
        $app->server->push(
            function( $message ) {
                return '欢迎使用使用lrss小程序';
            }
        );
        return $app->server->serve();
    }
}
