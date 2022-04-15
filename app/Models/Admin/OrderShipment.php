<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    private function generateTrackingNumber($order_id, $order_status_id, $shipping_courier_id, $invoice_no)
    {
        $order_shipment = self::where('order_id', $order_id)->first();

        return ($order_shipment) ? $order_shipment->tracking_number : $invoice_no . '-' . $order_status_id . '-' . $shipping_courier_id;
    }

    public function _insert($order_id, $order_status_id, $shipping_courier_id, $invoice_no)
    {
        $order_shipment = new OrderShipment();
        $order_shipment->order_id = $order_id;
        $order_shipment->order_status_id = $order_status_id;
        $order_shipment->shipping_courier_id = $shipping_courier_id;
        $order_shipment->tracking_number = $this->generateTrackingNumber($order_id, $order_status_id, $shipping_courier_id, $invoice_no);
        $order_shipment->save();
    }
}
