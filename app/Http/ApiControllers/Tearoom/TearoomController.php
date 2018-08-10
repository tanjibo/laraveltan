<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 6/8/2018
 * Time: 6:10 PM
 */

namespace App\Http\ApiControllers\Tearoom;


use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Tearoom\TearoomResource;
use App\Http\Resources\Tearoom\TimePriceResource;
use App\Models\Tearoom;
use Repositories\TearoomRepository;


class TearoomController extends ApiController
{


    public function index(TearoomRepository $repository )
    {
        return $this->success(TearoomResource::collection($repository->getTearoomList('DESC')));
    }

    public function timePrice( Tearoom $tearoom )
    {

        return $this->success(TimePriceResource::collection($tearoom->tearoom_prices()->get()));
    }

    public function priceChart()
    {
        $data = [
            'images' => 'https://static.liaorusanshe.com/price_table_2018.png',
        ];
        return $this->success($data);
    }

}