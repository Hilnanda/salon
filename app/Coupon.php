<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $dates = ['start_date_time', 'end_date_time', 'created_at'];

    public function customers(){    
        return $this->hasMany(CouponUser::class, 'coupon_id');
    }
}
