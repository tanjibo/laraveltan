<?php

namespace App\Policies;

use App\Models\Api\TearoomBooking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TearoomBookingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user,TearoomBooking $booking){
        return $user->id ==$booking->user_id;
    }
}
