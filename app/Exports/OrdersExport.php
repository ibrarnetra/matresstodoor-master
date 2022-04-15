<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use App\Models\Admin\Order;
use App\Models\Admin\Option;


class OrdersExport implements  FromView, ShouldAutoSize
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }
 
    public function view(): View
    {

        ### PARAMS ###
        $date_range = (isset($this->request->date_range) && !is_null($this->request->date_range)) ? $this->request->date_range : "-1";
        $delivery_date_range = (isset($this->request->delivery_date_range) && !is_null($this->request->delivery_date_range)) ? $this->request->delivery_date_range : "-1";
        $order_status_id = (isset($this->request->order_status) && !is_null($this->request->order_status)) ? $this->request->order_status : "-1";
        $customer_id = (isset($this->request->customer_id) && !is_null($this->request->customer_id)) ? $this->request->customer_id : "-1";
        $sales_rep = (isset($this->request->sales_rep_id) && !is_null($this->request->sales_rep_id)) ? $this->request->sales_rep_id : "-1";
        $store_id = (isset($this->request->store_id) && !is_null($this->request->store_id)) ? $this->request->store_id : "-1";
        $order_id = (isset($this->request->order_id) && !is_null($this->request->order_id)) ? $this->request->order_id : "-1";
        $city_id = (isset($this->request->city_id) && !is_null($this->request->city_id)) ? $this->request->city_id : "-1";
        $payment_method_id = (isset($this->request->payment_method_id) && !is_null($this->request->payment_method_id)) ? $this->request->payment_method_id : "-1";
        $custom_order = (isset($this->request->custom_order) && !is_null($this->request->custom_order)) ? $this->request->custom_order : "-1";
        $telephone = (isset($this->request->telephone) && !is_null($this->request->telephone)) ? $this->request->telephone : "-1";
        $tax_apply = (isset($this->request->tax_apply) && !is_null($this->request->tax_apply)) ? $this->request->tax_apply : "-1";
        $team_member_id = (isset($this->request->team_member_id) && !is_null($this->request->team_member_id)) ? $this->request->team_member_id : "-1";
        $country_id = (isset($this->request->country_id) && !is_null($this->request->country_id)) ? $this->request->country_id : "-1";
        $delivered_date_range = (isset($this->request->delivered_date_range) && !is_null($this->request->delivered_date_range)) ? $this->request->delivered_date_range : "-1";

        if ($date_range != '-1') {
            $split_date = explode(' to ', $date_range);
        }
        if ($delivery_date_range != '-1') {
            $split_delivery_date = explode(' to ', $delivery_date_range);
        }
        
        if ($delivered_date_range != '-1') {
            $split_delivered_date = explode(' to ', $delivered_date_range);
        }

        ### INIT QUERY ###
        $query = Order::with([
            'order_status' => function ($q) {
                $q->select('id', 'name');
            },
            'order_products' => function ($q) {
            },
            'order_products.product.discount' => function ($q) {
            },
            'order_products.order_options' => function ($q) {
            },
            'order_options.product_option_value' => function ($q) {
            },
        ])
            ->where('is_deleted', getConstant('IS_NOT_DELETED'));

        ### FETCH ORDER CREATED BY LOGGED IN USER ###
        if (
            !(Auth::guard('web')->user()->hasRole("Super Admin")) &&
            !(Auth::guard('web')->user()->hasRole("Office Admin"))
            && !(Auth::guard('web')->user()->hasRole("Dispatch Manager"))
            && !(Auth::guard('web')->user()->hasRole("Accounting"))
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
        } else {
            if ($sales_rep != '-1') {
                $query->where('created_by', $sales_rep);
            }
        }

        if ($store_id != '-1') {
            $query->where('store_id', $store_id);
        }
        ### ORDER DATE RANGE FILTER ###
        if ($date_range != '-1') {
            $query->whereRaw('DATE(created_at) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
        }
        ### CUSTOMER FILTER ###
        if ($customer_id != '-1') {
            $query->where('customer_id', $customer_id);
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

        ### ORDER ID FILTER ###
        if ($order_id != '-1') {
            $query->where('id', $order_id);
        }
        ### CUSTOM ORDER FILTER ###
        if ($custom_order != '-1') {
            $query->where('custom_order', $custom_order);
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

        ### CITY NAME FILTER ###
        ### City FILTER ###
        if ($city_id !== "-1") {
            $query->where('shipping_city', 'LIKE', "%" . $city_id . "%");
        }
         ### COUNTRY FILTER ###
         if ($country_id != '-1') {
            $country_user_ids = User::where('country_id',$country_id)->select('id')->pluck('id')->toArray();
            $query->whereIn('created_by', $country_user_ids);

        }
        ### RESULT ###
        $orders = $query->orderBy('id', 'DESC')
            ->get();

        $options = Option::select('id', 'type', 'sort_order', 'status', 'is_deleted')->with([
            'eng_description' => function ($q) {
                $q->select('option_id', 'language_id', 'name');
            }
        ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->get();

        return view('admin.orders.export_excel', compact('orders', 'options'));
    }

   
}
