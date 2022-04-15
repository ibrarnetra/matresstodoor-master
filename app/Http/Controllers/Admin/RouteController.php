<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\User;
use App\Models\Admin\Store;
use App\Models\Admin\Route;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\Order;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{
    public function index()
    {

        if (Auth::guard('web')->user()->hasPermissionTo('Read-Routes')) {
            ### CONST ###
            $menu_1 = 'sales';
            $active = 'routes';
            $title = 'Routes';

            $query = Route::where('is_deleted', getConstant('IS_NOT_DELETED'))->with([
                'route_created_by' => function ($q) {
                    $q->select('id', 'first_name', 'last_name');
                },
                'loading_sheet' => function ($q) {
                    $q->select('id', 'route_id', 'name');
                },
            ]);
            /**
             * check whether the authenticated user has role `Delivery Rep` then apply the where condition
             */
            if (Auth::guard('web')->user()->hasRole("Delivery Rep")) {
                $query->where('assigned_to', Auth::guard('web')->user()->id);
            }
            $routes = $query->orderBy('id', 'DESC')
                ->paginate(10);

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.routes.index', compact('menu_1', 'active', 'title', 'routes', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Routes')) {
            ### CONST ###
            $menu_1 = 'sales';
            $active = 'routes';
            $title = 'Create Route';
            $type = 'create';

            return view('admin.routes.form', compact('menu_1', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'start_location_id' => 'required',
            'dispatch_date' => 'required'
        ]);

        $res = (new Route())->_store($request);
        if ($res) {
            return redirect()->route('routes.index')->with('success', 'Route created successfully.');
        }
    }

    public function show($id)
    {
        return (new Route())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Routes')) {
            ### CONST ###
            $menu_1 = 'sales';
            $active = 'routes';
            $title = 'Edit Route';
            $type = 'edit';

            $modal = (new RouteController())->show($id);

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return view('admin.routes.form', compact('menu_1', 'active', 'title', 'type', 'id', 'modal', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'start_location_id' => 'required',
        ]);

        $res = (new Route())->_update($request, $id);
        if ($res) {
            return redirect()->route('routes.index')->with('success', 'Route updated successfully.');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted Route.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Routes')) {
            $del = (new Route())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Route())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Route())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Route())->_bulkDelete($request);
    }

    public function detail(Request $request, $id)
    {
        ### CONST ###
        $menu_1 = 'sales';
        $active = 'routes';
        $title = 'Route Detail';

        $route = (new Route())->_detail($request, $id);
        foreach ($route->route_locations as $location) {
            if ($location->order) {
                $total_collect_amount = 0;
                $payments = (new Route())->_orderCashSummary($location->order->id, $id);
                foreach ($payments as $payment) {
                    $total_collect_amount += $payment->paid_amount;
                }
                $location->order->total_collect_amount = $total_collect_amount;
            }
        }
        $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();
        ### FETCH DISPATCH MANAGERS ###
        $delivery_reps = [];
        if (
            Auth::guard('web')->user()->hasRole("Super Admin") ||
            Auth::guard('web')->user()->hasRole("Office Admin") ||
            Auth::guard('web')->user()->hasRole("Dispatch Manager")
        ) {
            $delivery_reps = User::whereHas("roles", function ($q) {
                $q->where("name", "Delivery Rep");
            })
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
        }

        return view('admin.routes.detail', compact('menu_1', 'active', 'title', 'route', 'id', 'delivery_reps', 'order_statuses', 'stores'));
    }

    public function assignDeliveryRep(Request $request)
    {
        $request->validate([
            'route_id' => 'required',
            'delivery_rep_id' => 'required',
        ]);

        Route::where('id', $request->route_id)->update(['assigned_to' => $request->delivery_rep_id]);
        return redirect()->back()->with('success', 'Route updated successfully.');
    }

    public function checkOrdersRoutes(Request $request)
    {
        $orders = (new Route())->_checkOrdersRoutes($request);
        $order_statuses = [];
        $already_assigned_orders_count = 0;
        foreach ($orders as $order) {
            // if ($order->route_location) {
            //     $already_assigned_orders_count++;
            // }
            array_push($order_statuses, $order->order_status->name);
        }
        $view = view('admin.routes.orders_with_routes', compact('orders'))->render();
        return json_encode(['status' => true, 'data' => $view, 'order_statuses' => $order_statuses, 'already_assigned_orders_count' => $already_assigned_orders_count]);
    }

    public function getOptimizedRoutes(Request $request, $id)
    {

        $optimized_routes = (new Route())->_getOptimizedRoutes($request, $id);
        $view = view('admin.routes.optimized_routes', compact('optimized_routes'))->render();
        return json_encode(['status' =>  true, 'data' => $view]);
    }

    public function optimizeRoutes(Request $request, $id)
    {
        return (new Route())->_optimizeRoutes($request, $id);
    }

    public function updateOrder($order_id)
    {
        $order = (new Order())->_getOrderDetail($order_id);
        $country_id = $order->shipping_country_id;
        $zone_id = $order->shipping_zone_id;
        $order_products = $order->order_products;
        $apply_tax = $order->apply_tax;
        $discount_amount = $order->discount_amount;
        $view = view('admin.routes.order_detail', compact('order'))->render();
        $partial_done = view('admin.routes.partial_done_table', compact('order_products', 'zone_id', 'country_id', 'apply_tax', 'discount_amount'))->render();
        return json_encode(['status' =>  true, 'data' => $view, 'partial_done' => $partial_done]);
    }

    public function getOrders(Request $request)
    {
        $view = view('admin.routes.orders_modal')->render();
        return json_encode(['status' =>  true, 'data' => $view]);
    }

    public function getRouteSummary($route_id)
    {
        ### CONST ###
        $menu_1 = 'sales';
        $active = 'routes';
        $title = 'Route Cash Summary';
        $route = (new Route())->_detail('', $route_id);
        $total_cash_amount = 0;
        $total_online_amount = 0;
        $total_card_amount = 0;

        foreach ($route->route_locations as $location) {
            if ($location->order) {
                $total_collect_amount = 0;

                $payments = (new Route())->_orderCashSummary($location->order->id, $route_id);
                foreach ($payments as $payment) {
                    $total_collect_amount += $payment->paid_amount;
                    if ($payment->mode == 'online transfer') {
                        $total_online_amount += $payment->paid_amount;
                    } else if ($payment->mode == 'cash') {
                        $total_cash_amount += $payment->paid_amount;
                    } else if ($payment->mode == 'card') {
                        $total_card_amount += $payment->paid_amount;
                    }
                }
                $location->order->total_collect_amount = $total_collect_amount;
            }
        }

        $order_statuses = OrderStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();


        return view('admin.routes.route_summary', compact('menu_1', 'active', 'title', 'route', 'order_statuses', 'total_cash_amount', 'total_card_amount', 'total_online_amount'));
    }

    public function routeOrderCashSummary(Request $request, $order_id, $route_id)
    {

        $payments = (new Route())->_orderCashSummary($order_id, $route_id);


        $view = view('admin.routes.order_cash_summary', compact('payments'))->render();

        return json_encode(['status' =>  true, 'data' => $view]);
    }

    public function truckLoaded($route_id)
    {
        $truck_loaded = (new Route())->_truckLoaded($route_id);
    
        return redirect()->back()->with('success', 'Loading has Successfully done.');
    }
}
