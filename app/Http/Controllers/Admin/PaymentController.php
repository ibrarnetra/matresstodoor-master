<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\Admin\Payment;
use App\Models\Admin\Order;
use App\Models\Admin\AuthorizeNet;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function orderPayment($encrypted_id)
    {
      
        $order = Order::where('id', Crypt::decryptString($encrypted_id))->first();

        $processing = true;
        if (!$order) {
            $processing = false;
        } else {
            if ($order->payment_link_status == "0") {
                $processing = false;
            }
        }

        if ($processing) {
            $title = "Order Payment";
            $description = "Order Payment";
            $image = asset('storage/config_logos/' . getWebsiteLogo());
            $url = route('payments.index', ['encrypted_id' => $encrypted_id]);
            $order_total = ($order) ? $order->total : 0;

            return view('admin.payment.order_payment', compact('title', 'description', 'image', 'url', 'encrypted_id', 'order_total'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'encrypted_id' => 'required',
            'card_name' => 'required',
            'card_number' => 'required',
            'card_exp_month' => 'required',
            'card_exp_year' => 'required',
            'card_cvv' => 'required',
            'paid_amount' => 'required',
        ]);

        $order = Order::where('id', Crypt::decryptString($request->encrypted_id))->first();
     

        $res = "";
        if ($order) {
            $res = (new AuthorizeNet())->_authorizeAndCapture(
                $request->card_number,
                $request->card_exp_year . "-" . $request->card_exp_month,
                $request->card_cvv,
                $order->first_name,
                $order->last_name,
                '',
                $order->shipping_address_1,
                $order->shipping_country,
                $order->shipping_zone,
                $order->shipping_city,
                $order->shipping_postcode,
                $order->customer_id,
                $order->email,
                $request->paid_amount,
                $order->invoice_no,
                "Walk in Customer",
                getConstant('AUTHORIZE_ENV'),
            );
            /**
             * check if authorize.net transaction was successful
             */
            if ($res['status']) {
                $payment_type = "partial";
                if ($request->paid_amount == $order->total) {
                    $payment_type = "full";
                }
                /**
                 * inserting data in payments table
                 */
                $route_id = null;
                (new Payment())->_insert($order->id, $order->payment_method, $payment_type, "authorize", $request->paid_amount, ($order->total - $request->paid_amount), $route_id);
                /**
                 * updating payment link status to disable to throw forbidden link error
                 */
                Order::where('id', $order->id)->update(['payment_link_status' => "0", 'payment_type' => $payment_type, 'payment_mode' => 'authorize']);
            }
            $success = Order::where('id', $order->id)->update(['payment_method_response' => json_encode($res)]);
        }

        if ($res['status']) {
            return redirect()->route('payments.success');
        } else {
            return redirect()->back()->with('error', 'Sorry, your request can not be processed at the moment, please contact admin for further assistance!');
        }
    }

    public function paymentSuccess()
    {
        $title = "Payment Success";
        $description = "Payment Success";
        $image = asset('storage/config_logos/' . getWebsiteLogo());
        $url = route('payments.success');

        return view('admin.payment.success', compact('title', 'description', 'image', 'url'));
    }
}
