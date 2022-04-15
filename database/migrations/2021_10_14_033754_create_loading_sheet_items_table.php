<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadingSheetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loading_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loading_sheet_id');
            $table->unsignedBigInteger('order_id');
            $table->enum('inventory_from', ['order', 'warehouse'])->default('order');
            $table->string('name');
            $table->unsignedInteger('quantity');
            $table->decimal('price', '10', '2')->default("0.00");
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
        Schema::dropIfExists('loading_sheet_items');
    }
}
