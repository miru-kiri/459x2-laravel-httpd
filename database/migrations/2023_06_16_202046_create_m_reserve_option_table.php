<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMReserveOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_reserve_option', function (Blueprint $table) {
            $table->id();
            $table->integer('reserve_id');
            $table->integer('option_id');
            $table->text('option_name')->nullable();
            $table->integer('option_money')->default(0);
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
        Schema::dropIfExists('m_reserve_option');
    }
}
