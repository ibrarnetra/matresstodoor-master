<?php

namespace App\Models\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DataTables;
use App\Models\Admin\Store;
use App\Models\Admin\Product;

class Manufacturer extends Model
{
    use HasFactory;

    public function stores()
    {
        return $this->belongsToMany(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    function _store($request)
    {
        $manufacturer = new Manufacturer();
        $manufacturer->name = $request->name;
        $manufacturer->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1";

        if ($request->hasFile('image')) {
            $manufacturer->image = saveImage($request->image, 'manufacturer_images');
        }

        $manufacturer->save();

        $manufacturer_id = $manufacturer->id;
        ### GENERATING SLUG ###
        self::where('id', $manufacturer_id)->update([
            'slug' => Str::slug($request->name) . "-" . $manufacturer_id,
        ]);
        ### SYNCING FOR PIVOT ###
        $manufacturer->stores()->sync($request->stores);

        return $manufacturer_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            'name' => $request->name,
            'sort_order' => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1",
        ]);

        if ($request->hasFile('image')) {
            if ($request->hasFile('old_image')) {
                ### REMOVE ORIGINAL ###
                if (file_exists(storage_path('app/public/manufacturer_images/' . $request->old_image))) {
                    unlink(storage_path('app/public/manufacturer_images/' . $request->old_image));
                }
                ### REMOVE RESIZED ###
                if (file_exists(storage_path('app/public/manufacturer_images/150x150/' . $request->old_image))) {
                    unlink(storage_path('app/public/manufacturer_images/150x150/' . $request->old_image));
                }
            }

            $image = saveImage($request->image, 'manufacturer_images');
            self::where('id', $id)->update([
                "image" => $image,
            ]);
        }

        ### SYNCING FOR PIVOT ###
        $manufacturer = Manufacturer::where('id', $id)->first();
        if ($request->has('stores')) {
            $manufacturer->stores()->sync($request->stores);
        } else {
            $manufacturer->stores()->sync([]);
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::select('id', 'name', 'image', 'sort_order', 'status', 'is_deleted')
            ->where('id', $id)
            ->first();

        return array(
            "id" => $query->id,
            "stores" => (new Store())->pluckIds($query->id, 'manufacturer_store', 'Manufacturer_id'),
            "name" => $query->name,
            "image" => $query->image,
            "sort_order" => $query->sort_order,
            "status" => $query->status,
        );
    }

    function _dataTable($request)
    {
        if ($request->ajax()) {
            $manufacturer = self::select('id', 'name', 'image', 'sort_order', 'status', 'is_deleted')->with([
                'stores' => function ($q) {
                    $q->select('id', 'name');
                },
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            return Datatables::of($manufacturer)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('stores', function ($row) {
                    $stores = '';
                    foreach ($row->stores as $store) {
                        $stores .= $store->name . ", ";
                    }
                    return $stores;
                })
                ->addColumn('status', function ($row) {
                    $param = "'" . route('manufacturers.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                    $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                                        onclick="updateStatus(' . $param . ')">
                                       ' . ($row->status == "1" ? "Active" : "Inactive") . '
                                    </a>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (Auth::guard('web')->user()->hasPermissionTo('Edit-Manufacturers')) {
                        $action .= '<a href="' . route('manufacturers.edit', ['id' => $row->id]) . '" class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-dark" data-bs-placement="top" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </a>';
                    }
                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Manufacturers')) {
                        $param = "'" . route('manufacturers.delete', ['id' => $row->id]) . "'";
                        $action .= '<a href="javascript:void(0);" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                        onclick="deleteData(' . $param . ')" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                                        data-bs-placement="top" title="Delete">
                                        <i class="far fa-trash-alt"></i>
                                    </a>';
                    }
                    return $action;
                })
                ->rawColumns([
                    'name', 'stores', 'status', 'action'
                ])
                ->make(true);
        }
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = Manufacturer::where(['id' => $id])->update(['status' => $new_status]);

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

    function _getAllManufacturersForFrontend()
    {
        return self::withCount([
            'products' => function ($q) {
                $q->where('is_deleted', getConstant('IS_NOT_DELETED'))
                    ->where('status', getConstant('IS_STATUS_ACTIVE'));
            }
        ])->where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
    }

    function _updateSlug($request, $id)
    {
        return self::where('id', $id)->update(['slug' => $request->slug . "-" . $id]);
    }
}
