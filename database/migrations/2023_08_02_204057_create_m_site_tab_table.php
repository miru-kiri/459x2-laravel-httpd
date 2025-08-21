<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSiteTabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_site_tab', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('template')->comment('1=風俗,2=メンズエステ,3=キャバクラ,4=セクキャバ,5=飲食店,6=コンパニオン');            
            $table->text('name')->comment('タブ名称');
            $table->text('url')->comment('リンク先');
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
        Schema::dropIfExists('m_site_tab');
    }
}
