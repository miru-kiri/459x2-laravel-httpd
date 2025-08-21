<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_course', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->string('name', 255);
            $table->integer('time');
            $table->integer('fee');
            $table->tinyInteger('type')->default('0')->comment('"0: Net, 1: Normal, 2: Free"');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_course');
    }
}
