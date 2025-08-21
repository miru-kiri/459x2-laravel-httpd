<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsSsoTokenFromDUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('d_user', function (Blueprint $table) {
            $table->text('sso_token')->nullable()->comment('ポータルログイントークン'); 
            $table->dateTime('sso_token_expiration')->nullable()->comment('有効期限'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('d_user', function (Blueprint $table) {
            $table->dropColumn(['sso_token','sso_token_expiration']);
        });
    }
}
