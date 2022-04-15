<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');

            $table->unsignedBigInteger('store_id')->default('0');
            $table->string('store_name')->nullable();
            $table->string('store_url')->nullable();

            $table->unsignedBigInteger('customer_id')->default('0');
            $table->unsignedBigInteger('customer_group_id')->default('0');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('telephone');

            $table->unsignedBigInteger('payment_country_id')->default('0');
            $table->unsignedBigInteger('payment_zone_id')->default('0');
            $table->string('payment_first_name');
            $table->string('payment_last_name');
            $table->string('payment_company')->nullable();
            $table->string('payment_address_1');
            $table->string('payment_address_2')->nullable();
            $table->string('payment_city');
            $table->string('payment_postcode')->nullable();
            $table->string('payment_country');
            $table->string('payment_zone');

            $table->unsignedBigInteger('shipping_country_id')->default('0');
            $table->unsignedBigInteger('shipping_zone_id')->default('0');
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_company')->nullable();
            $table->string('shipping_address_1');
            $table->string('shipping_address_2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_country');
            $table->string('shipping_zone');

            $table->unsignedBigInteger('shipping_method_id')->default('0');
            $table->string('shipping_method')->nullable();
            $table->string('shipping_method_code')->nullable();

            $table->unsignedBigInteger('payment_method_id')->default('0');
            $table->string('payment_method')->nullable();
            $table->string('payment_method_code')->nullable();

            $table->unsignedBigInteger('order_status_id')->default('0');

            $table->unsignedBigInteger('affiliate_id')->default('0');
            $table->unsignedBigInteger('marketing_id')->default('0');
            $table->unsignedBigInteger('language_id')->default('0');

            $table->unsignedBigInteger('currency_id')->default('0');
            $table->string('currency_code')->nullable();
            $table->decimal('currency_value', '10', '2')->default('0.00');

            $table->text('comment')->nullable();
            $table->decimal('commission', '10', '2')->default('0.00');
            $table->string('tracking')->nullable();
            $table->decimal('total', '10', '2')->default('0.00');

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
        Schema::dropIfExists('orders');
    }
}
