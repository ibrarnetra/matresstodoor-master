<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderDetail($order_id)
    {
        $title = "Order Detail";
        $meta_title = "Order Detail";
        $meta_description = "Order Detail";
        $meta_keyword = "Order Detail";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.orderDetail', ['id' => $order_id]);

        $order = (new Order())->_getOrderDetail($order_id);

        return view('frontend.order.detail', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'order'));
    }
}
