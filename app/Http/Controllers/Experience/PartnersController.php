<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\Partners;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;

class PartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Partners::query()->get();
        return view("experience.parnters.index", compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('experience.parnters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $token=str_random(40);

       $mix=[
           'n'=>sha1($request->name),
           "s"=>$token,
           "tm"=>md5(time()),
           "te"=>time()
       ];

        $arr = [
            'token'    => $token,
            'mini_url' => "pages/roomList?".build_query($mix),
            'name'=>$request->name
        ];
        return response()->json(Partners::store($arr));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partners $parnters
     * @return \Illuminate\Http\Response
     */
    public function destroy( Partners $experience_partner )
    {
       return response()->json($experience_partner->delete());
    }
}
