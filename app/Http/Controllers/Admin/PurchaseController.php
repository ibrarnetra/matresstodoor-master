<?php

namespace App\Http\Controllers\Admin;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admin\User;
use App\Models\Admin\Purchase;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\Product;
use App\Models\Admin\Cart;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\Category;
use App\Models\Admin\Currency;
use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;


class PurchaseController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Purchases')) {
            ### CONST ###
            $menu_1 = 'purchases';
            $active = 'purchases';
            $title = 'Purchases';

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
                $sales_reps = User::whereHas("roles", function ($q) {
                    $q->where("name", "Sales Rep");
                })
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                    ->get();
            }
            $warehouses = Warehouse::select('id', 'name')->get();

            return view('admin.purchase.index', compact('menu_1', 'active', 'title', 'dispatch_managers', 'sales_reps', 'warehouses'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create(Request $request)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Purchases')) {
            ### CONST ###
            $menu_1 = 'purchases';
            $active = 'purchases-create';
            $title = 'Create Purchase';
            $type = 'create';
            $warehouses = Warehouse::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $manufacturers = Manufacturer::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $categories = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $currencies = Currency::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            $is_clone = (isset($_GET['type']) && $_GET['type'] == "create") ? true : false;

            return view('admin.purchase.form', compact(
                'menu_1',
                'active',
                'title',
                'type',
                'warehouses',
                'categories',
                'manufacturers',
                'warehouses',
                'currencies',
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
            'warehouse_id' => 'required',
            'purchase_date' => 'required',
            'serial_no' => 'required',
            'product.*.product_id' => 'required'
        ]);


        if (isset($request->product)) {
            $inserted = (new Purchase())->_store($request);
        } else {
            return redirect()->back()->with('error', 'Cart does not contain any items.');
        }

        if ($inserted) {
            return redirect()->route('purchases.index')->with('success', 'Purchase Added Successfully');
        }
    }

    public function show($id)
    {
    }

    public function edit(Request $request, $id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Purchases')) {
            $is_clone = (isset($_GET['type']) && $_GET['type'] == "create") ? true : false;

            ### CONST ###
            $menu_1 = 'purchases';
            $active = 'purchases';
            $title = ($is_clone) ? 'Create Purchase' : 'Edit Purchase';
            $type = 'edit';


            $modal = (new Purchase())->_getPurchaseDetail($id);
            $warehouses = Warehouse::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();


            $currencies = Currency::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            $manufacturers = Manufacturer::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $categories = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))->get();

            return view('admin.purchase.form', compact(
                'menu_1',
                'active',
                'title',
                'type',
                'warehouses',
                'currencies',
                'id',
                'modal',
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
            'warehouse_id' => 'required',
            'purchase_date' => 'required',
            'serial_no' => 'required',
            'product.*.product_id' => 'required'
        ]);

        $is_clone = (isset($request->is_clone) && !is_null($request->is_clone)) ? true : false;

        if ($is_clone) {
            if (isset($request->product)) {
                $inserted = (new Purchase())->_store($request);
            } else {
                return redirect()->back()->with('error', 'Cart does not contain any items.');
            }

            if ($inserted) {
                return redirect()->route('purchases.index')->with('success', 'Purchase Added Successfully');
            }
        } else {
            $update = (new Purchase())->_update($request, $id);


            if ($update) {
                return redirect()->route('purchases.index')->with('success', 'Purchase Updated Successfully');
            }
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted purchase.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Purchases')) {
            $del = (new Purchase())->del($id);

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

        return (new Purchase())->_addToCart($request);
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
        return (new Purchase())->_validatePurchaseQty($request);
    }

    public function cartTotal(Request $request)
    {
        return (new Order())->_cartTotal($request);
    }

    public function removeCartItem(Request $request)
    {
        return (new Purchase())->_removeCartItem($request);
    }

    public function dataTable(Request $request)
    {

        return (new Purchase())->_dataTable($request);
    }

    public function generateInvoice(Request $request)
    {

        return (new Purchase())->_generateInvoice($request);
    }

    public function detail(Request $request, $id)
    {
        ### CONST ###
        $menu_1 = 'purchases';
        $active = 'purchases';
        $title = 'Purchase Detail';



        $purchase = (new Purchase())->_detail($request, $id);



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

        return view('admin.purchase.detail', compact('menu_1', 'active', 'title', 'purchase', 'id', 'dispatch_managers'));
    }

    public function productPurchaseHistory(Request $request, $id)
    {
        ### CONST ###
        $menu_1 = 'catalog';
        $active = 'products';
        $title = 'Purchase History';
        $warehouses = Warehouse::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $product = Product::with('eng_description')->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('id', $id)->first();
        $productOptionValues = ProductOptionValue::with('eng_description')->where("product_id", $id)->get();

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

        return view('admin.products.purchaseHistory', compact('menu_1', 'active', 'title', 'product', 'productOptionValues', 'warehouses', 'id', 'dispatch_managers'));
    }

    public function productPurchaseDataTable(Request $request)
    {
        return (new Purchase())->_purchaseDataTable($request);
    }

    public function generateCartForEdit(Request $request)
    {

        return (new Purchase())->_generateCartForEdit($request);
    }

    public function exportExcel(Request $request)
    {

        return Excel::download(new OrdersExport($request), 'orders-' . Carbon::now()->toDateString() . '.xlsx');
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
}
