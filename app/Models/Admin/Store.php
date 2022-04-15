<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Category;

class Store extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stores';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    function pluckIds($id, $table, $col)
    {
        return DB::table($table)->where($col, $id)->pluck('store_id')->toArray();
    }

    function _show($id)
    {
        return self::where('id', $id)->first();
    }

    function _store($request)
    {
        $store =  new Store();
        $store->name = capAll($request->name);
        $store->manager = $request->manager;
        $store->address = $request->address;
        $store->lat = (isset($request->lat) && !is_null($request->lat)) ? $request->lat : "0.0000";
        $store->lng = (isset($request->lng) && !is_null($request->lng)) ? $request->lng : "0.0000";
        $store->email = $request->email;
        $store->telephone = $request->telephone;
        $store->opening_time = (isset($request->opening_time) && !is_null($request->opening_time)) ? $request->opening_time : null;
        $store->closing_time = (isset($request->closing_time) && !is_null($request->closing_time)) ? $request->closing_time : null;
        $store->save();

        return $store;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "name" => capAll($request->name),
            "manager" => $request->manager,
            "address" => $request->address,
            "lat" => (isset($request->lat) && !is_null($request->lat)) ? $request->lat : "0.0000",
            "lng" => (isset($request->lng) && !is_null($request->lng)) ? $request->lng : "0.0000",
            "email" => $request->email,
            "telephone" => $request->telephone,
            "opening_time" => (isset($request->opening_time) && !is_null($request->opening_time)) ? $request->opening_time : null,
            "closing_time" => (isset($request->closing_time) && !is_null($request->closing_time)) ? $request->closing_time : null,
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
