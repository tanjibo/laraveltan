<?php

namespace App\Listeners;

use App\Events\SendExperienceBackendNotificationEvent;
use App\Models\ExperienceBooking;
use App\Models\Sm;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendExperienceBackendNotificationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
   public  $request='';
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    /**
     * Handle the event.
     *
     * @param  SendExperienceBackendNotificationEvent  $event
     * @return void
     */
    public function handle(SendExperienceBackendNotificationEvent $event)
    {



    }








}
