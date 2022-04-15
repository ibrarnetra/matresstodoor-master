<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodDescription extends Model
{
    use HasFactory;

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
