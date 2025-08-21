<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_shop', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('corporate_id')->default(0)->comment('法人ID');
            $table->text('name')->nullable()->comment('名前');
            $table->text('kana')->nullable()->comment('名前(カナ)');
            $table->text('short_name')->nullable()->comment('略称名');
            $table->text('short_kana')->nullable()->comment('略称名(カナ)');
            $table->integer('style')->nullable()->comment('店舗形態(1=shop,2=unshop,3=office)');
            $table->integer('genre_id')->nullable()->comment('ジャンルID(m_genre.id)');
            $table->text('responsible_name')->nullable()->comment('責任者');
            $table->text('postal_code')->nullable()->comment('郵便番号');
            $table->text('address1')->nullable()->comment('住所1');
            $table->text('address2')->nullable()->comment('住所2');
            $table->text('address3')->nullable()->comment('住所3');
            $table->text('tel')->nullable()->comment('電話番号');
            $table->text('fax')->nullable()->comment('FAX番号');
            $table->integer('is_notification')->nullable()->comment('届出確認');
            $table->text('remarks')->nullable()->comment('備考');
            $table->text('sort')->nullable()->comment('並び順');
            $table->text('applying')->nullable()->comment('風営法適用区分');
            $table->integer('is_cosmo')->nullable()->default(1)->comment('関連グループ(cosmogroup = 1,一般 = 2)');
            $table->text('workday_start_time')->nullable()->comment('平日営業開始時間(金以外)');
            $table->text('workday_end_time')->nullable()->comment('平日営業終了時間(金以外)');
            $table->text('friday_start_time')->nullable()->comment('金曜営業開始時間');
            $table->text('friday_end_time')->nullable()->comment('金曜営業終了時間');
            $table->text('saturday_start_time')->nullable()->comment('土曜日営業開始時間');
            $table->text('saturday_end_time')->nullable()->comment('土曜日営業終了時間');
            $table->text('sunday_start_time')->nullable()->comment('日・祝日営業開始時間');
            $table->text('sunday_end_time')->nullable()->comment('日・祝日営業終了時間');
            $table->text('holiday_start_time')->nullable()->comment('祝日営業開始時間');
            $table->text('holiday_end_time')->nullable()->comment('祝日営業終了時間');
            $table->text('mail')->nullable()->comment('問い合わせメールアドレス');
            $table->text('recruit_tel')->nullable()->comment('求人TEL番号');
            $table->text('recruit_mail')->nullable()->comment('求人メールアドレス');
            $table->integer('is_public')->default(0)->comment('公開フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_shop');
    }
}
