<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Store;
use App\Models\Admin\Language;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;


class CategoryController extends Controller
{

    public function index()
    {

        if (Auth::guard('web')->user()->hasPermissionTo('Read-Categories')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'categories';
            $title = 'Categories';

            return view('admin.categories.index', compact('menu_1', 'active', 'title'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Categories')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'categories';
            $title = 'Create Category';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $categories = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.categories.form', compact('menu_1', 'active', 'title', 'type', 'languages', 'categories', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'category_description.*.name' => 'required',
            'category_description.*.description' => 'required',
            'category_description.*.meta_title' => 'required',
            'image' => 'required|image',
            'stores' => 'required',
        ]);

        $inserted = (new Category())->_store($request);

        if ($inserted) {
            return redirect()->route('categories.index')->with('success', 'Category Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Categories')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'categories';
            $title = 'Edit Category';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $categories = Category::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('id', '!=', $id)
                ->get();
            $modal = (new Category())->fetchData($id);

            return view('admin.categories.form', compact('menu_1', 'active', 'title', 'modal', 'type', 'id', 'languages', 'categories', 'stores'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        //        return $request;
        $request->validate([
            'category_description.*.name' => 'required',
            'category_description.*.description' => 'required',
            'stores' => 'required',
        ]);

        $update = (new Category())->_update($request, $id);

        if ($update) {
            return redirect()->route('categories.index')->with('success', 'Category Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res["status"] = false;
        $res["data"] = "You are not authorize for this action";
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Categories')) {
            $res = ['status' => true, 'data' => 'Successfully deleted category.'];
            $del = (new Category())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function search(Request $request)
    {
        return (new Category())->_search($request);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Category())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Category())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Category())->_bulkDelete($request);
    }

    public function editSlug($id)
    {
        $product = Category::where('id', $id)->first();
        $view = view('admin.categories.edit_slug', compact('product'))->render();
        return json_encode(['status' => true, 'data' => $view]);
    }

    public function updateSlug(Request $request, $id)
    {
        $response = ['status' => true, 'data' => 'Successfully updated category slug.', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'slug' => 'required|alpha_dash',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $response['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $response['error'] = generateValidErrorResponse($validator->errors()->getMessages());
        } else {
            $res = (new Category())->_updateSlug($request, $id);

            if (!$res) {
                $response['status'] = false;
                $response['data'] = "Unable to update category slug.";
            }
        }

        return sendResponse($response);
    }
}
