<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductQuantityHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_quantity_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('purchase_detail_id');
            $table->unsignedInteger('old_quantity');
            $table->unsignedInteger('new_quantity');
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
        Schema::dropIfExists('purchase_product_quantity_history');
    }
}
