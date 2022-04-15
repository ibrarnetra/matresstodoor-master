<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;

class Subscriber extends Model
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
        $subscriber = self::where('email', $request->email)->first();
        if (!$subscriber) {
            $subscriber = self::create([
                'email' => $request->email,
                'is_subscribed' => (isset($request->is_subscribed) && !is_null($request->is_subscribed)) ? $request->is_subscribed : "1",
            ]);
        }
        return $subscriber;
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            ### INIT QUERY ###
            $subscribers = self::all();

            ### INIT DATATABLE ###
            return Datatables::of($subscribers)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return  '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox" name="id" value="' . $row->id . '" />
                                            </div>';
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('subscribers.update-status', ['id' => $row->id]) . "', '" . $row->is_subscribed . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->is_subscribed == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->is_subscribed == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->is_subscribed == "1" ? "Subscribed" : "Unsubscribed") . '
                                    </a>';
                    return $status;
                })
                ->rawColumns(['checkbox', 'status'])
                ->make(true);
        }
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('SUBSCRIBED')) {
            $new_status = getConstant('UNSUBSCRIBED');
        } else {
            $new_status = getConstant('SUBSCRIBED');
        }

        $update = self::where(['id' => $id])->update(['is_subscribed' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
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

    function _subUnsubToNewsletter($request)
    {
        $success = ['status' => true, 'data' => (isset($request->is_subbed) && !is_null($request->is_subbed) && $request->is_subbed == "1") ? "Successfully subscribed to newsletter" : "Successfully unsubscribed to newsletter"];

        $subscriber = self::where('email', Auth::guard('frontend')->user()->email)->first();
        ($subscriber) ? $subscriber->update(['is_subscribed' => (isset($request->is_subbed) && !is_null($request->is_subbed) && $request->is_subbed == "1") ? "1" : "0"]) : $success = ['status' => false, 'data' => 'Unable to process your request at the moment.'];

        return $success;
    }
}
