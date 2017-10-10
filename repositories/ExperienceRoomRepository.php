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


use App\Http\Resources\Front\ExperienceRoomResource;
use App\Models\ExperienceRoom;

class ExperienceRoomRepository implements RepositoryInterface
{
    function model()
    {
        return ExperienceRoom::class;
    }

    public function all(){

      return   ExperienceRoomResource::collection(ExperienceRoom::all());

    }

    public function find(string $id)
    {
     return new ExperienceRoomResource(ExperienceRoom::query()->find($id));
    }


}