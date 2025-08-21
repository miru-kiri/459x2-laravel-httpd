<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloneCastSheduleLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clone_cast_shedule_log', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date')->nullable()->comment('次回の日(Ymd)※基本2ヶ月先');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clone_cast_shedule_log');
    }
}
