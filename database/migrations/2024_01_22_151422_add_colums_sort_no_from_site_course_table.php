<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsSortNoFromSiteCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_course', function (Blueprint $table) {
            $table->integer('sort_no')->default(0)->comment('並び順');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_course', function (Blueprint $table) {
            $table->dropColumn(['sort_no']);  //カラム
        });
    }
}
