<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtRequest extends FormRequest
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
        switch ($this->method()){
            case "POST":
            case 'PUTCH':
                return [
                    'name'=>'required|min:3,max:30|unique:art_show,id,'.request()->id
                ];
            default:
                return [];
        }
    }
}
