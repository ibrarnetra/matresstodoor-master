<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Order;

class OrderBill extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    function _insert($order_id, $order_bills, $amount_type, $payment_id,$edit)
    {
      

        $bills = (isset($order_bills) && !is_null($order_bills)) ? $order_bills : [];
      
        if($edit)
        {
          $orderBill = self::where('order_id', $order_id)->where('amount_type', 'paid')->first();
          if($orderBill)
          {
          self::where('order_id', $order_id)->where('amount_type', 'paid')->delete();
          $payment_id = $orderBill->payment_id;
          }
        
        }

        if (count($bills) > 0) {
            foreach ($bills as $key => $value) {
                $order_bill = new OrderBill();
                $order_bill->order_id = $order_id;
                $order_bill->payment_id = $payment_id;
                $order_bill->bill_type = $key;
                $order_bill->amount_type = $amount_type;
                $order_bill->notes = (isset($value) && !is_null($value)) ? $value : 0;
                $order_bill->save();
            }
        }
       
    }

    function _getOrderBills($order_id)
    {
        $order_bills = self::where('order_id', $order_id)->get();
        $bills = [];
        if (count($order_bills) > 0) {
            foreach ($order_bills as $key => $value) {
                $bills[$value->bill_type] = $value->notes;
            }
        }
        return $bills;
    }
}
