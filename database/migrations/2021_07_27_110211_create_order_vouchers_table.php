<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedBigInteger('voucher_theme_id');
            $table->string('description');
            $table->string('code', '10');
            $table->string('from_name');
            $table->string('from_email');
            $table->string('to_name');
            $table->string('to_email');
            $table->text('message');
            $table->decimal('amount', '10', '2')->default('0.00');
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
        Schema::dropIfExists('order_vouchers');
    }
}
