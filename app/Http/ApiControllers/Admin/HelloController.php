<?php

namespace App\Http\ApiControllers\Admin;

use App\Http\ApiControllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelloController extends ApiController
{

    public function  hello(){
        dd(\Auth::guard('admin_api')->check());
    }


}
