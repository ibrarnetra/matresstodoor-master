<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\ProductOption;
use App\Models\Admin\Product;
use App\Models\Admin\OrderOption;
use App\Models\Admin\OptionValueDescription;
use App\Models\Admin\OptionValue;
use App\Models\Admin\Option;


class ProductOptionValue extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function product_option()
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function descriptions()
    {
        return $this->hasMany(OptionValueDescription::class, 'option_value_id', 'option_value_id');
    }

    public function eng_description()
    {
        return $this->hasOne(OptionValueDescription::class, 'option_value_id', 'option_value_id')->where('language_id', '=', '1');
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order_option()
    {
        return $this->hasOne(OrderOption::class);
    }

    public function option_value()
    {
        return $this->hasOne(OptionValue::class, 'id', 'option_value_id');
    }

    function _insert($product_option_id, $product_id, $option_values, $option_id, $type)
    {
        $option_ids = [];
        $option_value_ids = [];
        foreach ($option_values as $option_value_key => $option_value_val) {
            $option_ids[] = $option_id;
            $option_value_ids[] = $option_value_val['option_value_id'];

            $insert = [
                'product_option_id' => $product_option_id,
                'product_id' => $product_id,
                'option_id' => $option_id,
                'option_value_id' => $option_value_val['option_value_id'],
                'quantity' => $option_value_val['quantity'],
                'subtract' => $option_value_val['subtract'],
                'price' => $option_value_val['price'],
                'price_prefix' => $option_value_val['price_prefix'],
                'weight' => $option_value_val['weight'],
                'weight_prefix' => $option_value_val['weight_prefix'],
            ];
            if ($type == 'add') {
                $id = self::create($insert);
            } else {
                $exist_option_value = self::where([
                    'product_id' => $product_id,
                    'option_id' => $option_id,
                    'option_value_id' => $option_value_val['option_value_id'],
                    'product_option_id' => $product_option_id
                ])->first();
                if ($exist_option_value) {

                    $exist_option_value->quantity = $option_value_val['quantity'];
                    $exist_option_value->subtract = $option_value_val['subtract'];
                    $exist_option_value->price = $option_value_val['price'];
                    $exist_option_value->price_prefix = $option_value_val['price_prefix'];
                    $exist_option_value->weight = $option_value_val['weight'];
                    $exist_option_value->weight_prefix = $option_value_val['weight_prefix'];
                    $exist_option_value->save();
                } else {

                    $id = self::create($insert);
                }
            }
        }

    }

    function _updateOptionQuantity($product_option_value_id, $product)
    {
        $optionValue = ProductOptionValue::where(['id' => $product_option_value_id])->first();

        if ($optionValue) {
            $optionValue->quantity =  $optionValue->quantity + $product['quantity'];
            $optionValue->save();
        }
    }

    function _deleteOptionQuantity($product_option_value_id, $product)
    {
        $optionValue = ProductOptionValue::where(['id' => $product_option_value_id, 'product_id' => $product->product_id])->first();
        if ($optionValue) {
            $optionValue->quantity =  $optionValue->quantity - $product->quantity;
            $optionValue->save();
        }
    }

    function getProductByOptionValues($product_id, $product_option_id, $option_value_id)
    {
        return self::select('id', 'product_id', 'quantity', 'subtract')->with([
            'product' => function ($q) {
                $q->select('id', 'minimum');
            },
            'product.eng_description' => function ($q) {
                $q->select('product_id', 'language_id', 'name');
            }
        ])
            ->where('product_id', $product_id)
            ->where('product_option_id', $product_option_id)
            ->where('option_value_id', $option_value_id)
            ->first();
    }

    function _getProductionOptions()
    {
        return self::with([
            'option_value' => function ($q) {
                $q->select('id', 'option_id', 'sort_order')->with([
                    'eng_description' => function ($q) {
                        $q->select('option_value_id', 'language_id', 'name');
                    }
                ]);
            },
            'product' => function ($q) {
                $q->select('id')->with([
                    'eng_description' => function ($q) {
                        $q->select('product_id', 'language_id', 'name');
                    }
                ])->where('status', getConstant('IS_STATUS_ACTIVE'))
                    ->where('is_deleted', getConstant('IS_NOT_DELETED'));
            }
        ])
            ->groupBy('option_value_id')
            ->has('product')
            ->limit(6)
            ->get();
    }

    public function productValues()
    {
        $productOptionValues = ProductOptionValue::with('eng_description')->get();

        foreach ($productOptionValues as $values) {
          
            $order_products = OrderProduct::with('order_options')->where('product_id', $values->product_id)->get();
            foreach ($order_products as $orderProduct) {
                foreach ($orderProduct->order_options as $order_option) {
                    if ($order_option->value == $values->eng_description->name) {
                        $order_option->product_option_id = $values->product_option_id;
                        $order_option->product_option_value_id = $values->id;
                        $order_option->save();
                    }
                }
            }
        }
    }
}
