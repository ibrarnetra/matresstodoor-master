<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\Order;

class OrderHistory extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function generated_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    function _insert($order_id, $order_status, $comment = null, $delivery_date = null)
    {
        if (is_null($comment)) {
            $comment = "";
        }
        $order_history = new OrderHistory();
        $order_history->order_id = $order_id;
        $order_history->order_status_id = $order_status;
        $order_history->notify = 0;
        $order_history->comment = $comment;
        $order_history->delivery_date = $delivery_date;
        $order_history->created_by = (Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : 0;
        $order_history->save();
    }

    function _store($request)
    {
        $order_history = new OrderHistory();
        $order_history->order_id = $request->order_id;
        $order_history->order_status_id = $request->order_status_id;
        $order_history->notify = (isset($request->notify) && !is_null($request->notify)) ? $request->notify : 0;
        $order_history->comment = (isset($request->comment) && !is_null($request->comment)) ? $request->comment : "";
        $order_history->delivery_date = (isset($request->delivery_date) && !is_null($request->delivery_date)) ? $request->delivery_date : null;
        $order_history->created_by = (Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : 0;
        $order_history->save();

        return $order_history->id;
    }
}
