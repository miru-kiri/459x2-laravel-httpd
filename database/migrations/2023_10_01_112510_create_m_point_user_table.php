<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPointUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_point_user', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('user_id')->nullable()->comment('ユーザーID');
            $table->integer('site_id')->nullable()->comment('サイトID');
            $table->bigInteger('card_no')->default(0)->comment('カード番号');
            $table->text('name')->nullable()->comment('ユーザー名');
            $table->integer('year')->nullable()->comment('生年月日(年)');
            $table->integer('month')->nullable()->comment('生年月日(月)');
            $table->integer('day')->nullable()->comment('生年月日(日)');
            $table->integer('sex')->default(0)->comment('性別(1=男性,2=女性)');
            $table->text('tel')->nullable()->comment('電話番号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_point_user');
    }
}
