<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_image', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('site_id'); //stim1
            $table->integer('cast_id');//stim2
            $table->text('directory'); //stim8
            $table->text('path'); //stim8
            $table->integer('is_direction')->default(0)->comment('tate = 0,yoko = 1'); //stim6
            $table->text('comment')->nullable();//stim10
            $table->integer('sort_no')->deault(0)->nullable(); //stim9
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cast_image');
    }
}
