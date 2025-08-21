<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_answer', function (Blueprint $table) {
            $table->id();
            $table->integer('cast_id');
            $table->integer('question_id')->default(0);
            $table->text('answer')->nullable();
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
        Schema::dropIfExists('cast_answer');
    }
}
