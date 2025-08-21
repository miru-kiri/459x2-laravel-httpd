<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberMessageRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_message_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('message_id');
            $table->text('content')->nullable();
            $table->integer('author_flag')->default(0)->comment('"0: member; 1: cast"');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('is_read')->default('0')->comment('0: is new, 1: is read');
            $table->enum('status', ['close', 'reject', 'apporve'])->default('close');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_message_replies');
    }
}
