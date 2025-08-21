<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessageRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_message_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_message_id');
            $table->text('content')->nullable();
            $table->tinyInteger('is_read')->default(0)->comment('"0: not read; 1: is read"');
            $table->tinyInteger('author_flag')->default(0)->comment('"0: is user; 1: is admin"');
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
        Schema::dropIfExists('user_message_replies');
    }
}
