<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 20/7/2018
 * Time: 10:23 AM
 */

namespace Repositories;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MemberCardEventRepository
{

    public function __construct()
    {

    }

    public function handler( $message )
    {

        if (in_array($message[ 'MsgType' ], [ 'card_pass_check', 'card_not_pass_check','user_get_card' ])) {
            return call_user_func_array([ $this, lcfirst(Str::studly($message[ 'MsgType' ])) ], [ $message ]);
        }
    }

    /**
     * @param $message
     */
    public function cardPassCheck($message){

    }

   public function cardNotPassCheck($message){

   }

   public function userGetCard($message){
       Log::info('dsdssdsdsdsdsd');
    Log::info($message);
   }
}