<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Language;
use App\Models\Admin\Faq;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Faqs')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'faqs';
            $title = 'Faqs';

            $faqs = Faq::with([
                'eng_description'
            ])->where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.faqs.index', compact('menu_1', 'sub_menu', 'active', 'title', 'faqs'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Faqs')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'faqs';
            $title = 'Create Faq';
            $type = 'create';

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.faqs.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'faq_description.*.question' => 'required',
            'faq_description.*.answer' => 'required',
        ]);

        $res = (new Faq())->_store($request);

        if ($res) {
            return redirect()->route('faqs.index')->with('success', 'Faq Added Successfully');
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Faqs')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'faqs';
            $title = 'Edit Faq';
            $type = 'edit';

            $modal = (new Faq())->fetchData($id);

            $languages = Language::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->where('status', getConstant('IS_STATUS_ACTIVE'))
                ->get();

            return view('admin.faqs.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal', 'languages'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $faq = Faq::where('id', $id)->first();
        $request->validate([
            'faq_description.*.question' => 'required',
            'faq_description.*.answer' => 'required',
        ]);

        $res = (new Faq())->_update($request, $id);

        if ($res) {
            return redirect()->route('faqs.index')->with('success', 'Faq Updated Successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted Faq.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Faqs')) {
            $del = (new Faq())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Faq())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Faq())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Faq())->_bulkDelete($request);
    }
}
