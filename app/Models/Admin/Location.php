<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Store;
use App\Models\Admin\Setting;

class Location extends Model
{
    use HasFactory;

    function _show($id)
    {
        return self::where('id', $id)->first();
    }

    function _store($request)
    {
        $location =  new Location();
        $location->name = capAll($request->name);
        $location->address = $request->address;
        $location->telephone = $request->telephone;
        $location->geocode = $request->geocode;

        if ($request->hasFile('image')) {
            $location->image = saveImage($request->image, 'store_location_images');
        }

        $location->opening_time = $request->opening_time;
        $location->closing_time = $request->closing_time;
        $location->comment = $request->comment;
        $location->save();

        return $location;
    }

    function _update($request, $id)
    {
        if ($request->hasFile('image')) {
            if ($request->hasFile('old_image')) {
                ### REMOVE ORIGINAL ###
                if (file_exists(storage_path('app/public/store_location_images/' . $request->old_image))) {
                    unlink(storage_path('app/public/store_location_images/' . $request->old_image));
                }
                ### REMOVE RESIZED ###
                if (file_exists(storage_path('app/public/store_location_images/150x150/' . $request->old_image))) {
                    unlink(storage_path('app/public/store_location_images/150x150/' . $request->old_image));
                }
            }

            $image = saveImage($request->image, 'store_location_images');
            self::where('id', $id)->update([
                "image" => $image,
            ]);
        }

        self::where('id', $id)->update([
            "name" => capAll($request->name),
            "address" => $request->address,
            "telephone" => $request->telephone,
            "geocode" => $request->geocode,
            "opening_time" => $request->opening_time,
            "closing_time" => $request->closing_time,
            "comment" => $request->comment,
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
