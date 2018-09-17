<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        $sql=str_replace("?","'%s'",$event->sql);
       $params= collect($event->bindings)->map(function($item){
            if($item instanceof  \DateTime){
               return $item->getTimestamp();
            }else{
                return $item;
            }
        });
        try{
            $log=vsprintf($sql,$params->toArray());

            Log::info($log);
        }catch (\Exception $e){
             echo 111;
        }


    }
}
