<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCastQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_cast_question', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id')->default(0);
            $table->text('question')->nullable();
            $table->text('default_answer')->nullable()->comment('初期値');
            $table->integer('sort_no')->default(0)->comment('表示順');
            $table->integer('is_public')->default(0)->comment('公開フラグ');
            $table->integer('deleted_at')->default(0)->comment('削除フラグ');
            $table->integer('updated_stamp')->nullable()->comment('更新フラグ');
            $table->integer('created_stamp')->nullable()->comment('作成フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_cast_question');
    }
}
