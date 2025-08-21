<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastUpdatedStatusAtToMCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_cast', function (Blueprint $table) {
            $table->timestamp('last_updated_status_at')->nullable()->after('sokuhime_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_cast', function (Blueprint $table) {
            $table->dropColumn('last_updated_status_at');
        });
    }
}
