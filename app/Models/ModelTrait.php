<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 6/12/2017
 * Time: 4:30 PM
 */

namespace App\Models;


use Carbon\Carbon;

trait ModelTrait
{
    public function getPriceAttribute( $val )
    {
        return doubleval($val / 100);
    }

    public function getRealPriceAttribute( $val )
    {
        return doubleval($val / 100);
    }

    public function setPriceAttribute( $val )
    {
        $this->attributes[ 'price' ] = intval($val * 100);
    }

    public function setRealPriceAttribute( $val )
    {
        $this->attributes[ 'real_price' ] = intval($val * 100);
    }

    public function getCheckinAttribute( $val )
    {
        return (new Carbon($val))->toDateString();

    }

    public function setCheckinAttribute( $val )
    {
        $this->attributes[ 'checkin' ] = (new Carbon($val))->toDateString();
    }

    public function getCheckoutAttribute( $val )
    {
        return (new Carbon($val))->toDateString();
    }

    public function setCheckoutAttribute( $val )
    {
        $this->attributes[ 'checkout' ] = (new Carbon($val))->toDateString();
    }


    public function setFeeAttribute($value)
    {
        $this->attributes['fee'] = intval($value * 100);
    }


    public function getFeeAttribute($value)
    {
        return doubleval($value / 100);
    }

    public function setRealFeeAttribute($value)
    {
        $this->attributes['real_fee'] = intval($value * 100);
    }


    public function getRealFeeAttribute($value)
    {
        return doubleval($value / 100);
    }

}