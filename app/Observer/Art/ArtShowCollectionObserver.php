<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 27/12/2017
 * Time: 2:54 PM
 */

namespace App\Observer\Art;


use App\Models\ArtShowCollection;

class ArtShowCollectionObserver
{

     function created(ArtShowCollection $collection){
         
         $collection->art_show->increment('like_count');
     }
}