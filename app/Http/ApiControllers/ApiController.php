<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/9/2017
 * Time: 2:25 PM
 */

namespace App\Http\ApiControllers;

use App\Foundation\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ApiController extends Controller
{
    use ApiResponse;

    protected function token( Request $request,string $guard='api' )
    {

        $request->request->add(
            [
                'grant_type'    => 'password',
                'client_id'     => 6,
                'client_secret' => 'Bx2yV5kbl1MIiuWBxdLALBK2HRm8LaTsef68fckU',
                'username'      => $request->username?:$request->mobile,
                'password'      => $request->password,
                'guard' =>$guard
            ]
        );
        $proxy = Request::create('oauth/token', 'POST');

        $response = \Route::dispatch($proxy);
        return $response;
    }


}