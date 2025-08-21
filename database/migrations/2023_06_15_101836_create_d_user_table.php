<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_user', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('name_furigana');
            $table->text('nickname')->nullable();
            $table->text('name_show');
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->integer('site_id')->default(0);
            $table->string('rank', 3)->default(2);
            $table->integer('block')->default('0')->comment('"0: not block1: blocked"');
            $table->text('memo')->nullable();
            $table->text('address', 255)->nullable();
            $table->text('birth_day')->nullable();
            $table->text('avatar')->nullable()->commnet("顔画像");
            $table->text('created_at')->nullable();
            $table->text('updated_at')->nullable();
            $table->text('password')->nullable();
            $table->softDeletes();
            $table->string('otp_code')->nullable();
            $table->text('expired_otp_code')->nullable();
            $table->string('phone_otp')->nullable();
            $table->dateTime('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_user');
    }
}
