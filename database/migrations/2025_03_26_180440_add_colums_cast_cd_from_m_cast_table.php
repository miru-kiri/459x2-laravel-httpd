<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsCastCdFromMCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_cast', function (Blueprint $table) {
            $table->text('cast_cd')->nullable()->comment('キャスト独自コード'); 
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
            $table->dropColumn('cast_cd');
        });
    }
}
