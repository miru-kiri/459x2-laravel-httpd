<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsCardNoFromPointUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_user', function (Blueprint $table) {
            $table->bigInteger('card_no')->default(0)->comment('カード番号');
            $table->integer('is_app')->default(0)->comment('アプリからの登録かどうか。');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_user', function (Blueprint $table) {
            //
        });
    }
}
