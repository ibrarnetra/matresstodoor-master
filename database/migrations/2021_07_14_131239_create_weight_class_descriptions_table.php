<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightClassDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_class_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_class_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title', '32');
            $table->string('unit', '4');
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
        Schema::dropIfExists('weight_class_descriptions');
    }
}
