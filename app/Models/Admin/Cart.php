<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\Product;

class Cart extends Model
{
    use HasFactory;

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    function _store($product_id, $customer_id, $product_qty, $options)
    {
        $cart = new Cart();
        $cart->customer_id = $customer_id;
        $cart->product_id = $product_id;
        $cart->option = json_encode($options);
        $cart->quantity = $product_qty;
        $cart->save();

        return $cart->id;
    }

    function _update($cart_id, $updated_qty)
    {
        self::where('id', $cart_id)->update(['quantity' => $updated_qty]);

        return self::with([
            'product' => function ($q) {
                $q->select('id', 'price', 'minimum')->with([
                    'eng_description' => function ($q) {
                        $q->select('product_id', 'name');
                    }
                ]);
            }
        ])->where('id', $cart_id)->first();
    }

    function _all($customer_id)
    {
        return self::with([
            'product' => function ($q) {
                $q->select('id', 'price', 'minimum', 'quantity', 'subtract')->with([
                    'eng_description' => function ($q) {
                        $q->select('product_id', 'name');
                    }
                ]);
            }
        ])->where('customer_id', $customer_id)->get()->toArray();
    }

    function _addToCart($request, $slug)
    {
        $product = (new Product())->_getProductWithSlug($slug);
        $options = $request->input('option') ? $request->input('option') : [];
        $options = $this->sanitizeOptions($options);

        if ($product) {
            $uuid = $product->id;
            $price = (isset($product->discount) && !is_null($product->discount)) ? $product->price - $product->discount->price : $product->price;
            $cart = session()->get('cart', []);

            $option_arr = [];
            if (count((array)$options) > 0) {
                foreach ($options as $key => $val) {
                    $product_option = ProductOption::where('id', $key)->first(); // get option_id using product_option_id = $key
                    $option_name = (new Option())->getOptionsData($val, $key, $product->id, $product_option->option_id);
                    ### IF PRODUCT OPTION IS NOT DELETED AND EXISTS ###
                    if ($option_name) {
                        foreach ($option_name->product_option_values as $option_val) {
                            if ($option_val->price_prefix == "+") {
                                $price += $option_val->price;
                            } else {
                                $price -= $option_val->price;
                            }
                        }
                        $option_arr[] = $option_name;
                    }

                    if (is_array($val)) {
                        foreach ($val as $value) {
                            $uuid .= $value;
                        }
                    } else {
                        $uuid .= $val;
                    }
                }
            }

            if (isset($cart[$uuid])) {
                $cart[$uuid]['quantity'] += (isset($request->qty) && !is_null($request->qty) && $request->qty != 0) ? $request->qty : 1;
            } else {
                $cart[$uuid] = [
                    "name" => $product->eng_description->name,
                    "slug" => $product->slug,
                    "quantity" => (isset($request->qty) && !is_null($request->qty) && $request->qty > 0) ? $request->qty : 1,
                    "price" => $price,
                    "image" => ($product->thumbnail_image) ? $product->thumbnail_image->image : "",
                    "option" => $options,
                    "option_arr" => $option_arr,
                ];
            }

            session()->put('cart', $cart);
        }

        return [
            "name" => $product->eng_description->name,
            "slug" => $product->slug,
            "image" => ($product->thumbnail_image) ? $product->thumbnail_image->image : "",
        ];
    }

    function _updateCart($request, $slug)
    {
        $cart = session()->get('cart');
        $cart[$slug]["quantity"] = $request->qty;
        session()->put('cart', $cart);
        $new_cart = session()->get('cart');
        $order_total = 0.00;
        foreach ($new_cart as $key => $item) {
            $order_total += ($item['price'] * $item['quantity']);
        }

        return json_encode(['status' => true, 'data' => 'Cart updated successfully.', 'order_total' => $order_total]);
    }

    function _remove($slug)
    {
        $cart = session()->get('cart');
        if (isset($cart[$slug])) {
            unset($cart[$slug]);
            session()->put('cart', $cart);
        }

        return json_encode(['status' => true, 'data' => 'Successfully removed item from cart.']);
    }

    function _clearCart($request)
    {
        $res = ['status' => false, 'data' => 'Could not clear cart, please contact admin.'];
        $exec = self::where('customer_id', $request->customer_id)->delete();
        if ($exec) {
            $res['status'] = true;
            $res['data'] = '';
        }
        return json_encode($res);
    }
}
