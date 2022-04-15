<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_status_id');
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('tax_class_id');
            $table->unsignedBigInteger('weight_class_id')->default('0');
            $table->unsignedBigInteger('length_class_id')->default('0');

            $table->string('model', '100')->nullable();
            $table->string('sku', '100')->nullable();
            $table->string('location', '128')->nullable();
            $table->unsignedInteger('quantity')->default('0');
            $table->string('image')->nullable();
            $table->enum('shipping', ['0', '1'])->default('1');
            $table->decimal('price', '10', '2')->default('0.00');
            $table->unsignedInteger('points')->default('0');
            $table->date('date_available')->nullable();
            $table->decimal('weight', '10', '2')->default('0.00');
            $table->decimal('length', '10', '2')->default('0.00');
            $table->decimal('width', '10', '2')->default('0.00');
            $table->decimal('height', '10', '2')->default('0.00');
            $table->unsignedTinyInteger('subtract')->default('1');
            $table->unsignedInteger('minimum')->default('1');
            $table->unsignedInteger('viewed')->default('0');
            $table->unsignedInteger('sort_order')->default('0');
            $table->enum('status', ['0', '1'])->default('1');
            $table->enum('is_deleted', ['0', '1'])->default('0');
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
        Schema::dropIfExists('products');
    }
}
