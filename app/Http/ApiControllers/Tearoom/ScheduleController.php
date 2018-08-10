<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 7/8/2018
 * Time: 3:26 PM
 */

namespace App\Http\ApiControllers\Tearoom;


use App\Http\ApiControllers\ApiController;

use Illuminate\Http\Request;
use Repositories\TearoomScheduleRepository;

class ScheduleController extends ApiController
{

    function index( Request $request, TearoomScheduleRepository $repository )
    {
        return $this->success($repository->getTimeTableApi($request->price_id, $request->date));
    }
}