<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\Zone;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\Page;
use App\Models\Admin\Order;
use App\Models\Admin\Country;
use App\Models\Admin\Cart;
use App\Mail\OrderReceived;
use App\Mail\OrderPlaced;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function cart()
    {
        $cmsData = (new Page('en'))->getCmsPage('cart-view');

        $title = ($cmsData) ? $cmsData->title : 'Cart View';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Cart View';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Cart View";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Cart View";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.cart');

        $cart = session()->get('cart');
        $order_total = 0.00;
        if (isset($cart)) {
            foreach ($cart as $key => $item) {
                $order_total += ($item['price'] * $item['quantity']);
            }
        }
        return view('frontend.cart.cart', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cart', 'order_total'));
    }

    public function miniCart()
    {
        $cart = session()->get('cart');
        $total_items = isset($cart) ? count($cart) : 0;
        $view = view('frontend.cart.mini_cart', compact('cart', 'total_items'))->render();
        return json_encode(['status' => true, 'data' => $view, 'count' => $total_items]);
    }

    public function addToCart(Request $request, $slug)
    {
        // return $request;
        $res = (new Cart())->_addToCart($request, $slug);
        $cart = session()->get('cart');
        $total_items = isset($cart) ? count($cart) : 0;
        $view = view('frontend.cart.mini_cart', compact('cart', 'total_items'))->render();
        return json_encode(['status' => true, 'data' => $view, 'count' => $total_items, 'product' => $res]);
    }

    public function update(Request $request, $slug)
    {
        return (new Cart())->_updateCart($request, $slug);
    }

    public function remove($slug)
    {
        (new Cart())->_remove($slug);
        $cart = session()->get('cart');
        $order_total = 0.00;
        foreach ($cart as $key => $item) {
            $order_total += ($item['price'] * $item['quantity']);
        }
        $total_items = isset($cart) ? count($cart) : 0;

        $view = view('frontend.cart.mini_cart', compact('cart', 'total_items'))->render();

        return json_encode(['status' => true, 'data' => $view, 'count' => $total_items, 'order_total' => $order_total]);
    }

    public function checkoutView()
    {
        $cmsData = (new Page('en'))->getCmsPage('checkout');

        $title = ($cmsData) ? $cmsData->title : 'Checkout';
        $meta_title = ($cmsData) ? $cmsData->meta_title : 'Checkout';
        $meta_description = ($cmsData) ? $cmsData->meta_description : "Checkout";
        $meta_keyword = ($cmsData) ? $cmsData->meta_keyword : "Checkout";
        $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
        $meta_url = route('frontend.checkoutView');

        $cart = session()->get('cart');
        $order_total = 0.00;
        if (isset($cart)) {
            foreach ($cart as $key => $item) {
                $order_total += ($item['price'] * $item['quantity']);
            }
        }
        $total_items = isset($cart) ? count($cart) : 0;

        $payment_methods = PaymentMethod::with([
            'eng_description' => function ($q) {
                $q->select('payment_method_id', 'language_id', 'name');
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))
            ->get();
        $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        return view('frontend.cart.checkout', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'cart', 'total_items', 'payment_methods', 'countries', 'zones', 'order_total'));
    }

    public function checkout(Request $request)
    {
        // return $request;
        $request->validate([
            'account_type' => 'required',
            "shipping_method_id" => "required",
            "payment_method_id" => "required",
            ### REQUIRED IF account_type == 'auth' && billing_address_selection == 'existing' ###
            "auth_billing_shipping_address" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:billing_address_selection,new|required",
            ### REQUIRED IF account_type == 'auth' && billing_address_selection == 'new' ###
            "auth_billing_first_name" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_billing_last_name" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_billing_telephone" => "nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/",
            "auth_billing_address_1" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_billing_city" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_billing_postcode" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_billing_country_id" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_billing_zone_id" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            ### REQUIRED IF account_type == 'auth' && delivery_address_selection == 'existing' ###
            "auth_delivery_shipping_address" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,new|required",
            ### REQUIRED IF account_type == 'auth' && delivery_address_selection == 'new' ###
            "auth_delivery_first_name" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_delivery_last_name" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_delivery_telephone" => "nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/",
            "auth_delivery_address_1" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_delivery_city" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_delivery_postcode" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_delivery_country_id" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            "auth_delivery_zone_id" => "exclude_if:account_type,register|exclude_if:account_type,guest|exclude_if:delivery_address_selection,existing|required",
            ### REQUIRED IF account_type == 'register' || account_type == 'guest' ###
            'first_name' => 'exclude_if:account_type,auth|required',
            'last_name' => 'exclude_if:account_type,auth|required',
            'email' => 'exclude_if:account_type,auth|required|email|unique:customers,is_deleted,' . getConstant('IS_NOT_DELETED'),
            'telephone' => 'exclude_if:account_type,auth|required|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',

            'address_1' => 'exclude_if:account_type,auth|required',
            'city' => 'exclude_if:account_type,auth|required',
            'postcode' => 'exclude_if:account_type,auth|required',
            'country_id' => 'exclude_if:account_type,auth|required',
            'zone_id' => 'exclude_if:account_type,auth|required',

            "delivery_first_name" => "exclude_if:account_type,auth|required",
            "delivery_last_name" => "exclude_if:account_type,auth|required",
            "delivery_telephone" => "nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/",
            "delivery_address_1" => "exclude_if:account_type,auth|required",
            "delivery_city" => "exclude_if:account_type,auth|required",
            "delivery_postcode" => "exclude_if:account_type,auth|required",
            "delivery_country_id" => "exclude_if:account_type,auth|required",
            "delivery_zone_id" => "exclude_if:account_type,auth|required",
            ### REQUIRED IF account_type == 'register' ###
            'password' => 'exclude_if:account_type,auth|exclude_if:account_type,guest|required|min:8|confirmed',
            ### REQUIRED IF payment_method == 'authorize' ###
            'card_number' => 'required_if:payment_method_id,3',
            'card_exp_month' => 'required_if:payment_method_id,3',
            'card_exp_year' => 'required_if:payment_method_id,3',
            'card_cvv' => 'required_if:payment_method_id,3',
        ]);

        $cart = session()->get('cart');
        // return $cart;
          
        ### CHECK IF CART EXISTS AND IS NOT EMPTY ###
        if (isset($cart) && count($cart) > 0) {
          
            $res = (new Order())->_checkout($request, $cart);
           
            // return $res;
            ### IF CART AND GIVEN REQUEST WAS VALID ###
            if (!$res['status']) {
                return redirect()->back()->with("error", $res['data']);
            } else {
                $title = 'Checkout Success';
                $meta_title = 'Checkout Success';
                $meta_description = "Checkout Success";
                $meta_keyword = "Checkout Success";
                $meta_image = asset('storage/config_logos/' . getWebsiteLogo());
                $meta_url = route('frontend.checkoutView');

                $msg = $res['data'];
                $invoice_no = $res['invoice_no'];
              
                $order = (new Order())->_getOrderDetail($res['order_id']);
                ### SEND EMAIL TO CUSTOMER ###
                Mail::to($res['user']->email)->send(new OrderPlaced($order));
                ### SEND EMAIL TO ADMIN ###
                $store_admin_email = ($order->store) ? $order->store->email : "info@mattresstodoor.ca";
                Mail::to($store_admin_email)->send(new OrderReceived($order));

                return view('frontend.cart.success', compact('title', 'meta_title', 'meta_description', 'meta_keyword', 'meta_image', 'meta_url', 'msg', 'invoice_no'));
            }
        } else {
            return redirect()->back()->with("error", "Cart has no items, please add item to cart to place and order.");
        }
    }

    public function clearCart()
    {
        Session::forget('cart');
        return redirect()->back()->with("success", "Successfully cleared cart.");
    }
}
