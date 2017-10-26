<?php
/**
 * |--------------------------------------------------------------------------
 * |微信小程序自定义时间选择器接口
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 13/10/2017
 * Time: 11:03 AM
 */

namespace Repositories;


use App\Models\ExperienceRoom;
use App\Models\ExperienceSpecialPrice;
use Illuminate\Http\Request;

class MiniDateRepository
{
    protected $year;
    protected $month;
    protected $currentDate;
    protected $days; //获得给定的月份应有的天数
    protected $dayofweek; //得到给定的月份的 1号 是星期几
    protected $today;
    protected  $booking;
    protected  $request;
    public function __construct(ExperienceRoomBookingRepository $booking,Request $request)
    {
        $this->month = date('m');
        $this->year  = date('Y');
        $this->today = date('Y-m-d');
        $this->booking=$booking;
        $this->request=$request;

    }

 private   function initDate($month,$year)
    {

        $this->days        = date("t", mktime(0, 0, 0, $month, 1, $year));//得到给定的月份应有的天数
        $this->dayofweek   = date("w", mktime(0, 0, 0, $month, 1, $year));//得到给定的月份的 1号 是星期几
    }

  private  function _getDate()
    {

        $checkinDisableDate=$this->request->checkin?$this->booking->roomCheckoutDisableApi()->toArray():$this->booking->roomCheckinDisableApi()->toArray();

        //房间特殊价格
       $specialPrice = $this->specialPrice();

       //普通价格
        $commonPrice=$this->commonPrice();


        $week  = $this->dayofweek + 1;
        $third = [];
        for ( $i = 1; $i <= $this->dayofweek; $i++ ) {//输出1号之前的空白日期
            $_tmp[ 'date' ]      = '';
            $_tmp[ 'fullDate' ]  = date("Y-m-d", mktime(0, 0, 0, $this->month, $i, $this->year));
            $_tmp[ 'today' ]     = 0;
            $_tmp['today_price']= 0;
            array_push($third, $_tmp);
        }
        $second = [];
        for ( $i = 1; $i <= $this->days; $i++ ) {//输出天数信息
            $_tmp[ 'date' ]      = $i;

            $_tmp[ 'fullDate' ]  = date("Y-m-d", mktime(0, 0, 0, $this->month, $i, $this->year));

            //小于当天的时间不可用
            $_tmp['avaliable']=strtotime($_tmp['fullDate'])<strtotime(date('Y-m-d'))?0:1;

            //已经下订单的日期不可用
            if(in_array($_tmp['fullDate'],$checkinDisableDate))$_tmp['avaliable']=0;

            //当天价格
            $_tmp['today_price']=isset($specialPrice[$_tmp['fullDate']])?$specialPrice[$_tmp['fullDate']]:$commonPrice;

                //当天时间一个标志
            $_tmp[ 'today' ]     = $_tmp[ 'fullDate' ] == date('Y-m-d') ? 1 : 0;

            array_push($third, $_tmp);
            if ($week % 7 == 0) {//换行处理：7个一行
                array_push($second, $third);
                $third = [];
            }
            $week++;
        }
        array_push($second, $third);
        return  $second;

    }

    /**
     * @return array
     *  获取当月向后三个月
     */
   private function thirdDate()
    {
        $month = $this->month;
        $year  = $this->year;
        $date  = [];
        for ( $i = 1; $i <=3; $i++ ) {
            $date[ $i ][ 'month' ] = $month;
            $date[ $i ][ 'year' ]  = $year;
            $month++;
            if ($month > 12) {//处理出现月份大于12的情况
                $month = 1;
                $year++;
            }
            if ($month < 1) {//处理出现月份小于1的情况
                $month = 12;
                $year--;
            }
        }
        return $date;
    }

    public function getDate(){

       $date=$this->thirdDate();

       $arr=[];
       foreach($date as $key=>$v){
           $this->month =$v['month'];
           $this->year  =$v['year'];
           $this->initDate($this->month,$this->year);
           $_tmp['month']=$this->year.'-'.$this->month;
           $_tmp['day']=$this->_getDate();
           array_push($arr,$_tmp);
       }

       return $arr;

    }


    /**
     * 房间特殊价格
     */
    private function specialPrice(){
      return   ExperienceSpecialPrice::query()->where('experience_room_id',$this->request->room_id)->pluck('price','date');
    }

    /**
     * @return mixed
     * 房间普通价格
     */
    private function commonPrice(){
        return ExperienceRoom::query()->where('id',$this->request->room_id)->value('price');
    }
}