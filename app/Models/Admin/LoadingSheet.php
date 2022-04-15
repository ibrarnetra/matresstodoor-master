<?php

namespace App\Models\Admin;

use stdClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\Admin\User;
use App\Models\Admin\RouteLocation;
use App\Models\Admin\Route;
use App\Models\Admin\Product;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\OrderProduct;
use App\Models\Admin\LoadingSheetItemOption;
use App\Models\Admin\LoadingSheetItem;

class LoadingSheet extends Model
{
    use HasFactory;

    private function generateLoadingSheetName($route_id)
    {
        $route = Route::where('id', $route_id)->first();
        $const = "loading-sheet-" . Carbon::now(env('APP_TIMEZONE'))->format('dmY');
        return ($route) ? $route->name . "-" . $const : $const;
    }
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected function sanitizeOptions($options)
    {
        $arr = [];
        if (count((array)$options) > 0) {
            foreach ($options as $key => $val) {
                if ($val == 0) {
                    unset($options[$key]);
                }
            }
            $arr = $options;
        }
        return $arr;
    }

    /**
     * $calculated_price = product price
     * $option_arr = product options selected 
     * $product_option_value_id = `id` in `product_option_values`
     * $product_option_id = `product_option_id` in `product_option_values`
     * $product_id = `product_id` in `product_option_values`
     */
    protected function calculatePriceAndGetOptions($calculated_price, $option_arr, $product_option_value_id, $product_option_id, $product_id)
    {
        $product_option = ProductOption::where('id', $product_option_id)->first(); // get option_id using product_option_id = $key
        $option_name = (new Option())->getOptionsData($product_option_value_id, $product_option_id, $product_id, $product_option->option_id);
        ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
        if ($option_name) {
            foreach ($option_name->product_option_values as $option_val) {
                if ($option_val->price_prefix == "+") {
                    $calculated_price += $option_val->price;
                } else {
                    $calculated_price -= $option_val->price;
                }
            }
            $option_arr[] = $option_name;
        }
        return [$calculated_price, $option_arr];
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function loading_sheet_items()
    {
        return $this->hasMany(LoadingSheetItem::class);
    }

    function _store($request)
    {
        $loading_sheet = self::where('route_id', $request->route_id)->first();
        $order_ids = [];
        if (!$loading_sheet) {
            $loading_sheet = self::create([
                'route_id' => $request->route_id,
                'name' => $this->generateLoadingSheetName($request->route_id),
                'created_by' => Auth::guard('web')->user()->id,
            ]);
        } else {
            $order_ids =  LoadingSheetItem::where('loading_sheet_id', $loading_sheet->id)->where('order_id', '!=', '0')->where('inventory_from', 'order')->delete();
        }

        $route_locations = RouteLocation::where('route_id', $request->route_id)->get();
        if (count($route_locations) > 0) {
            foreach ($route_locations as $location) {
                $order_products = OrderProduct::select('id', 'order_id', 'product_id', 'name', 'quantity', 'price')
                    ->with([
                        'order_options' => function ($q) {
                            $q->select('id', 'order_id', 'order_product_id', 'product_option_value_id', 'name', 'value');
                        }
                    ])
                    ->where('order_id', $location->order_id)
                    ->get();

                if (count($order_products) > 0) {
                    foreach ($order_products as $order_product) {
                        $loading_sheet_item = LoadingSheetItem::create([
                            'loading_sheet_id' => $loading_sheet->id,
                            'order_id' => $location->order_id,
                            'name' => $order_product->name,
                            'quantity' => $order_product->quantity,
                            'price' => $order_product->price,
                            "product_id" => $order_product->product_id
                        ]);

                        if (count($order_product->order_options) > 0) {
                            foreach ($order_product->order_options as $order_option) {
                                $loading_sheet_item_option = LoadingSheetItemOption::create([
                                    'loading_sheet_item_id' => $loading_sheet_item->id,
                                    'product_option_value_id' => $order_option->product_option_value_id,
                                    'name' => $order_option->name,
                                    'value' => $order_option->value,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return $loading_sheet->id;
    }

    function _detail($request, $id)
    {
        $loading_sheet = self::with([
            'loading_sheet_items' => function ($q) {
                $q->with([
                    'loading_sheet_item_options',
                    'order' => function ($q) {
                        $q->select('id', 'first_name', 'last_name')
                            ->where('is_deleted', getConstant('IS_NOT_DELETED'));
                    },
                ]);
            },
        ])
            ->where('id', $id)
            ->first();

        return ($loading_sheet) ? $loading_sheet : null;
    }

    function _routeDetail($request, $id)
    {

        $loading_sheet = self::with([
            'loading_sheet_items' => function ($q) {
                $q->with([
                    'loading_sheet_item_options',
                    'order' => function ($q) {
                        $q->select('id', 'first_name', 'last_name')
                            ->where('is_deleted', getConstant('IS_NOT_DELETED'));
                    },
                ]);
            },
        ])
            ->where('route_id', $id)
            ->first();

        return ($loading_sheet) ? $loading_sheet : null;
    }

    function _addItem($request)
    {
        /**
         * $request params
         * predefined vars
         */
        $server_res = ['status' => false, 'data' => "", 'error' => []];
        $product_id = $request->input('product');
        $loading_sheet_id = $request->input('loading_sheet_id');
        $qty = $request->input('qty');
        $options = $request->input('option') ? $request->input('option') : [];
        $options = $this->sanitizeOptions($options);
        $calculated_price = 0;
        $option_arr = [];
        $new_product_options_dataset = [];
        $new_product_options_value_ids = [];
        $isNew = true;


        /**
         * product query
         */
        $product = Product::select('id', 'price')->with([
            'eng_description' => function ($q) {
                $q->select('product_id', 'language_id', 'name');
            }
        ])
            ->where('id', $product_id)
            ->first();


        /**
         * check whether product_id given exists.
         */
        if ($product) {
            $calculated_price = $product->price;
            /**
             * looping over product options and calculating price and fetching product option, option_value names.
             */
            if (count((array)$options) > 0) {
                foreach ($options as $key => $val) {
                    list($calculated_price, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $product_id);
                }
            }


            /**
             * looping over option_arr and generating a formatted array for comparing in case the product already exists in the loading sheet.
             */
            if (count($option_arr) > 0) {
                foreach ($option_arr as $option) {
                    foreach ($option->product_option_values as $option_value) {
                        $new_product_options_dataset[] = $option->eng_description->name . ":" . $option_value->eng_description->name . ":" . $option_value->id;
                        $new_product_options_value_ids[] = $option_value->id;
                    }
                }
            }


            /**
             * get first loading sheet item where params column values match the given request values.
             */
            $existing_items = LoadingSheetItem::with([
                'loading_sheet_item_options'
            ])
                ->where('loading_sheet_id', $loading_sheet_id)
                ->where('order_id', "0")
                ->where('inventory_from', "warehouse")
                ->where('product_id', $product->id)
                ->get();



            /**
             * if loading sheet item exists
             */
            if (count($existing_items) > 0) {
                /**
                 * loop over all items and check if any one has options matching with the given options.
                 */
                foreach ($existing_items as $item) {
                    $existing_product_options_dataset = [];
                    $existing_product_options_value_ids = [];
                    /**
                     * looping over existing product options and generating a formatted array for comparing.
                     */
                    if (count($item->loading_sheet_item_options) > 0) {
                        foreach ($item->loading_sheet_item_options as $option) {
                            $existing_product_options_dataset[] = $option->name . ":" . $option->value . ":" . $option->product_option_value_id;
                            $existing_product_options_value_ids[] = $option->product_option_value_id;
                        }
                    }


                    /**
                     * serializing existing and new product options to compare them.
                     * if both match then update the product quantity.  
                     */
                    $foo = $new_product_options_value_ids;
                    $bar = $existing_product_options_value_ids;
                    if (array_diff($foo,  $bar) === array_diff($bar,  $foo)) {
                        $isNew = false;
                        $loading_sheet_item = LoadingSheetItem::where('id', $item->id)->first();

                        if ($loading_sheet_item) {
                            $loading_options = LoadingSheetItemOption::where('loading_sheet_item_id', $loading_sheet_item->id)->get();

                            if ($loading_options->count()) {
                                foreach ($loading_options as $loading_option) {
                                    (new Product())->_decrementProductOption($loading_option->product_option_value_id, $qty);
                                }
                            } else {
                                (new Product())->_decrementProduct($loading_sheet_item->product_id, $qty);
                            }
                            $loading_sheet_item->quantity = $item->quantity + $qty;
                            $loading_sheet_item->save();
                        }

                        break;
                    }
                }
            }

            /**
             * runs when the we are trying to add a new item in loading sheet item
             */
            if ($isNew) {
                $loading_sheet_item = LoadingSheetItem::create([
                    'loading_sheet_id' => $loading_sheet_id,
                    'order_id' => "0",
                    'inventory_from' => "warehouse",
                    'name' => $product->eng_description->name,
                    'quantity' => $qty,
                    'price' => $calculated_price,
                    'product_id' => $product->id
                ]);



                if (count($new_product_options_dataset) > 0) {

                    foreach ($new_product_options_dataset as $option) {

                        $split_string = explode(":", $option);


                        $loading_sheet_item_option = LoadingSheetItemOption::create([
                            'loading_sheet_item_id' => $loading_sheet_item->id,
                            'product_option_value_id' => $split_string['2'],
                            'name' => $split_string['0'],
                            'value' => $split_string['1'],
                        ]);

                        (new Product())->_decrementProductOption($split_string['2'], $qty);
                    }
                } else {
                    (new Product())->_decrementProduct($product->id, $qty);
                }
            }

            $server_res['status'] = true;
            $server_res['data'] = "Successfully added item to loading sheet.";
        } else {
            $server_res['data'] = "The product field is invalid.";
            $server_res['error'] = ["The product field is invalid."];
        }
        return $server_res;
        // return [$calculated_price, $option_arr];
    }


    public function _routeInventory($route_id)
    {
        $order_id = RouteLocation::where('route_id', $route_id)->select('order_id')->pluck('order_id')->toArray();
        $orders = Order::with('order_products.order_options')->whereIn('id', $order_id)->where('is_deleted', '0')->get();
        $inventories = [];
        $products = [];
        foreach ($orders as $order) {
            if ($order->order_status_id == '3' || $order->order_status_id == '17') {
                foreach ($order->order_products as $order_product) {

                    $order_option = OrderOption::where('order_product_id', $order_product->id)->get();

                    if ($order_option->count() > 0) {
                        $option_id = "";
                        $option_name = "";
                        foreach ($order_option as $option_order) {
                            $option_id .= $option_order->product_option_value_id;
                            $option_name .= "-" . $option_order->name . ":" . $option_order->value;
                        }
                    } else {
                        $option_id = "0";
                        $option_name = "";
                    }

                    if (isset($inventories[$order_product->product_id][$option_id]) && $inventories[$order_product->product_id][$option_id]) {
                        $return_p = $inventories[$order_product->product_id][$option_id]['return'];
                        $deliver_p = $inventories[$order_product->product_id][$option_id]['delivered'];
                        $pending_p = $inventories[$order_product->product_id][$option_id]['pending'];

                        $inventories[$order_product->product_id][$option_id] = ['delivered' => $deliver_p, 'return' => $return_p + $order_product->quantity, 'pending' => $pending_p];
                    } else {

                        $products[] = ['product_id' => $order_product->product_id, 'option_id' => $option_id, 'option_name' => $option_name, 'product_name' => $order_product->name];
                        $inventories[$order_product->product_id][$option_id] = ['delivered' => '0', 'return' => $order_product->quantity, 'pending' => '0'];
                    }
                }
            } else if ($order->order_status_id == '16' ||  $order->order_status_id == '20') {
                foreach ($order->order_products as $order_product) {

                    $order_option = OrderOption::where('order_product_id', $order_product->id)->get();

                    if ($order_option->count() > 0) {
                        $option_id = "";
                        $option_name = "";
                        foreach ($order_option as $option_order) {
                            $option_id .= $option_order->product_option_value_id;
                            $option_name .= "-" . $option_order->name . ":" . $option_order->value;
                        }
                    } else {
                        $option_id = "0";
                        $option_name = "";
                    }

                    if (isset($inventories[$order_product->product_id][$option_id]) && $inventories[$order_product->product_id][$option_id]) {

                        $return_p = $inventories[$order_product->product_id][$option_id]['return'];
                        $deliver_p = $inventories[$order_product->product_id][$option_id]['delivered'];
                        $pending_p = $inventories[$order_product->product_id][$option_id]['pending'];

                        $inventories[$order_product->product_id][$option_id] = ['delivered' => $deliver_p + $order_product->quantity - $order_product->return_quantity, 'return' => $return_p + $order_product->return_quantity, 'pending' => $pending_p];
                    } else {

                        $products[] = ['product_id' => $order_product->product_id, 'option_id' => $option_id, 'option_name' => $option_name, 'product_name' => $order_product->name];
                        $inventories[$order_product->product_id][$option_id] = ['delivered' => $order_product->quantity - $order_product->return_quantity, 'return' => $order_product->return_quantity, 'pending' => "0"];
                    }
                }
            } else if ($order->order_status_id == '11' || $order->order_status_id == '1') {
                foreach ($order->order_products as $order_product) {

                    $order_option = OrderOption::where('order_product_id', $order_product->id)->get();
                    if ($order_option->count() > 0) {
                        $option_id = "";
                        $option_name = "";
                        foreach ($order_option as $option_order) {
                            $option_id .= $option_order->product_option_value_id;
                            $option_name .= "-" . $option_order->name . ":" . $option_order->value;
                        }
                    } else {
                        $option_id = "0";
                        $option_name = "";
                    }

                    if (isset($inventories[$order_product->product_id][$option_id]) && $inventories[$order_product->product_id][$option_id]) {

                        $return_p = $inventories[$order_product->product_id][$option_id]['return'];
                        $deliver_p = $inventories[$order_product->product_id][$option_id]['delivered'];
                        $pending_p = $inventories[$order_product->product_id][$option_id]['pending'];

                        $inventories[$order_product->product_id][$option_id] = ['delivered' => $deliver_p, 'return' => $return_p, 'pending' => $pending_p + $order_product->quantity];
                    } else {

                        $products[] = ['product_id' => $order_product->product_id, 'option_id' => $option_id, 'option_name' => $option_name, 'product_name' => $order_product->name];
                        $inventories[$order_product->product_id][$option_id] = ['delivered' => "0", 'return' => '0', 'pending' => $order_product->quantity];
                    }
                }
            }
        }

        return [$products, $inventories];
    }

    function _manualAddLoadingSheet($id)
    {
        $loading_sheet = self::with([
            'loading_sheet_items' => function ($q) {
                $q->with([
                    'loading_sheet_item_options'
                ])
                    ->where('order_id', '0')
                    ->where('inventory_from', 'warehouse');
            },
        ])
            ->where('id', $id)
            ->first();

        return ($loading_sheet) ? $loading_sheet : null;
    }
}
