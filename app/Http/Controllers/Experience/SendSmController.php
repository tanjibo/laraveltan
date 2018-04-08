<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\Backend\ExperienceBooking;
use App\Models\Backend\ExperienceRoom;
use App\Models\Sm;
use Illuminate\Http\Request;

/**
 * Class SendSmController
 * @package App\Http\Controllers\Experience
 * 发送短信
 */
class SendSmController extends Controller
{
    public function index()
    {
      userHasAccess(['experience_send_sm_show']);
      
        $model = ExperienceRoom::query()->active()->select('id', 'name')->get();

        return view("experience.send_sm.index", compact("model"));
    }


    public function store( Request $request )
    {

        $this->validate(
            $request, [
                        'checkin'  => 'required|date_format:Y-m-d', //入住时间
                        'checkout' => 'required|date_format:Y-m-d', //退房时间
                        'rooms'    => 'required|array', //房间id 不能为空
                        'mobile'   => 'bail|required|size:11|regex:/^1[34578][0-9]{9}$/', //电话号码
                    ]
        );

        $template = Sm::singleSendSmTemplate();
        return response()->json(Sm::send($request->mobile, $template, $request->smStatus));
    }
}
