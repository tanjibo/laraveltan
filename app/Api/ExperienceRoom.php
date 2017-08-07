<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;

class ExperienceRoom extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * 类型
     */
    const TYPE_SINGLE = 1;  // 独立
    const TYPE_ALL    = 2;  // 包场
    const TYPE_SHAN    = 3;  // 山云荟
    const TYPE_XIN    = 4;  //星月阁

    const ROOM_ID_TO_XINGYUEGE=10; //星月阁
    const ROOM_ID_TO_SHANYUNHUI=9; //山云荟

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'experience_room';

    /**
     * Hidden fields
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'intro',
        'type',
        'cover',
        'attach',
        'sort',
        'attach_url'
    ];



    /**
     * Set attribute with price
     *
     * @param  string  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = intval($value * 100);
    }



    /**
     * Get attribute with price
     *
     * @param  string  $value
     * @return void
     */
    public function getPriceAttribute($value)
    {
        return doubleval($value / 100);
    }

    /**
     * Field type to text
     *
     * @param  integer  $status  self::TYPE_*
     * @return string
     */
    public static function type2text($type)
    {
        switch ($type)
        {
            case self::TYPE_SINGLE:
                return '独立';

            case self::TYPE_ALL:
                return '包场';

            default:
                return '未知';
        }
    }

}

