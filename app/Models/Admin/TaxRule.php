<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{
    use HasFactory;

    public function tax_class()
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function tax_rate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    function _insert($tax_class_id, $tax_rate_id, $based, $priority)
    {
        $tax_rule = new TaxRule();
        $tax_rule->tax_class_id = $tax_class_id;
        $tax_rule->tax_rate_id = $tax_rate_id;
        $tax_rule->based = $based;
        $tax_rule->priority = $priority;
        $tax_rule->save();
    }
}
