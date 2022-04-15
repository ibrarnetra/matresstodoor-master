<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AuthorizeNet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorizeNetController extends Controller
{
    public function authorizeAndCapture(Request $request)
    {
        return $request;
    }
}
