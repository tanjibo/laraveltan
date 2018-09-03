<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TearoomBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price_id'    => "required",
            'date'        => 'required',
            'end_point'   => 'required',
            'start_point' => 'required',
            'tearoom_id'  => "required",
            //'limits'      => 'required',
            'customer'    => 'required|max:10',           //顾客
            //'gender'      => 'required',
            'pay_mode'    => 'required', //支付方式
            'peoples'     => 'required|min:1',//人数
            'mobile'      => 'bail|required|size:11|regex:/^1[34578][0-9]{9}$/', //电话号码

        ];

    }
}
