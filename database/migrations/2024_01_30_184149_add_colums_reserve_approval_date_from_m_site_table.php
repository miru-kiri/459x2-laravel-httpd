<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsReserveApprovalDateFromMSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_site', function (Blueprint $table) {
            $table->integer('reserve_approval_date')->nullable()->comment('ネット予約受付日(日)');
            $table->integer('reserve_buffer_time')->default(0)->comment('バッファ時間(分)');
            $table->integer('reserve_close_branch')->default(0)->comment('ネット予約締切(0=時間,1=日数)');
            $table->integer('reserve_close')->default(0)->comment('ネット予約締切');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_site', function (Blueprint $table) {
            $table->dropColumn(['reserve_approval_date','reserve_buffer_time']);
        });
    }
}
