<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 3/1/2018
 * Time: 6:29 PM
 */

namespace App\Http\Controllers\Art;


use App\Http\Controllers\Controller;
use App\Models\ArtShow;
use Illuminate\Http\Request;
use Repositories\ArtShowLikeAndCollectionRepository;

class ArtShowCollectController extends Controller
{

    protected $repository;
    public function __construct(ArtShowLikeAndCollectionRepository $repository) {
      $this->repository=$repository;
    }

    function store(Request $request,ArtShow $art_show){
        return response()->json($this->repository->artShowCollection($art_show));
     }
}