<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsSiteIdFromDSiteBlogLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('d_site_blog_log', function (Blueprint $table) {
            $table->integer('site_id')->nullable()->comment('サイトID');
            $table->integer('cast_id')->nullable()->comment('キャストID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('d_site_blog_log', function (Blueprint $table) {
            $table->dropColumn(['site_id','cast_id']);  //カラムの削除
        });
    }
}
