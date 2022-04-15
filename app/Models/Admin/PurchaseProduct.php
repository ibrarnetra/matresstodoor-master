<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Product;
use App\Models\Admin\Purchase;
use App\Models\Admin\PurchaseOption;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\ProductOption;
use App\Models\Admin\Option;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseProduct extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function purchase_options()
    {
        return $this->hasMany(PurchaseOption::class);
    }

    public function purchase_product_option_value()
    {
        return $this->belongsTo(ProductOptionValue::class, 'product_option_value_id', 'id');
    }

    function _insert($cart, $purchase_id, $method = 'create')
    {

        if ($method == 'edit') {
        }
        if ($purchase_id) {
            PurchaseOption::where('purchase_id', $purchase_id)->delete();
            PurchaseProduct::where('purchase_id', $purchase_id)->delete();
        }

        foreach ($cart as $item) {
            try {
                $product_id = $item->id;
                $quantity = $item->quantity;
            } catch (Exception $e) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
            }
            // $product = (new Product())->getProduct($product_id);
            // $discount = 0;
            // if (isset($product->discount) && !is_null($product->discount)) {
            //     $discount = $product->discount->price;
            // }



            $calculated_price = $item['price'];
            $product = (new Product())->getProduct($product_id);

            if (array_key_exists('option', $item)) {
                foreach ($item['option'] as $k => $v) {
                    ### sample $k = "3-Color-radio" ###
                    $split_key = explode('-', $k);
                    $product_option_id = $split_key[0];
                    $product_option_value_id = $v;

                    if (!is_array($v)) {
                        $split_value = explode('-', $v);
                        $product_option_value_id = $split_value[0];
                    }
                    $product_option = ProductOption::where('id', $product_option_id)->first(); // get option_id using product_option_id = $key
                    $option_name = (new Option())->getOptionsData($product_option_value_id, $product_option_id, $product_id, $product_option->option_id);



                    ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
                    // if ($option_name) {
                    //     foreach ($option_name->product_option_values as $option_val) {
                    //         if ($option_val->price_prefix == "+") {
                    //             $calculated_price += $option_val->price;
                    //         } else {
                    //             $calculated_price -= $option_val->price;
                    //         }
                    //     }
                    // }
                }
            } else {

                ### SUBTRACT STOCK OF SUBTRACT STOCK = 1 = Yes ###

            }


            $purchase_product = new PurchaseProduct();
            $purchase_product->purchase_id = $purchase_id;
            $purchase_product->product_id = $product->id;

            $purchase_product->name = $product->eng_description->name;
            $purchase_product->quantity = $quantity;

            $purchase_product->purchase_product_rate = is_null($item['price'])? 0:$item['price'];
            $total_amount =  $item['quantity'] *  $item['price'];
        

            $purchase_product->total_amount = $total_amount;

            $purchase_product->save();
           


            $purchase_product_id = $purchase_product->id;



            ### INSERT IN ORDER OPTIONS ###
            if (array_key_exists('option', $item)) {
                foreach ($item['option'] as $k => $v) {
                    ### sample $k = "3-Color-radio" ###
                    $split_key = explode('-', $k);
                    $product_option_id = $split_key[0];
                    $name = $split_key[1];
                    $type = $split_key[2];
                    if (is_array($v)) {
                        ### sample $v = ["21-Large", "22-Extra Large"] ###
                        foreach ($v as $v_value) {

                            $split_val = explode('-', $v_value);
                            $product_option_value_id = $split_val[0];
                            $value = $split_val[1];
                            $purchase_option = new PurchaseOption();
                            $purchase_option->purchase_id = $purchase_id;
                            $purchase_option->purchase_product_id = $purchase_product_id;
                            $purchase_option->product_option_id = $product_option_id;
                            $purchase_option->product_option_value_id = $product_option_value_id;
                            $purchase_option->name = $name;
                            $purchase_option->value = $value;
                            $purchase_option->type = $type;
                            $purchase_option->save();


                            $productOptionValue = ProductOptionValue::find($product_option_value_id);


                            $old_quantity = $productOptionValue->quantity;
                            $new_quantity = $old_quantity +  $item['quantity'];
                            (new ProductOptionValue())->_updateOptionQuantity($product_option_value_id,  $item);
                            $history = DB::table('purchase_product_quantity_histories')->where(['product_id' => $product['product_id'], 'product_option_value_id' => $product_option_value_id])->orderBy('id', 'desc')->first();


                            if ($history) {


                                DB::table('purchase_product_quantity_histories')->insert([
                                    'old_quantity' => $old_quantity,
                                    'new_quantity' => $new_quantity,
                                    'product_id' =>  $product->id,
                                    'product_option_value_id' => $product_option_value_id,
                                    'purchase_product_id' =>  $purchase_product_id,
                                    'purchase_option_id' =>   $purchase_option->id
                                ]);
                            } else {
                                DB::table('purchase_product_quantity_histories')->insert([
                                    'old_quantity' => $old_quantity,
                                    'new_quantity' => $new_quantity,
                                    'product_id' =>  $product->id,
                                    'product_option_value_id' => $product_option_value_id,
                                    'purchase_product_id' => $purchase_product_id,
                                    'purchase_option_id' =>   $purchase_option->id
                                ]);
                            }
                        }
                    } else {
                        ### sample $v = "27-Red" ###
                        $split_val = explode('-', $v);
                        $product_option_value_id = $split_val[0];
                        $value = $split_val[1];

                        $purchase_option = new PurchaseOption();
                        $purchase_option->purchase_id = $purchase_id;
                        $purchase_option->purchase_product_id = $purchase_product_id;
                        $purchase_option->product_option_id = $product_option_id;
                        $purchase_option->product_option_value_id = $product_option_value_id;
                        $purchase_option->name = $name;
                        $purchase_option->value = $value;
                        $purchase_option->type = $type;
                        $purchase_option->save();

                        $productOptionValue = ProductOptionValue::find($product_option_value_id);
                        $old_quantity = $productOptionValue->quantity;
                        $new_quantity = $old_quantity +  $item['quantity'];
                      
                      

                        (new ProductOptionValue())->_updateOptionQuantity($product_option_value_id,  $item);

                        $history = DB::table('purchase_product_quantity_histories')->where(['product_id' =>  $item['product_id'], 'product_option_value_id' => $product_option_value_id])->orderBy('id', 'desc')->first();

                        if ($history) {


                            DB::table('purchase_product_quantity_histories')->insert([
                                'old_quantity' => $old_quantity,
                                'new_quantity' => $new_quantity,
                                'product_id' => $product->id,
                                'product_option_value_id' => $product_option_value_id,
                                'purchase_product_id' =>  $purchase_product_id,
                                'purchase_option_id' =>   $purchase_option->id
                            ]);
                        } else {

                            DB::table('purchase_product_quantity_histories')->insert([
                                'old_quantity' => $old_quantity,
                                'new_quantity' => $new_quantity,
                                'product_id' => $product->id,
                                'product_option_value_id' => $product_option_value_id,
                                'purchase_product_id' => $purchase_product_id,
                                'purchase_option_id' =>   $purchase_option->id
                            ]);
                        }
                    }
                }
            } else {
                $product = Product::find($product_id);
                $old_quantity = $product->quantity;
                $new_quantity = $old_quantity + $product['quantity'];
                (new Product())->_incrementProduct($product_id,  $item['quantity']);
                DB::table('purchase_product_quantity_histories')->insert([
                    'old_quantity' => $old_quantity,
                    'new_quantity' => $new_quantity,
                    'product_id' =>  $product->id,
                    'purchase_product_id' =>  $purchase_product_id
                ]);
            }
        }
    }
}
