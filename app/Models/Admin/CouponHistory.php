<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponHistory extends Model
{
    use HasFactory;

    public function _insert($coupon_id, $order_id, $customer_id, $amount)
    {
        $coupon_history = new CouponHistory();
        $coupon_history->coupon_id = $coupon_id;
        $coupon_history->order_id = $order_id;
        $coupon_history->customer_id = $customer_id;
        $coupon_history->amount = $amount;
        $coupon_history->save();
    }
}
