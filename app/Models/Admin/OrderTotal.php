<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTotal extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    function _insert($order_id, $code, $title, $value)
    {
        $order_total = new OrderTotal();
        $order_total->order_id = $order_id;
        $order_total->code = $code;
        $order_total->title = $title;
        $order_total->value = $value;
        $order_total->save();
    }
}
