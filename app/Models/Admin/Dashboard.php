<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Route;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\Product;
use App\Models\Admin\User;
use App\Models\Admin\OptionValueDescription;

use App\Models\Admin\Order;

class Dashboard extends Model
{
    use HasFactory;

    /**
     * generic function for base query on page load for dashboard
     */
    protected function orderBaseQuery($current_user, $country_id)
    {
        $base_q = Order::where('is_deleted', getConstant('IS_NOT_DELETED'));
        /**
         * get orders where auth user is not `Super Admin`, `Dispatch Manager` and `Office Admin`
         */
        if (
            !($current_user->hasRole("Super Admin")) &&
            !($current_user->hasRole("Office Admin")) &&
            !($current_user->hasRole("Dispatch Manager"))
        ) {
            /**
             * create created by id list where $created_by_in = logged in user and its team members
             */
            $created_by_in = [];
            array_push($created_by_in, $current_user->id);

            $base_q->whereIn('created_by', $created_by_in);
        }
        /**
         * FETCH ORDERS ASSIGNED TO USER ONLY WHEN USER ROLE IS 'Dispatch Manager' 
         */
        if ($current_user->hasRole("Dispatch Manager")) {
            /**
             * create created by id list where $created_by_in = logged in user and its team members
             */
            $created_by_in = [];
            array_push($created_by_in, $current_user->id);

            $base_q->where(function ($q) use ($created_by_in, $current_user) {
                $q->whereIn('created_by', $created_by_in)
                    ->orWhere('assigned_to', $current_user->id);
            });
        }
        ### COUNTRY FILTER ###
        if ($country_id != '-1') {
            $country_user_ids = User::where('country_id', $country_id)->select('id')->pluck('id')->toArray();
            $base_q->whereIn('created_by', $country_user_ids);
        }
        return $base_q;
    }

    /**
     * generic function for base query on page filter for dashboard
     */
    protected function orderFilterQuery($current_user, $sales_rep_id, $date_range, $split_date, $team_member_id,$country_id, $status)
    {
        $store_id = "-1";
      
        $base_q = Order::where('is_deleted', getConstant('IS_NOT_DELETED'));
        /**
         * get orders where auth user is not `Super Admin`, `Dispatch Manager` and `Office Admin`
         */
        if (
            !($current_user->hasRole("Super Admin")) &&
            !($current_user->hasRole("Office Admin")) &&
            !($current_user->hasRole("Dispatch Manager"))
        ) {
            /**
             * create created by id list where $created_by_in = logged in user and its team members
             */
            $created_by_in = [];
            array_push($created_by_in, $current_user->id);
            /**
             * filter for team_member
             */
            if ($team_member_id !== "-1") {
                $created_by_in = [$team_member_id];
            }
        
            $base_q->whereIn('created_by', $created_by_in);
        }
        /**
         * FETCH ORDERS ASSIGNED TO USER ONLY WHEN USER ROLE IS 'Dispatch Manager' 
         */
        if ($current_user->hasRole("Dispatch Manager")) {
            /**
             * create created by id list where $created_by_in = logged in user and its team members
             */
            $created_by_in = [];
            array_push($created_by_in, $current_user->id);
            /**
             * filter for team_member
             */
            if ($team_member_id !== "-1") {
                $created_by_in = [$team_member_id];
            }

            $base_q->where(function ($q) use ($created_by_in, $current_user) {
                $q->whereIn('created_by', $created_by_in)
                    ->orWhere('assigned_to', $current_user->id);
            });
        }

      
        ### SALES REP FILTER ###
        if ($sales_rep_id != '-1') {
            $base_q->where('created_by', $sales_rep_id);
        }
        if($status == false)
        {
            $base_q->where('created_by', $current_user->id);
        }
        ### DATE RANGER FILTER ###
        if ($date_range != '-1') {
            $base_q->whereRaw('DATE(created_at) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
        }
        ### Store FILTER ###
        if ($store_id != '-1') {
            $base_q->where('store_id', $store_id);
        }
        ### COUNTRY FILTER ###
      
        if ($country_id != '-1') {
            $country_user_ids = User::where('country_id', $country_id)->select('id')->pluck('id')->toArray();
           
            $base_q->whereIn('created_by', $country_user_ids);
        }
        return $base_q;
    }

    /**
     * function to get team members data depending upon Auth user id.
     * $type == init to get `total_orders`, `total_customers` and `total_sales` of team members
     * $type == filter to get filtered `total_orders`, `total_customers` and `total_sales` of team members
     */
    protected function getTeamMembersData($type = 'init', $current_user = [], $date_range = '', $sales_rep_id = '', $team_member_id = '')
    {
        $team_members = [];
        if (count($current_user->team_members) > 0) {
            foreach ($current_user->team_members as $user) {
                $temp['full_name'] = $user->first_name . " " . $user->last_name;
                if ($type == "init") {
                    $data = $this->_getWidgetsData($user, $country_id="-1");
                } else {
                    $data = $this->_getData($user, $date_range, $sales_rep_id, $team_member_id, $country_id="-1");
                }
                $temp['total_orders'] = $data['total_orders'];
                $temp['total_sales'] = $data['total_sales'];
                $temp['total_customers'] = $data['total_customers'];
                $team_members[] = $temp;
            }
        }

        return $team_members;
    }

    function _getWidgetsData($current_user, $country_id)
    {
        // Order Statuses
        $order_status_id_in = OrderStatus::whereNotIn('name', ['Canceled', 'Denied', 'Failed', 'Reversed', 'Chargeback', 'Voided', 'Expired'])->pluck('id');
        // Total Unique Customers of Orders Placed
        $total_customers = $this->orderBaseQuery($current_user, $country_id)->distinct('customer_id')->count('customer_id');
        // Total Orders Sale
        $total_sales = $this->orderBaseQuery($current_user, $country_id)->whereIn('order_status_id', $order_status_id_in)->sum('total');
        // Orders Total
        $total_orders = $this->orderBaseQuery($current_user, $country_id)->count();
        // $sql = $total_orders->toSql();
        // $bindings = $total_orders->getBindings();
        // return [$sql, $bindings];
        $team_members = $this->getTeamMembersData('init', $current_user);

        return ["total_orders" => $total_orders, "total_sales" => $total_sales, "total_customers" => $total_customers, "team_members" => $team_members];
    }

    function _getData($current_user, $date_range, $sales_rep_id, $team_member_id, $country_id , $status = false)
    {
      
        $split_date = [];
        if ($date_range != '-1') {
            $split_date = explode(' to ', $date_range);
        }

        // Order Statuses
        $order_status_id_in = OrderStatus::whereNotIn('name', ['Canceled', 'Denied', 'Failed', 'Reversed', 'Chargeback', 'Voided', 'Expired'])->pluck('id');
        // Total Unique Customers of Orders Placed
        $total_customers = $this->orderFilterQuery($current_user, $sales_rep_id, $date_range, $split_date, $team_member_id,$country_id,$status)->distinct('customer_id')->count('customer_id');
   
        // Total Orders Sale
        $total_sales = $this->orderFilterQuery($current_user, $sales_rep_id, $date_range, $split_date, $team_member_id, $country_id,$status)->whereIn('order_status_id', $order_status_id_in)->sum('total');
        // Orders Total
        $total_orders = $this->orderFilterQuery($current_user, $sales_rep_id, $date_range, $split_date,$team_member_id,$country_id, $status)->count();

        $team_members = $this->getTeamMembersData('filter', $current_user, $date_range, $sales_rep_id, $team_member_id);

        return ["total_orders" => $total_orders, "total_sales" => $total_sales, "total_customers" => $total_customers, "team_members" => $team_members];
    }

    function _getOrderData($user_id)
    {
        return  Order::withCount([
            'order_products'
        ])->with([
            'order_products',
            'order_products.product',
            'order_products.order_options',
            'order_options.product_option_value',
            'order_totals' => function ($q) {
                $q->select('id', 'order_id', 'code', 'title', 'value');
            },
            'currency' => function ($q) {
                $q->select('id', 'title', 'symbol_left', 'symbol_right');
            },
            'order_histories' => function ($q) {
                $q->select('id', 'order_id', 'order_status_id', 'notify', 'comment', 'created_at');
            },
            'order_histories.order_status' => function ($q) {
                $q->select('id', 'name');
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('customer_id', $user_id)->get();
    }

    /**
     * $current_user = current logged in user
     * $date_range = Date Range filter
     * $split_date = Splitted Date Range
     * $team_member_id = Team member filter
     */
    protected function routeSummaryBaseQuery($current_user, $date_range = "-1", $split_date = "-1", $delivery_rep_id = "-1", $team_member_id = "-1")
    {
        $base_q = Route::select('id', 'name', 'created_by', 'assigned_to', 'status', 'is_deleted', 'created_at')->with([
            'route_locations' => function ($q) {
                $q->select('id', 'route_id', 'order_id', 'distance', 'sort_order', 'created_at')->with([
                    'order' => function ($q) {
                        $q->select(
                            'id',
                            'order_status_id',
                            'total',
                            DB::raw("(SELECT remaining_amount FROM `payments` WHERE `payments`.`order_id` = `orders`.`id` ORDER BY `payments`.`id` DESC LIMIT 0,1) AS remaining_amount")
                        )->with([
                            'order_status' => function ($q) {
                                $q->select('id', 'name');
                            }
                        ])
                            ->where('is_deleted', getConstant('IS_NOT_DELETED'));
                    }
                ])->orderBy('sort_order', 'ASC');
            },
            'route_assigned_to' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            }
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'));
        /**
         * get orders where auth user is not `Super Admin`, `Dispatch Manager` and `Office Admin`
         */
        if (
            !($current_user->hasRole("Super Admin")) &&
            !($current_user->hasRole("Office Admin")) &&
            !($current_user->hasRole("Dispatch Manager"))
        ) {
            /**
             * create created by id list where $created_by_in = logged in user and its team members
             */
            $created_by_in = [];
            array_push($created_by_in, $current_user->id);
            /**
             * filter for team_member
             */
            if ($team_member_id !== "-1") {
                $created_by_in = [$team_member_id];
            }

            $base_q->where(function ($q) use ($created_by_in, $current_user) {
                $q->whereIn('created_by', $created_by_in)
                    ->orWhere('assigned_to', $created_by_in);
            });
        }
        /**
         * FETCH ORDERS ASSIGNED TO USER ONLY WHEN USER ROLE IS 'Dispatch Manager' 
         */
        if ($current_user->hasRole("Dispatch Manager")) {
            /**
             * create created by id list where $created_by_in = logged in user and its team members
             */
            $created_by_in = [];
            array_push($created_by_in, $current_user->id);
            /**
             * filter for team_member
             */
            if ($team_member_id !== "-1") {
                $created_by_in = [$team_member_id];
            }

            $base_q->where(function ($q) use ($created_by_in, $current_user) {
                $q->whereIn('created_by', $created_by_in)
                    ->orWhere('assigned_to', $current_user->id);
            });
        }
        ### DELIVERY REP FILTER ###
        if ($delivery_rep_id != '-1') {
            $base_q->where('assigned_to', $delivery_rep_id);
        }
        ### DATE RANGER FILTER ###
        if ($date_range != '-1') {
            $base_q->whereRaw('DATE(dispatch_date) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
        }
        return $base_q;
    }

    function _getRouteSummaries($current_user, $date_range = "-1", $delivery_rep_id = "-1", $team_member_id = "-1")
    {
        $split_date = [];
        if ($date_range != '-1') {
            $split_date = explode(' to ', $date_range);
        }

        $routes = $this->routeSummaryBaseQuery($current_user, $date_range, $split_date, $delivery_rep_id, $team_member_id)->orderBy('id','Desc')->get();

        /**
         * looping over routes
         */
        $route_summaries = [];
        if (count($routes)) {
            foreach ($routes as $route) {
                $total_orders = 0;
                $total_orders_done = 0;
                $total_orders_postponed = 0;
                $total_orders_canceled = 0;
                $total_orders_pending = 0;
                $total_amount = 0;
                $total_paid_amount = 0;
                $total_remaining_amount = 0;

                /**
                 * filtering out nullable orders, we get order relation null when an already route associated order is deleted.
                 */
                $filtered_locations = $route->route_locations->filter(function ($location) {
                    return $location->order != null;
                });

                /**
                 * looping over route locations i.e. orders
                 */
                foreach ($filtered_locations as $route_location) {
                    /**
                     * counting order statuses for a specific route
                     */
                    if ($route_location->order->order_status->name == "Done") {
                        $total_orders_done++;
                    } else if ($route_location->order->order_status->name == "Postpone") {
                        $total_orders_postponed++;
                    } else if ($route_location->order->order_status->name == "Canceled") {
                        $total_orders_canceled++;
                    } else {
                        $total_orders_pending++;
                    }

                    /**
                     * calculating route amounts
                     */
                    $total_amount += $route_location->order->total;
                    $total_remaining_amount += $route_location->order->remaining_amount;
                    $total_paid_amount += ($route_location->order->total - $route_location->order->remaining_amount);
                    $total_orders++;
                }

                $temp['route_id'] = $route->id;
                $temp['route_name'] = $route->name;
                $temp['assigned_to'] = ($route->route_assigned_to) ? $route->route_assigned_to->first_name . " " . $route->route_assigned_to->last_name : "N/A";
                $temp['total_orders'] = $total_orders;
                $temp['total_orders_done'] = $total_orders_done;
                $temp['total_orders_postponed'] = $total_orders_postponed;
                $temp['total_orders_canceled'] = $total_orders_canceled;
                $temp['total_orders_pending'] = $total_orders_pending;
                $temp['total_amount'] = $total_amount;
                $temp['total_paid_amount'] = $total_paid_amount;
                $temp['total_remaining_amount'] = $total_remaining_amount;

                $route_summaries[] = $temp;
            }
        }

        return $route_summaries;
    }

    function _getStoreSaleSummary( $country_id, $date_range = "-1", $payment_link = false)
    {
        

        $split_date = [];
        if ($date_range != '-1') {
            $split_date = explode(' to ', $date_range);
        }

        /**
         * fetching stores
         */
        $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get(['id', 'name']);

        /**
         * lopping over stores
         */
        $store_sale_summaries = [];
        if (count($stores)) {
            foreach ($stores as $store) {
                /**
                 * vars
                 */
                $total_orders = 0;
                $total_orders_done = 0;
                $total_orders_postponed = 0;
                $total_orders_canceled = 0;
                $total_orders_pending = 0;
                $total_amount = 0;
                $total_paid_amount = 0;
                $total_remaining_amount = 0;

                $query = Order::select('id', 'order_status_id', 'total', 'created_at')->with([
                    'order_status' => function ($q) {
                        $q->select('id', 'name');
                    }, 'payments'
                ])
                    ->where('store_id', $store->id)
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'));
                if ($payment_link) {
                    $query->whereNotNull('payment_link');
                }
                if ($date_range != '-1') {
                    $query->whereRaw('DATE(created_at) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
                }

                ### COUNTRY FILTER ###
                if ($country_id != '-1') {
                    $country_user_ids = User::where('country_id', $country_id)->select('id')->pluck('id')->toArray();
                    $query->whereIn('created_by', $country_user_ids);
                }

                $orders = $query->get();

                /**
                 * looping over orders
                 */
                if (count($orders)) {
                    foreach ($orders as $order) {
                        /**
                         * counting order statuses for a specific route
                         */
                        if ($order->order_status->name == "Done") {
                            $total_orders_done++;
                        } else if ($order->order_status->name == "Postpone") {
                            $total_orders_postponed++;
                        } else if ($order->order_status->name == "Canceled") {
                            $total_orders_canceled++;
                        } else {
                            $total_orders_pending++;
                        }

                        /**
                         * calculating route amounts
                         */
                        if ($order->order_status->name != "Canceled") {
                            $total_amount += $order->total;
                        }
                        /**
                         * check to see whether there was a payment entry on `payments` table
                         * if there was a `payment` then the `remaining amount` is the amount `payable`
                         * if there was no `payment` then the `order total` is the amount `payable`
                         */
                        list($payment_exists, $remaining_amount) = getRemainingAmountFromPayments($order->id);


                        $total_remaining_amount += ($payment_exists) ? $remaining_amount : $order->total;
                        foreach ($order->payments as $payment) {
                            if ($payment->method == 'Payment on Counter') {
                                $total_paid_amount += $payment->paid_amount;
                            }
                        }
                        $total_orders++;
                    }
                }

                /**
                 * formatted result set
                 */

                $store_sale_summaries[$store->name] = [
                    'store_id' => $store->id,
                    'total_orders' => $total_orders,
                    'total_orders_done' => $total_orders_done,
                    'total_orders_postponed' => $total_orders_postponed,
                    'total_orders_canceled' => $total_orders_canceled,
                    'total_orders_pending' => $total_orders_pending,
                    'total_amount' => $total_amount,
                    'total_paid_amount' => $total_paid_amount,
                    'total_remaining_amount' => $total_remaining_amount,
                ];
            }
        }

        return $store_sale_summaries;
    }

    function _getProductOptionSummary()
    {
        $optionValueDescriptions = OptionValueDescription::where('option_id', '1')->get();

        $products = Product::with('eng_description', 'product_option_values')->select('id', 'quantity', 'manufacturer_id')->orderBy('manufacturer_id')->orderBy('slug')->where('is_deleted', '0')->get();
        $manufacturers = Manufacturer::select('id', 'name')->where('is_deleted', '0')->where('status', '1')->orderBy('name')->get();

        $productSummary = [];
        foreach ($products as $product) {
            if (!$product->product_option_values->isEmpty()) {
                foreach ($optionValueDescriptions as $optionValueDescription) {
                    foreach ($product->product_option_values as $values) {

                        if ($optionValueDescription->option_value_id == $values->option_value_id) {
                            $product_option_value_id = $values->id;
                            $pendingQuantity = OrderProduct::with('order', 'order_options')->where('product_id', $product->id)->whereHas('order', function ($q) {
                                $q->whereIn('order_status_id', [
                                    '1', '11', '17', '18', '22','25'
                                ])->where('is_deleted', '0')->where('inventory_status', '0');
                            })->whereHas('order_options', function ($q) use ($product_option_value_id) {
                                $q->where('product_option_value_id', $product_option_value_id);
                            })->sum(DB::raw('quantity - return_quantity'));


                            $productSummary[$product->manufacturer_id][$product->id][$values->option_value_id] = ['quantity' => $values->quantity, 'pending_quantity' => $pendingQuantity];
                        }
                    }
                }
            } else {
                $pendingQuantity = OrderProduct::with('order', 'order_options')->where('product_id', $product->id)->whereHas('order', function ($q) {
                    $q->whereIn('order_status_id', [
                        '1', '11', '17', '18', '22','25'
                    ])->where('is_deleted', '0')->where('inventory_status', '0');
                })->doesnthave('order_options')->sum(DB::raw('quantity - return_quantity'));

                $productSummary[$product->manufacturer_id][$product->id][$product->eng_description->name] = ['quantity' => $product->quantity, 'pending_quantity' => $pendingQuantity];
            }
        }
        $allProductSummary = ['products' => $products, 'optionValueDescriptions' => $optionValueDescriptions, 'productSummary' => $productSummary, 'manufacturers' => $manufacturers];
        return $allProductSummary;
    }
}
