<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Admin\RouteLocation;
use App\Models\Admin\Payment;
use App\Models\Admin\OrderHistory;
use App\Models\Admin\OrderBill;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\OrderTotal;
use App\Models\Admin\OrderProduct;
use App\Models\Admin\ProductOptionValue;
use App\Mail\NotifyOrderStatusChange;
use App\Http\Controllers\Controller;

class OrderHistoryController extends Controller
{
    public function store(Request $request)
    {

        // return $request;
        $request->validate([
            'order_id' => 'required',
            'order_status_id' => 'required',
        ]);


        $route_id = $request->route_id;
        ### updated order status ###
        $update = ['order_status_id' => $request->order_status_id];
        ### updated delivery date ###
        if (isset($request->delivery_date) && !is_null($request->delivery_date)) {
            $update['delivery_date'] = $request->delivery_date;
        }
        ### updated order ###
        Order::where('id', $request->order_id)->update($update);
        (new RouteLocation())->_updateRouteOrderStatus($route_id,  $request->order_id, $request->order_status_id);

        /**
         * Automated order removal from route
         */
        if ($request->order_status_id == "17") { // $request->order_status_id == "17" == "Postpone"
            $is_removable = (isset($request->is_removable) && !is_null($request->is_removable) && $request->is_removable == "1") ? true : false;
            if ($is_removable) {
                RouteLocation::where('route_id', $request->route_id)->where('order_id', $request->order_id)->delete();
            }
        }
        
        /**
         * get order
         */
        $order = Order::where('id', $request->order_id)->first();

        if ($request->order_status_id == "20" && $order->inventory_status == '1') {
            if (isset($request->order_product_id) && is_array($request->order_product_id)) {
                $order_product_ids = $request->order_product_id;
                $quantities = $request->quantity;
                for ($i = 0; $i < count($request->order_product_id); $i++) {
                    if (isset($quantities[$i]) && ($quantities[$i] == 0 || $quantities[$i] > 0)) {
                        $order_product = OrderProduct::with('order_options')->where('id', $order_product_ids[$i])->first();
                        $order_product->return_ind = 'Yes';
                        $order_product->return_quantity = $quantities[$i];
                        $order_product->save();
                        if ($order_product->order_options->isEmpty()) {
                            $product = Product::where('id', $order_product->product_id)->first();
                            if ($product && $product->subtract == '1') {
                                 $updated_qty = $product->quantity + $quantities[$i];
                                $product->quantity = $updated_qty;
                                $product->save();
                                ### UPDATE STOCK STATUS DEPENDING ON THE UPDATED QTY ###
                                if ($updated_qty == 0 || $updated_qty < $product->minimum) {
                                    Product::where('id', $product->id)->update(['stock_status_id' => 2]);
                                }
                            }
                        } else {
                            foreach ($order_product->order_options as $order_option) {

                                $product = ['quantity' => $quantities[$i], 'product_id' => $order_product->product_id];
                                (new ProductOptionValue())->_updateOptionQuantity($order_option->product_option_value_id, $product);
                            }
                        }
                    }
                }
            }
        }
      





        $is_payment = false;

        if ($request->order_status_id == "16" || $request->order_status_id == "20") {
            $order->delivered_date = date('Y-m-d');

            $order->save();
            list($payment_exists, $remaining_amount) = getRemainingAmountFromPayments($request->order_id);

            $paid_amount = ($payment_exists) ? $remaining_amount : $order->total;
            if (isset($request->payment_method) && !is_null($request->payment_method)  && ($request->payment_method == "COD" || $request->payment_method == "COC" || $request->payment_method == "p-link" || ($request->payment_method == "authorize" &&  $paid_amount > 0))) {

                /**
                 * check to see whether there was a payment entry on `payments` table
                 * if there was a `payment` then the `remaining amount` is the amount `payable`
                 * if there was no `payment` then the `order total` is the amount `payable`
                 */

                if ($request->order_status_id == '20') {
                    $order->discount_amount = "0.00";
                    $order->save();
                    OrderTotal::where('order_id', $order->id)->where('code', 'discount')->update(['value' => '0.00']);
                }




                $payment_received = (isset($request->payment_received) && !is_null($request->payment_received)) ? ($request->payment_received == "true" ? true : false) : true;
                /**
                 * used to make a separate record of payments for accounting 
                 * $order_id = `id`
                 * $request->payment_method = `payment_method` = `Payment on Delivery`, `Payment on Counter`, `Authorize.net`
                 * $request->payment_type = `payment_type` = `full`, `partial`
                 * $request->payment_mode = `payment_mode` = `online transfer`, `cash`, `card`
                 * $paid_amount = `paid_amount`
                 * $remaining_amount = `remaining_amount`
                 */
                if ($paid_amount > 0) {
                    $paid_amount =  $paid_amount - $request->return_total_amount;
                    if (!($request->payment_mode == "cash-card" || $request->payment_mode == "cash-online")) {
                        $payment = (new Payment())->_insert($request->order_id, $order->payment_method, $order->payment_type, $request->payment_mode, $paid_amount, 0.00, $route_id, $request->return_total_amount);
                    } else if ($request->payment_mode == "cash-card" || $request->payment_mode == "cash-online") {
                        $cash_amount = $request->both_cash_amount;
                        $card_amount = $paid_amount - $cash_amount;
                        $total_paid_amount =  $cash_amount + $card_amount;
                        $cash_payment = (new Payment())->_insert($request->order_id, $order->payment_method, $order->payment_type, 'cash', $cash_amount, 0.00, $route_id, $request->return_total_amount);
                        if ($cash_payment) {
                            (new OrderBill())->_insert($request->order_id, $request->bills, 'remaining', $cash_payment->id, $edit = false);
                        }
                        $payment_mode = "card";
                        if ($request->payment_mode == "cash-online") {
                            $payment_mode = "online transfer";
                        }
                        $credit_payment = (new Payment())->_insert($request->order_id, $order->payment_method, $order->payment_type,  $payment_mode, $card_amount, 0.00, $route_id, "0.00");
                        $orderTotal = OrderTotal::where(['order_id' => $request->order_id, 'code' => 'remaining_amount'])->first();
                        if ($orderTotal) {
                            $remaing_amount = $orderTotal->value - $total_paid_amount;
                            $orderTotal->value =  $remaing_amount;
                            $orderTotal->save();
                        }
                        $orderTotalPaid = OrderTotal::where(['order_id' => $request->order_id, 'code' => 'paid_amount'])->first();
                        if ($orderTotalPaid) {
                            $total_amount = $orderTotalPaid->value + $total_paid_amount;
                            $orderTotalPaid->value =   $total_amount;
                            $orderTotalPaid->save();
                        }
                    }
                }
                if ($paid_amount > 0 &&  $request->payment_mode == "cash") {
                    if (isset($payment)) {
                        $payment_id = $payment->id;
                    } else {
                        $payment_id = null;
                    }
                    (new OrderBill())->_insert($request->order_id, $request->bills, 'remaining', $payment_id, $edit = false);
                    $paid_amount = 0;
                    $notes = [
                        "hundred" => 100,
                        "fifty" => 50,
                        "twenty" => 20,
                        "ten" => 10,
                        "five" => 5,
                        "two" => 2,
                        "one" => 1,
                    ];
                    foreach ($request->bills as $key => $value) {
                        $paid_amount += $notes[$key] * $value;
                    }
                    $orderTotal = OrderTotal::where(['order_id' => $request->order_id, 'code' => 'remaining_amount'])->first();
                    if ($orderTotal) {
                        $remaing_amount = $orderTotal->value - $paid_amount;
                        $orderTotal->value =  $remaing_amount;
                        $orderTotal->save();
                    }
                    $orderTotalPaid = OrderTotal::where(['order_id' => $request->order_id, 'code' => 'paid_amount'])->first();
                    if ($orderTotalPaid) {
                        $total_amount = $orderTotalPaid->value + $paid_amount;
                        $orderTotalPaid->value =   $total_amount;
                        $orderTotalPaid->save();
                    }
                }
            }
        }
        
        if($request->order_status_id == "3"  && $order->inventory_status == "1")
        {
            $order_products = OrderProduct::with('order_options')->where('order_id', $order->id)->get();
            foreach($order_products as $order_product)
            {
            if ($order_product->order_options->isEmpty()) {
                $product = Product::where('id', $order_product->product_id)->first();
                if ($product) {
                    $updated_qty = $product->quantity + $order_product->quantity;
                    $product->quantity = $updated_qty;
                    $product->save();
                    ### UPDATE STOCK STATUS DEPENDING ON THE UPDATED QTY ###
                    if ($updated_qty == 0 || $updated_qty < $product->minimum) {
                        Product::where('id', $product->id)->update(['stock_status_id' => 2]);
                    }
                }
            } else {
                foreach ($order_product->order_options as $order_option) {

                    $product = ['quantity' => $order_product->quantity, 'product_id' => $order_product->product_id];
                    (new ProductOptionValue())->_updateOptionQuantity($order_option->product_option_value_id, $product);
                }
            }
        }
             $order->inventory_status = "0";
             $order->save();

        }
     
        if($request->order_status_id == "16"  && $order->inventory_status == "0")
        {
           
            $order_products = OrderProduct::with('order_options')->where('order_id', $order->id)->get();
            foreach ($order->order_products as $order_product) {
                $quantity = $order_product->quantity - $order_product->return_quantity;
                if ($order_product->order_options->isEmpty()) { 
                    (new Product())->_decrementProduct($order_product->product_id, $quantity);
                } else {
                    foreach ($order_product->order_options as $order_option) {
                        (new Product())->_decrementProductOption($order_option->product_option_value_id, $quantity);
                    }
                    
                }
            }
             $order->inventory_status = "1";
            $order->save();

            

        }

        ### CREATE ORDER HISTORY ###
        $inserted = (!$is_payment) ? (new OrderHistory())->_store($request) : true;

        if ($inserted) {
            if (isset($request->notify) && !is_null($request->notify) && $request->notify == '1') {
                Mail::to($order->email)->send(new NotifyOrderStatusChange($order->id, $order->order_status->name, $order->created_at));
            }
            return redirect()->back()->with('success', 'Order updated successfully.');
        }
    }
}
