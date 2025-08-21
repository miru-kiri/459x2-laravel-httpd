<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestFieldsToDReserveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('d_reserve', function (Blueprint $table) {
            $table->boolean('is_guest')->default(false)->after('id');
            $table->string('guest_name')->nullable()->after('is_guest');
            $table->string('guest_phone')->nullable()->after('guest_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('d_reserve', function (Blueprint $table) {
            $table->dropColumn(['is_guest', 'guest_name', 'guest_phone']);
        });
    }
}
