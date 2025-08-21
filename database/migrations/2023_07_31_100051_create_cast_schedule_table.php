<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('cast_id')->comment('管理者ID');
            $table->date('date')->comment('年月日(Y-m-d)');
            $table->integer('is_work')->length(2)->comment('出勤かどうか(0=休み dayoff,1=出勤 work)');
            $table->text('start_time')->comment('出勤時間');
            $table->text('end_time')->comment('退勤時間');
            $table->text('comment')->comment('コメント');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cast_schedule');
    }
}
