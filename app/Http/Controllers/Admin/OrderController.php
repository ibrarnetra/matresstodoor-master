<?php

namespace App\Http\Controllers\Admin;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admin\Zone;
use App\Models\Admin\User;
use App\Models\Admin\Store;
use App\Models\Admin\ShippingMethod;
use App\Models\Admin\PaymentMethod;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\OrderBill;
use App\Models\Admin\Order;
use App\Models\Admin\Option;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\LoadingSheet;
use App\Models\Admin\CustomerGroup;
use App\Models\Admin\Currency;
use App\Models\Admin\Country;
use App\Models\Admin\Category;
use App\Models\Admin\Cart;
use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use App\Exports\LoadingSheetExport;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Orders')) {
            ### CONST ###
            $menu_1 = 'sales';
            $active = 'orders';
            $title = 'Orders';

            $dispatch_managers = [];
            if (
                Auth::guard('web')->user()->hasRole("Super Admin") ||
                Auth::guard('web')->user()->hasRole("Office Admin")
            ) {
                $dispatch_managers = User::whereHas("roles", function ($q) {
                    $q->where("name", "Dispatch Manager");
                })
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                    ->get();
            }

            $sales_reps = [];
            if (
                Auth::guard('web')->user()->hasRole("Super Admin") ||
                Auth::guard('web')->user()->hasRole("Office Admin")
            ) {
                $sales_reps = User::where('is_deleted', getConstant('IS_NOT_DELETED'))
                    ->where('status', getConstant('IS_STATUS_ACTIVE'))->get();
            }

            $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $payment_methods = PaymentMethod::with('eng_description')->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->whereIn('name', ['India', 'Canada'])
                ->get();
            $store_id = (isset($request->store_id) && !is_null($request->store_id)) ? $request->store_id : "-1";
            $order_date = (isset($request->order_date) && !is_null($request->order_date)) ? $request->order_date : "-1";
            if ($order_date != '-1') {
                $split_date = explode(' to ', $request->order_date);
                $order_to = $split_date[1];
                $order_from = $split_date[0];
            } else {
                $order_to = "-1";
                $order_from = "-1";
            }


            return view('admin.orders.index', compact('menu_1', 'active', 'title', 'dispatch_managers', 'sales_reps', 'order_statuses', 'stores', 'store_id', 'order_from', 'order_to', 'payment_methods', 'countries'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create(Request $request)
    {


        if (Auth::guard('web')->user()->hasPermissionTo('Add-Orders')) {
            ### CONST ###
            $menu_1 = 'sales';
            $active = 'orders-create';
            $title = 'Create Order';
            $type = 'create';

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $currencies = Currency::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $customer_groups = CustomerGroup::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $zones = Zone::where('country_id', 38) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $shipping_methods = ShippingMethod::with([
                'eng_description' => function ($q) {
                    $q->select('shipping_method_id', 'language_id', 'name');
                }
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $payment_methods = PaymentMethod::with([
                'eng_description' => function ($q) {
                    $q->select('payment_method_id', 'language_id', 'name');
                }
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $manufacturers = Manufacturer::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $categories = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $is_clone = (isset($_GET['type']) && $_GET['type'] == "create") ? true : false;

            return view('admin.orders.form', compact(
                'menu_1',
                'active',
                'title',
                'type',
                'stores',
                'currencies',
                'customer_groups',
                'countries',
                'shipping_methods',
                'payment_methods',
                'order_statuses',
                'zones',
                'manufacturers',
                'categories',
                'is_clone'
            ));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {

        // return $request;
        $request->validate([
            'currency_id' => 'required',
            'store_id' => 'required',
            'currency_code' => 'required',
            'currency_value' => 'required',

            'customer_group_id' => 'required',
            'customer_id' => 'required',

            'shipping_first_name' => 'required',
            'shipping_last_name' => 'required',
            'shipping_telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'shipping_address_1' => 'required',
            'shipping_city' => 'required',
            'shipping_postcode' => 'required',
            'shipping_country_id' => 'required',
            'shipping_zone_id' => 'required',

            'shipping_method_id' => 'required',
            'shipping_method' => 'required',
            'shipping_method_code' => 'required',
            'shipping_method_cost' => 'required',

            'payment_method_id' => 'required',
            'payment_method' => 'required',
            'payment_method_code' => 'required',

            'card_number' => 'required_if:payment_method_code,authorize',
            'card_exp_month' => 'required_if:payment_method_code,authorize',
            'card_exp_year' => 'required_if:payment_method_code,authorize',
            'card_cvv' => 'required_if:payment_method_code,authorize',

            'payment_type' => 'required_if:payment_method_code,COC',
            'payment_mode' => 'required_if:payment_method_code,COC',

            'paid_amount' => 'required_if:payment_method_code,COC',

            'sub_total' => 'required',
            'grand_total' => 'required',
        ]);

        if (isset($request->product)) {
            $inserted = (new Order())->_store($request);
        } else {
            return redirect()->back()->with('error', 'Cart does not contain any items.');
        }

        if ($inserted) {
            return redirect()->route('orders.index')->with('success', 'Order Added Successfully');
        }
    }

    public function show($id)
    {
    }

    public function edit(Request $request, $id)
    {

        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Orders')) {
            $is_clone = (isset($_GET['type']) && $_GET['type'] == "create") ? true : false;

            ### CONST ###
            $menu_1 = 'sales';
            $active = 'orders';
            $title = ($is_clone) ? 'Create Order' : 'Edit Order';
            $type = 'edit';

            $modal = (new Order())->_getOrderDetail($id);
            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $currencies = Currency::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $customer_groups = CustomerGroup::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $zones = Zone::where('country_id', $modal->shipping_country_id) ### country_id = `38` = `Canada` ###
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $shipping_methods = ShippingMethod::with([
                'eng_description' => function ($q) {
                    $q->select('shipping_method_id', 'language_id', 'name');
                }
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $payment_methods = PaymentMethod::with([
                'eng_description' => function ($q) {
                    $q->select('payment_method_id', 'language_id', 'name');
                }
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $order_bills = (new OrderBill())->_getOrderBills($id);
            $manufacturers = Manufacturer::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $categories = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.orders.form', compact(
                'menu_1',
                'active',
                'title',
                'type',
                'stores',
                'currencies',
                'customer_groups',
                'countries',
                'shipping_methods',
                'payment_methods',
                'order_statuses',
                'zones',
                'id',
                'modal',
                'order_bills',
                'manufacturers',
                'categories',
                'is_clone'
            ));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {

        // return $request;
        $request->validate([
            'currency_id' => 'required',
            'currency_code' => 'required',
            'currency_value' => 'required',

            'customer_group_id' => 'required',
            'customer_id' => 'required',
            'store_id' => 'required',

            'shipping_first_name' => 'required',
            'shipping_last_name' => 'required',
            'shipping_telephone' => 'nullable|regex:/^\d{3}?[-]?\d{3}[-]?\d{4}$/',
            'shipping_address_1' => 'required',
            'shipping_city' => 'required',
            'shipping_postcode' => 'required',
            'shipping_country_id' => 'required',
            'shipping_zone_id' => 'required',

            'shipping_method_id' => 'required',
            'shipping_method' => 'required',
            'shipping_method_code' => 'required',
            'shipping_method_cost' => 'required',

            'payment_method_id' => 'required',
            'payment_method' => 'required',
            'payment_method_code' => 'required',

            'card_number' => 'required_if:payment_method_code,authorize',
            'card_exp_month' => 'required_if:payment_method_code,authorize',
            'card_exp_year' => 'required_if:payment_method_code,authorize',
            'card_cvv' => 'required_if:payment_method_code,authorize',

            'payment_type' => 'required_if:payment_method_code,COC',
            'payment_mode' => 'required_if:payment_method_code,COC',

            'paid_amount' => 'required_if:payment_method_code,COC',

            'sub_total' => 'required',
            'grand_total' => 'required',
        ]);

        $is_clone = (isset($request->is_clone) && !is_null($request->is_clone)) ? true : false;

        if ($is_clone) {
            if (isset($request->product)) {
                $inserted = (new Order())->_store($request);
            } else {
                return redirect()->back()->with('error', 'Cart does not contain any items.');
            }

            if ($inserted) {
                return redirect()->route('orders.index')->with('success', 'Order Added Successfully');
            }
        } else {
            $update = (new Order())->_update($request, $id);

            if ($update) {
                return redirect()->route('orders.index')->with('success', 'Order Updated Successfully');
            }
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted order.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Orders')) {
            $del = (new Order())->del($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Order())->_updateStatus($request, $id);
    }

    public function addToCart(Request $request)
    {
        return (new Order())->_addToCart($request);
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

    public function validatePurchaseQty(Request $request)
    {
        return (new Order())->_validatePurchaseQty($request);
    }

    public function cartTotal(Request $request)
    {
        return (new Order())->_cartTotal($request);
    }

    public function removeCartItem(Request $request)
    {
        return (new Order())->_removeCartItem($request);
    }

    public function dataTable(Request $request)
    {
        return (new Order())->_dataTable($request);
    }

    public function generateInvoice(Request $request)
    {
        return (new Order())->_generateInvoice($request);
    }

    public function detail(Request $request, $id)
    {
        ### CONST ###
        $menu_1 = 'sales';
        $active = 'orders';
        $title = 'Order Detail';

        $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        $order = (new Order())->_detail($request, $id);
    


        ### FETCH DISPATCH MANAGERS ###
        $dispatch_managers = [];
        if (
            Auth::guard('web')->user()->hasRole("Super Admin") ||
            Auth::guard('web')->user()->hasRole("Office Admin")
            || Auth::guard('web')->user()->hasRole("Dispatch Manager")
        ) {
            $dispatch_managers = User::whereHas("roles", function ($q) {
                $q->where("name", "Dispatch Manager");
            })
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
        }

        return view('admin.orders.detail', compact('menu_1', 'active', 'title', 'order', 'id', 'order_statuses', 'dispatch_managers'));
    }

    public function generateCartForEdit(Request $request)
    {
        return (new Order())->_generateCartForEdit($request);
    }

    public function exportExcel(Request $request)
    {

        return Excel::download(new OrdersExport($request), 'orders-' . Carbon::now()->toDateString() . '.csv');
    }

    public function clearCart(Request $request)
    {
        // return $request;
        return (new Cart())->_clearCart($request);
    }

    public function assignUnassignOrder(Request $request)
    {
        return (new Order())->_assignUnassignOrder($request);
    }

    public function exportLoadingSheet(Request $request)
    {
        return Excel::download(new LoadingSheetExport($request), 'load-sheet-' . Carbon::now()->toDateString() . '.xlsx');
    }

    public function isCartValid(Request $request)
    {
        return (new Order())->_isCartValid($request);
    }

    public function getUncalOrderTotal(Request $request)
    {
        return (new Order())->_getUncalOrderTotal($request);
    }

    public function search(Request $request)
    {
        return (new Order())->_search($request);
    }

    public function getLatLng(Request $request, $order_id)
    {
        return (new Order())->getLatLng($order_id);
    }


    public function orderSearch(Request $request)
    {
        return (new Order())->_order_search($request);
    }

    public function orderSummary(Request $request, $order_id)
    {


        $order = (new Order())->_detail($request, $order_id);
        if ($order) {
            $view = view('admin.routes.order_summary', compact('order'))->render();
            $status = true;
        } else {
            $view = "No Found";
            $status = false;
        }



        return json_encode(['status' =>   $status, 'data' => $view]);
    }
}
