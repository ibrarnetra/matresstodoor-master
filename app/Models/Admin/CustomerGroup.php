<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\TaxRate;
use App\Models\Admin\Language;
use App\Models\Admin\CustomerGroupDescription;

class CustomerGroup extends Model
{
    use HasFactory;

    public function tax_rates()
    {
        return $this->hasMany(TaxRate::class);
    }

    public function descriptions()
    {
        return $this->hasMany(CustomerGroupDescription::class);
    }

    public function eng_description()
    {
        return $this->hasOne(CustomerGroupDescription::class)->where('language_id', '1');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    function pluckIds($id, $table, $col)
    {
        return DB::table($table)->where($col, $id)->pluck('customer_group_id')->toArray();
    }

    function _store($request)
    {
        $customer_group = new CustomerGroup();
        $customer_group->approval = $request->approval;
        $customer_group->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1";
        $customer_group->save();

        $customer_group_id = $customer_group->id;

        foreach ($request->customer_group as $key => $val) {
            $customer_group_description = new CustomerGroupDescription();
            $language = (new Language())->getLangByCode($key);

            $customer_group_description->customer_group_id = $customer_group_id;
            $customer_group_description->language_id = $language->id;
            $customer_group_description->name = capAll($val['name']);
            $customer_group_description->description = $val['description'];
            $customer_group_description->save();
        }
        return $customer_group_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "approval" => $request->approval,
            "sort_order" => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1",
        ]);

        foreach ($request->customer_group as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            CustomerGroupDescription::where(['customer_group_id' => $id, 'language_id' => $language->id])->update([
                "name" => capAll($val['name']),
                "description" => $val['description'],
            ]);
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::where('id', $id)->first();
        return array(
            "id" => $query->id,
            "approval" => $query->approval,
            "sort_order" => $query->sort_order,
            "status" => $query->status,
            "customer_group" => (new CustomerGroupDescription())->getDescriptionsWithLanguages($id),
        );
    }

    function getCustomerGroupData($val, $key, $product_id)
    {
        return self::select('id', 'type')->with([
            'customer_group_description' => function ($q) {
                $q->select('name', 'customer_group_id');
            },
            'product_customer_group_values' => function ($q) use ($val, $product_id) {
                $temp = [];
                if (is_array($val)) {
                    $temp = $val;
                } else {
                    array_push($temp, $val);
                }
                $q->select('id', 'product_customer_group_id', 'product_id', 'customer_group_id', 'customer_group_value_id', 'quantity', 'subtract', 'price', 'price_prefix', 'weight', 'weight_prefix')->with([
                    'customer_group_value_description' => function ($q) {
                        $q->select('customer_group_value_id', 'name');
                    }
                ])->where('product_id', $product_id)->whereIn('customer_group_value_id', $temp);
            },
        ])->where('id', $key)->first();
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
            $customer_groups = self::select('id', 'type', 'sort_order', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('customer_group_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($customer_groups)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('customer_groups.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-customer_groups')) {
                        $action .= '<a href="' . route('customer_groups.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-customer_groups')) {
                        $param = "'" . route('customer_groups.delete', ['id' => $row->id]) . "'";
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

    function _getDefaultGroupId()
    {
        $customer_group = self::join('customer_group_descriptions as cgd', function ($q) {
            $q->on('customer_groups.id', '=', 'cgd.customer_group_id');
        })->select(
            'customer_groups.id as id',
            'cgd.name as customer_group_name'
        )
            ->where('customer_groups.is_deleted', getConstant('IS_NOT_DELETED'))
            ->where('cgd.name', 'like', '%Default%')
            ->first();

        return ($customer_group) ? $customer_group->id : 0;
    }
}
