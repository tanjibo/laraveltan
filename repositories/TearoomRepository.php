<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 7/8/2018
 * Time: 9:40 AM
 */

namespace Repositories;


use App\Models\Tearoom;

class TearoomRepository
{



    public function getTearoomList(string $order='ASC')
    {
        return Tearoom::query()->orderBy('sort', $order)->orderBy('id',$order)->get();
    }

}