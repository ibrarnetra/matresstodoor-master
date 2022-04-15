<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\VoucherHistory;
use App\Models\Admin\User;
use App\Models\Admin\TaxClass;
use App\Models\Admin\Store;
use App\Models\Admin\Setting;
use App\Models\Admin\RouteLocation;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\ProductOption;
use App\Models\Admin\Product;
use App\Models\Admin\PaymentMethodDescription;
use App\Models\Admin\Payment;
use App\Models\Admin\OrderTotal;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\OrderShipment;
use App\Models\Admin\OrderProduct;
use App\Models\Admin\OrderOption;
use App\Models\Admin\OrderManagementComment;
use App\Models\Admin\OrderHistory;
use App\Models\Admin\OrderBill;
use App\Models\Admin\Option;
use App\Models\Admin\CustomerGroup;
use App\Models\Admin\Customer;
use App\Models\Admin\Currency;
use App\Models\Admin\CouponHistory;
use App\Models\Admin\Cart;
use App\Models\Admin\AuthorizeNet;
use App\Models\Admin\Address;
use App\Mail\OrderReceived;
use App\Mail\OrderPlaced;

class Order extends Model
{
    use HasFactory;

    private function generateInvoiceNo()
    {
        $invoice_prefix = (new Setting())->_getStoreSetting('config_invoice_prefix');
        $id = self::max('id');
        return $invoice_prefix . ($id + 1);
    }

    protected function getTotals($cart, $shipping_cost)
    {
        $sub_total = 0;
        foreach ($cart as $item) {
            $product = (new Product())->getProduct($item->id);
            $discount = 0;
            if (isset($product->discount) && !is_null($product->discount)) {
                $discount = $product->discount->price;
            }
            $sub_total += $item->quantity * ($product->price - $discount);
        }
        $grand_total = $sub_total + $shipping_cost;
        return [$sub_total, $grand_total];
    }

    protected function sanitizeOptions($options)
    {
        $arr = [];
        if (count((array)$options) > 0) {
            foreach ($options as $key => $val) {
                if ($val == 0) {
                    unset($options[$key]);
                }
            }
            $arr = $options;
        }
        return $arr;
    }

    protected function callAuthorizeNet($card_number, $card_exp, $card_cvv, $first_name, $last_name, $shipping_address_1, $shipping_address_2, $shipping_country, $shipping_zone, $shipping_city, $shipping_postcode, $customer_id, $customer_email, $grand_total, $invoice_no, $transaction_type, $authorize_net_type)
    {

        return (new AuthorizeNet())->_authorizeAndCapture(
            $card_number,
            $card_exp,
            $card_cvv,
            $first_name,
            $last_name,
            $shipping_address_1,
            $shipping_address_2,
            $shipping_country,
            $shipping_zone,
            $shipping_city,
            $shipping_postcode,
            $customer_id,
            $customer_email,
            $grand_total,
            $invoice_no,
            $transaction_type,
            $authorize_net_type
        );
    }

    protected function getDefaultDispatchManager()
    {
        $dispatch_manger = User::role('Dispatch Manager')
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->first();
        return ($dispatch_manger) ? $dispatch_manger->id : 0;
    }

    /**
     * $calculated_price = product price
     * $option_arr = product options selected 
     * $product_option_value_id = `id` in `product_option_values`
     * $product_option_id = `product_option_id` in `product_option_values`
     * $product_id = `product_id` in `product_option_values`
     */
    protected function calculatePriceAndGetOptions($calculated_price, $option_arr, $product_option_value_id, $product_option_id, $product_id)
    {
        $product_option = ProductOption::where('id', $product_option_id)->first(); // get option_id using product_option_id = $key
        if (!$product_option) {
            $product_option = ProductOption::where('product_id', $product_id)->first();
        }
        $option_name = (new Option())->getOptionsData($product_option_value_id, $product_option_id, $product_id, $product_option->option_id);
        ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
        if ($option_name) {
            foreach ($option_name->product_option_values as $option_val) {
                if ($option_val->price_prefix == "+") {
                    $calculated_price += $option_val->price;
                } else {
                    $calculated_price -= $option_val->price;
                }
            }
            $option_arr[] = $option_name;
        }
        return [$calculated_price, $option_arr];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function descriptions()
    {
        return $this->hasMany(PaymentMethodDescription::class);
    }

    public function eng_description()
    {
        return $this->hasOne(PaymentMethodDescription::class)->where('language_id', '1');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order_histories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function order_options()
    {
        return $this->hasMany(OrderOption::class);
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function order_shipments()
    {
        return $this->hasMany(OrderShipment::class);
    }



    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function order_totals()
    {
        return $this->hasMany(OrderTotal::class);
    }

    public function order_management_comments()
    {
        return $this->hasMany(OrderManagementComment::class);
    }

    public function order_bills()
    {
        return $this->hasMany(OrderBill::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function route_locations()
    {
        return $this->hasMany(RouteLocation::class);
    }

    public function created_by_user()
    {
        return $this->belongTo(User::class);
    }

    public function assigned_to_user()
    {
        return $this->belongTo(User::class);
    }

    function _getOrderDetail($id)
    {
        return self::with([
            'customer_group.eng_description' => function ($q) {
                $q->select('customer_group_id', 'name');
            },
            'order_products' => function ($q) use ($id) {
                $q->where('order_id', $id);
            },
            'order_products.product.discount' => function ($q) {
            },
            'order_products.order_options' => function ($q) use ($id) {
                $q->where('order_id', $id);
            },
            'order_options.product_option_value' => function ($q) {
            },
            'order_totals' => function ($q) {
                $q->select('id', 'order_id', 'code', 'title', 'value');
            },
            'currency' => function ($q) {
                $q->select('id', 'title', 'symbol_left', 'symbol_right');
            },
            'order_histories' => function ($q) {
                $q->select('id', 'order_id', 'order_status_id', 'notify', 'comment', 'delivery_date', 'created_by', 'created_at')->with([
                    'generated_by' => function ($q) {
                        $q->select('id', 'first_name', 'last_name')->with([
                            'roles'
                        ]);
                    }
                ]);
            },
            'order_histories.order_status' => function ($q) {
                $q->select('id', 'name');
            },
            'order_management_comments',
            'order_management_comments.dispatcher',
            'order_management_comments.assignee',
            'route_locations.route',
            'order_bills' => function ($q) {
                $q->select('id', 'order_id', 'bill_type', 'notes')->orderBy('id', 'ASC');
            },
            'store' => function ($q) {
                $q->select('id', 'email');
            },
            'payments.orderBills'
        ])->where('id', $id)->first();
    }

    function _store($request)
    {


        ### INSERT ###
        $order = new Order();
        $order->invoice_no = $this->generateInvoiceNo();

        ### Store ###
        $store = Store::find($request->store_id);

        $order->store_id = $request->store_id;
        $order->store_name = (isset($store) && !is_null($store)) ? $store->name : null;
        $order->store_url = null;

        $order->customer_id = $request->customer_id;
        $order->customer_group_id = (isset($request->customer_group_id) && !is_null($request->customer_group_id)) ? $request->customer_group_id : (new CustomerGroup())->_getDefaultGroupId();

        $customer = Customer::where('id', $request->customer_id)->first();
        $order->first_name = ($customer) ? $customer->first_name : '';
        $order->last_name = ($customer) ? $customer->last_name : '';
        $order->email = ($customer) ? $customer->email : '';
        $order->telephone = ($customer) ? $customer->telephone : '';

        $order->payment_country_id = $request->shipping_country_id;
        $order->payment_zone_id = $request->shipping_zone_id;
        $order->payment_first_name = $request->shipping_first_name;
        $order->payment_last_name = $request->shipping_last_name;
        $order->payment_company = (isset($request->shipping_company) && !is_null($request->shipping_company)) ? $request->shipping_company : null;
        $order->payment_address_1 = $request->shipping_address_1;
        $order->payment_lat = (isset($request->shipping_lat) && !is_null($request->shipping_lat)) ? $request->shipping_lat : "0.0000";
        $order->payment_lng = (isset($request->shipping_lng) && !is_null($request->shipping_lng)) ? $request->shipping_lng : "0.0000";
        $order->payment_address_2 = (isset($request->shipping_address_2) && !is_null($request->shipping_address_2)) ? $request->shipping_address_2 : null;
        $order->payment_city = $request->shipping_city;
        $order->payment_postcode = (isset($request->shipping_postcode) && !is_null($request->shipping_postcode)) ? $request->shipping_postcode : null;
        $order->payment_country = $request->shipping_country;
        $order->payment_zone = $request->shipping_zone;

        $order->shipping_country_id = $request->shipping_country_id;
        $order->shipping_zone_id = $request->shipping_zone_id;
        $order->shipping_first_name = $request->shipping_first_name;
        $order->shipping_last_name = $request->shipping_last_name;
        $order->shipping_telephone = (isset($request->shipping_telephone) && !is_null($request->shipping_telephone)) ? $request->shipping_telephone : null;
        $order->shipping_company = (isset($request->shipping_company) && !is_null($request->shipping_company)) ? $request->shipping_company : null;
        $order->shipping_address_1 = $request->shipping_address_1;
        $order->shipping_lat = (isset($request->shipping_lat) && !is_null($request->shipping_lat)) ? $request->shipping_lat : "0.0000";
        $order->shipping_lng = (isset($request->shipping_lng) && !is_null($request->shipping_lng)) ? $request->shipping_lng : "0.0000";
        $order->shipping_address_2 = (isset($request->shipping_address_2) && !is_null($request->shipping_address_2)) ? $request->shipping_address_2 : null;
        $order->shipping_city = $request->shipping_city;
        $order->shipping_postcode =  (isset($request->shipping_postcode) && !is_null($request->shipping_postcode)) ? $request->shipping_postcode : null;
        $order->shipping_country = $request->shipping_country;
        $order->shipping_zone = $request->shipping_zone;

        $order->shipping_method_id = (isset($request->shipping_method_id) && !is_null($request->shipping_method_id)) ? $request->shipping_method_id : 0;
        $order->shipping_method = (isset($request->shipping_method) && !is_null($request->shipping_method)) ? $request->shipping_method : null;
        $order->shipping_method_code = (isset($request->shipping_method_code) && !is_null($request->shipping_method_code)) ? $request->shipping_method_code : null;

        $order->payment_method_id = (isset($request->payment_method_id) && !is_null($request->payment_method_id)) ? $request->payment_method_id : 0;
        $order->payment_method = (isset($request->payment_method) && !is_null($request->payment_method)) ? $request->payment_method : null;
        $order->payment_method_code = (isset($request->payment_method_code) && !is_null($request->payment_method_code)) ? $request->payment_method_code : null;
        $order->payment_method_response = (isset($request->payment_method_response) && !is_null($request->payment_method_response)) ? $request->payment_method_response : json_encode([]);

        $order->order_status_id = '11';
        $order->custom_order = (isset($request->custom_order) && !is_null($request->custom_order)) ? $request->custom_order : "No";

        $order->affiliate_id = (isset($request->affiliate_id) && !is_null($request->affiliate_id)) ? $request->affiliate_id : "0";
        $order->marketing_id = (isset($request->marketing_id) && !is_null($request->marketing_id)) ? $request->marketing_id : "0";
        $order->language_id = (isset($request->language_id) && !is_null($request->language_id)) ? $request->language_id : "1";

        $order->currency_id = (isset($request->currency_id) && !is_null($request->currency_id)) ? $request->currency_id : 0;
        $order->currency_code = (isset($request->currency_code) && !is_null($request->currency_code)) ? $request->currency_code : null;
        $order->currency_value = (isset($request->currency_value) && !is_null($request->currency_value)) ? $request->currency_value : 0.00;

        $order->comment = (isset($request->comment) && !is_null($request->comment)) ? $request->comment : null;
        $order->commission = (isset($request->commission) && !is_null($request->commission)) ? $request->commission : "0";
        $order->tracking = (isset($request->tracking) && !is_null($request->tracking)) ? $request->tracking : null;
        $order->apply_tax = (isset($request->apply_tax) && !is_null($request->apply_tax)) ? $request->apply_tax : "N";

        ### CHECK FOR EXTRA CHARGES ###
        $extra_charge_amount = (isset($request->extra_charge_amount) && !is_null($request->extra_charge_amount)) ? $request->extra_charge_amount : 0;
        $customer_notes = (isset($request->customer_notes) && !is_null($request->customer_notes)) ? ($request->customer_notes) : '';
        $discount_amount = (isset($request->discount_amount) && !is_null($request->discount_amount)) ? $request->discount_amount : 0.00;
        $grand_total = $request->grand_total;

        ### CHECK FOR PAYMENT METHOD `COC` ###
        $paid_amount = 0.00;
        $remaining_amount = 0.00;
        $payment_type = (isset($request->payment_type) && !is_null($request->payment_type)) ? ($request->payment_type) : null;
        $payment_mode = (isset($request->payment_mode) && !is_null($request->payment_mode)) ? ($request->payment_mode) : null;
        if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'partial') {
            $paid_amount = (isset($request->paid_amount) && !is_null($request->paid_amount)) ? floatval($request->paid_amount) : 0.00;
            $remaining_amount = floatval($grand_total - $paid_amount);
        } else if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'full') {
            if ($request->payment_method_code == 'COD') {
                $paid_amount = "0.00";
            } else {
                $paid_amount = $grand_total;
            }
            $remaining_amount = "0.00";
        }


        $order->discount_amount = $discount_amount;
        $order->payment_type = $payment_type;
        $order->payment_mode = $payment_mode;
        $order->paid_amount = $paid_amount;
        $order->remaining_amount = $remaining_amount;
        $order->extra_charge_amount = $extra_charge_amount;
        $order->customer_notes = $customer_notes;
        $order->total = $grand_total;
        $order->created_by = Auth::guard('web')->user()->id;
        $order->assigned_to = $this->getDefaultDispatchManager();

        ### ADD DELIVERY DATE ###
        $delivery_date = (isset($request->delivery_date) && !is_null($request->delivery_date)) ? ($request->delivery_date) : null;
        $order->delivery_date = $delivery_date;
        date_default_timezone_set("Asia/Calcutta");
        $order->created_india_time = date("Y-m-d H:i:s");
        date_default_timezone_set("Canada/Saskatchewan");

        $order->save();


        $order_id = $order->id;
        $invoice_no = $order->invoice_no;
        $order_status_id = $order->order_status_id;
        /**
         * generating payment link if payment_method_code == "p-link"
         */
        if ($request->payment_method_code == 'p-link') {
            Order::where('id', $order_id)->update(['payment_link' => route('payments.index', ['encrypted_id' => Crypt::encryptString($order_id)])]);
        }
        ### UPDATE ORDER STATUS IF PAYMENT METHOD = `authorize` ###

        if ($request->payment_method_code == 'authorize') {
            $res = $this->callAuthorizeNet(
                $request->card_number,
                $request->card_exp_year . "-" . $request->card_exp_month,
                $request->card_cvv,
                $customer->first_name,
                $customer->last_name,
                $request->shipping_address_1,
                $request->shipping_address_1,
                $request->shipping_country,
                $request->shipping_zone,
                $request->shipping_city,
                $request->shipping_postcode,
                $request->customer_id,
                $customer->email,
                $grand_total,
                $invoice_no,
                "Walk in Customer",
                getConstant('AUTHORIZE_ENV')
            );



            $order_status_id = ($res['status'] == true) ? '11' : '5';
            Order::where('id', $order_id)->update(['order_status_id' => $order_status_id, 'payment_method_response' => json_encode($res)]);
            if ($res['status']) {
                (new Payment())->_insert($order_id, $request->payment_method, "full", "authorize", $grand_total, $remaining_amount, $route_id = null);
            } else {
                (new Payment())->_insert($order_id, $request->payment_method, "full", "authorize",  $remaining_amount, $grand_total, $route_id = null);
            }
        }

        ### INSERT IN ORDER PRODUCTS ###
        (new OrderProduct())->_insert($request->product, $order_id);
        ### ORDER TOTAL LOGIC ###
        (new OrderTotal())->_insert($order_id, 'extra_charges', 'Additional-Charges', $extra_charge_amount);
        (new OrderTotal())->_insert($order_id, 'shipping', $request->shipping_method, $request->shipping_method_cost);
        (new OrderTotal())->_insert($order_id, 'discount', 'Flat Discount', $discount_amount);
        (new OrderTotal())->_insert($order_id, 'sub_total', 'Sub-Total', $request->sub_total);
        if (isset($request->tax_class) && !is_null($request->tax_class)) {
            (new OrderTotal())->_insert($order_id, 'tax', 'Tax (' . $request->tax_class . ')', $request->tax_amount);
        }
        (new OrderTotal())->_insert($order_id, 'payment_method', $request->payment_method, '0.00');
        if ($request->payment_method_code == "COC") {
            $payment_type_method = ($payment_type == 'full') ? 'Full Payment' : 'Partial Payment';
            $payment_mode = (isset($request->payment_mode) && !is_null($request->payment_mode)) ? ucwords($request->payment_mode) : "N/A";
            (new OrderTotal())->_insert($order_id, 'payment_type', $payment_type_method, '0.00');
            (new OrderTotal())->_insert($order_id, 'payment_mode', $payment_mode, '0.00');
            if ($payment_type_method == 'Partial Payment') {
                (new OrderTotal())->_insert($order_id, 'paid_amount', 'Paid-Amount', $paid_amount);
                (new OrderTotal())->_insert($order_id, 'remaining_amount', 'Remaining-Amount', $remaining_amount);
            }
        }
        if (!is_null($request->coupon_cost)) {
            (new OrderTotal())->_insert($order_id, 'coupon', $request->coupon_name, $request->coupon_cost);
        }
        if (!is_null($request->voucher_cost)) {
            (new OrderTotal())->_insert($order_id, 'voucher', $request->voucher_name, $request->voucher_cost);
        }
        (new OrderTotal())->_insert($order_id, 'grand_total', 'Grand-Total', $grand_total);

        ### INSERT ORDER HISTORY ###
        (new OrderHistory())->_insert($order_id, $order_status_id, $request->comment, $delivery_date);
        ### INSERT COUPON HISTORY ###
        if (!is_null($request->coupon_id)) {
            (new CouponHistory())->_insert($request->coupon_id, $order_id, $request->customer_id, $request->coupon_cost);
        }
        ### INSERT VOUCHER HISTORY ###
        if (!is_null($request->voucher_id)) {
            (new VoucherHistory())->_insert($request->voucher_id, $order_id, $request->customer_id, $request->voucher_cost);
        }
        /**
         * used to make a separate record of payments for accounting 
         * $order_id = `id`
         * $request->payment_method = `payment_method` = `Payment on Delivery`, `Payment on Counter`, `Authorize.net`
         * $request->payment_type = `payment_type` = `full`, `partial`
         * $request->payment_mode = `payment_mode` = `online transfer`, `cash`, `card`
         * $paid_amount = `paid_amount`
         * $remaining_amount = `remaining_amount`
         */

        if ($request->payment_method_code != "p-link" && $request->payment_method_code != 'authorize' && $request->payment_method_code != 'COD') {
            $route_id = null;
            $payment = (new Payment())->_insert($order_id, $request->payment_method, $request->payment_type, $request->payment_mode, $paid_amount, $remaining_amount, $route_id);
        }
        ### INSERT ORDER BILLS IF payment_mode == 'cash' ###
        if ($payment_mode == "Cash") {
            if (isset($payment)) {
                $payment_id = $payment->id;
            } else {
                $payment_id = null;
            }
            (new OrderBill())->_insert($order_id, $request->bills, 'paid', $payment_id, $edit = false);
        }



        if ($request->payment_method_code == 'COD'  || $request->payment_method_code == "p-link") {
            (new Payment())->_insert($order_id, $request->payment_method, $request->payment_type, $request->payment_mode, "0.00", $grand_total, $route_id = null);
        }

        ### CLEAR CART ###
        Cart::where('customer_id', $request->customer_id)->delete();

        ### SEND EMAIL ###
        $can_send_email = true;
        if ($request->payment_method == "Payment on Delivery") {
            $can_send_email = false;
        }

        if ($request->payment_method == "Payment on Counter") {
            if ($request->payment_type == "partial" && $request->payment_mode == "cash") {
                $can_send_email = false;
            }
        }

        if (!isset($request->apply_tax) || is_null($request->apply_tax) || $request->apply_tax == "N") {
            $can_send_email = false;
        }

        $order = $this->_getOrderDetail($order_id);


        ### SEND EMAIL TO CUSTOMER ###
        if ($can_send_email) {
            Mail::to($customer->email)->send(new OrderPlaced($order));
        }

        ### SEND EMAIL TO ADMIN ###
        $store_admin_email = ($order->store) ? $order->store->email : "info@mattresstodoor.ca";
        Mail::to($store_admin_email)->send(new OrderReceived($order));

        return $order_id;
    }

    function _update($request, $id)
    {

        ### HANDLE STORE ###
        $store = Store::find($request->store_id);


        ### CHECK FOR EXTRA CHARGES ###
        $extra_charge_amount = (isset($request->extra_charge_amount) && !is_null($request->extra_charge_amount)) ? $request->extra_charge_amount : 0;
        $customer_notes = (isset($request->customer_notes) && !is_null($request->customer_notes)) ? ($request->customer_notes) : '';
        $discount_amount = (isset($request->discount_amount) && !is_null($request->discount_amount)) ? $request->discount_amount : 0.00;
        $grand_total = $request->grand_total;

        $customer = Customer::where('id', $request->customer_id)->first();
        ### CHECK FOR PAYMENT METHOD `COC` ###
        $paid_amount = 0.00;
        $remaining_amount = 0.00;
        $payment_type =  (isset($request->payment_type) && !is_null($request->payment_type)) ? ($request->payment_type) : null;
        $payment_mode =  (isset($request->payment_mode) && !is_null($request->payment_mode)) ? ($request->payment_mode) : null;

        if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'partial') {
            $paid_amount = (isset($request->paid_amount) && !is_null($request->paid_amount)) ? floatval($request->paid_amount) : 0.00;
            $remaining_amount = floatval($grand_total - $paid_amount);
        } else if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'full') {
            if ($request->payment_method_code == 'COD') {
                $paid_amount = "0.00";
            } else {
                $paid_amount = $grand_total;
            }
            $remaining_amount = "0.00";
        }

        ### ADD DELIVERY DATE ###
        $delivery_date = (isset($request->delivery_date) && !is_null($request->delivery_date)) ? ($request->delivery_date) : null;
        $old_order = Order::find($id);
        $old_order_products = OrderProduct::where('order_id', $id)->get();
        $old_order_product_ids = OrderProduct::where('order_id', $id)->select('product_id')->pluck('product_id')->toArray();

        self::where('id', $id)->update([
            "store_id" => $request->store_id,
            "store_name" => (isset($store) && !is_null($store)) ? $store->name : null,
            "store_url" => null,

            "customer_id" => $request->customer_id,
            "customer_group_id" => (isset($request->customer_group_id) && !is_null($request->customer_group_id)) ? $request->customer_group_id : (new CustomerGroup())->_getDefaultGroupId(),

            "first_name" => ($customer) ? $customer->first_name : '',
            "last_name" => ($customer) ? $customer->last_name : '',
            "email" => ($customer) ? $customer->email : '',
            "telephone" => ($customer) ? $customer->telephone : '',

            "payment_country_id" => $request->shipping_country_id,
            "payment_zone_id" => $request->shipping_zone_id,
            "payment_first_name" => $request->shipping_first_name,
            "payment_last_name" => $request->shipping_last_name,
            "payment_company" => (isset($request->shipping_company) && !is_null($request->shipping_company)) ? $request->shipping_company : null,
            "payment_address_1" => $request->shipping_address_1,
            "payment_lat" => (isset($request->shipping_lat) && !is_null($request->shipping_lat)) ? $request->shipping_lat : "0.0000",
            "payment_lng" => (isset($request->shipping_lng) && !is_null($request->shipping_lng)) ? $request->shipping_lng : "0.0000",
            "payment_address_2" => (isset($request->shipping_address_2) && !is_null($request->shipping_address_2)) ? $request->shipping_address_2 : null,
            "payment_city" => $request->shipping_city,
            "payment_postcode" => (isset($request->shipping_postcode) && !is_null($request->shipping_postcode)) ? $request->shipping_postcode : null,
            "payment_country" => $request->shipping_country,
            "payment_zone" => $request->shipping_zone,
            "custom_order" => (isset($request->custom_order) && !is_null($request->custom_order)) ? $request->custom_order : 'No',

            "shipping_country_id" => $request->shipping_country_id,
            "shipping_zone_id" => $request->shipping_zone_id,
            "shipping_first_name" => $request->shipping_first_name,
            "shipping_last_name" => $request->shipping_last_name,
            "shipping_telephone" => (isset($request->shipping_telephone) && !is_null($request->shipping_telephone)) ? $request->shipping_telephone : null,
            "shipping_company" => (isset($request->shipping_company) && !is_null($request->shipping_company)) ? $request->shipping_company : null,
            "shipping_address_1" => $request->shipping_address_1,
            "shipping_lat" => (isset($request->shipping_lat) && !is_null($request->shipping_lat)) ? $request->shipping_lat : "0.0000",
            "shipping_lng" => (isset($request->shipping_lng) && !is_null($request->shipping_lng)) ? $request->shipping_lng : "0.0000",
            "shipping_address_2" => (isset($request->shipping_address_2) && !is_null($request->shipping_address_2)) ? $request->shipping_address_2 : null,
            "shipping_city" => $request->shipping_city,
            "shipping_postcode" => (isset($request->shipping_postcode) && !is_null($request->shipping_postcode)) ? $request->shipping_postcode : null,
            "shipping_country" => $request->shipping_country,
            "shipping_zone" => $request->shipping_zone,

            "shipping_method_id" => (isset($request->shipping_method_id) && !is_null($request->shipping_method_id)) ? $request->shipping_method_id : 0,
            "shipping_method" => (isset($request->shipping_method) && !is_null($request->shipping_method)) ? $request->shipping_method : null,
            "shipping_method_code" => (isset($request->shipping_method_code) && !is_null($request->shipping_method_code)) ? $request->shipping_method_code : null,

            "payment_method_id" => (isset($request->payment_method_id) && !is_null($request->payment_method_id)) ? $request->payment_method_id : 0,
            "payment_method" => (isset($request->payment_method) && !is_null($request->payment_method)) ? $request->payment_method : null,
            "payment_method_code" => (isset($request->payment_method_code) && !is_null($request->payment_method_code)) ? $request->payment_method_code : null,

            "affiliate_id" => (isset($request->affiliate_id) && !is_null($request->affiliate_id)) ? $request->affiliate_id : "0",
            "marketing_id" => (isset($request->marketing_id) && !is_null($request->marketing_id)) ? $request->marketing_id : "0",
            "language_id" => (isset($request->language_id) && !is_null($request->language_id)) ? $request->language_id : "1",

            "currency_id" => (isset($request->currency_id) && !is_null($request->currency_id)) ? $request->currency_id : 0,
            "currency_code" => (isset($request->currency_code) && !is_null($request->currency_code)) ? $request->currency_code : 0,
            "currency_value" => (isset($request->currency_id) && !is_null($request->currency_id)) ? $request->currency_id : 0.00,

            "comment" => (isset($request->comment) && !is_null($request->comment)) ? $request->comment : null,
            "commission" => (isset($request->commission) && !is_null($request->commission)) ? $request->commission : "0",
            "tracking" => (isset($request->tracking) && !is_null($request->tracking)) ? $request->tracking : null,

            "discount_amount" => $discount_amount,
            "payment_type" => $payment_type,
            "payment_mode" => $payment_mode,
            "paid_amount" => $paid_amount,
            "apply_tax" => (isset($request->apply_tax) && !is_null($request->apply_tax)) ? $request->apply_tax : "N",
            "remaining_amount" => $remaining_amount,
            "extra_charge_amount" => $extra_charge_amount,
            "customer_notes" => $customer_notes,
            "total" => $grand_total,
            // "created_by" => Auth::guard('web')->user()->id,
            // "assigned_to" => Order::where('id', $id)->pluck('assigned_to'),
        ]);

        ### UPDATED DELIVERY DATE WHEN IT EXISTS ###
        if (!is_null($delivery_date)) {
            self::where('id', $id)->update(["delivery_date" => $delivery_date]);
        }
        $payment_type = (isset($request->payment_type) && !is_null($request->payment_type)) ? ($request->payment_type) : null;
        $payment_mode = (isset($request->payment_mode) && !is_null($request->payment_mode)) ? ($request->payment_mode) : null;
        if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'partial') {
            $paid_amount = (isset($request->paid_amount) && !is_null($request->paid_amount)) ? floatval($request->paid_amount) : 0.00;
            $remaining_amount = floatval($grand_total - $paid_amount);
        } else if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'full') {
            if ($request->payment_method_code == 'COD') {
                $paid_amount = "0.00";
            } else {
                $paid_amount = $grand_total;
            }
            $remaining_amount = "0.00";
        }


        $order = Order::where('id', $id)->first();
        $invoice_no = $order->invoice_no;
        $order_status_id = $order->order_status_id;

        ### UPDATE ORDER STATUS IF PAYMENT METHOD = `authorize` ###
        if ($request->payment_method_code == 'authorize') {
            $res = $this->callAuthorizeNet(
                $request->card_number,
                $request->card_exp_year . "-" . $request->card_exp_month,
                $request->card_cvv,
                $customer->first_name,
                $customer->last_name,
                $request->shipping_address_1,
                $request->shipping_address_1,
                $request->shipping_country,
                $request->shipping_zone,
                $request->shipping_city,
                $request->shipping_postcode,
                $request->customer_id,
                $customer->email,
                $grand_total,
                $invoice_no,
                "Walk in Customer",
                getConstant('AUTHORIZE_ENV'),
            );


            $order_status_id = ($res['status'] == true) ? '11' : '5';
            Order::where('id', $id)->update(['order_status_id' => $order_status_id, 'payment_method_response' => json_encode($res)]);
            if ($res['status']) {
                (new Payment())->_update($id, $request->payment_method, "full", "authorize", $grand_total, $remaining_amount, $route_id = null);
            } else {
                (new Payment())->_update($id, $request->payment_method, "full", "authorize",  $remaining_amount, $grand_total, $route_id = null);
            }
        }
        ### INSERT IN ORDER PRODUCTS ###
        (new OrderProduct())->_insert($request->product, $id, 'edit');

        ### ORDER TOTAL LOGIC ###
        OrderTotal::where('order_id', $id)->delete();
        (new OrderTotal())->_insert($id, 'extra_charges', 'Additional-Charges', $extra_charge_amount);
        (new OrderTotal())->_insert($id, 'shipping', $request->shipping_method, $request->shipping_method_cost);
        (new OrderTotal())->_insert($id, 'discount', 'Flat Discount', $discount_amount);
        (new OrderTotal())->_insert($id, 'sub_total', 'Sub-Total', $request->sub_total);
        if (isset($request->tax_class) && !is_null($request->tax_class)) {
            (new OrderTotal())->_insert($id, 'tax', 'Tax (' . $request->tax_class . ')', $request->tax_amount);
        }
        (new OrderTotal())->_insert($id, 'payment_method', $request->payment_method, '0.00');
        if ($request->payment_method_code == "COC") {
            $payment_type_method = ($payment_type == 'full') ? 'Full Payment' : 'Partial Payment';
            $payment_mode = (isset($request->payment_mode) && !is_null($request->payment_mode)) ? ucwords($request->payment_mode) : "N/A";
            (new OrderTotal())->_insert($id, 'payment_type', $payment_type_method, '0.00');
            (new OrderTotal())->_insert($id, 'payment_mode', $payment_mode, '0.00');
            if ($payment_type_method == 'Partial Payment') {
                (new OrderTotal())->_insert($id, 'paid_amount', 'Paid-Amount', $paid_amount);
                (new OrderTotal())->_insert($id, 'remaining_amount', 'Remaining-Amount', $remaining_amount);
            }
        }
        if ($request->payment_method_code != "p-link" && $request->payment_method_code != 'authorize' && $request->payment_method_code != 'COD') {
            $route_id = null;
            (new Payment())->_update($id, $request->payment_method, $request->payment_type, $request->payment_mode, $paid_amount, $remaining_amount, $route_id);
        }
        if ($request->payment_method_code == 'COD'  || $request->payment_method_code == "p-link") {
            (new Payment())->_update($id, $request->payment_method, $request->payment_type, $request->payment_mode, "0.00", $grand_total, $route_id = null);
        }

        if (!is_null($request->coupon_cost)) {
            (new OrderTotal())->_insert($id, 'coupon', $request->coupon_name, $request->coupon_cost);
        }
        if (!is_null($request->voucher_cost)) {
            (new OrderTotal())->_insert($id, 'voucher', $request->voucher_name, $request->voucher_cost);
        }
        (new OrderTotal())->_insert($id, 'grand_total', 'Grand-Total', $grand_total);
        ### INSERT ORDER BILLS IF payment_mode == 'cash' ###

        if ($payment_mode == "Cash") {
            $edit = true;
            $payment_id = null;
            (new OrderBill())->_insert($id, $request->bills, 'paid', $payment_id, $edit);
        } else {
            OrderBill::where('order_id', $id)->where('amount_type', 'paid')->delete();
        }
        $comment = "";
        ### INSERT ORDER HISTORY ###
        if ($old_order->extra_charge_amount != $order->extra_charge_amount) {
            $old_extra_charge = $old_order->extra_charge_amount;
            if (gettype($old_extra_charge) === 'NULL') {
                $old_extra_charge = "0";
            }
            $extra_charge = $order->extra_charge_amount;
            if (gettype($extra_charge) === 'NULL') {
                $extra_charge = "0";
            }
            if ($old_extra_charge > $extra_charge) {
                $total_extra_charge = floatval($old_extra_charge) - floatval($extra_charge);
                $comment .= "Removed $" .  $total_extra_charge . " additional charge";
            } else {
                $total_extra_charge = floatval($extra_charge) - floatval($old_extra_charge);
                $comment .= "Added $" . (floatval($extra_charge) - floatval($old_extra_charge)) . " additional charge";
            }
        }
        if ($old_order->apply_tax != $order->apply_tax) {
            if ($order->apply_tax == 'Y') {
                $comment .= " Tax Applied";
            } else {
                $comment .= " Tax Removed";
            }
        }

        if ($old_order->discount_amount != $order->discount_amount) {
            $old_discount_amount = $old_order->discount_amount;
            if (gettype($old_discount_amount) === 'NULL') {
                $old_discount_amount = "0";
            }
            $discount_amount = $order->discount_amount;
            if (gettype($discount_amount) === 'NULL') {
                $discount_amount = "0";
            }
            if ($old_discount_amount > $discount_amount) {
                $total_discount_amount = floatval($old_discount_amount) - floatval($discount_amount);
                $comment .= " Removed $" .  $total_discount_amount . " discount amount";
            } else {
                $total_discount_amount = floatval($discount_amount) - floatval($old_discount_amount);
                $comment .= " Given $" . $total_discount_amount . " discount amount";
            }
        }
        if ($old_order->payment_method != $order->payment_method) {
            $comment .= " Old payment method " . $old_order->payment_method . ", new payment method " . $order->payment_method;
        }

        $new_Order_Products = OrderProduct::where('order_id', $id)->get();
        $new_Order_Product_ids = OrderProduct::where('order_id', $id)->select('product_id')->pluck('product_id')->toArray();

        $new_product = "";
        $old_product = "";
        foreach ($new_Order_Products as $new_order_product) {
            if (!in_array($new_order_product->product_id, $old_order_product_ids)) {
                $new_product .= " " . $new_order_product->name . ",";
            }
        }
        foreach ($old_order_products as $old_order_product) {
            if (!in_array($old_order_product->product_id, $new_Order_Product_ids)) {
                $old_product .= " " . $old_order_product->name . ",";
            }
        }
        if ($new_product != "") {
            $comment .= "new add products " . $new_product;
        }
        if ($old_product != "") {
            $comment .= "removed products " . $old_product;
        }
        (new OrderHistory())->_insert($id, $order_status_id, $comment, $delivery_date);
        ### INSERT COUPON HISTORY ###
        if (!is_null($request->coupon_id)) {
            CouponHistory::where('order_id', $id)->delete();
            (new CouponHistory())->_insert($request->coupon_id, $id, $request->customer_id, $request->coupon_cost);
        }
        ### INSERT VOUCHER HISTORY ###
        if (!is_null($request->voucher_id)) {
            VoucherHistory::where('order_id', $id)->delete();
            (new VoucherHistory())->_insert($request->voucher_id, $id, $request->customer_id, $request->voucher_cost);
        }
        ### CLEAR CART ###
        Cart::where('customer_id', $request->customer_id)->delete();

        return $id;
    }

    function del($id)
    {
        $order = self::where('id', $id)->first();
        if ($order->inventory_status == '1') {
            (new OrderOption())->_incrementQuantityProductOption($id);
            (new OrderProduct())->_incrementOrderProduct($id);
        }
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function pluckIds($id, $table, $col)
    {
        return DB::table($table)->where($col, $id)->pluck('payment_method_id')->toArray();
    }

    function _orderDetail($order_id, $customer_id)
    {
        $order = self::where('id', $order_id)->where('customer_id', $customer_id)->first();
    }

    function validateProducts($data)
    {
        $cart = json_decode($data);
        $res = ['status' => false, 'msg' => []];
        foreach ($cart as $item) {
            $product = (new Product())->getProduct($item->id);
            if ($item->quantity < $product->minimum) {
                $res = ['status' => true, 'msg' => ['Minimum Order Quantity' => 'The product `' . $product->name . '` amount placed is to low to be purchased!']];
                break;
            }
            if ($product->subtract_stock == "1") {
                if ($product->quantity - $item->quantity < 0) {
                    $res = ['status' => true, 'msg' => ['Maximum Order Quantity' => 'The product `' . $product->name . '` amount placed is to high to be purchased!']];
                    break;
                }
            }
        }
        return $res;
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            ### PARAMS ###
            $date_range = (isset($request->date_range) && !is_null($request->date_range)) ? $request->date_range : "-1";
            $sales_rep_id = (isset($request->sales_rep_id) && !is_null($request->sales_rep_id)) ? $request->sales_rep_id : "-1";
            $delivery_date_range = (isset($request->delivery_date_range) && !is_null($request->delivery_date_range)) ? $request->delivery_date_range : "-1";
            $delivered_date_range = (isset($request->delivered_date_range) && !is_null($request->delivered_date_range)) ? $request->delivered_date_range : "-1";
            $order_status_id = (isset($request->order_status) && !is_null($request->order_status)) ? $request->order_status : "-1";
            $customer_id = (isset($request->customer_id) && !is_null($request->customer_id)) ? $request->customer_id : "-1";
            $team_member_id = (isset($request->team_member_id) && !is_null($request->team_member_id)) ? $request->team_member_id : "-1";
            $store_id = (isset($request->store_id) && !is_null($request->store_id)) ? $request->store_id : "-1";
            $order_id = (isset($request->order_id) && !is_null($request->order_id)) ? $request->order_id : "-1";
            $city_id = (isset($request->city_id) && !is_null($request->city_id)) ? $request->city_id : "-1";
            $payment_method_id = (isset($request->payment_method_id) && !is_null($request->payment_method_id)) ? $request->payment_method_id : "-1";
            $custom_order = (isset($request->custom_order) && !is_null($request->custom_order)) ? $request->custom_order : "-1";
            $telephone = (isset($request->telephone) && !is_null($request->telephone)) ? $request->telephone : "-1";
            $tax_apply = (isset($request->tax_apply) && !is_null($request->tax_apply)) ? $request->tax_apply : "-1";
            $country_id = (isset($request->country_id) && !is_null($request->country_id)) ? $request->country_id : "-1";

            if ($date_range != '-1') {
                $split_date = explode(' to ', $request->date_range);
            }
            if ($delivery_date_range != '-1') {
                $split_delivery_date = explode(' to ', $request->delivery_date_range);
            }

            if ($delivered_date_range != '-1') {
                $split_delivered_date = explode(' to ', $delivered_date_range);
            }
            ### INIT QUERY ###
            $query = self::select(
                'id',
                'customer_id',
                'invoice_no',
                'total',
                'order_status_id',
                'created_at',
                'assigned_to',
                'updated_at',
                'status',
                'is_deleted',
                'created_by',
                'delivery_date',
                'shipping_telephone',
                'shipping_city',
                'shipping_address_1',
                'payment_method',
                'payment_method_code',
                'payment_link',
                'payment_link_status',
                'custom_order',
                'delivered_date'
            )
                ->with([
                    'customer' => function ($q) {
                        $q->select('id', 'first_name', 'last_name');
                    },
                    'order_products.order_options',
                    'order_status'

                ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'));
            ### FETCH ORDER CREATED BY LOGGED IN USER ONLY WHEN USER ROLE IS NOT 'Super Admin' AND 'Dispatch Manager' ###
            if (
                !(Auth::guard('web')->user()->hasRole("Super Admin")) &&
                !(Auth::guard('web')->user()->hasRole("Office Admin")) &&
                !(Auth::guard('web')->user()->hasRole("Dispatch Manager")) &&
                !(Auth::guard('web')->user()->hasRole("Accounting"))
            ) {
                /**
                 * create created by id list where $created_by_in = logged in user and its team members
                 */
                $created_by_in = Auth::guard('web')->user()->team_members->pluck('id')->toArray();
                array_push($created_by_in, Auth::guard('web')->user()->id);
                /**
                 * filter for team_member
                 */
                if ($team_member_id !== "-1") {
                    $created_by_in = [$team_member_id];
                }


                $query->whereIn('created_by', $created_by_in);
            }
            ### FETCH ORDERS ASSIGNED TO USER ONLY WHEN USER ROLE IS 'Dispatch Manager' ###
            if (Auth::guard('web')->user()->hasRole("Dispatch Manager")) {
                /**
                 * create created by id list where $created_by_in = logged in user and its team members
                 */
                $created_by_in = Auth::guard('web')->user()->team_members->pluck('id')->toArray();
                array_push($created_by_in, Auth::guard('web')->user()->id);
                /**
                 * filter for team_member
                 */
                if ($team_member_id !== "-1") {
                    $created_by_in = [$team_member_id];
                }

                $query->where(function ($q) use ($created_by_in) {
                    $q->whereIn('created_by', $created_by_in)
                        ->orWhere('assigned_to', Auth::guard('web')->user()->id);
                });
            }
            ### SALES REP FILTER ###
            if ($sales_rep_id != '-1') {
                $query->where('created_by', $sales_rep_id);
            }
            ### City FILTER ###
            if ($city_id !== "-1") {

                $query->where('shipping_city', 'LIKE', "%" . $city_id . "%");
            }
            ### ORDER DATE RANGE FILTER ###
            if ($date_range != '-1') {
                $query->whereRaw('DATE(created_at) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
            }

            ### CUSTOMER FILTER ###
            if ($customer_id != '-1') {
                $query->where('customer_id', $customer_id);
            }
            ### PAYMENT METHOD FILTER ###
            if ($payment_method_id != '-1') {
                $query->where('payment_method_id', $payment_method_id);
            }
            ### TAX APPLY FILTER ###
            if ($tax_apply != '-1') {
                $query->where('apply_tax', $tax_apply);
            }
            ### TELEPHONE FILTER ###
            if ($telephone != '-1') {
                $query->where('telephone', $telephone);
            }
            ### ORDER DELIVERY DATE RANGE FILTER ###
            if ($delivery_date_range != '-1') {
                $query->whereRaw('DATE(delivery_date) BETWEEN "' . $split_delivery_date[0] . '" AND "' . $split_delivery_date[1] . '" ');
            }
              ### ORDER DELIVERED DATE RANGE FILTER ###
              if ($delivered_date_range != '-1') {
                $query->whereRaw('DATE(delivered_date) BETWEEN "' . $split_delivered_date[0] . '" AND "' . $split_delivered_date[1] . '" ');
            }
            ### ORDER STATUS FILTER ###
            if ($order_status_id != '-1') {
                $query->where('order_status_id', $order_status_id);
            }

            ### CUSTOM ORDER FILTER ###
            if ($custom_order != '-1') {
                $query->where('custom_order', $custom_order);
            }

            ### STORE FILTER ###
            if ($store_id != '-1') {
                $query->where('store_id', $store_id);
            }
            ### ORDER FILTER ###
            if ($order_id != '-1') {
                $query->where('id', $order_id);
            }

             ### COUNTRY FILTER ###
             if ($country_id != '-1') {
                $country_user_ids = User::where('country_id',$country_id)->select('id')->pluck('id')->toArray();
                $query->whereIn('created_by', $country_user_ids);
            }
            ### RESULT ###
            $orders = $query->get();

            // $sql = $query->toSql();
            // $bindings = $query->getBindings();
            // return [$sql, $bindings];
            ### INIT DATATABLE ###
            $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($orders)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return  '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox" name="id" value="' . $row->id . '" />
                                            </div>';
                })
                ->addColumn('customer_name', function ($row) {
                    $customer_name = '';
                    if ($row->customer) {
                        $customer_name = $row->customer->first_name . ' ' . $row->customer->last_name;
                    }
                    return $customer_name;
                })
                ->addColumn('customer_telephone', function ($row) {
                    $customer_telephone = 'N/A';
                    if (isset($row->shipping_telephone) && !is_null($row->shipping_telephone) && $row->shipping_telephone != "") {
                        $customer_telephone = $row->shipping_telephone;
                    }
                    return $customer_telephone;
                })
                ->addColumn('customer_city', function ($row) {
                    $customer_city = 'N/A';
                    if (isset($row->shipping_city) && !is_null($row->shipping_city) && $row->shipping_city != "") {
                        $customer_city = $row->shipping_city;
                    }
                    return $customer_city;
                })
                ->addColumn('customer_address', function ($row) {
                    $customer_address = 'N/A';
                    if (isset($row->shipping_address_1) && !is_null($row->shipping_address_1) && $row->shipping_address_1 != "") {
                        $customer_address = $row->shipping_address_1;
                    }
                    return $customer_address;
                })
                ->addColumn('date_added', function ($row) {
                    return date(getConstant('DATE_FORMAT'), strtotime($row->created_at));
                })
                ->addColumn('amount_due', function ($row) {
                    $remaining_amount = "0.00";
                    list($payment_exists, $remaining_amount) = getRemainingAmountFromPayments($row->id);
                    return  $remaining_amount;
                })
                ->addColumn('delivery_date', function ($row) {
                    $delivery_date = 'N/A';
                    if (isset($row->delivery_date) && !is_null($row->delivery_date) && $row->delivery_date != "") {
                        $delivery_date = date(getConstant('DATE_FORMAT'), strtotime($row->delivery_date));
                    }
                    return $delivery_date;
                })
                ->addColumn('delivered_date', function ($row) {
                    $delivered_date = 'N/A';
                    if (isset($row->delivered_date) && !is_null($row->delivered_date) && $row->delivered_date != "") {
                        $delivered_date = date(getConstant('DATE_FORMAT'), strtotime($row->delivered_date));
                    }
                    return $delivered_date;
                })
                ->addColumn('order_comment', function ($row) {
                    $order_comment = '';
                    $orderHistory = DB::table('order_histories')->whereNotNull('comment')->where('comment', '<>', '')->orderBy('id', 'ASC')->where('order_id', $row->id)->first();
                    if ($orderHistory) {
                        $order_comment = $orderHistory->comment;
                    }
                    return $order_comment;
                })
                ->addColumn('order_item', function ($row) {
                    $order_item = '';
                    if (!$row->order_products->isEmpty()) {
                        foreach ($row->order_products as $order_product) {
                            $order_item .= $order_product->name;
                            if (!$order_product->order_options->isEmpty()) {
                                foreach ($order_product->order_options as $order_option) {
                                    $order_item .= "(" . $order_option->value . ")";
                                    if ($row->order_products->last() != $order_product) {
                                        $order_item .= ",&nbsp;&nbsp;";
                                    }
                                }
                            } else {
                                if ($row->order_products->last() != $order_product) {
                                    $order_item .= ",&nbsp;&nbsp;";
                                }
                            }
                        }
                    } else {
                        $order_item = 'N/A';
                    }

                    return  $order_item;
                })
                ->addColumn('order_status', function ($row) use ($order_statuses) {
                    if (false) {
                        $params = "'" . route('orders.update-status', ['id' => $row->id]) . "', '-1', true, this";
                        $html = '<select class="form-select form-select-solid form-select-sm" id="order_status_id" data-id="' . $row->id . '" name="order_status_id" onchange="updateStatus(' . $params . ')">
                                                      <option value="" selected disabled>Select Order Status</option>';
                        foreach ($order_statuses as $order_status) {
                            if (Auth::guard('web')->user()->hasRole("Delivery Rep") && !in_array($order_status->name, ['Postpone', 'Done', 'Canceled'])) {
                                continue;
                            }
                            $selected = ($order_status->id == $row->order_status_id) ? "selected" : "";
                            $html .= '<option value="' . $order_status->id . '" ' .  $selected . '>' . $order_status->name . '</option>';
                        }
                        return $html .= '</select>';
                    } else {

                        return $row->order_status->name;
                    }
                })
                ->addColumn('color_class', function ($row) use ($order_statuses) {
                    $color_class = "";
                    if ($row->custom_order == 'No' || $row->order_status_id == '16' || $row->order_status_id == '20') {
                        foreach ($order_statuses as $order_status) {
                            if ($order_status->id == $row->order_status_id) {
                                $color_class = $order_status->color_class;
                                break;
                            }
                        }
                    } else {
                        $color_class = "special_order_color";
                    }
                    return $color_class;
                })
                // ->addColumn('status', function ($row) {
                //     $param = "'" . route('orders.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                //     $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                //                         data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                //                         onclick="updateStatus(' . $param . ')">
                //                        ' . ($row->status == "1" ? "Active" : "Inactive") . '
                //                     </a>';
                //     return $status;
                // })
                ->addColumn('action', function ($row) {
                    $action = '<div class="dropdown">
                                    <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle" type="button" id="' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="' . $row->id . '">';

                    $action .= '<li>
                                    <a href="' . route('orders.generateInvoice', ['id' => $row->id]) . '" target="_blank" class="dropdown-item">
                                        <i class="far fa-file-alt me-2"></i> Generate Invoice
                                    </a>
                                </li>';

                    if ($row->payment_method_code == "p-link" && $row->payment_link_status == "1") {
                        $action .= '<li>
                                        <a href="javascript:void(0);" class="dropdown-item" id="copy-payment-link" onclick="copyPaymentLink(this)">
                                            <i class="fas fa-link me-2"></i> Copy Payment Link
                                        </a>
                                    </li>';
                    }

                    $action .= '<li>
                                    <a href="' . route('orders.detail', ['id' => $row->id]) . '" class="dropdown-item">
                                        <i class="far fa-eye me-2"></i> Detail
                                    </a>
                                </li>';

                    $action .= '<li>
                                    <a href="' . route('orders.edit', ['id' => $row->id, 'type' => 'create']) . '" class="dropdown-item">
                                        <i class="far fa-copy me-2"></i> Clone
                                    </a>
                                </li>';

                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Orders')) {
                        $action .= '<li>
                                        <a href="' . route('orders.edit', ['id' => $row->id]) . '" class="dropdown-item">
                                            <i class="far fa-edit me-2"></i> Edit
                                        </a>
                                    </li>';
                    }

                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Orders')) {
                        $param = "'" . route('orders.delete', ['id' => $row->id]) . "'";
                        $action .= '<li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            onclick="deleteData(' . $param . ')">
                                            <i class="far fa-trash-alt me-2"></i> Delete
                                        </a>
                                    </li>';
                    }

                    $action .= '</ul></div>';
                    return $action;
                })
                ->rawColumns([
                    'checkbox',
                    'customer_name',
                    'date_added',
                    'data_modified',
                    'order_status',
                    'action',
                    'customer_telephone',
                    'customer_city',
                    'customer_address',
                    'amount_due',
                    'order_item',
                    'color_class',
                    'delivered_date'
                ])
                ->make(true);
        }
    }

    function _addToCart($request)
    {
        $product_id = $request->input('product');
        $customer_id = $request->input('customer_id');
        $product_qty = $request->input('product_qty');
        $options = $request->input('option') ? $request->input('option') : [];
        $options = $this->sanitizeOptions($options);
        $currency_symbol = $request->input("currency_symbol");

        $res = $res = ['status' => false, 'data' => '', 'index' => 0, 'msg' => ''];

        if ($res['msg'] == "") {
            $cart_id = (new Cart())->_store($product_id, $customer_id, $product_qty, $options);
            if ($cart_id) {
                $carts = (new Cart())->_all($customer_id);

                if (count($carts) > 0) {
                    $cart_item_html = '';
                    $total = 0;
                    $total_units = count($carts);
                    $total_quantity = 0;
                    foreach ($carts as $idx => $cart) {
                        $uuid = $cart['product_id'];
                        $calculated_price = $cart['product']['price'];
                        $total_quantity += $cart['quantity'];
                        $option_arr = [];
                        $options = json_decode($cart['option']);
                        if (count((array)$options) > 0) {
                            foreach ($options as $key => $val) {
                                list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                                if (is_array($val)) {
                                    foreach ($val as $value) {
                                        $uuid .= $value;
                                    }
                                } else {
                                    $uuid .= $val;
                                }
                            }
                        }

                        $total += $cart['quantity'] * $calculated_price;
                        $cart_item_html .= view('admin.orders.add_to_cart', compact('option_arr', 'options', 'idx', 'cart', 'uuid', 'calculated_price', 'currency_symbol'))->render();
                    }
                    $res = ['status' => true, 'data' => $cart_item_html, 'msg' => '', 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity];
                }
            }
        }
        return json_encode($res);
    }

    function _validatePurchaseQty($request)
    {
        $cart_id = $request->input('cart_id');
        $updated_qty = $request->input('updated_qty');
        $customer_id = $request->input('customer_id');
        $price = $request->input('price');

        $cart = (new Cart())->_update($cart_id, $updated_qty);
        $res = ['status' => false, 'data' => '', 'msg' => '', 'price' => ''];
        if ($cart) {
            $carts = (new Cart())->_all($customer_id);
            $total = 0;
            $total_units = count($carts);
            $total_quantity = 0;
            foreach ($carts as $item) {
                $calculated_price = $item['product']['price'];
                $total_quantity += $item['quantity'];
                $options = json_decode($item['option']);
                $option_arr = [];
                ### VALIDATE PURCHASE QUANTITY ###
                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $item['product_id']);
                    }
                }
                $total += $item['quantity'] * $calculated_price;
            }
            $res = ['status' => true, 'msg' => 'success', 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity];
            $res['data'] = $cart;
            $res['price'] = $price;
        }
        return json_encode($res);
    }

    function _removeCartItem($request)
    {
        $cart_id = $request->input('id');
        $customer_id = $request->input('customer_id');
        $total = 0;
        $total_quantity = 0;
        $total_units = 0;

        Cart::where('id', $cart_id)->delete();
        $carts = (new Cart())->_all($customer_id);

        if (count($carts) > 0) {
            $total_units = count($carts);
            foreach ($carts as $cart) {
                $calculated_price = $cart['product']['price'];
                $total_quantity += $cart['quantity'];
                $options = json_decode($cart['option']);
                $option_arr = [];
                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                    }
                }
                $total += $cart['quantity'] * $calculated_price;
            }
        }
        return json_encode(['status' => true, 'msg' => 'success', 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity]);
    }

    function _cartTotal($request)
    {
        $customer_id = $request->input('customer_id');
        $country_id = $request->input('country_id');
        $zone_id = $request->input('zone_id');
        $currency_symbol = $request->input('currency_symbol');

        $carts = (new Cart())->_all($customer_id);

        $res = ['status' => false, 'data' => ''];
        if (count($carts) > 0) {
            $cart_total_html = '';
            $total = 0;
            $total_units = count($carts);
            $total_quantity = 0;
            foreach ($carts as $cart) {
                $calculated_price = $cart['product']['price'];
                $total_quantity += $cart['quantity'];
                $option_arr = [];
                $options = json_decode($cart['option']);

                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                    }
                }

                $total += $cart['quantity'] * $calculated_price;
                $cart_total_html .= view('admin.orders.cart_total', compact('option_arr', 'options', 'cart', 'calculated_price', 'total', 'currency_symbol'))->render();
            }
            ### TAX AMOUNT GENERATION / IMPLEMENTATION ###
            $tax_class = (new TaxClass())->_getApplicableTaxClass($country_id, $zone_id);
            $tax_rate = 0.0;
            $tax_type = 'fixed';
            $tax_title = 'N/A';
            if ($tax_class) {
                $tax_title = $tax_class->tax_class;
                $tax_type = $tax_class->tax_type;
                $tax_rate = $tax_class->tax_rate;
            }

            $tax_html = '<tr class="fs-6 text-gray-800" id="order-tax">' .
                '<td colspan="2" class="fw-bolder text-end">' .
                '    <label class="form-label fw-bolder">Apply Tax: </label>' .
                '    <div class="form-check form-check-solid form-check-inline ms-2">' .
                '        <input class="form-check-input" type="radio" id="apply-tax-yes" name="apply_tax" value="Y"' .
                '        onclick="handleTax(this)" checked />' .
                '        <label class="form-check-label" for="apply-tax-yes">' .
                '            Yes' .
                '        </label>' .
                '    </div>' .
                '    <div class="form-check form-check-solid form-check-inline">' .
                '        <input class="form-check-input" type="radio" id="apply-tax-no" name="apply_tax" value="N" onclick="handleTax(this)"' .
                '             />' .
                '        <label class="form-check-label" for="apply-tax-no">' .
                '            No' .
                '        </label>' .
                '    </div>' .
                '</td>' .
                '<input type="hidden" id="input-tax-class" name="tax_class" value="' . $tax_title . '">' .
                '<input type="hidden" id="input-tax-rate" name="tax_rate" value="' . $tax_rate . '">' .
                '<input type="hidden" id="input-tax-type" name="tax_type" value="' . $tax_type . '">' .
                '<input type="hidden" id="input-tax-amount" name="tax_amount">' .
                '<td colspan="2" class="fw-bolder text-end" id="order-tax-amount">' .
                '</td>' .
                '</tr>';

            $res = ['status' => true, 'data' => $cart_total_html, 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity, 'tax_html' => $tax_html];
        }
        return json_encode($res);
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');
        $res = ['status' => true, 'current_status' => $current_status];

        if ($current_status == "-1") {
            $order = self::select('id', 'order_status_id', 'shipping_method_id')->where('id', $id)->first();
            $update = self::where(['id' => $id])->update(['order_status_id' => $request->order_status_id]);
            if ($request->order_status_id == '2' || $request->order_status_id == '4') {
                (new OrderShipment())->_insert($id, $request->order_status_id, $order->shipping_method_id, $order->invoice_no);
            }
            if (!$update) {
                $res['status'] = false;
            }
        } else {
            if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
                $res['current_status'] = $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
            } else {
                $res['current_status'] = $new_status = getConstant('IS_STATUS_ACTIVE');
            }

            $update = self::where(['id' => $id])->update(['status' => $new_status]);

            if (!$update) {
                $res['status'] = false;
            }
        }

        return $res;
    }

    function _generateInvoice($request)
    {
        $order = $this->_getOrderDetail($request->id);
        ### GENERATE PDF ###
        $title = 'Order #' . $order->invoice_no;

        $content = view('admin.orders.generate_invoice', compact('order'))->render();

        generatePdf($content, $title);
    }

    function _detail($request, $id)
    {
        return $this->_getOrderDetail($id);
    }

    function _generateCartForEdit($request)
    {
        $order = $this->_getOrderDetail($request->order_id);
        $currency_symbol = $request->input('currency_symbol');
        $product_id = '';
        $customer_id = $order->customer_id;
        $product_qty = '';
        $options = [];

        Cart::where('customer_id', $customer_id)->delete();
        if (count($order->order_products) > 0) {
            foreach ($order->order_products as $idx => $order_product) {
                $product_id = $order_product->product_id;
                $product_qty = $order_product->quantity;

                if (count($order_product->order_options) > 0) {
                    $arr = [];
                    foreach ($order_product->order_options as $order_option) {
                        if ($order_option->type != 'checkbox') {
                            $options[$order_option->product_option_id] = strval($order_option->product_option_value_id);
                        } else {
                            array_push($arr, strval($order_option->product_option_value_id));
                            $options[$order_option->product_option_id] = $arr;
                        }
                    }
                }
                $cart_id = (new Cart())->_store($product_id, $customer_id, $product_qty, $options);
            }
        }

        $carts = (new Cart())->_all($customer_id);

        if (count($carts) > 0) {
            $cart_item_html = '';
            $total = 0;
            $total_units = count($carts);
            $total_quantity = 0;

            foreach ($carts as $idx => $cart) {
                $uuid = $cart['product_id'];
                $calculated_price = $cart['product']['price'];
                $total_quantity += $cart['quantity'];
                $option_arr = [];
                $options = json_decode($cart['option']);
                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                        if (is_array($val)) {
                            foreach ($val as $value) {
                                $uuid .= $value;
                            }
                        } else {
                            $uuid .= $val;
                        }
                    }
                }
                $total += $cart['quantity'] * $calculated_price;
                $cart_item_html .= view('admin.orders.add_to_cart', compact('option_arr', 'options', 'idx', 'cart', 'uuid', 'calculated_price', 'currency_symbol'))->render();
            }
        }
        return json_encode(['status' => true, 'data' => $cart_item_html, 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity]);
    }

    function _checkout($request, $cart)
    {
        $processing = true;

        if ($request->account_type == 'auth' && $request->billing_address_selection == 'existing') {
            $user = Auth::guard('frontend')->user();
            ### VALIDATE SHIPPING ADDRESS ###
            $shipping_detail = Address::where('id', $request->auth_billing_shipping_address)->first();
            $processing = ($shipping_detail) ? true : false;
            if (!$processing) {
                return ['status' => false, 'data' => "Invalid shipping address, please select a valid address to complete the checkout process."];
            }
        }

        if ($request->account_type == 'auth' && $request->billing_address_selection == 'new') {
            $user = Auth::guard('frontend')->user();

            $first_name = (isset($request->auth_billing_first_name) && !is_null($request->auth_billing_first_name)) ? $request->auth_billing_first_name : '';
            $last_name = (isset($request->auth_billing_last_name) && !is_null($request->auth_billing_last_name)) ? $request->auth_billing_last_name : '';
            $company = (isset($request->auth_billing_company) && !is_null($request->auth_billing_company)) ? $request->auth_billing_company : '';
            $address_1 = (isset($request->auth_billing_address_1) && !is_null($request->auth_billing_address_1)) ? $request->auth_billing_address_1 : '';
            $address_2 = (isset($request->auth_billing_address_2) && !is_null($request->auth_billing_address_2)) ? $request->auth_billing_address_2 : '';
            $city = (isset($request->auth_billing_city) && !is_null($request->auth_billing_city)) ? $request->auth_billing_city : '';
            $postcode = (isset($request->auth_billing_postcode) && !is_null($request->auth_billing_postcode)) ? $request->auth_billing_postcode : '';
            $country_id = (isset($request->auth_billing_country_id) && !is_null($request->auth_billing_country_id)) ? $request->auth_billing_country_id : '';
            $zone_id = (isset($request->auth_billing_zone_id) && !is_null($request->auth_billing_zone_id)) ? $request->auth_billing_zone_id : '';
            $lat = isset($request->auth_billing_lat) && !is_null($request->auth_billing_lat) ? $request->auth_billing_lat : "0.0000";
            $lng = isset($request->auth_billing_lng) && !is_null($request->auth_billing_lng) ? $request->auth_billing_lng : "0.0000";
            $telephone = (isset($request->auth_billing_telephone) && !is_null($request->auth_billing_telephone)) ? $request->auth_billing_telephone : '';

            $address_id = (new Address())->_insert($user->id, $first_name, $last_name, $company, $address_1, $address_2, $city, $postcode, $country_id, $zone_id, false, $lat, $lng, $telephone);
            $shipping_detail = Address::where('id', $address_id)->first();
        }

        if ($request->account_type == 'guest' || $request->account_type == 'register') {
            $customer = new Customer();
            $customer->customer_group_id = (isset($request->customer_group_id) && !is_null($request->customer_group_id)) ? $request->customer_group_id : (new CustomerGroup())->_getDefaultGroupId();
            $customer->store_id = (isset($request->store_id) && !is_null($request->store_id)) ? $request->store_id : "0";
            $customer->language_id = (isset($request->language_id) && !is_null($request->language_id)) ? $request->language_id : "1";
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->email = $request->email;
            $customer->telephone = $request->telephone;
            $customer->password = (isset($request->password) && !is_null($request->password)) ? Hash::make($request->password) : Hash::make(random_password(10));
            $customer->save();

            $customer_id = $customer->id;
            $user = Customer::where('id', $customer_id)->first();

            $first_name = (isset($request->first_name) && !is_null($request->first_name)) ? $request->first_name : '';
            $last_name = (isset($request->last_name) && !is_null($request->last_name)) ? $request->last_name : '';
            $company = (isset($request->company) && !is_null($request->company)) ? $request->company : '';
            $address_1 = (isset($request->address_1) && !is_null($request->address_1)) ? $request->address_1 : '';
            $address_2 = (isset($request->address_2) && !is_null($request->address_2)) ? $request->address_2 : '';
            $city = (isset($request->city) && !is_null($request->city)) ? $request->city : '';
            $postcode = (isset($request->postcode) && !is_null($request->postcode)) ? $request->postcode : '';
            $country_id = (isset($request->country_id) && !is_null($request->country_id)) ? $request->country_id : '';
            $zone_id = (isset($request->zone_id) && !is_null($request->zone_id)) ? $request->zone_id : '';
            $lat = isset($request->lat) && !is_null($request->lat) ? $request->lat : "0.0000";
            $lng = isset($request->lng) && !is_null($request->lng) ? $request->lng : "0.0000";
            $telephone = (isset($request->telephone) && !is_null($request->telephone)) ? $request->telephone : '';

            $address_id = (new Address())->_insert($customer_id, $first_name, $last_name, $company, $address_1, $address_2, $city, $postcode, $country_id, $zone_id, false, $lat, $lng, $telephone);
            $shipping_detail = Address::where('id', $address_id)->first();
        }

        /**
         * get payment method detail
         */
        $payment_method = PaymentMethod::with([
            'eng_description' => function ($q) {
                $q->select('payment_method_id', 'language_id', 'name');
            }
        ])->where('id', $request->payment_method_id)->first();
        $processing = ($payment_method) ? true : false;

        if (!$processing) {
            return ['status' => false, 'data' => "Invalid payment method, please select a valid payment method to complete the checkout process."];
        }

        /**
         * validate cart items and generate required data
         */
        $products = [];
        $sub_total = 0;
        $grand_total = 0;
        foreach ($cart as $key => $value) {
            $product = (new Product())->_getProductWithSlug($value['slug']);
            if (!$product) {
                $processing = false;
                break;
            }

            $option = [];
            $price = (isset($product->discount) && !is_null($product->discount)) ? $product->price - $product->discount->price : $product->price;
            if (count((array)$value['option']) > 0) {
                foreach ($value['option'] as $key => $val) {
                    $product_option = ProductOption::where('id', $key)->first(); // get option_id using product_option_id = $key
                    $option_name = (new Option())->getOptionsData($val, $key, $product->id, $product_option->option_id);
                    ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
                    if ($option_name) {
                        foreach ($option_name->product_option_values as $option_val) {
                            if ($option_val->price_prefix == "+") {
                                $price += $option_val->price;
                            } else {
                                $price -= $option_val->price;
                            }

                            $temp = [];
                            $custom_option_val = $val;
                            if ($option_name->type == "checkbox") {
                                foreach ($val as $check_value) {
                                    $check_value .= '-' . $option_val->eng_description->name;
                                    array_push($temp, $check_value);
                                }
                                $custom_option_val = $temp;
                            } else {
                                $custom_option_val = $val . '-' . $option_val->eng_description->name;
                            }
                            $option[$key . '-' . $option_name->eng_description->name . '-' . $option_name->type] = $custom_option_val;
                        }
                    }
                }
            }
            if (count($option) > 0) {
                $products[] = [
                    'product_id' => $product->id,
                    'quantity' => $value['quantity'],
                    'option' => $option,
                ];
            } else {
                $products[] = [
                    'product_id' => $product->id,
                    'quantity' => $value['quantity'],
                ];
            }

            $grand_total = $sub_total += ($price * $value['quantity']);
        }
        if (!$processing) {
            return ['status' => false, 'data' => "Invalid cart items, please recreate your cart in order to complete the checkout process."];
        }

        /**
         * TAX AMOUNT GENERATION / IMPLEMENTATION
         */
        $tax_class = (new TaxClass())->_getApplicableTaxClass($shipping_detail->country_id, $shipping_detail->zone_id);
        $tax_rate = 0.0;
        $tax_type = 'fixed';
        $tax_title = 'N/A';
        if ($tax_class) {
            $tax_title = $tax_class->tax_class;
            $tax_type = $tax_class->tax_type;
            $tax_rate = $tax_class->tax_rate;
        }
        $applied_tax = 0.0;
        if ($tax_type == "fixed") {
            $applied_tax = $tax_rate;
            $grand_total += $tax_rate;
        } else {
            $applied_tax = setDefaultPriceFormat((($grand_total * $tax_rate) / 100));
            $grand_total += $applied_tax;
        }
        // return [$sub_total, $grand_total];

        /**
         * if every this is valid make an order
         */
        if ($processing) {
            $order = new Order();

            $order->invoice_no = $this->generateInvoiceNo();

            $order->store_id = (isset($request->store_id) && !is_null($request->store_id)) ? $request->store_id : 0;
            $order->store_name = (isset($request->store_name) && !is_null($request->store_name)) ? $request->store_name : null;
            $order->store_url = (isset($request->store_url) && !is_null($request->store_url)) ? $request->store_url : null;

            $order->customer_id = $user->id;
            $order->customer_group_id = (isset($user->customer_group_id) && !is_null($user->customer_group_id)) ? $user->customer_group_id : (new CustomerGroup())->_getDefaultGroupId();
            $order->first_name = $user->first_name;
            $order->last_name = $user->last_name;
            $order->email = $user->email;
            $order->telephone = $user->telephone;

            $order->payment_country_id = $shipping_detail->country_id;
            $order->payment_zone_id = $shipping_detail->zone_id;
            $order->payment_first_name = $shipping_detail->first_name;
            $order->payment_last_name = $shipping_detail->last_name;
            $order->payment_company = (isset($shipping_detail->company) && !is_null($shipping_detail->company)) ? $shipping_detail->company : null;
            $order->payment_address_1 = $shipping_detail->address_1;
            $order->payment_lat = $shipping_detail->lat;
            $order->payment_lng = $shipping_detail->lng;
            $order->payment_address_2 = (isset($shipping_detail->address_2) && !is_null($shipping_detail->address_2)) ? $shipping_detail->address_2 : null;
            $order->payment_city = $shipping_detail->city;
            $order->payment_postcode = (isset($shipping_detail->postcode) && !is_null($shipping_detail->postcode)) ? $shipping_detail->postcode : null;
            $order->payment_country = $shipping_detail->country->name;
            $order->payment_zone = $shipping_detail->zone->name;

            $order->shipping_country_id = $shipping_detail->country_id;
            $order->shipping_zone_id = $shipping_detail->zone_id;
            $order->shipping_first_name = $shipping_detail->first_name;
            $order->shipping_last_name = $shipping_detail->last_name;
            $order->shipping_telephone = (isset($request->shipping_telephone) && !is_null($request->shipping_telephone)) ? $request->shipping_telephone : $user->telephone;
            $order->shipping_company = (isset($shipping_detail->company) && !is_null($shipping_detail->company)) ? $shipping_detail->company : null;
            $order->shipping_address_1 = $shipping_detail->address_1;
            $order->shipping_lat = $shipping_detail->lat;
            $order->shipping_lng = $shipping_detail->lng;
            $order->shipping_address_2 = (isset($shipping_detail->address_2) && !is_null($shipping_detail->address_2)) ? $shipping_detail->address_2 : null;
            $order->shipping_city = $shipping_detail->city;
            $order->shipping_postcode =  (isset($shipping_detail->postcode) && !is_null($shipping_detail->postcode)) ? $shipping_detail->postcode : null;
            $order->shipping_country = $shipping_detail->country->name;
            $order->shipping_zone = $shipping_detail->zone->name;

            $order->shipping_method_id = 0;
            $order->shipping_method = null;
            $order->shipping_method_code = null;

            $order->payment_method_id = $payment_method->id;
            $order->payment_method = $payment_method->eng_description->name;
            $order->payment_method_code = $payment_method->code;

            $order->order_status_id = 11;

            $order->affiliate_id = (isset($request->affiliate_id) && !is_null($request->affiliate_id)) ? $request->affiliate_id : "0";
            $order->marketing_id = (isset($request->marketing_id) && !is_null($request->marketing_id)) ? $request->marketing_id : "0";
            $order->language_id = (isset($request->language_id) && !is_null($request->language_id)) ? $request->language_id : "1";

            $order->currency_id = (isset($request->currency_id) && !is_null($request->currency_id)) ? $request->currency_id : 0;
            $order->currency_code = (isset($request->currency_code) && !is_null($request->currency_code)) ? $request->currency_code : null;
            $order->currency_value = (isset($request->currency_value) && !is_null($request->currency_value)) ? $request->currency_value : 0.00;

            $order->comment = (isset($request->comment) && !is_null($request->comment)) ? $request->comment : null;
            $order->commission = (isset($request->commission) && !is_null($request->commission)) ? $request->commission : "0";
            $order->tracking = (isset($request->tracking) && !is_null($request->tracking)) ? $request->tracking : null;

            ### CHECK FOR EXTRA CHARGES ###
            $extra_charge_amount = (isset($request->extra_charge_amount) && !is_null($request->extra_charge_amount)) ? $request->extra_charge_amount : 0;
            $customer_notes = (isset($request->customer_notes) && !is_null($request->customer_notes)) ? ($request->customer_notes) : '';
            $discount_amount = (isset($request->discount_amount) && !is_null($request->discount_amount)) ? $request->discount_amount : 0.00;

            ### CHECK FOR PAYMENT METHOD `COC` ###
            $paid_amount = 0.00;
            $remaining_amount = 0.00;
            $payment_type = (isset($request->payment_type) && !is_null($request->payment_type)) ? ($request->payment_type) : null;
            if (isset($payment_type) && !is_null($payment_type) && $payment_type == 'partial') {
                $paid_amount = (isset($request->paid_amount) && !is_null($request->paid_amount)) ? floatval($request->paid_amount) : 0.00;
                $remaining_amount = floatval($grand_total - $paid_amount);
            }

            $order->discount_amount = $discount_amount;
            $order->payment_type = $payment_type;
            $order->payment_mode = (isset($request->payment_mode) && !is_null($request->payment_mode)) ? ($request->payment_mode) : null;
            $order->paid_amount = $paid_amount;
            $order->remaining_amount = $remaining_amount;
            $order->extra_charge_amount = $extra_charge_amount;
            $order->customer_notes = $customer_notes;
            $order->total = $grand_total;
            $order->created_by = $user->id;
            $order->assigned_to = $this->getDefaultDispatchManager();

            ### ADD DELIVERY DATE ###
            $delivery_date = (isset($request->delivery_date) && !is_null($request->delivery_date)) ? ($request->delivery_date) : null;
            $order->delivery_date = $delivery_date;

            $order->save();

            $order_id = $order->id;
            $invoice_no = $order->invoice_no;

            ### UPDATE ORDER STATUS IF PAYMENT METHOD = `authorize` ###
            if ($payment_method->code == 'authorize') {
                $res = $this->callAuthorizeNet(
                    $request->card_number,
                    $request->card_exp_year . "-" . $request->card_exp_month,
                    $request->card_cvv,
                    $user->first_name,
                    $user->last_name,
                    $shipping_detail->address_1,
                    $shipping_detail->address_1,
                    $shipping_detail->country->name,
                    $shipping_detail->zone->name,
                    $shipping_detail->city,
                    $shipping_detail->postcode,
                    $user->id,
                    $user->email,
                    $grand_total,
                    $invoice_no,
                    "Online Shopping",
                    getConstant('AUTHORIZE_ENV'),
                );

                $order_status_id = ($res['status'] == true) ? '11' : '5';
                Order::where('id', $order_id)->update(['order_status_id' => $order_status_id, 'payment_method_response' => json_encode($res)]);
                if ($res['status']) {
                    (new Payment())->_insert($order_id, $payment_method->eng_description->name, "full", "authorize", $grand_total, 0.00, $route_id = null);
                }
            }
            ### INSERT IN ORDER PRODUCTS ###
            (new OrderProduct())->_insert($products, $order_id);
            ### ORDER TOTAL LOGIC ###
            (new OrderTotal())->_insert($order_id, 'sub_total', 'Sub-Total', $sub_total);
            (new OrderTotal())->_insert($order_id, 'tax', 'Tax (' . $tax_title . ')', $applied_tax);
            (new OrderTotal())->_insert($order_id, 'payment_method', $payment_method->eng_description->name, '0.00');
            (new OrderTotal())->_insert($order_id, 'grand_total', 'Grand-Total', $grand_total);
            ### INSERT ORDER HISTORY ###
            (new OrderHistory())->_insert($order_id, '11', null, $delivery_date);
            /**
             * used to make a separate record of payments for accounting 
             * $order_id = `id`
             * $request->payment_method = `payment_method` = `Payment on Delivery`, `Payment on Counter`, `Authorize.net`
             * $request->payment_type = `payment_type` = `full`, `partial`
             * $request->payment_mode = `payment_mode` = `online transfer`, `cash`, `card`
             * $paid_amount = `paid_amount`
             * $remaining_amount = `remaining_amount`
             */
            if ($payment_method->code == 'COD') {
                (new Payment())->_insert($order_id, $payment_method->eng_description->name, "full", null, 0.00, $grand_total, $route_id = null);
            }
            Session::forget('cart');

            ### SEND EMAIL ###
            $can_send_email = true;
            if ($payment_method->code == 'COD') {
                $can_send_email = false;
            }

            $order = $this->_getOrderDetail($order_id);
            ### SEND EMAIL TO CUSTOMER ###
            if ($can_send_email) {
                Mail::to($user->email)->send(new OrderPlaced($order));
            }

            ### SEND EMAIL TO ADMIN ###
            $store_admin_email = ($order->store) ? $order->store->email : "info@mattresstodoor.ca";

            Mail::to($store_admin_email)->send(new OrderReceived($order));

            return ['status' => true, 'data' => "Successfully placed an order.", 'invoice_no' => $order->invoice_no, 'order_id' => $order_id, 'user' => $user];
        }
    }

    function _assignUnassignOrder($request)
    {
        // return $request;
        $dispatch_manager_id = $request->dispatch_manager_id;
        $orders_in = $request->order_id;
        $res = ['status' => false, 'data' => 'Unable to perform the action.'];

        $update = self::whereIn('id', $orders_in)->update(['assigned_to' => $dispatch_manager_id]);
        if ($update) {
            $res['status'] = true;
            $res['data'] = "Successfully assigned dispatch manager.";
        }

        return json_encode($res);
    }

    function _isCartValid($request)
    {
        $res = ['status' => false, 'data' => 'You have no items in your cart, please add item to cart in order to move forward.'];
        $cart = Cart::where('customer_id', $request->customer_id)->count('id');
        if ($cart > 0) {
            $res['status'] = true;
            $res['data'] = '';
        }
        return json_encode($res);
    }

    function _getUncalOrderTotal($request)
    {
        $customer_id = $request->input('customer_id');

        $carts = (new Cart())->_all($customer_id);

        $res = ['status' => false, 'sub_total' => '0.00'];
        if (count($carts) > 0) {
            $total = 0;
            $total_quantity = 0;
            foreach ($carts as $cart) {
                $calculated_price = $cart['product']['price'];
                $total_quantity += $cart['quantity'];
                $option_arr = [];
                $options = json_decode($cart['option']);

                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                    }
                }

                $total += $cart['quantity'] * $calculated_price;
            }

            $res = ['status' => true, 'sub_total' => $total];
        }
        return json_encode($res);
    }

    function _search($request)
    {
        $order = self::select('id', 'order_status_id', 'first_name', 'last_name', 'shipping_address_1')->withCount([
            'route_locations'
        ])
            ->whereIn('order_status_id', ['1', '11', '17', '18', '19', '20', '22', '23']) // 'Pending', 'Processing', 'Ready'
            ->where('id', $request->q)
            ->first();

        $arr = [];
        if ($order) {
            $tr = '<tr>
                    <td>
                        ' . $order->id . '
                        <input class="id" type="hidden" name="orders[' . $order->id . '][id]" value="' . $order->id . '"
                            data-order-status="' . $order->order_status->name . '">
                    </td>
                    <td>' . $order->first_name . " " . $order->last_name . '</td>
                    <td>' . $order->shipping_address_1 . '</td>
                    <td>' . $order->order_status->name . '</td>
                    <td>
                        <input type="number" name="orders[' . $order->id . '][sort_order]" class="form-control form-control-sm form-control-solid sort_order"
                            placeholder="Sort Order" autocomplete="off" value="1">
                            <input type="hidden" name="orders[' . $order->id . '][order_status_id]" class="form-control form-control-sm form-control-solid"
                            autocomplete="off" value="' . $order->order_status->id . '">
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-active-light-primary" onclick="removeOrder(this)" title="Remove Order">
                            <i class="far fa-trash-alt" title="Remove Order"></i>
                        </a>
                    </td>
            </tr>';
            $temp['id'] = $order->id;
            $temp['text'] = $order->first_name . " " . $order->last_name . " - " . $order->order_status->name;
            $temp['tr'] = $tr;
            $arr[] = $temp;
        }

        return json_encode(["status" => true, "search" => $arr, 'data' => $order]);
    }

    function getLatLng($order_id)
    {
        $res = ['status' => false, 'data' => 'Unable to process your request.'];
        $order = self::select(
            'id',
            'first_name',
            'last_name',
            'email',
            'telephone',
            'currency_id',
            'shipping_address_1',
            'shipping_lat',
            'shipping_lng',
            'shipping_city',
            'shipping_postcode',
            'shipping_country',
            'shipping_zone',
        )
            ->where('id', $order_id)
            ->first();
        if ($order) {
            $data = getLatLngByGoogleMapApi($order->shipping_address_1);
            self::where('id', $order_id)->update([
                'shipping_lat' => $data['lat'],
                'shipping_lng' => $data['lng'],
            ]);
            Address::where('address_1', 'like', $order->shipping_address_1)->update([
                'lat' => $data['lat'],
                'lng' => $data['lng'],
            ]);
            $res['status'] = true;
            $res['data'] = "Success";
        }
        return json_encode($res);
    }

    public function _order_search($request)
    {


        $orders = self::with('customer')->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('id', $request->q)
            ->get(['id', 'customer_id']);


        $arr = [];
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $temp['id'] =  $order->id;
                $temp['text'] = $order->id . '-' . $order->customer->first_name . ' ' .  $order->customer->last_name;
                $arr[] = $temp;
            }
        }

        return json_encode(["status" => true, "search" => $arr, 'data' =>  $orders]);
    }
}
