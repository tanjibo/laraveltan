<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\Backend\ExperienceRoomCommonSetting;
use App\Models\MiniCommonSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $question = ExperienceRoomCommonSetting::question();
        $img      = ExperienceRoomCommonSetting::img();
        $tip      = ExperienceRoomCommonSetting::tip();
        $mini     = MiniCommonSettings::experienceMiniSetting()??'[]';

        return view('experience.settings.index', compact('question', 'img', 'tip', 'mini'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        return response()->json(ExperienceRoomCommonSetting::store());
    }

    public function miniSetting( Request $request )
    {

        $this->validate(
            $request, [
            'mini_type'            => 'required',
            'navigation_bar_color' => 'required',
            'banner_url'           => 'required',
            'common_color'         => 'required',
        ]
        );
        return response()->json(MiniCommonSettings::store($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        //
    }
}
