<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSpecial extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    function _insert($specials, $product_id)
    {
        foreach ($specials as $key => $val) {
            $table = 'product_specials';
            $insert = [
                'product_id' => $product_id,
                'customer_group_id' => $val['customer_group_id'],
                'priority' => !is_null($val['priority']) ? $val['priority'] : 0,
                'price' => !is_null($val['price']) ? $val['price'] : 0,
                'date_start' => $val['date_start'],
                'date_end' => $val['date_end'],
            ];
            $id = self::create($insert);
        }
    }
}
