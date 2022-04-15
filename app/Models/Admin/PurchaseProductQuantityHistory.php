<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\PurchaseProduct;

class PurchaseProductQuantityHistory extends Model
{
    use HasFactory;

    public function purchaseProduct()
    {
        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id','id');
    }

}
