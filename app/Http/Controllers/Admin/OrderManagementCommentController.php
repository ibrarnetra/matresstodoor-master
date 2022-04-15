<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\OrderManagementComment;
use App\Models\Admin\Order;
use App\Http\Controllers\Controller;

class OrderManagementCommentController extends Controller
{
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'commented_by' => 'required',
            'dispatch_manager_id' => 'required',
            'dispatch_comment' => 'required',
        ]);

        ### ASSIGN ORDER TO `Dispatch Manager`  ###
        Order::where('id', $request->order_id)->update(['assigned_to' => $request->dispatch_manager_id]);
        ### ADD COMMENT HISTORY ###
        $inserted = (new OrderManagementComment())->_store($request);
        if ($inserted) {
            return redirect()->back()->with('success', 'Order updated successfully.');
        }
    }
}
