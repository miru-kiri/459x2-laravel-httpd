<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsIsSerchFromMGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_genre', function (Blueprint $table) {
            $table->integer('search_category')->default(0)->comment('位置情報からの検索条件に表示するか');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_genre', function (Blueprint $table) {
            $table->dropColumn('search_category');  //カラムの削除
        });
    }
}
