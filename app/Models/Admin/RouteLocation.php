<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Route;
use App\Models\Admin\Order;
use App\Models\Admin\OrderStatus;
use App\Models\Admin\LoadingSheet;
use App\Models\Admin\LoadingSheetItem;
use App\Models\Admin\LoadingSheetItemOption;

class RouteLocation extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function route_order_status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }



    function _insert($route_id, $order_id, $order_status_id, $sort_order = '1')
    {
        self::create([
            'route_id' => $route_id,
            'order_id' => $order_id,
            'sort_order' => $sort_order,
            'order_status_id' => $order_status_id
        ]);
    }

    function _checkValidityOfOrderList($orders_in)
    {
        return self::with([
            'route' => function ($q) {
                $q->select('id', 'name');
            }
        ])->whereIn('order_id', $orders_in)->get();
    }

    function _destroy($id)
    {
        $location = self::where('id', $id)->first();
        $loading_sheet = LoadingSheet::where('route_id', $location->route_id)->first();
        if ($loading_sheet) {
            $loadingSheetItem = LoadingSheetItem::where(['loading_sheet_id' => $loading_sheet->id, 'order_id' => $location->order_id])->first();
            if ($loadingSheetItem) {
                LoadingSheetItemOption::where('loading_sheet_item_id', $loadingSheetItem->id)->delete();
            }
            $loadingSheetItem->delete();
        }
        return  $location->delete();
    }

    function _updateRouteOrderStatus($route_id, $order_id, $order_status_id)
    {
        self::where([
            'route_id' => $route_id,
            'order_id' => $order_id
        ])->update(['order_status_id' => $order_status_id]);
    }
}
