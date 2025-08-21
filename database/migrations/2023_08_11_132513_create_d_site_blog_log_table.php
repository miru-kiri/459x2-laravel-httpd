<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDSiteBlogLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_site_blog_log', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('user_id')->default(0)->comment('ユーザーID');            
            $table->integer('category_id')->default(0)->comment('種別(1=店舗ブログ,2=店長ブログ,3=キャストブログ)');            
            $table->integer('blog_id')->default(0)->comment('サイトID');            
            $table->integer('date')->length(8)->comment('年月日');
            $table->text('time')->comment('時刻');
            $table->integer('device')->default(0)->comment('1=モバイル,2=PC');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_site_blog_log');
    }
}
