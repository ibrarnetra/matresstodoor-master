<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\PurchaseProduct;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Cart;
use App\Models\Admin\PurchaseCart;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\Product;
use App\Models\Admin\PurchaseOption;
use App\Models\Admin\PurchaseProductQuantityHistory;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;


class Purchase extends Model
{
    use HasFactory;

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function _dataTable($request)
    {
        if ($request->ajax()) {
            ### PARAMS ###

            $date_range = (isset($request->date_range) && !is_null($request->date_range)) ? $request->date_range : "-1";
            $sales_rep_id = (isset($request->sales_rep_id) && !is_null($request->sales_rep_id)) ? $request->sales_rep_id : "-1";
            $warehouse_id = (isset($request->warehouse_id) && !is_null($request->warehouse_id)) ? $request->warehouse_id : "-1";

            if ($date_range != '-1') {
                $split_date = explode(' to ', $request->date_range);
            }

            ### INIT QUERY ###
            $query = self::select(
                'id',
                'warehouse_id',
                'invoice_no',
                'serial_no',
                'purchase_date',
                'created_at',
                'vehicle_no',
                'updated_at',
                'purchase_type',
                'is_deleted',
                'created_by',
                'purchase_total_amount',
                'purchase_total_payable_amount',
                'remarks',
            )
                ->with([
                    'warehouse' => function ($q) {
                        $q->select('id', 'name');
                    },
                ])->where('is_deleted', getConstant('IS_NOT_DELETED'));

            ### FETCH ORDER CREATED BY LOGGED IN USER ONLY WHEN USER ROLE IS NOT 'Super Admin' AND 'Dispatch Manager' ###
            if (
                !(Auth::guard('web')->user()->hasRole("Super Admin")) &&
                !(Auth::guard('web')->user()->hasRole("Office Admin")) &&
                !(Auth::guard('web')->user()->hasRole("Dispatch Manager"))
            ) {

                /**
                 * create created by id list where $created_by_in = logged in user and its team members
                 */

                $created_by_in = Auth::guard('web')->user()->team_members->pluck('id')->toArray();

                array_push($created_by_in, Auth::guard('web')->user()->id);
                /**
                 * filter for team_member
                 */
                // if ($team_member_id !== "-1") {
                //     $created_by_in = [$team_member_id];
                // }

                $query->whereIn('created_by', $created_by_in);
            }

            ### FETCH ORDERS ASSIGNED TO USER ONLY WHEN USER ROLE IS 'Dispatch Manager' ###
            if (Auth::guard('web')->user()->hasRole("Dispatch Manager")) {
                /**
                 * create created by id list where $created_by_in = logged in user and its team members
                 */
                $created_by_in = Auth::guard('web')->user()->team_members->pluck('id')->toArray();
                array_push($created_by_in, Auth::guard('web')->user()->id);
                /**
                 * filter for team_member
                 */
                // if ($team_member_id !== "-1") {
                //     $created_by_in = [$team_member_id];
                // }


                $query->where(function ($q) use ($created_by_in) {
                    $q->whereIn('created_by', $created_by_in);
                });
            }
            ### SALES REP FILTER ###
            if ($sales_rep_id != '-1') {
                $query->where('created_by', $sales_rep_id);
            }
            ### ORDER DATE RANGE FILTER ###
            if ($date_range != '-1') {
                $query->whereRaw('DATE(purchase_date) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
            }
            ### CUSTOMER FILTER ###
            if ($warehouse_id != '-1') {
                $query->where('warehouse_id', $warehouse_id);
            }


            ### RESULT ###
            $purchases = $query->get();

            // $sql = $query->toSql();
            // $bindings = $query->getBindings();
            // return [$sql, $bindings];
            ### INIT DATATABLE ###

            return Datatables::of($purchases)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return  '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox" name="id" value="' . $row->id . '" />
                                            </div>';
                })
                ->addColumn('warehouse_name', function ($row) {
                    $warehouse_name = '';
                    if ($row->warehouse) {
                        $warehouse_name = $row->warehouse->name;
                    }
                    return $warehouse_name;
                })

                ->addColumn('serial_no', function ($row) {
                    $serial_no = 'N/A';
                    if (isset($row->serial_no) && !is_null($row->serial_no) && $row->serial_no != "") {
                        $serial_no = $row->serial_no;
                    }
                    return $serial_no;
                })
                ->addColumn('purchase_total_payable_amount', function ($row) {
                    $purchase_total_payable_amount = '0';
                    if (isset($row->purchase_total_payable_amount) && !is_null($row->purchase_total_payable_amount) && $row->purchase_total_payable_amount != "") {
                        $purchase_total_payable_amount = $row->purchase_total_payable_amount;
                    }
                    return  $purchase_total_payable_amount;
                })
                ->addColumn('purchase_date', function ($row) {
                    return date(getConstant('DATE_FORMAT'), strtotime($row->purchase_date));
                })
                // ->addColumn('status', function ($row) {
                //     $param = "'" . route('orders.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                //     $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                //                         data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                //                         onclick="updateStatus(' . $param . ')">
                //                        ' . ($row->status == "1" ? "Active" : "Inactive") . '
                //                     </a>';
                //     return $status;
                // })
                ->addColumn('action', function ($row) {
                    $action = '<div class="dropdown">
                                    <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle" type="button" id="' . $row->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="' . $row->id . '">';
                    if (Auth::guard('web')->user()->hasPermissionTo('Read-Purchases')) {
                        $action .= '<li>
                                    <a href="' . route('purchases.generateInvoice', ['id' => $row->id]) . '" target="_blank" class="dropdown-item">
                                        <i class="far fa-file-alt me-2"></i> Generate Invoice
                                    </a>
                                </li>';
                    }

                    if (Auth::guard('web')->user()->hasPermissionTo('Read-Purchases')) {
                        $action .= '<li>
                                    <a href="' . route('purchases.detail', ['id' => $row->id]) . '" class="dropdown-item">
                                        <i class="far fa-eye me-2"></i> Detail
                                    </a>
                                </li>';
                    }

                    // $action .= '<li>
                    //                 <a href="' . route('orders.edit', ['id' => $row->id, 'type' => 'create']) . '" class="dropdown-item">
                    //                     <i class="far fa-copy me-2"></i> Clone
                    //                 </a>
                    //             </li>';

                    // if (Auth::guard('web')->user()->hasPermissionTo('Edit-Orders')) {
                    //     $action .= '<li>
                    //                     <a href="' . route('orders.edit', ['id' => $row->id]) . '" class="dropdown-item">
                    //                         <i class="far fa-edit me-2"></i> Edit
                    //                     </a>
                    //                 </li>';
                    // }

                    if (Auth::guard('web')->user()->hasPermissionTo('Delete-Purchases')) {
                        $param = "'" . route('purchases.delete', ['id' => $row->id]) . "'";
                        $action .= '<li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            onclick="deleteData(' . $param . ')">
                                            <i class="far fa-trash-alt me-2"></i> Delete
                                        </a>
                                    </li>';
                    }

                    $action .= '</ul></div>';
                    return $action;
                })
                ->rawColumns([
                    'checkbox',
                    'warehouse_name',
                    'purchase_date',
                    'serial_no',
                    'purchase_total_payable_amount',
                    'action'
                ])
                ->make(true);
        }
    }

    function _addToCart($request)
    {

        $product_id = $request->input('product');
        $warehouse_id = $request->input('warehouse_id');
        $product_qty = $request->input('product_qty');
        $product_unit_price = $request->input('purchase_unit_price');
        $options = $request->input('option') ? $request->input('option') : [];
        $options = $this->sanitizeOptions($options);
        $currency_symbol = $request->input("currency_symbol");

        $res = $res = ['status' => false, 'data' => '', 'index' => 0, 'msg' => ''];

        if ($res['msg'] == "") {

            $cart_id = (new PurchaseCart())->_store($product_id, $warehouse_id, $product_qty, $options,  $product_unit_price);

            if ($cart_id) {
                $carts = (new PurchaseCart())->_all($warehouse_id);


                if (count($carts) > 0) {
                    $cart_item_html = '';
                    $total = 0;
                    $total_units = count($carts);
                    $total_quantity = 0;
                    foreach ($carts as $idx => $cart) {
                        $uuid = $cart['product_id'];
                        $calculated_price = $cart['price'];
                        $total_quantity += $cart['quantity'];
                        $option_arr = [];
                        $options = json_decode($cart['option']);
                        if (count((array)$options) > 0) {
                            foreach ($options as $key => $val) {
                                list($calculated, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                                if (is_array($val)) {
                                    foreach ($val as $value) {
                                        $uuid .= $value;
                                    }
                                } else {
                                    $uuid .= $val;
                                }
                            }
                        }

                        $total += $cart['quantity'] * $calculated_price;
                        $cart_item_html .= view('admin.purchase.add_to_cart', compact('option_arr', 'options', 'idx', 'cart', 'uuid', 'calculated_price', 'currency_symbol'))->render();
                    }
                    $res = ['status' => true, 'data' => $cart_item_html, 'msg' => '', 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity];
                }
            }
        }
        return json_encode($res);
    }
    function _store($request)
    {

        ### INSERT ###
        $purchase = new Purchase();

        $purchase->invoice_no = $this->generateInvoiceNo();



        $purchase->warehouse_id = $request->warehouse_id;
        $purchase->serial_no = $request->serial_no;
        $purchase->vehicle_no = $request->vehicle_no;

        $purchase->purchase_status = 'recieved';
        $purchase->purchase_date = $request->purchase_date;
        $purchase->purchase_discount_amount = "0.00";
        $purchase->purchase_total_payable_amount = "0.00";
        $purchase->purchase_total_payable_amount = "0.00";
        $purchase->purchase_status = "true";
        $purchase->remarks = $request->remarks;
        $purchase->currency_id = $request->currency_id;

        $purchase->created_by = Auth::guard('web')->user()->id;

        $purchase->save();




        $purchase_id = $purchase->id;

        $products = $request->product;
        $total_discount_amount = 0;
        $total_purchase_amount = 0;

        if ($purchase && $products) {
            (new PurchaseProduct())->_insert($products,  $purchase_id);
        }

        $purchase_total_payable_amount = $total_purchase_amount - $total_discount_amount;


        self::where('id', $purchase_id)->update(['purchase_total_amount' => $total_purchase_amount, 'purchase_total_payable_amount' => $purchase_total_payable_amount]);

        Cart::where('warehouse_id', $request->warehouse_id)->delete();
        return $purchase_id;
    }

    function _update($request, $id)
    {


        ### ADD DELIVERY DATE ###


        self::where('id', $id)->update([
            "warehouse_id" => $request->warehouse_id,
            "serial_no" => $request->serial_no,
            "vehicle_no" => $request->vehicle_no,
            "vehicle_no" => $request->vehicle_no,
            "purchase_date" => $request->purchase_date,
            "remarks" => $request->remarks,
            "currency_id" => $request->currency_id,
            "created_by" => Auth::guard('web')->user()->id


        ]);
        $products = $request->product;
        $total_purchase_amount = 0;
        $total_discount_amount = 0;

        if ($products) {
            $purchaseDetails = PurchaseProduct::with('purchase_options')->where('purchase_id', $id)->get();
            foreach ($purchaseDetails as $purchaseDetail) {
                if (isset($purchaseDetail->purchase_options) && $purchaseDetail->purchase_options) {
                    foreach($purchaseDetail->purchase_options as $purchase_option)
                    {
                    (new ProductOptionValue())->_deleteOptionQuantity($purchase_option->product_option_value_id, $purchaseDetail);
                    }
                } else {
                    $single_product = Product::where('id', $purchaseDetail->product_id)->first();

                    if ($single_product) {

                        $single_product->quantity =  $single_product->quantity - $purchaseDetail->quantity;
                        $single_product->save();
                    }
                }

                DB::table('purchase_product_quantity_histories')->where('purchase_product_id', $purchaseDetail->id)->delete();
            }


            PurchaseProduct::where('purchase_id', $id)->delete();
            PurchaseOption::where('purchase_id', $id)->delete();

            if ( $products) {
                (new PurchaseProduct())->_insert($products,  $id);
            }

            $purchase_total_payable_amount = $total_purchase_amount - $total_discount_amount;


            self::where('id', $id)->update(['purchase_total_amount' => $total_purchase_amount, 'purchase_total_payable_amount' => $purchase_total_payable_amount]);

            Cart::where('warehouse_id', $request->warehouse_id)->delete();

            return $id;
        }
    }
    function _getPurchaseDetail($id)
    {

        return self::with([
            'purchase_products' => function ($q) use ($id) {
                $q->where('purchase_id', $id);
            },
            'purchase_products.purchase_options',
            'warehouse',
            'currency' => function ($q) {
                $q->select('id', 'title', 'symbol_left', 'symbol_right');
            },
            'purchase_products.product',
            'purchase_products.purchase_product_option_value'
        ])->where('id', $id)->first();
    }

    function _detail($request, $id)
    {

        return $this->_getPurchaseDetail($id);
    }

    function _generateInvoice($request)
    {

        $purchase = $this->_getPurchaseDetail($request->id);
    
        ### GENERATE PDF ###
        $title = $purchase->invoice_no;
     

        $content = view('admin.purchase.generate_invoice', compact('purchase'))->render();

        generatePdf($content, $title);
    }

    function _purchaseDataTable($request)
    {
        if ($request->ajax()) {
            ### PARAMS ###

            $date_range = (isset($request->purchase_date_range) && !is_null($request->purchase_date_range)) ? $request->purchase_date_range : "-1";
            $product_option_value_id = (isset($request->product_option_value_id) && !is_null($request->product_option_value_id)) ? $request->product_option_value_id : "-1";
            $warehouse_id = (isset($request->warehouse_id) && !is_null($request->warehouse_id)) ? $request->warehouse_id : "-1";
            $id = $request->product_id;
            if ($date_range != '-1') {
                $split_date = explode(' to ', $request->purchase_date_range);
            }


            ### INIT QUERY ###

            $query = PurchaseProductQuantityHistory::with('purchaseProduct.purchase.warehouse', 'purchaseProduct.purchase_options')->where('product_id', $id);
         
 

            ### ORDER DATE RANGE FILTER ###
            if ($date_range != '-1') {
                $query->whereHas('purchaseProduct', function ($q)  use ($split_date) {
                    $q->whereHas('purchase', function ($qe)  use ($split_date) {

                        $qe->whereRaw('DATE(purchase_date) BETWEEN "' . $split_date[0] . '" AND "' . $split_date[1] . '" ');
                    });
                });
            }
          
            ### WAREHOUSE FILTER ###
            if ($warehouse_id != '-1') {
                $query->whereHas('purchaseProduct', function ($q) use ($warehouse_id) {
                    $q->whereHas('purchase', function ($q)  use ($warehouse_id) {
                        $q->where('warehouse_id', $warehouse_id);
                    });
                });
            }
         
      
            ### OPTION FILTER ###
            if ($product_option_value_id != '-1') {
              
                $query->where("product_option_value_id", $product_option_value_id);
               
            }
          



            ### RESULT ###
            $purchases = $query->get();
          

            // $sql = $query->toSql();
            // $bindings = $query->getBindings();
            // return [$sql, $bindings];
            ### INIT DATATABLE ###

            return Datatables::of($purchases)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return  '<div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input multi-dispatch-checkbox" type="checkbox" name="id" value="' . $row->id . '" />
                                            </div>';
                })
                ->addColumn('total_purchase', function ($row) {
                    if (isset($row->new_quantity)) {
                        return $row->new_quantity - $row->old_quantity;
                    }
                })
                ->addColumn('warehouse_name', function ($row) {
                    $warehouse_name = "";
                    if (isset($row->purchaseProduct->purchase->warehouse)) {
                        $warehouse_name = $row->purchaseProduct->purchase->warehouse->name;
                    }
                    return  $warehouse_name;
                })
                ->addColumn('purchase_date', function ($row) {
                    $purchase_date = "";
                    if (isset($row->purchaseProduct->purchase)) {
                        $purchase_date =  date(getConstant('DATE_FORMAT'), strtotime($row->purchaseProduct->purchase->purchase_date));
                    }
                    return  $purchase_date;
                })
                ->addColumn('product_option_value', function ($row) {
                    $product_option_value = "";
                    if (isset($row->product_option_value_id)) {
                        if(isset($row->purchaseProduct->purchase_options) && $row->purchaseProduct->purchase_options)
                        {
                        foreach($row->purchaseProduct->purchase_options as  $purchase_option)
                        {
                            if($purchase_option->product_option_value_id == $row->product_option_value_id)
                            {
                                $product_option_value = $purchase_option->value;
                            }
                        }
                    }
                    }
                    return  $product_option_value;
                })
                ->addColumn('created_by', function ($row) {
                    $created_by = "";
                    if (isset($row->purchaseProduct->purchase)) {
                        $user_id = $row->purchaseProduct->purchase->created_by;
                        $user = User::find($user_id);
                        if ($user) {
                            $created_by = $user->first_name . ' ' . $user->last_name;
                        }
                    }
                    return  $created_by;
                })
                ->addColumn('created_at', function ($row) {
                    $created_at = "";
                    if (isset($row->purchaseProduct)) {
                        $created_at =  date(getConstant('DATE_FORMAT'), strtotime($row->purchaseProduct->created_at));
                    }
                    return   $created_at;
                })

                // ->addColumn('status', function ($row) {
                //     $param = "'" . route('orders.update-status', ['id' => $row->id]) . "', '" . $row->status . "'";
                //     $status = '<a href="javascript:void(0);" class="badge badge-light-' . ($row->status == "1" ? "success" : "danger") . '" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                //                         data-bs-placement="top" title="' . ($row->status == "1" ? "Active" : "Inactive") . '"
                //                         onclick="updateStatus(' . $param . ')">
                //                        ' . ($row->status == "1" ? "Active" : "Inactive") . '
                //                     </a>';
                //     return $status;
                // })

                ->rawColumns([
                    'checkbox',
                    'total_purchase',
                    'warehouse_name',
                    'product_option_value',
                    'purchase_date',
                    'created_by',
                    'created_at'

                ])
                ->make(true);
        }
    }

    function del($id)
    {
        $purchaseDetails = PurchaseProduct::with('purchase_options')->where('purchase_id', $id)->get();
     
        foreach ($purchaseDetails as $purchaseDetail) {
            if (isset($purchaseDetail->purchase_options) && $purchaseDetail->purchase_options) {
                foreach ($purchaseDetail->purchase_options as $purchase_option) {
            
                    (new ProductOptionValue())->_deleteOptionQuantity($purchase_option->product_option_value_id, $purchaseDetail);
                }
            } else {
                $single_product = Product::where('id', $purchaseDetail->product_id)->first();

                if ($single_product) {

                    $single_product->quantity =  $single_product->quantity - $purchaseDetail->quantity;
                    $single_product->save();
                }
            }

            DB::table('purchase_product_quantity_histories')->where('purchase_product_id', $purchaseDetail->id)->delete();
        }


        PurchaseProduct::where('purchase_id', $id)->delete();

        PurchaseOption::where('purchase_id', $id)->delete();
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function _validatePurchaseQty($request)
    {
        $cart_id = $request->input('cart_id');
        $updated_qty = $request->input('updated_qty');
        $customer_id = $request->input('customer_id');
        $warehouse_id = $request->input('warehouse_id');
        $price = $request->input('price');

        $cart = (new PurchaseCart())->_update($cart_id, $updated_qty);
        $res = ['status' => false, 'data' => '', 'msg' => '', 'price' => ''];
        if ($cart) {
            $carts = (new Cart())->_all($customer_id);
            $total = 0;
            $total_units = count($carts);
            $total_quantity = 0;
            foreach ($carts as $item) {
                $calculated_price = $item['price'];
                $total_quantity += $item['quantity'];
                $options = json_decode($item['option']);
                $option_arr = [];
                ### VALIDATE PURCHASE QUANTITY ###
                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $item['product_id']);
                    }
                }
                $total += $item['quantity'] * $calculated_price;
            }
            $res = ['status' => true, 'msg' => 'success', 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity];
            $res['data'] = $cart;
            $res['price'] = $price;
        }
        return json_encode($res);
    }

    function _removeCartItem($request)
    {

        $cart_id = $request->input('id');
        $warehouse_id = $request->input('warehouse_id');
        $total = 0;
        $total_quantity = 0;
        $total_units = 0;


        Cart::where('id', $cart_id)->delete();
        $carts = (new PurchaseCart())->_all($warehouse_id);


        if (count($carts) > 0) {
            $total_units = count($carts);
            foreach ($carts as $cart) {
                $calculated_price = $cart['price'];
                $total_quantity += $cart['quantity'];
                $options = json_decode($cart['option']);
                $option_arr = [];
                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {
                        list($calculated, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);
                    }
                }
                $total += $cart['quantity'] * $calculated_price;
            }
        }
        return json_encode(['status' => true, 'msg' => 'success', 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity]);
    }

    function _generateCartForEdit($request)
    {

        $purchase = $this->_getPurchaseDetail($request->purchase_id);
        $currency_symbol = $request->input('currency_symbol');
        $product_id = '';
        $warehouse_id = $purchase->warehouse_id;
        $product_qty = '';
        $options = [];


        Cart::where('warehouse_id', $warehouse_id)->delete();


        if (count($purchase->purchase_products) > 0) {
            foreach ($purchase->purchase_products as $idx => $purchase_product) {

                $product_id = $purchase_product->product_id;
                $product_qty = $purchase_product->quantity;



                if (isset($purchase_product->purchase_options) && $purchase_product->purchase_options) {
                    $arr = [];
                    foreach ($purchase_product->purchase_options as $purchase_option) {

                        if ($purchase_option->type != 'checkbox') {
                            $options[$purchase_option->product_option_id] = strval($purchase_option->product_option_value_id);
                        } else {
                            array_push($arr, strval($purchase_option->product_option_value_id));
                            $options[$purchase_option->product_option_id] = $arr;
                        }
                    }
                }

                $cart_id = (new PurchaseCart())->_store($product_id, $warehouse_id, $product_qty, $options, $purchase_product->purchase_unit_price);
            }
        }

        $carts = (new PurchaseCart())->_all($warehouse_id);



        if (count($carts) > 0) {
            $cart_item_html = '';
            $total = 0;
            $total_units = count($carts);
            $total_quantity = 0;

            foreach ($carts as $idx => $cart) {
                $uuid = $cart['product_id'];

                $calculated_price = $cart['price'];
                $total_quantity += $cart['quantity'];
                $option_arr = [];
                $options = json_decode($cart['option']);

                if (count((array)$options) > 0) {
                    foreach ($options as $key => $val) {

                        list($calculated, $option_arr) = $this->calculatePriceAndGetOptions($calculated_price, $option_arr, $val, $key, $cart['product_id']);

                        if (is_array($val)) {
                            foreach ($val as $value) {
                                $uuid .= $value;
                            }
                        } else {
                            $uuid .= $val;
                        }
                    }
                }

                $total += $cart['quantity'] * $calculated_price;

                $cart_item_html .= view('admin.purchase.add_to_cart', compact('option_arr', 'options', 'idx', 'cart', 'uuid', 'calculated_price', 'currency_symbol'))->render();
            }
        }

        return json_encode(['status' => true, 'data' => $cart_item_html, 'sub_total' => $total, 'total_units' => $total_units, 'total_quantity' => $total_quantity]);
    }

    private function generateInvoiceNo()
    {
        $invoice_prefix = "Purchase#";
        $id = self::max('id');
        return $invoice_prefix . ($id + 1);
    }


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
}
