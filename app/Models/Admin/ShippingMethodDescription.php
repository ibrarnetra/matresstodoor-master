<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\ShippingMethod;

class ShippingMethodDescription extends Model
{
    use HasFactory;

    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
