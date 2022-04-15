<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\TaxRule;

class TaxClass extends Model
{
    use HasFactory;

    public function tax_rules()
    {
        return $this->hasMany(TaxRule::class);
    }

    function _store($request)
    {
        $tax_class = new TaxClass();
        $tax_class->title = $request->title;
        $tax_class->description = $request->description;
        $tax_class->save();

        $tax_class_id = $tax_class->id;

        ### INSERT INTO TAX RULE ###
        foreach ($request->tax_rule as $key => $value) {
            (new TaxRule())->_insert($tax_class_id, $value['tax_rate_id'], $value['based'], $value['priority']);
        }

        return $tax_class_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "title" => $request->title,
            "description" => $request->description,
        ]);

        ### INSERT INTO TAX RULE ###
        TaxRule::where('tax_class_id', $id)->delete();
        foreach ($request->tax_rule as $key => $value) {
            (new TaxRule())->_insert($id, $value['tax_rate_id'], $value['based'], $value['priority']);
        }

        return $id;
    }

    function _show($id)
    {
        return self::with([
            'tax_rules',
        ])
            ->where('id', $id)
            ->first();
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
            $tax_classes = self::select('id', 'value', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('tax_class_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($tax_classes)
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

    function _getApplicableTaxClass($country_id, $zone_id)
    {
        return DB::table('tax_classes as tc')
            ->join('tax_rules as trule', function ($q) use ($country_id, $zone_id) {
                $q->on('trule.tax_class_id', '=', 'tc.id')
                    ->join('tax_rates as trate', function ($q) use ($country_id, $zone_id) {
                        $q->on('trate.id', '=', 'trule.tax_rate_id')
                            ->join('geozone_zone as gz', function ($q) use ($country_id, $zone_id) {
                                $q->on('gz.geozone_id', '=', 'trate.geo_zone_id')
                                    ->where('gz.country_id', '=', $country_id)
                                    ->where('gz.zone_id', '=', $zone_id);
                            });
                    })
                    ->where('trule.based', 'shipping')
                    ->orderBy('trule.priority', 'ASC');
            })
            ->where('tc.is_deleted', getConstant('IS_NOT_DELETED'))
            ->select(
                'tc.title as tax_class',
                'trate.rate as tax_rate',
                'trate.type as tax_type',
                'gz.country_id as country_id',
                'gz.zone_id as zone_id',
            )
            ->first();
    }
}
