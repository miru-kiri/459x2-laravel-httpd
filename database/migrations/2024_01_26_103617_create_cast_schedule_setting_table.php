<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastScheduleSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_schedule_setting', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->date('date');
            $table->string('time');
            $table->bigInteger('cast_id');
            $table->tinyInteger('status')->comment('1: tel, 2: not work');
            $table->timestamps();
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cast_schedule_setting');
    }
}
