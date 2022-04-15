<?php

namespace App\Http\Controllers\Admin;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Admin\Subscriber;
use App\Http\Controllers\Controller;
use App\Exports\SubscribersExport;


class SubscriberController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Subscribers')) {
            ### CONST ###
            $menu_1 = 'customer-manager';
            $active = 'subscribers';
            $title = 'Subscribers';

            return view('admin.subscribers.index', compact('menu_1', 'active', 'title'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        return (new Subscriber())->_updateStatus($request, $id);
    }

    public function dataTable(Request $request)
    {
        return (new Subscriber())->_dataTable($request);
    }

    public function bulkDelete(Request $request)
    {
        return (new Subscriber())->_bulkDelete($request);
    }

    public function exportExcel()
    {
        return Excel::download(new SubscribersExport(), 'subscribers-' . Carbon::now()->toDateString() . '.xlsx');
    }
}
