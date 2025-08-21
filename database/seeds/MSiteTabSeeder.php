<?php

namespace Database\Seeders;

use App\Models\M_Site;
use App\Models\M_Site_Tab;
use Illuminate\Database\Seeder;

class MSiteTabSeeder extends Seeder
{
    public function getTab() {
        $tabs = [
            [
                'created_at' => time(),
                'template' => 1,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '出勤情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.work',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '在籍一覧',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.cast',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '写メ日記',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.diary',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 1,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#6D66AA",
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '出勤情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.work',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#6D66AA",
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '在籍一覧',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.cast',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#6D66AA",
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '写メ日記',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.diary', 
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
            ],
            [
                'created_at' => time(),
                'template' => 2,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => 'トップ',
                'url' => 'site.detail.top',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '出勤情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.work',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '在籍一覧',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.cast',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop', 
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '写メ日記',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.diary',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
            ],
            [
                'created_at' => time(),
                'template' => 3,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => 'トップ',
                'url' => 'site.detail.top',
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '出勤情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.work',
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '在籍一覧',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.cast',
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '写メ日記',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.diary',
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
            ],
            [
                'created_at' => time(),
                'template' => 4,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
            ], 
            [
                'created_at' => time(),
                'template' => 5,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.top',
            ],
            // [
            //     'created_at' => time(),
            //     'template' => 5,
            //     'name' => '出勤情報',
            //     'url' => 'site.detail.work',
            // ],
            // [
            //     'created_at' => time(),
            //     'template' => 5,
            //     'name' => '在籍一覧',
            //     'url' => 'site.detail.cast',
            // ],
            [
                'created_at' => time(),
                'template' => 5,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
            ],
            [
                'created_at' => time(),
                'template' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop',
            ],
            [
                'created_at' => time(),
                'template' => 5,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
            ],
            // [
            //     'created_at' => time(),
            //     'template' => 5,
            //     'name' => '写メ日記',
            //     'url' => 'site.detail.diary',
            // ],
            [
                'created_at' => time(),
                'template' => 5,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
            ],
            [
                'created_at' => time(),
                'template' => 5,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
            ],
            [
                'created_at' => time(),
                'template' => 6,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.top',
            ],
            // [
            //     'created_at' => time(),
            //     'template' => 6,
            //     'name' => '出勤情報',
            //     'url' => 'site.detail.work',
            // ],
            // [
            //     'created_at' => time(),
            //     'template' => 6,
            //     'name' => '在籍一覧',
            //     'url' => 'site.detail.cast',
            // ],
            [
                'created_at' => time(),
                'template' => 6,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
            ],
            [
                'created_at' => time(),
                'template' => 6,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
                // 'content' =>
            ],
            // [
            //     'created_at' => time(),
            //     'template' => 6,
            //     'name' => '写メ日記',
            //     'url' => 'site.detail.diary',
            // ],
            [
                'created_at' => time(),
                'template' => 6,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
            ],
            [
                'created_at' => time(),
                'template' => 6,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
            ]
        ];
        return $tabs;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $formatParameter = $this->getTab();
        $formatParameter = [
            [
                'created_at' => time(),
                'template' => 7,
                'name' => 'トップ',
                'url' => 'site.detail.top',
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '出勤情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.work',
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '在籍一覧',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.cast',
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '料金システム',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.price',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '店舗情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.shop',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => 'イベント情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.event',
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.diary',
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '店長ブログ',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
            ],
            [
                'created_at' => time(),
                'template' => 7,
                'name' => '求人情報',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.recruit',
            ], 
            [
                'created_at' => time(),
                'template' => 7,
                'name' => 'ショップニュース',
                'url' => 'site.detail.top',
                // 'url' => 'site.detail.blogManager',
                // 'active_color' => "#FFFFFF",
                // 'active_background' => "#EF747D",
            ],
        ];
        M_Site_Tab::insert($formatParameter);
    }
}
