<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Product;
use App\Models\Admin\Customer;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function _store($request)
    {
        $customer_id = Auth::guard('frontend')->check() ? Auth::guard('frontend')->user()->id : "0";
        $name = Auth::guard('frontend')->check() ? Auth::guard('frontend')->user()->first_name . " " . Auth::guard('frontend')->user()->last_name : $request->name;
        $email = Auth::guard('frontend')->check() ? Auth::guard('frontend')->user()->email : $request->email;

        $review = self::create([
            'customer_id' => $customer_id,
            'product_id' => $request->product_id,
            'name' => $name,
            'email' => $email,
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return $review;
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            ### INIT QUERY ###
            $subscribers = self::with([
                'product' => function ($q) {
                    $q->select('id')->with([
                        'eng_description' => function ($q) {
                            $q->select('product_id', 'language_id', 'name');
                        }
                    ]);
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            ### INIT DATATABLE ###
            return Datatables::of($subscribers)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return  '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox" name="id" value="' . $row->id . '" />
                                            </div>';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('reviews.update-status', ['id' => $row->id]) . "', '" . $row->is_approved . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->is_approved == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->is_approved == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->is_approved == "1" ? "Approved" : "Unapproved") . '
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

        if ($current_status == getConstant('APPROVED')) {
            $new_status = getConstant('UNAPPROVED');
        } else {
            $new_status = getConstant('APPROVED');
        }

        $update = self::where(['id' => $id])->update(['is_approved' => $new_status]);

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
}
