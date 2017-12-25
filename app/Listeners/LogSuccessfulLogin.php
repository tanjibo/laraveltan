<?php

namespace App\Listeners;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jenssegers\Agent\Facades\Agent;

class LogSuccessfulLogin
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
     * @param  Login $event
     * @return void
     */
    public function handle( Login $event )
    {
        $this->updateUser($event->user);
    }


    /**
     * @param User $user
     * 登录成功之后更新用户
     */
    public function updateUser( User $user )
    {
        $data = [
            "terminal"     => Agent::device() . " " . Agent::browser(),
            'updated_at' => Carbon::now(),
        ];
        $user->update($data);
    }
}
