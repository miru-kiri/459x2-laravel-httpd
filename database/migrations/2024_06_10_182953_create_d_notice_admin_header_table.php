<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDNoticeAdminHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_notice_admin_header', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->text('title')->nullable()->comment('タイトル');
            $table->text('content')->nullable()->comment('内容');
            $table->integer('type')->default(0)->comment('0=記事,1=機能');
            $table->integer('is_draft')->default(0)->comment('下書きフラグ');
            $table->dateTime('display_date')->nullable()->comment('公開日');
            $table->integer('created_user')->default(0)->comment('作成者');
            $table->integer('updated_user')->default(0)->comment('更新者');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_notice_admin_header');
    }
}
