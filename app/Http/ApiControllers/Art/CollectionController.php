<?php
/**
 * |--------------------------------------------------------------------------
 * |收藏
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 26/12/2017
 * Time: 3:00 PM
 */

namespace App\Http\ApiControllers\Art;


use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Art\ArtShowResource;
use App\Models\ArtShow;
use App\Models\ArtShowCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Repositories\ArtShowLikeAndCollectionRepository;

class CollectionController extends ApiController
{


    protected $repository;

    public function __construct(ArtShowLikeAndCollectionRepository $repository) {
        $this->repository=$repository;
    }

    public function index( Request $request )
    {
        $user = app()->environment() == 'local' ? User::query()->find(165) : auth()->user();

        $data=$user->art_show()->paginate(12);

        $links=['current_page'=>$data->currentPage(),'total'=>$data->lastPage()];

        $collections = ArtShowResource::collection($data);
        // 标记为已读，未读数量清零
        return  $this->success(['data'=>$collections,'link'=>$links]);

    }


    /**
     * @param Request $request
     * @param ArtShow $art_show
     * @return mixed 收藏
     */
    function store(Request $request,ArtShow $art_show){

        return $this->success($this->repository->artShowCollection($art_show));
    }

}