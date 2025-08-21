<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSokuhimeCommentFromMCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_cast', function (Blueprint $table) {
            $table->text('sokuhime_comment')->nullable()->comment('即姫コメント')->after('is_sokuhime');
            $table->text('password_reset_token')->nullable()->comment('パスワード再発行時のトークン')->after('password');
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
            $table->dropColumn(['sokuhime_comment','password_reset_token']);  //カラム
        });
    }
}
