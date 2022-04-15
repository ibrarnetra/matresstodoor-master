<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebNotification extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    function _store($request)
    {
        $web_notification = self::create([
            'notification' => $request->notification,
            'sort_order' => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1",
        ]);

        return $web_notification;
    }

    function _show($id)
    {
        return self::where('id', $id)->first();
    }

    function _update($request, $id)
    {
        $web_notification =  self::where('id', $id)->update([
            'notification' => $request->notification,
            'sort_order' => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1",
        ]);

        return $web_notification;
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
}
