<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_cast', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('site_id')->comment('サイトID');
            $table->text('source_name')->comment('源氏名');
            $table->text('catch_copy')->nullable()->comment('キャッチコピー');
            $table->integer('shop_id')->nullable()->comment('店舗ID');
            $table->integer('stay_status')->nullable()->comment("在籍状態('00_member' => '在籍',  = 1'01_support' => '他店より応援', = 2 '99_dammy' => 'ダミー' = -1)");
            $table->integer('exclusive_status')->nullable()->comment("限定状態('50_none' => '通常', = 1'10_new' => '新人',  = 2'05_experi' => '体験入店', = 3 '01_limit' => '期間限定', = 4'60_recommend' => '店長おススメ', = 5'71_rank' => '当店人気第1位', = 6'72_rank' => '当店人気第2位', =7'73_rank' => '当店人気第3位', = 8'99_vacation' => '長期休暇' = 9)");
            $table->integer('age')->nullable()->comment('年齢');
            $table->text('blood_type')->nullable()->comment('血液型');
            $table->text('constellation')->nullable()->comment('星座(月で入っている)');
            $table->integer('height')->nullable()->comment("身長");
            $table->text('bust')->nullable()->comment('バスト');
            $table->text('cup')->nullable()->comment('カップ');
            $table->text('waist')->nullable()->comment('ウェスト');
            $table->text('hip')->nullable()->comment('ヒップ');
            $table->text('figure')->nullable()->comment("体型('t_s_bs' => '身長は小柄で細身', = 1't_s_bm' => '身長は小柄で普通', = 2't_s_bb' => '身長は小柄でぽっちゃり', = 3 't_m_bs' => '身長は標準で細身',  = 4't_m_bm' => '身長は標準で普通',  = 5't_m_bb' => '身長は標準でぽっちゃり', = 6 't_b_bs' => '身長は高めで細身',  = 7't_b_bm' => '身長は高めで普通',  = 8't_b_bb' => '身長は高めでぽっちゃ' = 9)");
            $table->text('figure_comment')->nullable()->comment('体型コメント');
            $table->text('self_pr')->nullable()->comment('自己PR');
            $table->text('shop_manager_pr')->nullable()->comment('店長PR');
            $table->text('post_mail')->nullable()->comment('投稿メールアドレス');
            $table->text('huzoku_dx_id')->nullable()->comment('風俗DXID(パスワード?)');
            $table->text('sokuhime_date')->nullable()->comment('今だけ日付？(今すぐ設定の日付)');
            $table->text('sokuhime_status')->nullable()->comment("今だけステータス？");
            $table->integer('is_sokuhime')->nullable()->comment('今だけチェック？');
            $table->text('transfer_mail')->nullable()->comment('転送先メールアドレス');
            $table->text('recruit_status')->nullable()->comment("募集状態('11_normal' => '通常',  = 1'22_scout' => 'スカウト',  = 2'33_dekasegi' => '出稼ぎ' = 3)");
            $table->text('serch_word')->nullable()->comment('検索ワード');
            $table->integer('is_auto')->nullable()->comment('自動フラグ(auto OR none)');
            $table->text('auto_start_time')->nullable()->comment('自動出勤時間');
            $table->text('auto_end_time')->nullable()->comment('自動退勤時間');
            $table->text('auto_rest_comment')->nullable()->comment('自動お休みコメント');
            $table->text('auto_week')->nullable()->comment('自動出勤曜日');
            $table->text('sort')->nullable()->comment('WEB順番');
            $table->text('username')->nullable()->comment('システム表示名称');
            $table->text('password')->nullable()->comment('パスワード');
            $table->dateTime('generate_link_register_at')->nullable();
            $table->text('token_register')->nullable();
            $table->dateTime('register_at')->nullable();
            $table->integer('block')->nullable()->default(0);
            $table->text('post_email')->nullable();
            $table->text('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_cast');
    }
}
