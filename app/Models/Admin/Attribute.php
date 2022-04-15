<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Language;
use App\Models\Admin\AttributeDescription;

class Attribute extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(AttributeDescription::class);
    }

    public function eng_description()
    {
        return $this->hasOne(AttributeDescription::class)->where('language_id', '1');
    }

    public function attribute_group()
    {
        return $this->belongsTo(AttributeGroup::class);
    }

    function _store($request)
    {
        $attribute = new Attribute();
        $attribute->attribute_group_id = $request->attribute_group_id;
        $attribute->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order  : "1";

        $attribute->save();

        $attribute_id = $attribute->id;

        foreach ($request->attribute_description as $key => $val) {
            $attribute_description = new AttributeDescription();

            $language = (new Language())->getLangByCode($key);

            $attribute_description->attribute_id = $attribute_id;
            $attribute_description->language_id = $language->id;

            $attribute_description->name = capAll($val['name']);
            $attribute_description->save();
        }

        return $attribute_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "attribute_group_id" => $request->attribute_group_id,
            "sort_order" => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order  : "1",
        ]);

        foreach ($request->attribute_description as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            AttributeDescription::where(['attribute_id' => $id, 'language_id' => $language->id])->update([
                "name" => capAll($val['name']),
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
            "attribute_group_id" => $query->attribute_group_id,
            "sort_order" => $query->sort_order,
            "status" => $query->status,
            "attribute_description" => (new AttributeDescription())->getDescriptionsWithLanguages($id),
        );
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
            $attributes = self::select('id', 'attribute_group_id', 'sort_order', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('attribute_id', 'language_id', 'name');
                },
                'attribute_group' => function ($q) {
                    $q->select('id');
                },
                'attribute_group.eng_description' => function ($q) {
                    $q->select('attribute_group_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($attributes)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('attribute_group_name', function ($row) {
                    return $row->attribute_group->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('attributes.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Attributes')) {
                        $action .= '<a href="' . route('attributes.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Attributes')) {
                        $param = "'" . route('attributes.delete', ['id' => $row->id]) . "'";
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
