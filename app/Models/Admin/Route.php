<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\User;
use App\Models\Admin\Store;
use App\Models\Admin\RouteLocation;
use App\Models\Admin\OrderRouteLog;
use App\Models\Admin\Order;
use App\Models\Admin\LoadingSheet;
use App\Models\Admin\Payment;
use App\Models\Admin\Product;
use net\authorize\api\contract\v1\OrderType;


class Route extends Model
{
    use HasFactory;

    private function setRouteEndLocation($location, $orders)
    {
        if (is_null($location)) {
            if (!is_null($orders)) {
                $last_order = $orders[key(array_slice($orders, -1, 1, true))];
                $location = $last_order['shipping_address_1'];
            }
        }
        return $location;
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function route_created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function route_assigned_to()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function route_locations()
    {
        return $this->hasMany(RouteLocation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function start_location()
    {
        return $this->belongsTo(Store::class, 'start_location_id', 'id');
    }

    public function loading_sheet()
    {
        return $this->hasOne(LoadingSheet::class);
    }

    function _show($id)
    {
        return self::with([
            'start_location' => function ($q) {
                $q->select('id', 'name', 'address', 'lat', 'lng');
            },
            'route_created_by' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            },
            'route_assigned_to' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            },
            'route_locations' => function ($q) {
                $q->select('id', 'route_id', 'order_id', 'sort_order', 'order_status_id')->with([
                    'route_order_status',
                    'order' => function ($q) {
                        $q->select('id', 'first_name', 'last_name', 'email', 'telephone', 'currency_id', 'shipping_address_1', 'shipping_lat', 'shipping_lng', 'shipping_city', 'shipping_postcode', 'shipping_country', 'shipping_zone', 'payment_method_code', 'payment_type', 'order_status_id', 'paid_amount', 'custom_order', 'remaining_amount', 'total')->with([
                            'order_status' => function ($q) {
                                $q->select('id', 'name', 'color_class');
                            },
                            'currency' => function ($q) {
                                $q->select('id', 'title', 'symbol_left', 'symbol_right');
                            }
                        ])
                            ->where('is_deleted', getConstant('IS_NOT_DELETED'));
                    }
                ])->orderBy('sort_order');
            }
        ])
            ->where('id', $id)
            ->first();
    }

    function _store($request)
    {
        $end_location = (isset($request->end_location) && !is_null($request->end_location)) ? $request->end_location : null;
        $orders = (isset($request->orders) && !is_null($request->orders)) ? $request->orders : null;

        $route = new Route();
        $route->name = capAll($request->name);
        $route->start_location_id = $request->start_location_id;
        $route->end_location = $this->setRouteEndLocation($end_location, $orders);
        $route->dispatch_date = $request->dispatch_date;
        $route->created_by = Auth::guard('web')->user()->id;
        $route->assigned_to = 0;
        $route->save();

        $route_id = $route->id;

        if ($request->has('orders')) {
            foreach ($request->orders as $k => $v) {
                (new RouteLocation())->_insert($route_id, $v['id'], $v['order_status_id'], $v['sort_order']);
                (new OrderRouteLog())->_insert($v['id'], $route_id);
            }
        }

        return $route;
    }

    function _update($request, $id)
    {
        $end_location = (isset($request->end_location) && !is_null($request->end_location)) ? $request->end_location : null;
        $orders = (isset($request->orders) && !is_null($request->orders)) ? $request->orders : null;
        $route = self::where('id', $id)->first();
        self::where('id', $id)->update([
            "name" => capAll($request->name),
            "start_location_id" => $request->start_location_id,
            "dispatch_date" => isset($request->dispatch_date) ? $request->dispatch_date : $route->dispatch_date,
            "end_location" => $this->setRouteEndLocation($end_location, $orders),
        ]);

        // delete existing associated route locations and insert new
        RouteLocation::where('route_id', $id)->delete();
        OrderRouteLog::where('route_id', $id)->delete();
        if ($request->has('orders')) {
            foreach ($request->orders as $k => $v) {
                (new RouteLocation())->_insert($id, $v['id'], $v['order_status_id'], $v['sort_order']);
                (new OrderRouteLog())->_insert($v['id'], $id);
            }
        }

        return $id;
    }

    function _destroy($id)
    {
        RouteLocation::where('route_id', $id)->delete();
        return self::where('id', $id)->delete();
        // return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = self::where(['id' => $id])->update(['status' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
    }

    function _dataTable($request)
    {
    }

    function _bulkDelete($request)
    {
        // return $request;
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = self::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
    }

    function _detail($request, $id)
    {

        return $this->_show($id);
    }

    function _assignUnassignOrder($request)
    {
        // return $request;
        $delivery_rep_id = $request->delivery_rep_id;
        $route_id = $request->route_id;
        $res = ['status' => false, 'data' => 'Unable to perform the action.'];

        $update = self::where('id', $route_id)->update(['assigned_to' => $delivery_rep_id]);
        if ($update) {
            $res['status'] = true;
            $res['data'] = "Successfully updated route.";
        }

        return json_encode($res);
    }

    function _checkOrdersRoutes($request)
    {
        return Order::select('id', 'first_name', 'last_name', 'shipping_address_1', 'order_status_id', 'total', 'delivery_date')->with([
            'order_status' => function ($q) {
                $q->select('id', 'name');
            },
            'route_locations' => function ($q) {
                $q->select('id', 'route_id', 'order_id')->with([
                    'route' => function ($q) {
                        $q->select('id', 'name');
                    }
                ]);
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->whereIn('id', $request->order_ids)
            ->get();
    }

    function _getOptimizedRoutes($request, $id)
    {
        $end_location = (isset($request->end_location) && !is_null($request->end_location)) ? $request->end_location : null;


        $start_location_id = (isset($request->start_location_id) && !is_null($request->start_location_id)) ? $request->start_location_id : null;

        $route = $this->_show($id);
        if ($start_location_id) {
            $store = Store::where('id', $start_location_id)->first();
            $address = $store->address;
            $lat = $store->lat;
            $lng = $store->lng;
            $city = $store->name;
        } else {
            $address = $route->start_location->address;

            $lat = $route->start_location->lat;
            $lng = $route->start_location->lng;
            $city = $route->start_location->name;
        }



        /**
         * generating locations array required RouteXL tour api.
         */
        $locations = [
            [
                'address' => $address,
                'lat' => $lat,
                'lng' =>  $lng,
            ]
        ];
        $all_order_ids = RouteLocation::where('route_id', $route->id)->pluck('order_id')->toArray();
        $orders = Order::whereIn('id', $all_order_ids)->where(['shipping_lat' => '0.0000', 'shipping_lng' => '0.0000'])->get();
        foreach ($orders as $order) {
            $get_latLng = getLatLngByGoogleMapApi($order->shipping_address_1);
            $order->shipping_lat = $get_latLng['lat'];
            $order->shipping_lng = $get_latLng['lng'];
            $order->save();
        }

        $order_ids = [];
        $same_address_route_location = DB::table("route_locations")
            ->join('orders', 'route_locations.order_id', '=', 'orders.id')
            ->select('route_locations.sort_order as sort_order', 'orders.shipping_address_1 as address', 'orders.shipping_lat as shipping_lat', 'orders.shipping_lng as shipping_lng', 'orders.id as order_id')
            ->where('route_locations.route_id', $route->id)->where('orders.shipping_address_1', 'LIKE', $address)->get();
        foreach ($same_address_route_location as $same_address) {
            array_push($locations, [
                'address' => $same_address->address,
                'lat' =>   $same_address->shipping_lat,
                'lng' =>  $same_address->shipping_lng,
            ]);
            $order_ids[] = $same_address->order_id;
        }
        $same_city_route_locations = DB::table("route_locations")
            ->join('orders', 'route_locations.order_id', '=', 'orders.id')
            ->select('route_locations.sort_order as sort_order', 'orders.shipping_address_1 as address', 'orders.shipping_lat as shipping_lat', 'orders.shipping_lng as shipping_lng', 'orders.id as order_id')
            ->where('route_locations.route_id', $route->id)->where('orders.shipping_city', 'LIKE', $city)
            ->whereNotIn('orders.id', $order_ids)
            ->orderBy(DB::raw("3959 * acos( cos( radians($lat) ) * cos( radians( orders.shipping_lat ) ) * cos( radians( orders.shipping_lng ) - radians(-$lng) ) + sin( radians($lat) ) * sin(radians(orders.shipping_lat)) )"), 'DESC')
            ->get();
        foreach ($same_city_route_locations as $same_city) {

            array_push($locations, [
                'address' => $same_city->address,
                'lat' =>  $same_city->shipping_lat,
                'lng' =>  $same_city->shipping_lng,
            ]);
            $order_ids[] = $same_city->order_id;
        }

        $route_locations = RouteLocation::with('order')->where('route_id', $route->id)->whereNotIn('order_id', $order_ids)->get();


        foreach ($route_locations as $location) {

            array_push($locations, [
                'address' => $location->order->shipping_address_1,
                'lat' => $location->order->shipping_lat,
                'lng' => $location->order->shipping_lng,
            ]);
        }

        if ($end_location) {
            $getLatLng = getLatLngByGoogleMapApi($end_location);
            array_push($locations, [
                'address' => $end_location,
                'lat' => $getLatLng['lat'],
                'lng' => $getLatLng['lng'],
            ]);
        }


        // return $locations;

        /**
         * RouteXL tour api
         * properly formatting RouteXL api response.
         */
        $res = json_decode(initRouteXL($locations));
        Session::forget('optimized_routes');
        session()->put('optimized_routes', $res);
        return $res;
    }

    function _optimizeRoutes($request, $id)
    {
        $original_routes = $this->_show($id)->route_locations->toArray();
        $optimized_routes = session()->get('optimized_routes');

        /**
         * looping over session stored optimized routes for optimizing delivery route.
         */
        foreach ($optimized_routes->data->route as $key => $route) {
            /**
             * ignoring starting location i.e. store location.
             */
            if ($key == 0) {
                continue;
            }
            /**
             * looping over original routes
             */
            foreach ($original_routes as $k2 => $original_route) {
                /**
                 * map optimized route address from $optimized_routes with order address from $original_routes
                 * where `true` update route_location sort_order based on $optimized_routes loop key
                 * after updating sort_order remove that specific order form $original_route and break the loop
                 * this will prevent unwanted sort_order's if multiple same locations exist.
                 */
                if ($route->name == $original_route['order']['shipping_address_1']) {
                    RouteLocation::where('route_id', $id)->where('order_id', $original_route['order']['id'])->update(['sort_order' => $key, 'distance' => $route->distance]);
                    unset($original_routes[$k2]);
                    break;
                }
            }
        }
        $end_location = (isset($request->end_location) && !is_null($request->end_location)) ? $request->end_location : null;
        $orders = (isset($request->orders) && !is_null($request->orders)) ? $request->orders : null;
        $start_location_id = (isset($request->start_location_id) && !is_null($request->start_location_id)) ? $request->start_location_id : null;
        if ($start_location_id || $end_location) {
            self::where('id', $id)->update([
                "start_location_id" => $start_location_id,
                "end_location" => $this->setRouteEndLocation($end_location, $orders),
            ]);
        }

        Session::forget('optimized_routes');
        return json_encode(['status' => true, 'data' => '']);
    }

    public function _orderCashSummary($order_id, $route_id)
    {
        $payment = Payment::with('orderBills')->where(['order_id' => $order_id, 'route_id' => $route_id])->get();
        return $payment;
    }

    public function _truckLoaded($route_id)
    {
        Route::where('id',$route_id)->update(['loading_status'=> '1']);
        $order_ids = RouteLocation::where('route_id', $route_id)->select('order_id')->pluck('order_id')->toArray();
        if ($order_ids) {
            $orders = Order::with('order_products.order_options')->where('inventory_status', '0')->whereIn('id', $order_ids)->get();
            foreach ($orders as $order) {
                foreach ($order->order_products as $order_product) {
                    if ($order_product->order_options->isEmpty()) {
                        (new Product())->_decrementProduct($order_product->product_id, $order_product->quantity);
                    } else {
                        foreach ($order_product->order_options as $order_option) {
                            (new Product())->_decrementProductOption($order_option->product_option_value_id, $order_product->quantity);
                        }
                        
                    }
                }

                $order->inventory_status = '1';
                $order->save();

            }
        }
    }
}
