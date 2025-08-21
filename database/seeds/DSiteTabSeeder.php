<?php

namespace Database\Seeders;

use App\Models\D_Site_Tab;
use App\Models\M_Site;
use App\Models\M_Site_Tab;
use Illuminate\Database\Seeder;

class DSiteTabSeeder extends Seeder
{
    public function getActiveTabColor($template) {
        $tabs = [
            1 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#EF747D",
            ],
            2 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#6D66AA",
            ],
            3 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#F684A6",
            ],
            4 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#B66497",
            ],
            5 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#F27847",
            ],
            6 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#C02639",
            ],
        ];
        if(!isset($tabs[$template])) {
            return [];
        }
        return $tabs[$template];
    }
    public function getTab($siteId,$template) {
        $tabs = [
        1 => 
        [
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                'is_display' => 1,
                'sort_no' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'name' => '出勤情報',
                'url' => 'site.detail.work',
                'is_display' => 1,
                'sort_no' => 2,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'name' => '在籍一覧',
                'url' => 'site.detail.cast',
                'is_display' => 1,
                'sort_no' => 3,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.price',
                'is_display' => 1,

            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.shop',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.event',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.diary',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 8,
                'name' => '店長ブログ',
                'url' => 'site.detail.blogManager',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 9,
                'name' => '求人情報',
                'url' => 'site.detail.recruit',
                'is_display' => 1,
            ],
        ],
        2 => 
        [
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 1,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 2,
                'name' => '出勤情報',
                'url' => 'site.detail.work',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 3,
                'name' => '在籍一覧',
                'url' => 'site.detail.cast',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.price',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.shop',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.event',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.diary',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 8,
                'name' => '店長ブログ',
                'url' => 'site.detail.blogManager',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 9,
                'name' => '求人情報',
                'url' => 'site.detail.recruit',
                'is_display' => 1,
            ],
        ],
        3 =>
        [
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 1, 
                'name' => 'トップ',
                'url' => 'site.detail.top',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 2,
                'name' => '出勤情報',
                'url' => 'site.detail.work',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 3,
                'name' => '在籍一覧',
                'url' => 'site.detail.cast',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.price',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.shop',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.event',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.diary',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 8,
                'name' => '店長ブログ',
                'url' => 'site.detail.blogManager',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 9,
                'name' => '求人情報',
                'url' => 'site.detail.recruit',
                'is_display' => 1,
            ],
        ],
        4 =>
        [
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 1,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 2,
                'name' => '出勤情報',
                'url' => 'site.detail.work',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 3,
                'name' => '在籍一覧',
                'url' => 'site.detail.cast',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.price',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.shop',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.event',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.diary',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 8,
                'name' => '店長ブログ',
                'url' => 'site.detail.blogManager',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 9,
                'name' => '求人情報',
                'url' => 'site.detail.recruit',
                'is_display' => 1,
            ], 
        ],
        5 =>
        [
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 1,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 2,
                'name' => '出勤情報',
                'url' => 'site.detail.work',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 3,
                'name' => '在籍一覧',
                'url' => 'site.detail.cast',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.price',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.shop',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.event',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.diary',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 8,
                'name' => '店長ブログ',
                'url' => 'site.detail.blogManager',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 9,
                'name' => '求人情報',
                'url' => 'site.detail.recruit',
                'is_display' => 1,
            ],
        ],
        6 =>
        [
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 1,
                'name' => 'トップ',
                'url' => 'site.detail.top',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 2,
                'name' => '出勤情報',
                'url' => 'site.detail.work',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 3,
                'name' => '在籍一覧',
                'url' => 'site.detail.cast',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 4,
                'name' => '料金システム',
                'url' => 'site.detail.price',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 5,
                'name' => '店舗情報',
                'url' => 'site.detail.shop',
                'is_display' => 1,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 6,
                'name' => 'イベント情報',
                'url' => 'site.detail.event',
                'is_display' => 0,
                // 'content' =>
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 7,
                'name' => '写メ日記',
                'url' => 'site.detail.diary',
                'is_display' => 0,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 8,
                'name' => '店長ブログ',
                'url' => 'site.detail.blogManager',
                'is_display' => 1,
            ],
            [
                'created_at' => time(),
                'site_id' => $siteId,
                'sort_no' => 9,
                'name' => '求人情報',
                'url' => 'site.detail.recruit',
                'is_display' => 1,
            ]
        ],
        ];
        return $tabs[$template];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formatParameter = [];
        // $id = [115,147];
        $siteData = M_Site::FetchAll();
        $loop = 0;
        foreach($siteData as $site) {
            // $formatParameter = [];
            // if(!in_array($site->id,$id)) {
            //     continue;
            // }
            if($site->template == 0) {
                continue;
            }
            $masterTabs = M_Site_Tab::fetchFilteringData(['template' => $site->template])->toArray();
            $masterIdAry = D_Site_Tab::fetchFilteringDataPluckId(['site_id' => $site->id]);
            foreach($masterTabs as $index => $tab) {
                if(!in_array($tab['id'],$masterIdAry)) {
                    $formatParameter[$loop] = $this->getActiveTabColor($site->template);
                    $formatParameter[$loop]['created_at'] = time();
                    $formatParameter[$loop]['master_id'] = $tab['id'];
                    $formatParameter[$loop]['site_id'] = $site->id;
                    $formatParameter[$loop]['name'] = $tab['name'];
                    // $formatParameter[$loop]['url'] = $tab['url'];
                    //全部一緒になった。
                    $formatParameter[$loop]['url'] = 'site.detail.top';
                    // $formatParameter[$loop]['is_display'] = $tab['is_display'];
                    $formatParameter[$loop]['sort_no'] = $index + 1;
                    $loop++;
                }
            }
        }
        D_Site_Tab::insert($formatParameter);
    }
}
