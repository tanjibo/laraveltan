<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

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
class ExperienceRoomCommonSetting extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'experience_room_common_setting';

    protected $fillable
        = [
            'url',
            'system_tip',
            'type',
            'name',
        ];

    protected $hidden
        = [
            'deleted_at',
        ];

    /**
     * @param array $attach_url_id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     */
    public  static function attachUrl( array $attach_url_id )
    {
        return static::query()->whereIn('id', $attach_url_id)->where('type', 'supporting_url')->select('url')->get();

    }

    /**
     * @return mixed
     */
    public static function unsubscribe()
    {
        $un = static::query()->where('type', 'system_tip')->value('system_tip');
        return str_replace([ "\r\n", "\n", "\r" ], '<br/>', $un);
    }


}
