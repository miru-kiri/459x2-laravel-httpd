<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_contact', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('user_id')->default(0)->comment('ユーザーID');
            $table->integer('site_id')->default(0)->comment('サイトID');
            $table->integer('cast_id')->default(0)->comment('キャストID');
            $table->date('date')->nullable()->comment('取得日(Ymd)');
            $table->integer('time')->default(0)->comment('取得時間(HI)');
            $table->text('name')->nullable()->comment('氏名');
            $table->text('site_name')->nullable()->comment('サイト名');
            $table->text('cast_name')->nullable()->comment('キャスト名');
            $table->text('phone')->nullable()->comment('電話番号');
            $table->text('email')->nullable()->comment('メールアドレス');
            $table->text('title')->nullable()->comment('タイトル');
            $table->text('content')->nullable()->comment('内容');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_contact');
    }
}
