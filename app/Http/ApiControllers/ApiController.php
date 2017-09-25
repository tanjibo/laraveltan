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

    protected function token( Request $request, string $guard = 'api' )
    {

        $request->request->add(
            config('passport') +
            [
                'username' => $request->username ?: $request->mobile,
                'password' => $request->password,
                'guard'    => $guard,
            ]
        );
        $proxy = Request::create('oauth/token', 'POST');

        $response = \Route::dispatch($proxy);

        $data = json_decode($response->getContent(), true);
        if ($response->getStatusCode() == $this->statusCode) {
            return $this->success($data);
        }
        else {
            return $this->notFound($data);
        }
    }


}