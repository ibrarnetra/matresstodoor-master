<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Country;

class Zone extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    function _dataTable($request)
    {
    }

    function _search($request)
    {
        $q = "%" . $request->q . "%";
        $zones = self::where('country_id', $request->country_id)->where('name', 'like', $q)->get();
        $arr = [];
        if (count($zones) > 0) {
            foreach ($zones as $zone) {
                $temp['id'] = $zone->id;
                $temp['text'] = $zone->name;
                $arr[] = $temp;
            }
        }
        return json_encode(["status" => true, "search" => $arr, 'data' => $zones]);
    }

    function _getRelatedZones($geozone_id)
    {
        return DB::table('geozone_zone as gzz')
            ->leftJoin('zones as z', function ($q) {
                $q->on('z.id', '=', 'gzz.zone_id');
            })->select('gzz.geozone_id', 'z.*')->where('gzz.geozone_id', $geozone_id)->get();
    }

    function _getZones($request)
    {
        $country_id = $request->country_id;

        $zones = self::where('country_id', $country_id)->get(['id', 'name as text']);
        return json_encode(['status' => true, 'data' => $zones]);
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
