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




use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class ApiController extends Controller
{
   use ApiResponse;

    protected function token( Request $request, string $client = 'experience' )
    {

         $user =$request->username?:$request->union_id;
        $request->request->add(
            config('passport.'.$client) +
            [
                'username' => $user,
                'password' => $request->password,
            ]
        );
        $proxy = Request::create('oauth/token', 'POST');

        $response = \Route::dispatch($proxy);

        $data = json_decode($response->getContent(), true);
        if ($response->getStatusCode() == $this->statusCode) {
           $data['time']=time();
            return $this->success($data);
        }
        else {
            return $this->notFound($data);
        }
    }




}