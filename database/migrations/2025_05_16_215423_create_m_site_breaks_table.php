<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSiteBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_site_breaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->tinyInteger('weekday');
            $table->string('break_start', 4);
            $table->string('break_end', 4);
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('m_site')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_site_breaks');
    }
}
