<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsIsRecommendFromMCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_cast', function (Blueprint $table) {
            $table->integer('is_recommend')->default(0)->comment('おすすめフラグ(1=おすすめ)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_cast', function (Blueprint $table) {
            $table->dropColumn('is_recommend');  //カラムの削除
        });
    }
}
