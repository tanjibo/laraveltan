<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        return [
            'mobile'   => $this->mobile,
            'id'       => $this->id,
            'nickname' => $this->nickname,
            'avatar'   => $this->avatar,
            $this->mergeWhen(
                $request->user_id, [
                'gender'         => $this->gender,
                'email'          => $this->email,
                'total_credit'   => $this->total_credit,
                'surplus_credit' => $this->surplus_credit,
            ]
            ),
        ];
    }
}
