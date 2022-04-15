<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    public function descriptions()
    {
        return $this->hasMany(ShippingMethodDescription::class);
    }

    public function eng_description()
    {
        return $this->hasOne(ShippingMethodDescription::class)->where('language_id', '1');
    }
}
