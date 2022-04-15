<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
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

    function _insert($images, $product_id)
    {
        foreach ($images as $file) {
            $image = saveImage($file, 'product_images');
            $insert = [
                'product_id' => $product_id,
                'image' => $image,
                'type' => 'original',
                'sort_order' => '0',
            ];
            $id = self::create($insert);
        }
    }
}
