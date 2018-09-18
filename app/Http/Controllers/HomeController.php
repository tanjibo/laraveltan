<?php

namespace App\Http\Controllers;


use App\Charts\ExperienceRoomBillFlowChart;
use App\Charts\UserChart;
use App\Models\Api\ExperienceBooking;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $barObj    = new ExperienceRoomBillFlowChart;
        $userObj=new UserChart;
        $userLineChart=$userObj->lineChart();
        $totalUserNum=$userObj->totalUserNum;
        $lineObj=clone $barObj;
        $barChart=$barObj->barChart();
        $lineChart=$lineObj->lineChart();

        return view('home', compact('barChart','lineChart','userLineChart','totalUserNum'));
    }
}
