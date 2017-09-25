<?php

namespace App\Http\ApiControllers\Admin;

use App\Http\ApiControllers\ApiController;
use App\Models\ExperienceBooking;
use Illuminate\Http\Request;


class ExperienceBookingController extends ApiController
{

    public function search( Request $request )
    {
        if (!$request->query('q')) return $this->notFound('not found');

        $data = ExperienceBooking::query()->search($request->query('q'), 20, true)->paginate(20);
       return  $this->success($data);
    }
}
