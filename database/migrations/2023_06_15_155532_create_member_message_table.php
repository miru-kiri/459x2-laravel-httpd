<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_message', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('cast_id');
            $table->text('content');
            $table->integer('admin_check_status')->nullable()->comment('1 approved, 0 rejected');
            $table->dateTime('cast_last_visited')->nullable();
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
        Schema::dropIfExists('member_message');
    }
}
