<?php

namespace App\Policies;

use App\Models\ExperienceBooking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ExperienceRoomBookingPolicy
 * @package App\Policies
 * 订单授权
 */
class ExperienceRoomBookingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function update(User $user,ExperienceBooking $booking){
        return $user->id ==$booking->user_id;
    }
}
