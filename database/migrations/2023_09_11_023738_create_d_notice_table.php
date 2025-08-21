<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_notice', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            // $table->integer('site_id')->default(0)->comment('m_site.id');
            $table->text('title')->nullable()->comment('タイトル');
            $table->text('content')->nullable()->comment('内容');
            // $table->integer('is_display')->default(0)->comment('公開設定(0=非公開,1=公開)');
            $table->dateTime('display_date')->nullable()->comment('公開日');
            $table->integer('created_user')->default(0)->comment('作成者');
            $table->integer('updated_user')->default(0)->comment('更新者');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_notice');
    }
}
