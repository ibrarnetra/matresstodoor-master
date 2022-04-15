<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;

class TaxRate extends Model
{
    use HasFactory;

    public function customer_groups()
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

    public function tax_rule()
    {
        return $this->hasOne(TaxRule::class);
    }

    public function geozone()
    {
        return $this->belongsTo(Geozone::class, 'geo_zone_id', 'id');
    }

    function _store($request)
    {
        $tax_rate = new TaxRate();
        $tax_rate->name = $request->name;
        $tax_rate->rate = $request->rate;
        $tax_rate->type = $request->type;
        $tax_rate->geo_zone_id = $request->geo_zone_id;
        $tax_rate->save();

        $tax_rate_id = $tax_rate->id;

        ### SYNCING FOR PIVOT ###
        if ($request->has('customer_groups')) {
            $tax_rate->customer_groups()->sync($request->customer_groups);
        }

        return $tax_rate_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "name" => $request->name,
            "rate" => $request->rate,
            "type" => $request->type,
            "geo_zone_id" => $request->geo_zone_id,
        ]);

        ### SYNCING FOR PIVOT ###
        $tax_rate = TaxRate::where('id', $id)->first();
        if ($request->has('customer_groups')) {
            $tax_rate->customer_groups()->sync($request->customer_groups);
        } else {
            $tax_rate->customer_groups()->sync([]);
        }

        return $id;
    }

    function _show($id)
    {
        $query = self::select('id', 'geo_zone_id', 'name', 'rate', 'type', 'status')
            ->with([
                'geozone' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
            ->where('id', $id)
            ->first();

        return array(
            "id" => $query->id,
            "geo_zone_id" => $query->geo_zone_id,
            "geozone" => $query->geozone,
            "name" => $query->name,
            "rate" => $query->rate,
            "type" => $query->type,
            "status" => $query->status,
            "customer_groups" => (new CustomerGroup())->pluckIds($query->id, 'customer_group_tax_rate', 'tax_rate_id'),
        );
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
            $tax_ratees = self::select('id', 'value', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('tax_rate_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($tax_ratees)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('length-classes.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Length-Classes')) {
                        $action .= '<a href="' . route('length-classes.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Length-Classes')) {
                        $param = "'" . route('length-classes.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                        onclick="deleteData(' . $param . ')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>';
                    }
                    return $action;
                })
                ->rawColumns(['name', 'status', 'action'])
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
