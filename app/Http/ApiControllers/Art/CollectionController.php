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
use App\Models\ArtShowCollection;
use App\Models\User;
use Illuminate\Http\Request;

class CollectionController extends ApiController
{

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
     * 收藏喜欢的艺术品
     */
    public function store( Request $request )
    {

        $request[ 'user_id' ] = app()->environment() == 'local' ? 165 : auth()->id();

        $model = ArtShowCollection::toggle($request->all());

        if (is_bool($model)) {
            return $this->success([ 'message' => '取消关注成功' ]);
        }

        return $this->success($model);
    }

}