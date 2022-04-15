<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('warehouse_id');
            $table->string('purchase_type');
            $table->string('invoice_no');
            $table->date('purchase_date');
            $table->string('vehicle_no')->nullable();
            $table->string('serial_no');
            $table->decimal('purchase_total_amount');
            $table->decimal('purchase_discount_amount');
            $table->decimal('purchase_total_payable_amount');
            $table->string('purchase_status');
            $table->unsignedInteger('created_by');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
