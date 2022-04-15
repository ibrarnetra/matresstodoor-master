<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Admin\Subscriber;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $success = ['status' => true, 'data' => 'Success', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:subscribers',
        ]);


        if ($validator->fails()) {
            $err['status'] = false;
            $err['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $err['error'] = generateValidErrorResponse($validator->errors()->getMessages());
            return sendResponse($err);
        }

        $inserted = (new Subscriber())->_store($request);

        if ($inserted) {
            return sendResponse($success);
        }
    }

    public function subUnsubToNewsletter(Request $request)
    {
        // return $request;
        return (new Subscriber())->_subUnsubToNewsletter($request);
    }
}
