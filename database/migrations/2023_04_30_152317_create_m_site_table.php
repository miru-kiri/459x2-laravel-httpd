<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_site', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_at')->length(10)->comment('レコード制作タイムスタンプ');
            $table->integer('updated_at')->length(10)->nullable()->default(0)->comment('レコード更新日タイムスタンプ');
            $table->integer('deleted_at')->length(10)->nullable()->default(0)->comment('レコード削除日タイムスタンプ');
            $table->integer('shop_id')->default(0)->comment('店舗ID');
            $table->text('url')->nullable()->comment('サイトURL');
            $table->text('name')->nullable()->comment('名前');
            $table->integer('style')->default(0)->comment('サイト形態(オフィシャル店舗系サイト = 1,オフィシャル企業系サイト = 2,リクルート系サイト = 3,portal = 4)');
            $table->text('top_url')->nullable()->comment('トップベージURL');
            $table->text('recruit_key')->nullable()->comment('グループ求人誌記号');
            $table->text('template')->nullable()->comment('テンプレート設定(***系的な感じで設定できたら)');
            $table->integer('is_cosmo')->default(0)->comment('関連グループ名(コスモグループ = 1,一般 = 2)');
            $table->integer('sort')->default(0)->comment('並び順');
            $table->text('remarks')->nullable()->comment('備考');
            $table->text('content')->nullable()->comment('サイト紹介');
            $table->text('switching_time')->nullable()->comment('出勤切り替え時間(分)24:00より何分後');
            $table->text('blog_owner_host')->nullable()->comment('ブログ店長メール投稿設定(ホスト)');
            $table->text('blog_owner_user')->nullable()->comment('ブログ店長メール投稿設定(ユーザー)');
            $table->text('blog_owner_pass')->nullable()->comment('ブログ店長メール投稿設定(パスワード)');
            $table->text('blog_staff_host')->nullable()->comment('ブログスタッフメール投稿設定(ホスト)');
            $table->text('blog_staff_user')->nullable()->comment('ブログスタッフメール投稿設定(ユーザー)');
            $table->text('blog_staff_pass')->nullable()->comment('ブログスタッフメール投稿設定(パスワード)');
            $table->text('mail_magazine_url')->nullable()->comment('メルマガ設定登録用URL');
            $table->text('mail_magazine_key')->nullable()->comment('メルマガ設定登録用キー(ハイフン区切り)');
            $table->text('mail_magazine_create_mail')->nullable()->comment('メルマガ設定登録用メールアドレス');
            $table->text('mail_magazine_delete_mail')->nullable()->comment('メルマガ設定削除用メールアドレス');
            $table->text('recruit_line_url')->nullable()->comment('求人用LINE設定友達追加URL');
            $table->text('recruit_line_id')->nullable()->comment('求人用LINE設定ID');
            $table->text('analytics_code')->nullable()->comment('アナリティクス設定トラッキングコード');
            $table->text('analytics_api')->nullable()->comment('アナリティクス設定API用プロファイルID');
            $table->integer('is_https')->default(0)->comment('httpsかどうか');
            $table->text('portal_template_url')->nullable()->comment('ポータルショップテンプレート(/459x/***)');
            $table->text('portal_tab')->nullable()->comment('ポータルショップページ指定カンマ区切り(top,staff,**)タブ部分');
            $table->integer('staff_sort')->default(0)->comment('女の子一覧ソート設定(web= 1,scd  =2,name = 3,shopno = 4)');
            $table->text('open_time')->nullable()->comment('OPEN時間');
            $table->text('close_time')->nullable()->comment('CLOSE時間');
            $table->integer('is_externally_server')->default(0)->comment('対外サーバーかどうか');
            $table->integer('is_public')->default(0)->comment('公開フラグ(1=公開,非公開=0)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_site');
    }
}
