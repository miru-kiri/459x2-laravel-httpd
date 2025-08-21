<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDReserveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_reserve', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('cast_id')->nullable();
            $table->integer('status')->default(1)->comment("仮予約 = 1,確定 = 2; 事前確認= 3,完了= 4,キャンセル = 5");
            $table->integer('type')->default(0)->comment("0: indicate, 1: free");
            $table->integer('type_reserve')->default(1);
            $table->integer('indicate_fee1')->default(0);
            $table->integer('indicate_fee1_flg')->default(1)->comment("0 : not use, 1: use");
            $table->integer('indicate_fee2')->default(0);
            $table->integer('indicate_fee2_flg')->default(1)->comment("0 : not use, 1: use");
            $table->integer('extension_time')->default(0);
            $table->integer('extension_money')->default(0);
            $table->integer('discount')->default(0);
            $table->bigInteger('site_id_reserve')->nullable();
            $table->integer('amount')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->text('memo')->nullable();
            $table->text('address')->nullable();
            $table->integer('course_money')->nullable()->default(0);
            $table->integer('course_time')->default(0);
            $table->integer('transaction_fee')->nullable()->default(0);
            $table->text('course_name')->nullable();
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
        Schema::dropIfExists('d_reserve');
    }
}
