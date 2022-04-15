<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\WebNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebNotificationController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Web-Notifications')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'web-notifications';
            $title = 'Web Notifications';

            $web_notifications = WebNotification::where('is_deleted', getConstant('IS_NOT_DELETED'))
                ->orderBy('id', 'DESC')
                ->get();

            return view('admin.web_notifications.index', compact('menu_1', 'sub_menu', 'active', 'title', 'web_notifications'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function create()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Add-Web-Notifications')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'web-notifications';
            $title = 'Create Web Notification';
            $type = 'create';

            return view('admin.web_notifications.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'notification' => 'required',
            'sort_order' => 'required',
        ]);

        $res = (new WebNotification())->_store($request);

        if ($res) {
            return redirect()->route('web-notifications.index')->with('success', 'Web notification added successfully');
        }
    }

    public function show($id)
    {
        return (new WebNotification())->_show($id);
    }

    public function edit($id)
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Edit-Web-Notifications')) {
            ### CONST ###
            $menu_1 = 'system';
            $sub_menu = 'localization';
            $active = 'web-notifications';
            $title = 'Edit Web Notification';
            $type = 'edit';
            $modal = (new WebNotificationController())->show($id);

            return view('admin.web_notifications.form', compact('menu_1', 'sub_menu', 'active', 'title', 'type', 'id', 'modal'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'notification' => 'required',
            'sort_order' => 'required',
        ]);

        $res = (new WebNotification())->_update($request, $id);

        if ($res) {
            return redirect()->route('web-notifications.index')->with('success', 'Web notification updated successfully');
        }
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted web notification.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Web-Notifications')) {
            $del = (new WebNotification())->_destroy($id);

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function updateStatus(Request $request, $id)
    {
        return (new WebNotification())->_updateStatus($request, $id);
    }
}
