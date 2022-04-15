<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingLatAndShippingLngAndPaymentLatAndPaymentLngToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_lat', 10, 4)->after('shipping_address_1')->default("0.0000");
            $table->decimal('shipping_lng', 10, 4)->after('shipping_lat')->default("0.0000");
            $table->decimal('payment_lat', 10, 4)->after('payment_address_1')->default("0.0000");
            $table->decimal('payment_lng', 10, 4)->after('payment_lat')->default("0.0000");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_lat');
            $table->dropColumn('shipping_lng');
            $table->dropColumn('payment_lat');
            $table->dropColumn('payment_lng');
        });
    }
}
