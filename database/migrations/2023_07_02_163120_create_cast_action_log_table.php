<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastActionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_action_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('cast_id')->comment('キャストID');
            $table->integer('category_id')->lengthgfggtgtrgrgfgggfgtrtgrt(2)->comment('ログカテゴリID(1=ログイン,2=CRAD,3=エラー)');
            $table->integer('date')->length(8)->comment('年月日');
            $table->text('time')->comment('時刻');
            $table->text('content')->comment('内容');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cast_action_log');
    }
}
