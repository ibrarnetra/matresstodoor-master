<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\RouteLocation;
use App\Http\Controllers\Controller;

class RouteLocationController extends Controller
{
    public function checkValidityOfOrderList(Request $request)
    {
        $res = ['status' => true, 'data' => []];
        $data = (new RouteLocation())->_checkValidityOfOrderList($request->order_ids);
        if (count($data) > 0) {
            $res['status'] = false;
            foreach ($data as $order) {
                array_push($res['data'], [
                    "order_id" => $order->order_id,
                    "msg" => "This order is assigned to Route: " . $order->route->name . "."
                ]);
            }
        }
        return json_encode($res);
    }

    public function destroy($id)
    {
        $res = ['status' => true, 'data' => 'Successfully deleted Route Location.'];
        $del = (new RouteLocation())->_destroy($id);

        if (!$del) {
            $res["status"] = false;
            $res["data"] = "Error.";
        }
        return json_encode($res);
    }
}
