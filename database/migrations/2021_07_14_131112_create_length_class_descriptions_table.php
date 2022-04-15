<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLengthClassDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('length_class_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('length_class_id');
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
        Schema::dropIfExists('Length_class_descriptions');
    }
}
