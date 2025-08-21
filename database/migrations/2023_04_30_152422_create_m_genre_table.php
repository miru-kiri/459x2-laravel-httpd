<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_genre', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->text('name')->comment('名前');
            $table->integer('group_id')->default(0)->comment('ジャンルグループID(m_genre_group.id)'); 
            $table->text('content')->nullable()->comment('名前');
            $table->text('remarks')->nullable()->comment('備考');
            $table->integer('sort')->nullable()->comment('並び順');
            $table->integer('is_public')->default(0)->comment('公開フラグ(1=OK,0=NG)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_genre');
    }
}
