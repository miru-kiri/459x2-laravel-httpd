<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMGenreGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_genre_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('category_id')->default(0)->comment('高収入= 1,乾杯系=2,一般系=3');
            $table->text('name')->comment('名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_genre_group');
    }
}
