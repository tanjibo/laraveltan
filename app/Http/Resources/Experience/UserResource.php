<?php

namespace App\Http\Resources\Experience;

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
        $this->https();
        return [
            'mobile'   => $this->mobile,
            'id'       => $this->id,
            'nickname' => $this->nickname,
            'avatar'   => $this->https(),
            $this->mergeWhen(
                $request->user_id, [
                                     'gender'         => $this->gender,
                                     'email'          => $this->email,
                                     'total_credit'   => $this->total_credit,
                                     'surplus_credit' => $this->surplus_credit,
                                     'level'          => $this->credit2level($this->credit)['name'],
                                     'balance'        => $this->balance,
                                     'notification_count'=>$this->notification_count
                                 ]
            ),
        ];
    }

    private function https()
    {
        preg_match('/^(http[s]?)\:\/\/(.+)/i', $this->avatar, $data);

        if ($data[ 1 ] == 'http')
            return str_replace('http', 'https', $this->avatar);
        else
            return $this->avatar;
    }
}
