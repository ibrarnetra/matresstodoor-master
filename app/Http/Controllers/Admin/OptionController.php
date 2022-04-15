<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\OptionValue;
use App\Models\Admin\Option;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductOption;

class OptionController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Options')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'options';
            $title = 'Options';

            $options = Option::with([
                'eng_description' => function ($q) {
                    $q->select('option_id', 'language_id', 'name');
                }
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.options.index', compact('menu_1', 'active', 'title', 'options'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Options')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'options';
            $title = 'Create Option';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.options.form', compact('menu_1', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'option_description.*.name' => 'required',
            'option_type' => 'required',
        ]);

        $inserted = (new Option())->_store($request);

        if ($inserted) {
            return redirect()->route('options.index')->with('success', 'Option Added Successfully');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Options')) {
            ### CONST ###
            $menu_1 = 'catalog';
            $active = 'options';
            $title = 'Edit Option';
            $type = 'edit';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))->where('status', getConstant('IS_STATUS_ACTIVE'))->get();

            $modal = (new Option())->fetchData($id);

            return view('admin.options.form', compact('menu_1', 'active', 'title', 'modal', 'type', 'id', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'option_description.*.name' => 'required',
            'option_type' => 'required',
        ]);

        $update = (new Option())->_update($request, $id);

        if ($update) {
            return redirect()->route('options.index')->with('success', 'Option Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted option.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Options')) {
            $products = ProductOption::where('option_id', $id)->count('id');

            if ($products > 0) {
                $res["status"] = false;
                $res["data"] = "Warning: This option cannot be deleted as it is currently assigned to " . $products . " products!";
            } else {
                (new Option())->_destroy($id);
            }
        }

        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Option())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Option())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Option())->_bulkDelete($request);
    }

    public function deleteOptionValue($id)
    {
        return (new OptionValue())->_deleteOptionValue($id);
    }
}
