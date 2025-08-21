<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_review', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('cast_id');
            $table->bigInteger('bookcast_id')->nullable();
            $table->string('title', 100);
            $table->text('content')->nullable();
            $table->date('time_play');
            $table->text('admin_feedback')->nullable();
            $table->dateTime('admin_feedback_time')->nullable();
            // $table->float('vote', 2, 1)->default(5);
            $table->integer('display')->default(0);
            $table->integer('site_id')->nullable();
            $table->tinyInteger('sendmail_flg')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_review');
    }
}
