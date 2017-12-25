<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Tearoom
 *
 * @property int $id
 * @property string $name
 * @property int $limits
 * @property int $start_point
 * @property int $end_point
 * @property string $image
 * @property int $sort
 * @property int $type
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $tearoom_bookings
 * @property \Illuminate\Database\Eloquent\Collection $tearoom_prices
 * @property \App\Models\TearoomSchedule $tearoom_schedule
 *
 * @package App\Models
 */
class Tearoom extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'tearoom';
    use LogsActivity;
    /**
     * 时间类型
     */
    const TYPE_SINGLE = 0;  // 单独
    consT TYPE_ALL    = 1;  // 全部

    /**
     * 状态
     *
     * @var integer
     */
    const STATUS_SHOW = 1;    // 显示
    const STATUS_HIDE = 0;    // 隐藏

    protected $casts
        = [
            'limits'      => 'int',
            'start_point' => 'int',
            'end_point'   => 'int',
            'sort'        => 'string',
            'type'        => 'string',
            'status'      => 'string',
        ];

    protected $hidden
        = [
            'deleted_at',
        ];

    protected $fillable
        = [
            'name',
            'limits',
            'start_point',
            'end_point',
            'image',
            'sort',
            'type',
            'status',
        ];

    public function tearoom_bookings()
    {
        return $this->hasMany(\App\Models\TearoomBooking::class);
    }

    public function tearoom_prices()
    {
        return $this->hasMany(\App\Models\TearoomPrice::class);
    }

    public function tearoom_schedule()
    {
        return $this->hasMany(\App\Models\TearoomSchedule::class);
    }

    /**
     * Field type to text
     *
     * @param  integer $status self::TYPE_*
     * @return string
     */
    public static function type2text( $type )
    {
        switch ( $type ) {
            case self::TYPE_SINGLE:
                return '单独';

            case self::TYPE_ALL:
                return '全部';

            default:
                return '未知';
        }
    }

    /**
     * Field Status to text
     *
     * @param  integer $status self::STATUS_*
     * @return string
     */
    public static function status2text( $status )
    {
        switch ( $status ) {
            case self::STATUS_SHOW:
                return '显示';

            case self::STATUS_HIDE:
                return '隐藏';

            default:
                return '未知';
        }
    }

    /**
     * 存储数据
     *
     * @param  array $data ['tearoom' => ..., 'price' => ...]
     * @return boolean
     */
    public static function store( Request $request )
    {
        try {
            \DB::beginTransaction();

            $tearoom = static::query()->create($request->except('prices'));

            if (isset($request->prices)) {
                foreach ( $request->prices as $v ) {
                    if ($v[ 'durations' ] == '' || $v[ 'fee' ] == '')
                        continue;
                    $v[ 'tearoom_id' ] = $tearoom->id;
                    TearoomPrice::query()->create($v);
                }
            }

            \DB::commit();
            return $tearoom;
        }
        catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 修改数据
     *
     * @param  integer $id ID
     * @param  array $data ['tearoom' => ..., 'price' => ...]
     * @return boolean
     */
    public static function modify( Tearoom $tearoom, Request $request )
    {
        if (!$tearoom)
            return false;

        try {
            \DB::beginTransaction();

            $result = $tearoom->update($request->except('prices'));
            if ($prices = $request->prices?? false) {

                $prices = collect($prices)->filter(
                    function( $item ) {
                        if ($item[ 'durations' ] && $item[ 'fee' ]) return true;
                    }
                );

                $tearoom->tearoom_prices()->forceDelete();

                $tearoom->tearoom_prices()->createMany($prices->toArray());

            }

            \DB::commit();
            return $result;
        }
        catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }


}
