<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DataTables;

class OrderStatus extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function order_history()
    {
        return $this->hasMany(OrderHistory::class);
    }

    function _show($id)
    {
        return self::where('id', $id)->first();
    }

    function _store($request)
    {
        $order_status =  new OrderStatus();
        $order_status->language_id = "1";
        $order_status->name = capAll($request->name);
        $order_status->save();

        return $order_status;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "language_id" => "1",
            "name" => capAll($request->name),
        ]);

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
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
}
