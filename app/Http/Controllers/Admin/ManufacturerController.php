<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Store;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\Language;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class ManufacturerController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Manufacturers')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'manufacturers';
            $title = 'Manufacturers';

            $manufacturers = Manufacturer::with([
                'stores' => function ($q) {
                    $q->select('id', 'name');
                },
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.manufacturers.index', compact('menu_1', 'active', 'title', 'manufacturers'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Manufacturers')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'manufacturers';
            $title = 'Create Manufacturer';
            $type = 'create';

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $manufacturers = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.manufacturers.form', compact('menu_1', 'active', 'title', 'type', 'manufacturers', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required',
            'stores' => 'required',
            'image' => 'image',
        ]);

        $inserted = (new Manufacturer())->_store($request);

        if ($inserted) {
            return redirect()->route('manufacturers.index')->with('success', 'Manufacturer Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Manufacturers')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'manufacturers';
            $title = 'Edit Manufacturer';
            $type = 'edit';

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $modal = (new Manufacturer())->fetchData($id);
            $manufacturers = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->where('id', '!=', $id)
                ->get();

            return view('admin.manufacturers.form', compact('menu_1', 'active', 'title', 'modal', 'type', 'id', 'manufacturers', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        //        return $request;
        $request->validate([
            'name' => 'required',
            'stores' => 'required',
            'image' => 'image',
        ]);

        $update = (new Manufacturer())->_update($request, $id);

        if ($update) {
            return redirect()->route('manufacturers.index')->with('success', 'Manufacturer Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted manufacturer.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Manufacturers')) {
            $del = (new Manufacturer())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function search(Request $request)
    {
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Manufacturer())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Manufacturer())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Manufacturer())->_bulkDelete($request);
    }

    public function editSlug($id)
    {
        $product = Manufacturer::where('id', $id)->first();
        $view = view('admin.manufacturers.edit_slug', compact('product'))->render();
        return json_encode(['status' => true, 'data' => $view]);
    }

    public function updateSlug(Request $request, $id)
    {
        $response = ['status' => true, 'data' => 'Successfully updated manufacturer slug.', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'slug' => 'required|alpha_dash',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $response['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $response['error'] = generateValidErrorResponse($validator->errors()->getMessages());
        } else {
            $res = (new Manufacturer())->_updateSlug($request, $id);

            if (!$res) {
                $response['status'] = false;
                $response['data'] = "Unable to update manufacturer slug.";
            }
        }

        return sendResponse($response);
    }
}
