<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionValueDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_value_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('option_value_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('option_id');
            $table->string('name', '150');
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
        Schema::dropIfExists('option_value_descriptions');
    }
}
