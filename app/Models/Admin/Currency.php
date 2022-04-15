<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DataTables;

class Currency extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    function _show($id)
    {
        return self::where('id', $id)->first();
    }

    function _store($request)
    {
        $currency =  new Currency();
        $currency->title = capAll($request->title);
        $currency->code = $request->code;
        $currency->symbol_left = (isset($request->symbol_left) && !is_null($request->symbol_left)) ? $request->symbol_left : "";
        $currency->symbol_right = (isset($request->symbol_right) && !is_null($request->symbol_right)) ? $request->symbol_right : "";
        $currency->decimal_place = (isset($request->decimal_place) && !is_null($request->decimal_place)) ? $request->decimal_place : "2";
        $currency->value = (isset($request->value) && !is_null($request->value)) ? $request->value : "0.00";
        $currency->save();

        return $currency;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "title" => capAll($request->title),
            "code" => $request->code,
            "symbol_left" => (isset($request->symbol_left) && !is_null($request->symbol_left)) ? $request->symbol_left : "",
            "symbol_right" => (isset($request->symbol_right) && !is_null($request->symbol_right)) ? $request->symbol_right : "",
            "decimal_place" => (isset($request->decimal_place) && !is_null($request->decimal_place)) ? $request->decimal_place : "2",
            "value" => (isset($request->value) && !is_null($request->value)) ? $request->value : "0.00",
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
        if ($request->ajax()) {
            $users = self::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get(['id', 'title', 'code', 'value', 'status', 'is_deleted']);

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->rawColumns(['status'])
                ->make(true);
        }
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
