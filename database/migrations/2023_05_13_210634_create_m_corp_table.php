<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCorpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_corp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->text('name')->comment('名前');
            $table->text('short_name')->nullable()->comment('略称');
            $table->integer('type')->nullable()->default(0)->comment('1=法人,2=個人');
            $table->text('responsible_name')->comment('責任者');
            $table->text('postal_code')->nullable()->comment('郵便番号');
            $table->text('address1')->nullable()->comment('住所1');            
            $table->text('address2')->nullable()->comment('住所2');            
            $table->text('address3')->nullable()->comment('住所3');            
            $table->text('tel')->nullable()->comment('電話番号');
            $table->text('fax')->nullable()->comment('FAX番号');
            $table->text('remarks')->nullable()->comment('備考');
            $table->integer('is_cosmo')->nullable()->default(1)->comment('関連グループ(cosmogroup = 1,一般 = 2)');
            $table->text('sort')->nullable()->comment('並び順');
            $table->integer('is_public')->default(0)->comment('公開フラグ(1=OK,0=NG)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_corp');
    }
}
