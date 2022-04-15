<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\OrderProduct;

class OrderOption extends Model
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

    public function order_product()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function product_option_value()
    {
        return $this->belongsTo(ProductOptionValue::class, 'product_option_value_id', 'option_value_id');
    }

    function _insert($products, $order_id, $order_product_id)
    {
        foreach ($products as $product) {
            if (array_key_exists('option', $product)) {
                foreach ($product['option'] as $k => $v) {
                    ### sample $k = "3-Color-radio" ###
                    $split_key = explode('-', $k);
                    $product_option_id = $split_key[0];
                    $name = $split_key[1];
                    $type = $split_key[2];
                    if (is_array($v)) {
                        ### sample $v = ["21-Large", "22-Extra Large"] ###
                        foreach ($v as $item) {
                            $split_val = explode('-', $item);
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

    function _incrementQuantityProductOption($id)
    {

        $orderOptions = OrderOption::with('order_product')->where('order_id', $id)->get();
    
        if ($orderOptions->count() > 0) {
            foreach ($orderOptions as $orderOption) {

                $productValue = ProductOptionValue::where('id', $orderOption->product_option_value_id)->first();
                if ($productValue &&  $productValue->subtract == '1') {
                    if ($orderOption->order_product) {

                        $updated_qty =  $productValue->quantity + $orderOption->order_product->quantity;
                        $productValue->quantity = $updated_qty;
                        $productValue->save();
                    }
                }
            }
        }
        
       
    }
}
