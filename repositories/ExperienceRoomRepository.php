<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 28/9/2017
 * Time: 10:50 AM
 */

namespace Repositories;


use App\Http\Resources\Experience\ExperienceRoomResource;
use App\Models\Backend\ExperienceRoom;
use Qiniu\Http\Request;
use Carbon\Carbon;


class ExperienceRoomRepository
{
    function model()
    {
        return ExperienceRoom::class;
    }

    public function all()
    {
        $rooms = ExperienceRoom::query()->whereIn('type', [ ExperienceRoom::TYPE_SINGLE, ExperienceRoom::TYPE_ALL ])->get();
        return ExperienceRoomResource::collection($rooms);

    }

    public function find( string $id )
    {
        return new ExperienceRoomResource(ExperienceRoom::query()->find($id));
    }



    /**
     * @param Request $request
     * @param ExperienceRoom $room
     * 根据时间段生成日期
     */
    public function makePrice( Request $request, ExperienceRoom $room )
    {

        return collect($this->makeDate($request->dateSelect))->map(
            function( $item ) use ( $request, $room ) {
                return [
                    'date'               => $item,
                    'price'              => $request->price,
                    'type'               => $request->type,
                    'experience_room_id' => $room->id,
                ];
            }
        );

    }


    /**
     * 更新房间
     */
    public function updateRoom( Request $request, ExperienceRoom $room )
    {
        $speicalPrice = static::_filteSpecialPrice($request->specialPrice, $request->newSpecialPrice);


        if ($speicalPrice->toArray()) {
            $room->experience_special_price()->delete();
            $room->experience_special_price()->createMany($speicalPrice->toArray());

        }
        if($request->sliders){
            $room->sliders()->delete();
            $room->sliders()->createMany($request->sliders);
        }

        return $room->fill($request->all())->save();
    }

    /**
     * 过滤价格：合并新旧价格，并且过滤过期的价格
     * @param array $specialPrice
     * @param array $newSpecialPrice
     * return collect
     */
    static private function _filteSpecialPrice( array $specialPrice, array $newSpecialPrice )
    {
        return collect($specialPrice)->merge($newSpecialPrice)->unique('date')->values()->transform(
            function( $item ) {
                $item[ 'date' ] = date("Y-m-d", strtotime($item[ 'date' ]));
                return $item;
            }
        )->where('date', '>', Carbon::today())
            ;

    }

    public function makeDate( array $dateArr )
    : array {
        array_filter($dateArr);

        if (empty($dateArr)) abort(409);

        $tmpArr    = [];
        $startDate = static::_date(current($dateArr));
        $endDate   = static::_date(end($dateArr));

        while ( $startDate <= $endDate ) {
            array_push($tmpArr, date('Y-m-d', $startDate));
            $startDate = strtotime('+1 day', $startDate);
        }
        return $tmpArr;
    }


    private static function _date( $date )
    {
        return strtotime($date);
    }

}