<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\WeightClass;
use App\Models\Admin\TaxClass;
use App\Models\Admin\Store;
use App\Models\Admin\StockStatus;
use App\Models\Admin\Product;
use App\Models\Admin\Option;
use App\Models\Admin\Manufacturer;
use App\Models\Admin\LengthClass;
use App\Models\Admin\Language;
use App\Models\Admin\CustomerGroup;
use App\Models\Admin\Category;
use App\Models\Admin\AttributeGroup;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Products')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'products';
            $title = 'Products';
            return view('admin.products.index', compact('menu_1', 'active', 'title'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Products')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'products';
            $title = 'Create Product';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $length_classes = LengthClass::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $weight_classes = WeightClass::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $attribute_groups = AttributeGroup::with([
                'eng_description',
                'attributes' => function ($q) {
                    $q->with([
                        'eng_description'
                    ])->where('is_deleted', getConstant('IS_NOT_DELETED'));
                },
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $categories = Category::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $options = Option::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $customer_groups = CustomerGroup::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $manufacturers = Manufacturer::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $stock_statuses = StockStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $tax_classes = TaxClass::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            $is_clone = (isset($_GET['type']) && $_GET['type'] == "create") ? true : false;

            return view('admin.products.form', compact('menu_1', 'active', 'title', 'type', 'languages', 'stores', 'length_classes', 'weight_classes', 'stock_statuses', 'attribute_groups', 'manufacturers', 'categories', 'options', 'customer_groups', 'tax_classes', 'is_clone'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'product_description.*.name' => 'required',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric',
            'minimum' => 'required|numeric|min:1',
            'subtract' => 'required',
            'stock_status_id' => 'required|numeric',
            'date_available' => 'required|date',
            'image.*' => 'required|image',
            'thumbnail' => 'required|image',
        ]);

        $inserted = (new Product())->_store($request);

        if ($inserted) {
            return redirect()->route('products.index')->with('success', 'Product Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Products')) {
            $is_clone = (isset($_GET['type']) && $_GET['type'] == "create") ? true : false;

            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'products';
            $title = ($is_clone) ? 'Create Product' : 'Edit Product';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();
            $length_classes = LengthClass::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $weight_classes = WeightClass::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $attribute_groups = AttributeGroup::with([
                'eng_description',
                'attributes',
                'attributes.eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $categories = Category::with([
                'eng_description',
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();
            $options = Option::with([
                'eng_description'
            ])
                ->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->get();

            $customer_groups = CustomerGroup::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $manufacturers = Manufacturer::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $stores = Store::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $stock_statuses = StockStatus::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();
            $tax_classes = TaxClass::where('is_deleted', getConstant('IS_NOT_DELETED'))->get();

            $modal = (new Product())->fetchData($id);

            return view('admin.products.form', compact(
                'menu_1',
                'active',
                'title',
                'modal',
                'type',
                'id',
                'languages',
                'stores',
                'length_classes',
                'weight_classes',
                'stock_statuses',
                'attribute_groups',
                'manufacturers',
                'categories',
                'options',
                'customer_groups',
                'tax_classes',
                'is_clone'
            ));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'product_description.*.name' => 'required',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric',
            'minimum' => 'required|numeric|min:1',
            'subtract' => 'required',
            'stock_status_id' => 'required|numeric',
            'date_available' => 'required|date',
            'image.*' => 'image',
        ]);

        $is_clone = (isset($request->is_clone) && !is_null($request->is_clone)) ? true : false;

        if ($is_clone) {
            $inserted = (new Product())->_store($request);

            if ($inserted) {
                return redirect()->route('products.index')->with('success', 'Product Added Successfully');
            }
        } else {
            $update = (new Product())->_update($request, $id);

            if ($update) {
                return redirect()->route('products.index')->with('success', 'Product Updated Successfully');
            }
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted product.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Products')) {
            $del = (new Product())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Product())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Product())->_dataTable($request);
    }

    public function search(Request $request)
    {
        return (new Product())->_search($request);
    }

    public function loadOptionValue(Request $request)
    {
        return (new Product())->_loadOptionValue($request);
    }

    public function deleteOptionValue(Request $request)
    {
        return (new Product())->_deleteOptionValue($request);
    }

    public function getOptions(Request $request)
    {
        return (new Product())->_getOptions($request);
    }

    public function deleteImage(Request $request)
    {
        return (new Product())->_deleteImage($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Product())->_bulkDelete($request);
    }

    public function createProductForAdminPanel(Request $request)
    {
        // return $request;
        $success = ['status' => true, 'data' => 'Success', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|min:1',
        ]);

        if ($validator->fails()) {
            $err['status'] = false;
            $err['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $error_res = generateValidErrorResponse($validator->errors()->getMessages());
            $err['error'] = $error_res;
            return sendResponse($err);
        }

        $res = (new Product())->_store($request);

        if ($res) {
            $success['product_id'] = $res;
            return sendResponse($success);
        }
    }

    public function editSlug($id)
    {
        $product = Product::where('id', $id)->first();
        $view = view('admin.products.edit_slug', compact('product'))->render();
        return json_encode(['status' => true, 'data' => $view]);
    }

    public function updateSlug(Request $request, $id)
    {
        $response = ['status' => true, 'data' => 'Successfully updated product slug.', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'slug' => 'required|alpha_dash',
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $response['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $response['error'] = generateValidErrorResponse($validator->errors()->getMessages());
        } else {
            $res = (new Product())->_updateSlug($request, $id);

            if (!$res) {
                $response['status'] = false;
                $response['data'] = "Unable to update product slug.";
            }
        }

        return sendResponse($response);
    }
}
