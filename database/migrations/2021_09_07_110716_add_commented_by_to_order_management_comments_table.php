<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentedByToOrderManagementCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_management_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('commented_by')->after('comment')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_management_comments', function (Blueprint $table) {
            $table->dropColumn('commented_by');
        });
    }
}
