<?php

namespace App\Http\ApiControllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Models\ArtShowSuggestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuggestionController extends ApiController
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['user_id']=auth()->id();
       $model= ArtShowSuggestion::query()->create($request->all());

       if($model){
        return    $this->success(['message'=>'感谢您的建议']);
       }else{
          return $this->error(['error']);
       }

    }


}
