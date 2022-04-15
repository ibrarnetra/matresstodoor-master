<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\OptionValueDescription;
use App\Models\Admin\OptionValue;
use App\Models\Admin\OptionDescription;
use App\Models\Admin\Language;

class Option extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(OptionDescription::class, 'option_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(OptionDescription::class, 'option_id', 'id')->where('language_id', '=', '1');
    }

    public function option_values()
    {
        return $this->hasMany(OptionValue::class);
    }

    public function product_option_values()
    {
        return $this->hasMany(ProductOptionValue::class, 'option_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    function _store($request)
    {
        $singletons = ["text", "textarea", "file", "date", "time", "datetime"];
        $option = new Option();
        $option->type = $request->option_type;
        $option->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1";
        $option->save();

        $option_id = $option->id;

        foreach ($request->option_description as $key => $val) {
            $option_description = new OptionDescription();
            $language = (new Language())->getLangByCode($key);

            $option_description->option_id = $option_id;
            $option_description->language_id = $language->id;
            $option_description->name = capAll($val['name']);
            $option_description->save();

            if (!in_array($request->option_type, $singletons)) {
                if (isset($val['data'])) {
                    foreach ($val['data'] as $option_value_data) {
                        $option_value = new OptionValue();
                        $option_value->option_id = $option_id;
                        $option_value->sort_order = (isset($option_value_data['sort_order']) && !is_null($option_value_data['sort_order'])) ? $option_value_data['sort_order'] : "1";
                        if (isset($option_value_data['image'])) {
                            $option_value->image = saveImage($option_value_data['image'], 'option_value_images');
                        }
                        $option_value->save();
                        $option_value_id = $option_value->id;

                        $option_value_description = new OptionValueDescription();
                        $option_value_description->option_value_id = $option_value_id;
                        $option_value_description->language_id = $language->id;
                        $option_value_description->option_id = $option_id;
                        $option_value_description->name = capAll($option_value_data['option_value_name']);
                        $option_value_description->save();
                    }
                }
            }
        }
        return $option_id;
    }

    function _update($request, $id)
    {
        $singletons = ["text", "textarea", "file", "date", "time", "datetime"];
        self::where('id', $id)->update([
            "type" => $request->option_type,
        ]);

        foreach ($request->option_description as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            OptionDescription::where(['option_id' => $id, 'language_id' => $language->id])->update([
                "name" => capAll($val['name']),
            ]);

            if (!in_array($request->option_type, $singletons)) {
                if (isset($val['data'])) {
                    foreach ($val['data'] as $option_value_data) {
                        ### RUN THIS IF A NEW OPTION VALUE IS ADDED ###
                        if (!isset($option_value_data['option_value_id'])) {
                            $option_value = new OptionValue();
                            $option_value->option_id = $id;
                            $option_value->sort_order = (isset($option_value_data['sort_order']) && !is_null($option_value_data['sort_order'])) ? $option_value_data['sort_order'] : "1";
                            if (isset($option_value_data['image'])) {
                                $option_value->image = saveImage($option_value_data['image'], 'option_value_images');
                            }
                            $option_value->save();
                            $option_value_id = $option_value->id;

                            $option_value_description = new OptionValueDescription();
                            $option_value_description->option_value_id = $option_value_id;
                            $option_value_description->language_id = $language->id;
                            $option_value_description->option_id = $id;
                            $option_value_description->name = capAll($option_value_data['option_value_name']);
                            $option_value_description->save();
                        } else {
                            ### RUN THIS TO UPDATE EXISTING OPTION VALUE ###
                            $old_option_value = OptionValue::where('id', $option_value_data['option_value_id'])->first();
                            $image = $old_option_value->image;
                            if (isset($option_value_data['image'])) {
                                if (isset($option_value_data['old_image'])) {
                                    ### REMOVE ORIGINAL ###
                                    if (file_exists(storage_path('app/public/option_value_images/' . $option_value_data['old_image']))) {
                                        unlink(storage_path('app/public/option_value_images/' . $option_value_data['old_image']));
                                    }
                                    ### REMOVE RESIZED ###
                                    if (file_exists(storage_path('app/public/option_value_images/150x150/' . $option_value_data['old_image']))) {
                                        unlink(storage_path('app/public/option_value_images/150x150/' . $option_value_data['old_image']));
                                    }
                                }

                                $image = saveImage($option_value_data['image'], 'option_value_images');
                            }

                            OptionValue::where('id', $option_value_data['option_value_id'])->update([
                                'option_id' => $id,
                                'sort_order' => (isset($option_value_data['sort_order']) && !is_null($option_value_data['sort_order'])) ? $option_value_data['sort_order'] : $old_option_value->sort_order,
                                'image' => $image,
                            ]);

                            OptionValueDescription::where(['option_id' => $id, 'language_id' => $language->id, 'option_value_id' => $option_value_data['option_value_id']])->update([
                                'language_id' => $language->id,
                                'option_id' => $id,
                                'name' => capAll($option_value_data['option_value_name']),
                            ]);
                        }
                    }
                }
            }
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
            "type" => $query->type,
            "sort_order" => $query->sort_order,
            "status" => $query->status,
            "option_description" => (new OptionDescription())->getDescriptionsWithLanguages($id),
        );
    }

    function getOptionsData($product_option_value_id, $product_option_id, $product_id, $option_id)
    {
        return self::select('id', 'type')->with([
            'eng_description' => function ($q) {
                $q->select('name', 'option_id');
            },
            'product_option_values' => function ($q) use ($product_option_value_id, $product_id, $product_option_id) {
                $arr = [];
                if (is_array($product_option_value_id)) {
                    $arr = $product_option_value_id;
                } else {
                    array_push($arr, $product_option_value_id);
                }
                $q->select('id', 'product_option_id', 'product_id', 'option_id', 'option_value_id', 'quantity', 'subtract', 'price', 'price_prefix', 'weight', 'weight_prefix')->with([
                    'eng_description' => function ($q) {
                        $q->select('option_value_id', 'name');
                    }
                ])
                    ->where('product_option_id', $product_option_id)
                    ->where('product_id', $product_id)
                    ->whereIn('id', $arr);
            },
        ])
            ->where('id', $option_id)
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->first();
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
            $options = self::select('id', 'type', 'sort_order', 'status', 'is_deleted')->with([
                'eng_description' => function ($q) {
                    $q->select('option_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($options)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->eng_description->name;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('options.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Options')) {
                        $action .= '<a href="' . route('options.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Options')) {
                        $param = "'" . route('options.delete', ['id' => $row->id]) . "'";
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
