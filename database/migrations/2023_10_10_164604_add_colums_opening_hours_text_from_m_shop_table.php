<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsOpeningHoursTextFromMShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_shop', function (Blueprint $table) {
            $table->text('opening_text')->nullable()->comment('営業時間(テキスト)');
            $table->text('holiday_text')->nullable()->comment('休業日(テキスト)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_shop', function (Blueprint $table) {
            $table->dropColumn(['opening_text','holiday_text']);  //カラムの削除
        });
    }
}
