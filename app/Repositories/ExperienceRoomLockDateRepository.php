<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 11/10/2017
 * Time: 6:10 PM
 */

namespace App\Repositories;


use App\Models\ExperienceRoomLockdate;

class ExperienceRoomLockDateRepository
{

    /**
     * @param int $id
     * @return array|bool 初始化锁定时间
     */
    public static function initLockDate( int $id )
    {
        //全院
        switch ( $id ) {
            case 1:
                return self::getAllYardLockDate($id);

            case 3:
                return self::getRuRoomLockDate($id);

            case 8:
                return self::getXingAndZhiLockDate($id);

            default:
                return self::getCommonRoomDate($id);

        }
    }


    /**
     * @param int $id
     * @return array|bool  获得全院的锁定时间，包括自己的实际，还有其他房间的
     */
    static private function getAllYardLockDate( int $id )
    {
        if ($id != 1) return false;
        $self = ExperienceRoomLockdate::query()->where('room_id', $id)->value('lockdate');

        $other = ExperienceRoomLockdate::query()->whereNotIn('room_id', [ 1 ])->select('lockdate')->get()->toArray();
        $other = collect($other)->flatten(10)->toArray();

        return [ 'allyard' => $self, 'father' => array_unique($other), 'self' => $self ];

    }

    /**
     * @param int $id
     * @return array|bool 获得如房间的锁定时间
     */
    static private function getRuRoomLockDate( int $id )
    {
        if ($id != 3) return false;
        //全院时间
        $allyard = ExperienceRoomLockdate::query()->where('room_id', 1)->value('lockdate');
        //自己
        $self = ExperienceRoomLockdate::where('room_id', $id)->value('lockdate');
        //其他
        $other = ExperienceRoomLockdate::whereIn('room_id', [ 8, 10 ])->select('lockdate')->get()->toArray();
        $other = collect($other)->flatten(10)->toArray();

        return [ 'allyard' => $allyard, 'self' => $self, 'father' => $other ];
    }

    /**
     * @param int $id
     * @return bool 获得星月阁和之未卢房间锁定日期
     */
    static private function getXingAndZhiLockDate( int $id ): array
    {
        if (!in_array($id, [ 8, 10 ])) return false;
        //全院
        $allyard = ExperienceRoomLockdate::query()->where('room_id', 1)->value('lockdate');
        //父级房间:如
        $father = ExperienceRoomLockdate::query()->where('room_id', 3)->value('lockdate');
        //自己
        $self = ExperienceRoomLockdate::where('room_id', $id)->value('lockdate');

        return [ 'self' => $self, 'allyard' => $allyard, 'father' => $father ];
    }

    /**
     * @param $id
     * @return array 普通房间
     */
    static private function getCommonRoomDate( $id ): array
    {
        //全院
        $allyard = ExperienceRoomLockdate::query()->where('room_id', 1)->value('lockdate');
        //自己
        $self = ExperienceRoomLockdate::query()->where('room_id', $id)->value('lockdate');
        return [ 'self' => $self, 'allyard' => $allyard, 'father' => [] ];
    }


}