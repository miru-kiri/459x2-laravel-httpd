<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsOldNameFromMGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_genre', function (Blueprint $table) {
            $table->text('old_name')->nullable()->comment('旧名称')->after('name');
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
            $table->dropColumn(['old_name','old_group_name']);  //カラムの削除
        });
    }
}
