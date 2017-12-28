<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */
namespace App\Models\Backend;

use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ExperienceRoomCommonSetting
 *
 * @property int $id
 * @property string $url
 * @property string $system_tip
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $name
 *
 * @package App\Models
 */
class ExperienceRoomCommonSetting extends \App\Models\ExperienceRoomCommonSetting
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use LogsActivity;


    const QUESTION = 'question_tip';  //问题
    const TIP      = 'system_tip';    //退款提示
    const IMG      = 'supporting_url'; //图片


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * 全部问题
     */
    static public function question()
    {
        return static::query()->where('type', static::QUESTION)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * 提示
     */
    static public function tip()
    {
        return static::query()->where('type', static::TIP)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * 图片
     */
    static public function img()
    {
        return static::query()->where('type', static::IMG)->get();
    }


    static public function store()
    {
        //图片
        if (request()->type == static::IMG) {

           static::query()->where('type', static::IMG)->delete();

            collect(request()->input('data'))->map(
                function( $item ) {
                    if($item['id']){
                        static::query()->where('id',$item['id'])->restore();
                    }else{
                        static::create($item);
                    }
                }
            );
            return true;
        }

        $model = static::query()->where('type', request()->type)->first();
        if ($model) {
            $model->fill(request()->all())->save();
        }
        else {
            $model = ExperienceRoomCommonSetting::query()->create($_POST);
        }
        return $model;
    }
}
