<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\ProductOptionValue;
use App\Models\Admin\Product;
use App\Models\Admin\OptionDescription;
use App\Models\Admin\Option;

class ProductOption extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function option()
    {
        return $this->hasOne(Option::class, 'id', 'option_id');
    }

    public function descriptions()
    {
        return $this->hasMany(OptionDescription::class, 'option_id', 'option_id');
    }

    public function eng_description()
    {
        return $this->hasOne(OptionDescription::class, 'option_id', 'option_id')->where('language_id', '=', '1');
    }

    public function product_option_values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    function _insert($options, $product_id, $type)
    {
        foreach ($options as $key => $val) {
            if (isset($val['value'])) {

                $insert = [
                    'product_id' => $product_id,
                    'option_id' => $val['option_id'],
                    'value' => $val['value'],
                    'required' => $val['required'],
                ];
                if ($type == 'add') {
                    $product_option = self::create($insert);
                } else {
                    $product_option = self::where(['product_id'=>$product_id,'option_id'=> $val['option_id']])->first();
                    if (!$product_option) {
                        $product_option = self::create($insert);
                    }
                }
            } else {
                $insert = [
                    'product_id' => $product_id,
                    'option_id' => $val['option_id'],
                    'required' => $val['required'],
                ];
                if ($type == 'add') {
                    $product_option = self::create($insert);
                } else {
                    $product_option = self::where(['product_id'=>$product_id,'option_id'=> $val['option_id']])->first();
                    if (!$product_option) {
                        $product_option = self::create($insert);
                    }
                }

                if (isset($val['option_value']) && count($val['option_value']) > 0) {
                    (new ProductOptionValue())->_insert($product_option->id, $product_id, $val['option_value'], $val['option_id'],$type);
                }
            }
        }
    }

    function getOptions($product_id)
    {
        return self::select('product_options.id', 'product_options.product_id', 'product_options.option_id', 'product_options.value', 'product_options.required')->with([
            'option' => function ($q) {
                $q->select('id', 'type');
            },
            'eng_description' => function ($q) {
                $q->select('option_id', 'name');
            },
            'product_option_values' => function ($q) {
                $q->select('id', 'product_option_id', 'option_value_id', 'quantity', 'subtract', 'price', 'price_prefix', 'weight', 'weight_prefix')->with([
                    'eng_description' => function ($q) {
                        $q->select('option_value_id', 'name');
                    }
                ]);
            },
            'product' => function ($q) {
                $q->select("id", 'price');
            }
        ])
            ->join('options', function ($q) {
                $q->on('product_options.option_id', '=', 'options.id')
                    ->where('options.is_deleted', getConstant('IS_NOT_DELETED'));
            })
            ->where('product_id', $product_id)
            ->get();
    }
}
