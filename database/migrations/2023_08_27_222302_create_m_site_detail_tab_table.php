<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSiteDetailTabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_site_detail_tab', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('master_id')->default(0)->comment('m_site_tab.id');
            $table->text('title')->nullable()->comment('タイトル');
            $table->text('sub_title')->nullable()->comment('サブタイトル');
            $table->text('content')->nullable()->comment('内容');
            $table->integer('sort_no')->default(0)->comment('並び順');
            $table->integer('is_display')->default(1)->comment('表示=1,非表示=0');
            $table->text('color')->nullable()->comment('コンテンツごとのカラー');
            $table->text('background_color')->nullable()->comment('コンテンツごとのアクティブ背景カラー');
            $table->text('event')->nullable()->comment('コンテンツごとに表示する独自イベント名(work = 出勤情報等)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_site_detail_tab');
    }
}
