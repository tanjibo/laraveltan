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


use App\Exceptions\InternalException;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ApiController extends Controller
{
    use ApiResponse;




}