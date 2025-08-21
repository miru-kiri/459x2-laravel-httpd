<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDOptPhoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_opt_phone', function (Blueprint $table) {
            $table->id();
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('user_id')->default(0)->comment('ユーザーID');
            $table->integer('category_id')->default(0)->comment('1=登録認証,2=変更認証');
            $table->integer('expiration_time')->default(0)->comment('有効期限(time())');
            $table->text('phone')->nullable()->comment('電話番号');
            $table->text('code')->nullable()->comment('認証コード');
            $table->integer('is_confirm')->default(0)->comment('1=認証ずみ,0=未認証');
            $table->text('token')->nullable()->comment('認証トークン');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_opt_phone');
    }
}
