<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeozoneZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geozone_zone', function (Blueprint $table) {
            $table->unsignedBigInteger('geozone_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('zone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geozone_zone');
    }
}
