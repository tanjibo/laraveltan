<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 30/9/2017
 * Time: 10:40 AM
 */

namespace App\Http\Resources\Experience;


trait ExperienceRoomBookingTrait
{

    public function common()
    {
        return [
            'id'           => $this->id,
            'balance'      => $this->balance,
            'pay_mode'     => static::paymode2text($this->pay_mode),
            'customer'     => $this->customer,
            'gender'       => static::gender($this->gender),
            'mobile'       => $this->mobile,
            'people'       => $this->people,
            'requirements' => $this->requirements,
            'remark'       => $this->remark,
            'status'       => static::status2text($this->status),
            'created_at'   => $this->created_at->toDateTimeString(),
            'is_comment'   => $this->is_comment?:0,


        ];

    }

}