<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Zone;

class Country extends Model
{
    use HasFactory;

    public function zones()
    {
        return $this->hasMany(Zone::class);
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
        $countries = self::where('name', 'like', $q)->get();
        $arr = [];
        if (count($countries) > 0) {
            foreach ($countries as $country) {
                $temp['id'] = $country->id;
                $temp['text'] = $country->name;
                $arr[] = $temp;
            }
        }
        return json_encode(["status" => true, "search" => $arr, 'data' => $countries]);
    }

    function _getRelatedCountries($geozone_id)
    {
        return DB::table('geozone_zone as gzz')
            ->leftJoin('countries as c', function ($q) {
                $q->on('c.id', '=', 'gzz.country_id');
            })->select('gzz.geozone_id', 'c.*')->where('gzz.geozone_id', $geozone_id)->get();
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
