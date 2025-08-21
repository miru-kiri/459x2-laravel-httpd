<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCastOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_cast_option', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('site_id')->default(0)->comment('m_site.id');
            $table->text('class')->nullable()->comment('クラス名(旧DB参照)');
            $table->text('name')->nullable()->comment('名称');
            $table->text('img')->nullable()->comment('画像名(旧DB参照)');
            $table->text('type')->nullable()->comment('おそらくデフォルト設定(旧DB参照)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_cast_option');
    }
}
