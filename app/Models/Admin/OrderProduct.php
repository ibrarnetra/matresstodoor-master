<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exception;
use App\Models\Admin\ProductOption;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\Product;
use App\Models\Admin\OrderOption;
use App\Models\Admin\Order;
use App\Models\Admin\Option;
use App\Models\Admin\LoadingSheetItem;

class OrderProduct extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order_options()
    {
        return $this->hasMany(OrderOption::class);
    }

    protected function calculatePrice($calculated_price, $val, $key, $product_id)
    {
        $product_option = ProductOption::where('id', $key)->first(); // get option_id using product_option_id = $key
        $option_name = (new Option())->getOptionsData($val, $key, $product_id, $product_option->option_id);
        ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
        if ($option_name) {
            foreach ($option_name->product_option_values as $option_val) {
                if ($option_val->price_prefix == "+") {
                    $calculated_price += $option_val->price;
                } else {
                    $calculated_price -= $option_val->price;
                }
            }
        }
        return $calculated_price;
    }


    function _insert($cart, $order_id, $method = 'create')
    {
        if ($method == 'edit') {
          

          

        }
        if($order_id)
        {
            OrderOption::where('order_id', $order_id)->delete();
            OrderProduct::where('order_id', $order_id)->delete();
        }
       
        foreach ($cart as $item) {
            try {
                $product_id = $item->id;
                $quantity = $item->quantity;
            } catch (Exception $e) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
            }
            $product = (new Product())->getProduct($product_id);
            $discount = 0;
            if (isset($product->discount) && !is_null($product->discount)) {
                $discount = $product->discount->price;
            }
          
           

            $calculated_price = $product->price;
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
                    $option_name = (new Option())->getOptionsData($product_option_value_id, $product_option_id, $product->id, $product_option->option_id);
                 
                    
                  
                    ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
                    if ($option_name) {
                        foreach ($option_name->product_option_values as $option_val) {
                            if ($option_val->price_prefix == "+") {
                                $calculated_price += $option_val->price;
                            } else {
                                $calculated_price -= $option_val->price;
                            }
                        }
                    }
                }
            }
            else{
                ### SUBTRACT STOCK OF SUBTRACT STOCK = 1 = Yes ###
           
            }

            $order_product = new OrderProduct();
          
            $order_product->order_id = $order_id;
            $order_product->product_id = $product->id;
            $order_product->name = $product->eng_description->name;
            $order_product->quantity = $quantity;
            $order_product->price = $calculated_price - $discount;
            $order_product->total = $quantity * ($calculated_price - $discount);
            $order_product->save();

            $order_product_id = $order_product->id;

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
                            $order_option = new OrderOption();
                            $order_option->order_id = $order_id;
                            $order_option->order_product_id = $order_product_id;
                            $order_option->product_option_id = $product_option_id;
                            $order_option->product_option_value_id = $product_option_value_id;
                            $order_option->name = $name;
                            $order_option->value = $value;
                            $order_option->type = $type;
                            $order_option->save();
                        }
                    } else {
                        ### sample $v = "27-Red" ###
                        $split_val = explode('-', $v);
                        $product_option_value_id = $split_val[0];
                        $value = $split_val[1];
                        $order_option = new OrderOption();
                        $order_option->order_id = $order_id;
                        $order_option->order_product_id = $order_product_id;
                        $order_option->product_option_id = $product_option_id;
                        $order_option->product_option_value_id = $product_option_value_id;
                        $order_option->name = $name;
                        $order_option->value = $value;
                        $order_option->type = $type;
                        $order_option->save();
                    }
                }
            }
        }
    }

    function _incrementOrderProduct($id)
    {
      
        $orderProducts = OrderProduct::doesnthave('order_options')->where('order_id', $id)->get();

       if($orderProducts->count()>0)  
      {    
        foreach($orderProducts as $orderProduct)
        {
           
        
           $product = Product::where('id',$orderProduct->product_id)->first();
            if($product && $product->subtract == '1')
            {
                $updated_qty = $product->quantity +  $orderProduct->quantity;
                $product->quantity = $updated_qty;
                $product->save();
                ### UPDATE STOCK STATUS DEPENDING ON THE UPDATED QTY ###
                if ($updated_qty == 0 || $updated_qty < $product->minimum) {
                    Product::where('id',$product->id)->update(['stock_status_id' => 2]);
                }

            }
        }
       }
    }
}
