<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Language;

class LengthClass extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(LengthClassDescription::class, 'length_class_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(LengthClassDescription::class, 'length_class_id', 'id')->where('language_id', '=', '1');
    }

    function _store($request)
    {
        $length_class = new LengthClass();
        $length_class->value = (isset($request->value) && !is_null($request->value)) ? $request->value : "0.0000";
        $length_class->save();

        $length_class_id = $length_class->id;

        foreach ($request->length_class_description as $key => $val) {
            $length_class_description = new LengthClassDescription();
            $language = (new Language())->getLangByCode($key);

            $length_class_description->length_class_id = $length_class_id;
            $length_class_description->language_id = $language->id;
            $length_class_description->title = capAll($val['title']);
            $length_class_description->unit = $val['unit'];
            $length_class_description->save();
        }
        return $length_class_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "value" => (isset($request->value) && !is_null($request->value)) ? $request->value : "0.0000",
        ]);

        foreach ($request->length_class_description as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            LengthClassDescription::where(['length_class_id' => $id, 'language_id' => $language->id])->update([
                "title" => capAll($val['title']),
                "unit" => $val['unit'],
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
            "value" => $query->value,
            "status" => $query->status,
            "length_class_description" => (new LengthClassDescription())->getDescriptionsWithLanguages($id),
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
            $length_classes = self::select('id', 'value', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('length_class_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($length_classes)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('length-classes.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Length-Classes')) {
                        $action .= '<a href="' . route('length-classes.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Length-Classes')) {
                        $param = "'" . route('length-classes.delete', ['id' => $row->id]) . "'";
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
