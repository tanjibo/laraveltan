<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 11/10/2017
 * Time: 5:51 PM
 */

namespace Repositories;


use App\Models\ExperienceBooking;
use App\Models\ExperienceSpecialRoomBooking;
use App\Models\ExperienceSpecialRoomBookingXinyuege;

class BaseRepository
{
    static function _getBookingClassApi( $type )
    {

        switch ( $type ) {
            case 1:
                return ExperienceBooking::class;

            case 3:
                return ExperienceSpecialRoomBooking::class;

            case 4:
                return ExperienceSpecialRoomBookingXinyuege::class;

        }

    }
}