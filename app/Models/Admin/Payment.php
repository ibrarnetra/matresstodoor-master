<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderBills()
    {
        return $this->hasMany(OrderBill::class);
    }

    function _insert($order_id, $method, $type = null, $mode = null, $paid_amount = 0.00, $remaining_amount = 0.00, $route_id=null, $return_total_amount = 0.00)
    {
     
        $payment = self::create([
            'order_id' => $order_id,
            'route_id' => $route_id,
            'method' => $method,
            'type' => $type,
            'mode' => $mode,
            'paid_amount' => $paid_amount,
            'remaining_amount' => $remaining_amount,
            'return_amount' => $return_total_amount
        ]);
     
        return $payment;
    }

    function _update($order_id, $method, $type = null, $mode = null, $paid_amount = 0.00, $remaining_amount = 0.00, $route_id=null)
    {
        $exist_payment =  self::whereNull('route_id')->where('order_id', $order_id)->first();
        if($exist_payment)
        {
            $payment = self::where('id',$exist_payment->id)->update([
                'method' => $method,
                'type' => $type,
                'mode' => $mode,
                'paid_amount' => $paid_amount,
                'remaining_amount' => $remaining_amount,
            ]);
        }
        else{
            $payment = self::create([
                'order_id' => $order_id,
                'route_id' => $route_id,
                'method' => $method,
                'type' => $type,
                'mode' => $mode,
                'paid_amount' => $paid_amount,
                'remaining_amount' => $remaining_amount,
            ]);
         
        }
      
        return $payment;
    }
}
