<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_user', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('user_id')->nullable()->comment('ユーザーID');
            $table->integer('site_id')->nullable()->comment('サイトID');
            $table->integer('date')->default(0)->comment('取得日(Ymd)');
            $table->integer('time')->default(0)->comment('取得時間(HI)');
            $table->integer('category_id')->default(0)->comment('1=取得,2=利用,3=引き継ぎ');
            $table->integer('branch_id')->default(0)->comment('取得または利用したときのtableId(d_reseve.id) or 3 = card_no');
            $table->integer('expiration_date')->default(0)->comment('有効期限(Ymd)');
            $table->integer('point')->default(0)->comment('ポイント数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_user');
    }
}
