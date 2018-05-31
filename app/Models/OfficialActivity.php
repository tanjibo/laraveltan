<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialActivity extends Model
{
    use SoftDeletes;
    protected $table = "official_activity";

    protected $fillable
        = [
            'name',
            'qr_code_url',
            'default_welcome',
            'be_recommend_welcome',
            'start_time',
            'end_time',
            'is_active',
            'auto_reply_welcome',
            'poster_base_img_url'
        ];

    const IS_PREPARE = 0; //准备中
    const IS_ACTIVE  = 1;  //进行中
    const IS_END     = 2;  //结束

    public function setStartTimeAttribute( $value )
    {

        $this->attributes[ 'start_time' ] = date('Y-m-d 00:00:00', strtotime($value));
    }

    public static function activeId()
    {
        return self::activeActivity()->id;
    }

    public static function activeActivity( $id = null )
    {
        $condition = [
            'is_active' => self::IS_ACTIVE,
        ];
        if ($id) {
            $condition[ 'id' ] = $id;
        }
        return self::query()->where($condition)->first();
    }

    public function setEndTimeAttribute( $value )
    {
        $this->attributes[ 'end_time' ] = date('Y-m-d 23:59:59', strtotime($value));
    }


    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * 获取活动分页
     */
    public static function list()
    {
        return self::query()->with('share_settings')->orderByDesc("created_at")->paginate(15);
    }


    /**
     * 添加一个活动
     */
    public static function addActivity()
    {

        $model = self::query()->create(request()->all());
        //生成二维码
        $url = makeActivityMixUrl('officialAccount.home', $model);

        $model->qr_code_url = QrCode($url);

         $model->save();
         return $model;

    }


    public function share_settings()
    {
        return $this->hasMany(OfficialActivityShareSetting::class);
    }


}

