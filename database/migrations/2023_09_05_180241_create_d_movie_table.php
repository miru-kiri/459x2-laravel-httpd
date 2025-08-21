<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDMovieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_movie', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('shop_id')->default(0)->comment('m_shop.id');
            $table->integer('site_id')->default(0)->comment('m_site.id');
            $table->integer('cast_id')->default(0)->comment('m_site.id');
            $table->integer('file_path')->default(0)->comment('パス');
            $table->integer('file_name')->default(0)->comment('ファイル名');
            // $table->integer('exten')->default(0)->comment('動画拡張子');
            $table->text('cast_name')->nullable()->comment('キャスト名称');
            $table->text('title')->nullable()->comment('タイトル');
            $table->text('content')->nullable()->comment('内容');
            $table->text('time')->nullable()->comment('再生時間(分)');
            $table->text('tag_name')->nullable()->comment('タグ名称');
            $table->integer('is_cm_display')->default(0)->comment('cm非表示');
            $table->integer('is_display')->default(0)->comment('非表示');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_movie');
    }
}
