<?php

use Database\Seeders\CastAnswerSeeder;
use Database\Seeders\CastScheduleSeeder;
use Database\Seeders\DCastBlogImageSeeder;
use Database\Seeders\DCastBlogSeeder;
use Database\Seeders\DCastBlogVideoSeeder;
use Database\Seeders\DMoveSeeder;
use Database\Seeders\DReserveSeeder;
use Database\Seeders\DShopBlogSeeder;
use Database\Seeders\DShopManagerBlogSeeder;
use Database\Seeders\DSiteDetailTabSeeder;
use Database\Seeders\DSiteTabSeeder;
use Database\Seeders\DUserSeeder;
use Database\Seeders\MAdminSeeder;
use Database\Seeders\MAreaGroupSeeder;
use Database\Seeders\MAreaSeeder;
use Database\Seeders\MCastImageSeeder;
use Database\Seeders\MCastQuestionSeeder;
use Database\Seeders\MCastSeeder;
use Database\Seeders\MCorpSeeder;
use Database\Seeders\MGenreGroupSeeder;
use Database\Seeders\MGenreSeeder;
use Database\Seeders\MSiteDetailTabSeeder;
use Database\Seeders\MSiteSeeder;
use Database\Seeders\MSiteTabSeeder;
use Database\Seeders\SiteInfoSeeder;
use Database\Seeders\GuestUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //管理者
            MAdminSeeder::class,
            //会社
            MCorpSeeder::class,
            //エリア
            MAreaGroupSeeder::class,
            MAreaSeeder::class,
            //ジャンル
            MGenreSeeder::class,
            MGenreGroupSeeder::class,
            //サイト
            MSiteSeeder::class,
            // MShopSeeder::class,未完成
            SiteInfoSeeder::class,
            //キャストマスタ
            MCastSeeder::class,
            MCastImageSeeder::class,
            //キャストQ&A
            MCastQuestionSeeder::class,
            CastAnswerSeeder::class,
            //サイト編集
            MSiteTabSeeder::class,
            MSiteDetailTabSeeder::class,
            DSiteTabSeeder::class,
            DSiteDetailTabSeeder::class,
            //キャストスケジュール
            CastScheduleSeeder::class,
            //動画管理
            DMoveSeeder::class,
            //ブログ管理
            //わからんけど、データが重すぎてPHPエラー吐く。
            // DShopBlogSeeder::class,
            // DShopManagerBlogSeeder::class,
            // DCastBlogSeeder::class,
            // DCastBlogImageSeeder::class,
            // DCastBlogVideoSeeder::class,
            //ユーザー管理
            DUserSeeder::class,
            //予約管理
            DReserveSeeder::class,
            GuestUserSeeder::class,
        ]);
    }
}
