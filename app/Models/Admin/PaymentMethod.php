<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(PaymentMethodDescription::class);
    }

    public function eng_description()
    {
        return $this->hasOne(PaymentMethodDescription::class)->where('language_id', '1');
    }
}
