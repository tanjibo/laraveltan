<?php

/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/12/2017
 * Time: 1:39 PM
 */
namespace App\Observer\Art;

use App\Models\ArtShow;
use Illuminate\Support\Facades\DB;

class ArtShowObserver
{

    function saved(ArtShow $artShow){
        DB::table('art_show')->where('id',$artShow->id)->update(['mini_route'=>'pages/detail?art_show_id='.$artShow->id]);
    }


    function deleting(ArtShow $artShow){


    }


}