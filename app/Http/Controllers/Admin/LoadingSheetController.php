<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin\Route;
use App\Models\Admin\LoadingSheetItemOption;
use App\Models\Admin\LoadingSheetItem;
use App\Models\Admin\LoadingSheet;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\OrderOption;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\RouteLocation;
use App\Http\Controllers\Controller;

class LoadingSheetController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->user()->hasPermissionTo('Read-Loading-Sheets')) {
            ### CONST ###
            $menu_1 = 'sales';
            $active = 'loading-sheets';
            $title = 'Loading Sheets';

            $query = LoadingSheet::with([
                'created_by_user' => function ($q) {
                    $q->select('id', 'first_name', 'last_name');
                },
            ]);

            /**
             * check whether the authenticated user has role `Super Admin` then apply the where condition
             */
            if (
                !Auth::guard('web')->user()->hasRole("Super Admin") &&
                !Auth::guard('web')->user()->hasRole("Dispatch Manager") &&
                !Auth::guard('web')->user()->hasRole("Office Admin") &&
                !Auth::guard('web')->user()->hasRole("Delivery Manager")
            ) {
                if (Auth::guard('web')->user()->hasRole("Delivery Rep")) {
                    $routes_in = Route::where('assigned_to', Auth::guard('web')->user()->id)->pluck('id');
                    $query->whereIn('route_id', $routes_in);
                } else {
                    $query->where('created_by', Auth::guard('web')->user()->id);
                }
            }

            $loading_sheets = $query->orderBy('id', 'DESC')
                ->paginate(10);

            return view('admin.loading_sheets.index', compact('menu_1', 'active', 'title', 'loading_sheets'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function store(Request $request)
    {

        // return $request;
        $success = ['status' => true, 'data' => 'Success', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'route_id' => 'required',
        ]);

        if ($validator->fails()) {
            $err['status'] = false;
            $err['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $error_res = generateValidErrorResponse($validator->errors()->getMessages());
            $err['error'] = $error_res;
            return sendResponse($err);
        }

        $res = (new LoadingSheet())->_store($request);

        if ($res) {
            $success['loading_sheet_id'] = $res;
            return sendResponse($success);
        }
    }

    public function detail(Request $request, $id)
    {

        ### CONST ###
        $menu_1 = 'sales';
        $active = 'loading-sheets';
        $title = 'Loading Sheet Detail';

        $loading_sheet = (new LoadingSheet())->_detail($request, $id);
        if ($loading_sheet) {
            $loading_sheet_id = $loading_sheet->id;
            $route_id = $loading_sheet->route_id;
        } else {
            $request->merge(['route_id' => $id]);
            (new LoadingSheet())->_store($request);
            $loading_sheet = (new LoadingSheet())->_routeDetail($request, $id);
            if ($loading_sheet) {
                $loading_sheet_id = $loading_sheet->id;
                $route_id = $loading_sheet->route_id;
            } else {
                $loading_sheet_id = null;
                $route_id = null;
            }
        }

        /**
         * Preventing `order` loading sheet items duplication
         */
        $items = [];
        $item_all = [];
        if ($loading_sheet) {
            if (count($loading_sheet->loading_sheet_items) > 0) {
                for ($i = 1; $i < 6; $i++) {
                    foreach ($loading_sheet->loading_sheet_items as $loading_sheet_item) {
                        $structured = true;
                        /**
                         * generating product name
                         */
                        $product_name = $loading_sheet_item->name;

                        if (count($loading_sheet_item->loading_sheet_item_options) > 0) {

                            foreach ($loading_sheet_item->loading_sheet_item_options as $loading_sheet_item_option) {
                                if ($loading_sheet_item_option->value == 'King' && $i == '1') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Queen' && $i == '2') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Double' && $i == '3') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Single' && $i == '4') {

                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                }
                            }
                        }
                        if ($loading_sheet_item->loading_sheet_item_options->isEmpty() && $i == 5) {
                            $product_name = $loading_sheet_item->name;
                            if (array_search($product_name, array_column($item_all, 'product_name')) !== FALSE) {
                                $item_all[array_search($product_name, array_column($item_all, 'product_name'))]['quantity'] += $loading_sheet_item->quantity;
                            } else {
                                $temps['id'] = $loading_sheet_item->id;
                                $temps['product_name'] =   $product_name;
                                $temps['name'] =  $product_name;
                                $temps['option'] = "";
                                $temps['quantity'] =   $loading_sheet_item->quantity;
                                $item_all[] = $temps;
                            }
                        }

                        // if ($loading_sheet_item->inventory_from == "order") {
                        //     $structured = false;
                        /**
                         * checking whether the product name already exists in multi-dimensional array
                         */

                        // } else {
                        //     $structured = true;
                        // }

                        /**
                         * generating structured result set
                         */
                    }
                }
            }
        }

        // return $items;

        return view('admin.loading_sheets.detail', compact('menu_1', 'item_all', 'active', 'title', 'id', 'loading_sheet_id','route_id'));
    }

    public function routeDetail(Request $request, $id)
    {
        ### CONST ###
        $menu_1 = 'sales';
        $active = 'routes';
        $title = 'Route Loading Sheet Detail';

        $loading_sheet = (new LoadingSheet())->_routeDetail($request, $id);

        if ($loading_sheet) {
            $loading_sheet_id =   $loading_sheet->id;
            $route_id = $loading_sheet->route_id;
        } else {
            $request->merge(['route_id' => $id]);
            (new LoadingSheet())->_store($request);
            $loading_sheet = (new LoadingSheet())->_routeDetail($request, $id);
            if ($loading_sheet) {
                $loading_sheet_id = $loading_sheet->id;
                $route_id = $loading_sheet->route_id;
            } else {
                $loading_sheet_id = null;
                $route_id = null;
            }
        }



        /**
         * Preventing `order` loading sheet items duplication
         */
        $item_all = [];
        if ($loading_sheet) {
            if (count($loading_sheet->loading_sheet_items) > 0) {
                for ($i = 1; $i < 6; $i++) {
                    foreach ($loading_sheet->loading_sheet_items as $loading_sheet_item) {

                        /**
                         * generating product name
                         */
                        $product_name = $loading_sheet_item->name;

                        if (count($loading_sheet_item->loading_sheet_item_options) > 0) {

                            foreach ($loading_sheet_item->loading_sheet_item_options as $loading_sheet_item_option) {
                                if ($loading_sheet_item_option->value == 'King' && $i == '1') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Queen' && $i == '2') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Double' && $i == '3') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Single' && $i == '4') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                }
                            }
                        }
                        if ($loading_sheet_item->loading_sheet_item_options->isEmpty() && $i == 5) {
                            $product_name = $loading_sheet_item->name;
                            if (array_search($product_name, array_column($item_all, 'product_name')) !== FALSE) {
                                $item_all[array_search($product_name, array_column($item_all, 'product_name'))]['quantity'] += $loading_sheet_item->quantity;
                            } else {
                                $temps['id'] = $loading_sheet_item->id;
                                $temps['product_name'] =   $product_name;
                                $temps['name'] =  $product_name;
                                $temps['option'] = "";
                                $temps['quantity'] =   $loading_sheet_item->quantity;
                                $item_all[] = $temps;
                            }
                        }
                    }
                }
            }
        }




        return view('admin.loading_sheets.detail', compact('menu_1', 'active', 'title', 'id', 'item_all', 'loading_sheet_id', 'route_id'));
    }

    public function addItem(Request $request)
    {
        // return $request;
        $success = ['status' => true, 'data' => 'Success', 'error' =>  generateValidErrorResponse([])];

        $validator = Validator::make($request->all(), [
            'product' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            $res['status'] = false;
            $res['data'] = pluckErrorMsg($validator->errors()->getMessages());
            $res['error'] = generateValidErrorResponse($validator->errors()->getMessages());
        }

        return $server_res = (new LoadingSheet())->_addItem($request);

        if ($server_res['status']) {
            $res['data'] = $server_res['data'];
        } else {
            $res['status'] = false;
            $res['data'] = $server_res['data'];
            $res['error'] = $server_res['error'];
        }

        return sendResponse($res);
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->type;
        $res = ['status' => true, 'data' => 'Successfully deleted Loading Sheet Item.'];
        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Loading-Sheets')) {

            if ($type == "loading-sheet") {
                $loading_sheet_item_ids = LoadingSheetItem::where('loading_sheet_id', $id)->pluck('id');
                LoadingSheetItemOption::whereIn('loading_sheet_item_id', $loading_sheet_item_ids)->delete();
                LoadingSheetItem::where('loading_sheet_id', $id)->delete();
                $del = LoadingSheet::where('id', $id)->delete();
            } else {
                LoadingSheetItemOption::where('loading_sheet_item_id', $id)->delete();
                $del = LoadingSheetItem::where('id', $id)->delete();
            }

            if (!$del) {
                $res["status"] = false;
                $res["data"] = "Error.";
            }
        }
        return json_encode($res);
    }

    public function deleteSingleLoadingSheetItem(Request $request, $id)
    {


        $res = ['status' => true, 'data' => 'Successfully deleted Loading Sheet Item.'];

        if (Auth::guard('web')->user()->hasPermissionTo('Delete-Loading-Sheets')) {
            $itemOptions = LoadingSheetItemOption::where('loading_sheet_item_id', $id)->get();
            if ($itemOptions->count()) {
                $sheetItem = LoadingSheetItem::where('id', $id)->first();
                foreach ($itemOptions as $itemOption) {
                    $product = ['product_id' => $sheetItem->product_id, 'quantity' => $sheetItem->quantity];
                    (new ProductOptionValue())->_updateOptionQuantity($itemOption->product_option_value_id, $product);
                    $itemOption->delete();
                }
                $sheetItem->delete();
                if (!$sheetItem) {
                    $res["status"] = false;
                    $res["data"] = "Error.";
                }
            } else {
                $sheetItem = LoadingSheetItem::where('id', $id)->first();
                $products = Product::where('id', $sheetItem->product_id)->first();


                if ($products) {


                    $products->quantity =  $products->quantity +  $sheetItem->quantity;
                    $products->save();
                }
                $sheetItem->delete();
                if (!$sheetItem) {
                    $res["status"] = false;
                    $res["data"] = "Error.";
                }
            }
        }


        return json_encode($res);
    }

    public function routeInventory($route_id)
    {
        ### CONST ###
        $menu_1 = 'sales';
        $active = 'routes';
        $title = 'Route Inventory';

        list($products, $inventories) = (new LoadingSheet())->_routeInventory($route_id);

        return   view('admin.loading_sheets.routeInventory', compact('menu_1', 'active', 'title', 'products', 'inventories'));
    }

    public function manualAddLoadingSheet($id)
    {
        ### CONST ###
        $menu_1 = 'sales';
        $active = 'loading-sheets';
        $title = 'Manual Add Loading Sheet ';
        $loading_sheet =  (new LoadingSheet())->_manualAddLoadingSheet($id);
        if ($loading_sheet) {
            $loading_sheet_id = $loading_sheet->id;
        } else {
            $loading_sheet_id = null;
        }

        $item_all = [];

        if ($loading_sheet) {
            if (count($loading_sheet->loading_sheet_items) > 0) {
                for ($i = 1; $i < 6; $i++) {
                    foreach ($loading_sheet->loading_sheet_items as $loading_sheet_item) {

                        /**
                         * generating product name
                         */
                        $product_name = $loading_sheet_item->name;

                        if (count($loading_sheet_item->loading_sheet_item_options) > 0) {

                            foreach ($loading_sheet_item->loading_sheet_item_options as $loading_sheet_item_option) {
                                if ($loading_sheet_item_option->value == 'King' && $i == '1') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Queen' && $i == '2') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Double' && $i == '3') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                } else if ($loading_sheet_item_option->value == 'Single' && $i == '4') {
                                    $this->sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, $item_all, $loading_sheet_item->id);
                                }
                            }
                        }
                        if ($loading_sheet_item->loading_sheet_item_options->isEmpty() && $i == 5) {
                            $product_name = $loading_sheet_item->name;
                            if (array_search($product_name, array_column($item_all, 'product_name')) !== FALSE) {
                                $item_all[array_search($product_name, array_column($item_all, 'product_name'))]['quantity'] += $loading_sheet_item->quantity;
                            } else {
                                $temps['id'] = $loading_sheet_item->id;
                                $temps['product_name'] =   $product_name;
                                $temps['name'] =  $product_name;
                                $temps['option'] = "";
                                $temps['quantity'] =   $loading_sheet_item->quantity;
                                $item_all[] = $temps;
                            }
                        }
                    }
                }
            }
        }


        return   view('admin.loading_sheets.manual_add', compact('menu_1', 'active', 'title', 'item_all', 'id'));
    }

    protected function sortOptionLoadingSheet($loading_sheet_item, $loading_sheet_item_option, &$item_all, $id)
    {
        $product_name = $loading_sheet_item->name;

        $loading_sheet_item_options = LoadingSheetItemOption::where('loading_sheet_item_id', $loading_sheet_item->id)->get();
        foreach ($loading_sheet_item_options as $loading_sheeting_item_option) {
            $product_name .= "<br> - " . $loading_sheeting_item_option->name . ": " . $loading_sheeting_item_option->value;
        }

        if (array_search($product_name, array_column($item_all, 'product_name')) !== FALSE) {
            $item_all[array_search($product_name, array_column($item_all, 'product_name'))]['quantity'] += $loading_sheet_item->quantity;
        } else {
            $temps['id'] =   $id;
            $temps['product_name'] =   $product_name;
            $temps['name'] = $loading_sheet_item->name;
            $temps['option'] = $loading_sheet_item_option->value;
            $temps['quantity'] =   $loading_sheet_item->quantity;
            $item_all[] = $temps;
        }
    }
}
