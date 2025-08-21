<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteNominationFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_nomination_fee', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->integer('nomination_fee')->default(0);
            $table->integer('extension_fee')->default(0);
            $table->integer('extension_time_unit')->default(0);
            $table->integer('fee')->nullable();
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
        Schema::dropIfExists('site_nomination_fee');
    }
}
