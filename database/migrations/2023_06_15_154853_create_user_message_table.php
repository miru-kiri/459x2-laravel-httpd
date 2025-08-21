<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_message', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('site_id');
            $table->string('title', 500)->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('is_read')->default(0)->comment('"0: not read; 1: is read"');
            $table->tinyInteger('author_flag')->default(0)->comment('"0: is user; 1: is admin"');
            $table->dateTime('user_last_visited')->nullable();
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
        Schema::dropIfExists('user_message');
    }
}
