<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPointEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_point_event', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('deleted_at')->default(0)->comment('削除フラグ');
            $table->integer('site_id')->default(0)->comment('サイトID');
            $table->text('title')->nullable()->comment('イベント名');
            $table->text('content')->nullable()->comment('詳細');
            $table->date('start_date')->nullable()->comment('開始日');
            $table->date('end_date')->nullable()->comment('終了日');
            $table->double('persent')->default(0)->comment('何%アップか');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_point_event');
    }
}
