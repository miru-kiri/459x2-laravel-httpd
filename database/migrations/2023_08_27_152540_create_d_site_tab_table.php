<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDSiteTabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_site_tab', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('master_id')->default(0)->comment('m_site_tab.id');
            $table->integer('site_id')->default(0)->comment('サイトID');
            $table->text('name')->nullable()->comment('タブ名称');
            $table->text('url')->nullable()->comment('url');
            $table->text('content')->nullable()->comment('内容');
            $table->integer('sort_no')->default(0)->comment('並び順');
            $table->integer('is_display')->default(1)->comment('表示=1,非表示=0');
            $table->text('color')->nullable()->comment('コンテンツごとのカラー');
            $table->text('background_color')->nullable()->comment('コンテンツごとのアクティブ背景カラー');
            $table->text('active_color')->nullable()->comment('コンテンツごとのアクティブカラー');
            $table->text('active_background')->nullable()->comment('コンテンツごとのアクティブ背景カラー');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_site_tab');
    }
}
