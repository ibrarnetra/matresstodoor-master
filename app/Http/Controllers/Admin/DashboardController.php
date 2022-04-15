<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\User;
use App\Models\Admin\Dashboard;
use App\Models\Admin\Country;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function index()
    {
        ### CONST ###
        $path = 'dashboard';
        $title = 'Dashboard';
        $active = "dashboard";

        $user = Auth::guard('web')->user();
        $widgets_data = (new Dashboard())->_getWidgetsData($user, $country_id = '-1');
        $route_summaries = (new Dashboard())->_getRouteSummaries($user);

        $sales_reps = [];
        if (
            $user->hasRole("Super Admin") ||
            $user->hasRole("Office Admin")
        ) {
            $sales_reps = User::where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('status', getConstant('IS_STATUS_ACTIVE'))->get();
        }

        $delivery_reps = [];
        if (
            $user->hasRole("Super Admin") ||
            $user->hasRole("Office Admin") ||
            $user->hasRole("Delivery Manager")
        ) {
            $delivery_reps = User::whereHas("roles", function ($q) {
                $q->where("name", "Delivery Rep");
            })
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
        }

        $sale_rep_stats = [];
        if (count($sales_reps) > 0) {
            foreach ($sales_reps as $sales_rep) {
                $individual_stats = (new Dashboard())->_getWidgetsData($sales_rep, $country_id);
                $temp['full_name'] = $sales_rep->first_name . " " . $sales_rep->last_name;
                $temp['total_orders'] = $individual_stats['total_orders'];
                $temp['total_sales'] = $individual_stats['total_sales'];
                $temp['total_customers'] = $individual_stats['total_customers'];
                $sale_rep_stats[] = $temp;
            }
        }
        $countries = Country::where('is_deleted', getConstant('IS_NOT_DELETED'))
        ->whereIn('name',['India','Canada'])
        ->get();
        // return $sale_rep_stats;
        return view('admin.dashboard.dashboard', compact(
            'title',
            'active',
            'sales_reps',
            'sale_rep_stats',
            'delivery_reps',
            'widgets_data',
            'route_summaries',
            'countries'
        ));
    }

    public function getData(Request $request)
    {
        $user = Auth::guard('web')->user();
        ### PARAMS ###
        $date_range = (isset($request->date_range) && !is_null($request->date_range)) ? $request->date_range : "-1";
        $sales_rep_id = (isset($request->sales_rep_id) && !is_null($request->sales_rep_id)) ? $request->sales_rep_id : "-1";
        $delivery_rep_id = (isset($request->delivery_rep_id) && !is_null($request->delivery_rep_id)) ? $request->delivery_rep_id : "-1";
        $team_member_id = (isset($request->team_member_id) && !is_null($request->team_member_id)) ? $request->team_member_id : "-1";
        $country_id = (isset($request->country_id) && !is_null($request->country_id)) ? $request->country_id : "-1";
       
     
        $sales_reps = [];
        if (
            $user->hasRole("Super Admin") ||
            $user->hasRole("Office Admin")
        ) {
            if($country_id != "-1")
            {
            $sales_reps = User::Where('is_deleted', getConstant('IS_NOT_DELETED'))
               ->where('country_id', $country_id)
                ->get();
            }
            else{
                $sales_reps = User::Where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            }   
        }
        /**
         * sales rep stats
         */
        $sale_rep_stats = [];
        if (count($sales_reps) > 0 && $sales_rep_id == "-1") {
            
            foreach ($sales_reps as $sales_rep) {
                $individual_stats = (new Dashboard())->_getData($sales_rep, $date_range, $sales_rep_id, $team_member_id, $country_id,$store = false);
                $temp['full_name'] = $sales_rep->first_name . " " . $sales_rep->last_name;
                $temp['total_orders'] = $individual_stats['total_orders'];
                $temp['total_sales'] = $individual_stats['total_sales'];
                $temp['total_customers'] = $individual_stats['total_customers'];
                $sale_rep_stats[] = $temp;
            }
        }
  
     
        $res = (new Dashboard())->_getData($user, $date_range, $sales_rep_id, $team_member_id,  $country_id, $status = true);
      
        $sale_rep_stats = view('admin.dashboard.stats', compact('res', 'sale_rep_stats'))->render();
      
        $total_sale_rep_stats = view('admin.dashboard.total_sale_summary', compact('res'))->render();
      
        /**
         * route summary stats
         */
        $route_summaries = (new Dashboard())->_getRouteSummaries($user, $date_range, $delivery_rep_id, $team_member_id);
        $route_summary_stats = view('admin.dashboard.route_summary_stats', compact('route_summaries'))->render();
      
        /**
         * store sale summary stats
         */
        $store_sale_summaries = (new Dashboard())->_getStoreSaleSummary($country_id,$date_range);
        $store_sale_summary_stats = view('admin.dashboard.store_sale_summary_stats', compact('store_sale_summaries'))->render();


        /**
         * store sale summary stats
         */
        $product_option_quantity_summaries = (new Dashboard())->_getProductOptionSummary();
       
        $product_option_quantity_summaries = view('admin.dashboard.product_option_quantity_summaries', compact('product_option_quantity_summaries'))->render();

        /**
         * payment link summary by store stats
         */
        $store_sale_summaries = (new Dashboard())->_getStoreSaleSummary($country_id,$date_range,true);
        $payment_link_summary_stats = view('admin.dashboard.store_sale_summary_stats', compact('store_sale_summaries'))->render();
    
       
        return json_encode(['status' => true, 'data' => [
            'sale_rep_stats' => $sale_rep_stats,
            'route_summary_stats' => $route_summary_stats,
            'store_sale_summary_stats' => $store_sale_summary_stats,
            'product_option_quantity_summaries' =>  $product_option_quantity_summaries,
            'payment_link_summary_stats' => $payment_link_summary_stats,
            'total_sale_rep_stats' => $total_sale_rep_stats
        ]]);
    }
}
