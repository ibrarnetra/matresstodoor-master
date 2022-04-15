<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DataTables;

class Warehouse extends Model
{
    use HasFactory;

    function _store($request)
    {
        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->contact_no = $request->contact_no;
        $warehouse->email_no = $request->email_no;

        $warehouse->save();

        $warehouse_id = $warehouse->id;

        return $warehouse_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'email_no' => $request->email_no
        ]);

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::where('id', $id)
            ->first();


        return array(
            'name' =>  $query->name,
            'address' =>  $query->address,
            'contact_no' =>  $query->contact_no,
            'email_no' =>  $query->email_no
        );
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            $warehouse = self::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($warehouse)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Manufacturers')) {
                        $action .= '<a href="' . route('warehouses.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Manufacturers')) {
                        $param = "'" . route('warehouses.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                        onclick="deleteData(' . $param . ')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>';
                    }
                    return $action;
                })
                ->rawColumns([
                    'name', 'action'
                ])
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

    function _getAllManufacturersForFrontend()
    {
        return self::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
    }
}
