<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Zone;
use App\Models\Admin\Country;

class Geozone extends Model
{
    use HasFactory;

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'geozone_zone');
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'geozone_zone');
    }

    public function tax_rules()
    {
        return $this->hasMany(TaxRule::class);
    }

    public function tax_rate()
    {
        return $this->hasOne(TaxRate::class);
    }

    protected function _getCountriesAndZones($geozone_id)
    {
        return DB::table('geozone_zone as gzz')
            ->leftJoin('countries as c', function ($q) {
                $q->on('c.id', '=', 'gzz.country_id');
            })
            ->leftJoin('zones as z', function ($q) {
                $q->on('z.id', '=', 'gzz.zone_id');
            })
            ->select('gzz.geozone_id', 'c.id as country_id', 'c.name as country_name', 'z.id as zone_id', 'z.name as zone_name')
            ->where('gzz.geozone_id', $geozone_id)
            ->get();
    }

    function _store($request)
    {
        $geo_zone = new Geozone();
        $geo_zone->name = $request->name;
        $geo_zone->description = $request->description;
        $geo_zone->save();

        $geo_zone_id = $geo_zone->id;

        ### INSERT INTO `geozone_zone` ###
        foreach ($request->geozone as $key => $value) {
            DB::table("geozone_zone")->insert([
                'geozone_id' => $geo_zone_id,
                'country_id' => $value['country'],
                'zone_id' => $value['zone'],
            ]);
        }

        return $geo_zone_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "name" => $request->name,
            "description" => $request->description,
        ]);

        ### INSERT INTO TAX RULE ###
        DB::table('geozone_zone')->where('geozone_id', $id)->delete();
        foreach ($request->geozone as $key => $value) {
            DB::table("geozone_zone")->insert([
                'geozone_id' => $id,
                'country_id' => $value['country'],
                'zone_id' => $value['zone'],
            ]);
        }

        return $id;
    }

    function _show($id)
    {
        $data = self::with([
            'countries' => function ($q) {
                $q->select('id', 'name');
            },
            'countries.zones' => function ($q) {
                $q->select('id', 'name', 'country_id');
            },
            'zones' => function ($q) {
                $q->select('id', 'name');
            },
        ])
            ->where('id', $id)
            ->first();
        return [
            'id' => $data->id,
            'name' => $data->name,
            'countries' => $data->countries,
            'zones' => $data->zones,
            'description' => $data->description,
            'status' => $data->status,
        ];
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
            $geo_zonees = self::select('id', 'value', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('geo_zone_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($geo_zonees)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('geozones.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Geozones')) {
                        $action .= '<a href="' . route('geozones.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Geozones')) {
                        $param = "'" . route('geozones.delete', ['id' => $row->id]) . "'";
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
