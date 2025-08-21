<?php

namespace Database\Seeders;

use App\Models\M_Site_Detail_Tab;
use App\Models\M_Site_Tab;
use Illuminate\Database\Seeder;

class MSiteDetailTabSeeder extends Seeder
{
    public function getTab($masterId,$template) {
        $tabs = [
            1 =>[
                1 => [
                    [
                        'created_at' => time(),
                        'title' => '本日の出勤',
                        'sort_no' => 1,
                        'event' => 'top_today_work'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ショップニュース',
                        'sub_title' => 'お店からのお得な情報をピックアップ',
                        'sort_no' => 2,
                        'event' => 'top_shop_news'
                        
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長BLOG',
                        'sub_title' => '店長しか知らない㊙︎な情報を配信',
                        'sort_no' => 3,
                        'event' => 'top_manager_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '写メ日記',
                        'sub_title' => '在籍の女の子を日記をリアルタイムで紹介',
                        'sort_no' => 4,
                        'event' => 'top_cast_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '指名数ランキング',
                        'sort_no' => 5,
                        'event' => 'top_ranking'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長おすすめガール',
                        'sort_no' => 6,
                        'event' => 'top_recommend'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '天国ネット動画',
                        'sort_no' => 7,
                        'event' => 'top_movie'
                    ],
                ],
                2 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'today_work'
                    ],
                ],
                3 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'cast'
                    ],
                ],
                4 =>[
                    [
                        'created_at' => time(),
                        'title' => '料金システム',
                        'sort_no' => 1,
                        'event' => 'base_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '延長システム',
                        'sort_no' => 2,
                        'event' => 'extension_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'その他',
                        'sort_no' => 3,
                        'event' => 'other_price'
                    ],
                ],
                5 =>[
                    [
                        'created_at' => time(),
                        'title' => '店舗情報',
                        'sort_no' => 1,
                        'event' => 'shop_info'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'アクセスマップ',
                        'sort_no' => 2,
                        'event' => 'shop_access'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ギャラリー',
                        'sort_no' => 3,
                        'event' => 'shop_gallery'
                    ],
                ],
                6 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'event'
                    ],
                ],
                7 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'cast_news'
                    ],
                ],
                8 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'manager_news'
                    ],
                ],
                9 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'recruit'
                    ],
                ],
            ],
            2 =>[
                10 => [
                    [
                        'created_at' => time(),
                        'title' => '本日の出勤',
                        'sort_no' => 1,
                        'event' => 'top_today_work'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ショップニュース',
                        'sub_title' => 'お店からのお得な情報をピックアップ',
                        'sort_no' => 2,
                        'event' => 'top_shop_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長BLOG',
                        'sub_title' => '店長しか知らない㊙︎な情報を配信',
                        'sort_no' => 3,
                        'event' => 'top_manager_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '写メ日記',
                        'sub_title' => '在籍の女の子を日記をリアルタイムで紹介',
                        'sort_no' => 4,
                        'event' => 'top_cast_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '指名数ランキング',
                        'sort_no' => 5,
                        'event' => 'top_ranking'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長おすすめガール',
                        'sort_no' => 6,
                        'event' => 'top_recommend'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '天国ネット動画',
                        'sort_no' => 7,
                        'event' => 'top_movie'
                    ],
                ],
                11 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'today_work'
                    ],
                ],
                12 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'cast'
                    ],
                ],
                13 =>[
                    [
                        'created_at' => time(),
                        'title' => '料金システム',
                        'sort_no' => 1,
                        'event' => 'base_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '延長システム',
                        'sort_no' => 2,
                        'event' => 'extension_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'その他',
                        'sort_no' => 3,
                        'event' => 'other_price'
                    ],
                ],
                14 =>[
                    [
                        'created_at' => time(),
                        'title' => '店舗情報',
                        'sort_no' => 1,
                        'event' => 'shop_info'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'アクセスマップ',
                        'sort_no' => 2,
                        'event' => 'shop_access'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ギャラリー',
                        'sort_no' => 3,
                        'event' => 'shop_gallery'
                    ],
                ],
                15 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'event'
                    ],
                ],
                16 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'cast_news'
                    ],
                ],
                17 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'manager_news'
                    ],
                ],
                18 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'recruit'
                    ],
                ],
            ],
            3 =>[
                19 => [
                    [
                        'created_at' => time(),
                        'title' => '本日の出勤',
                        'sort_no' => 1,
                        'event' => 'top_today_work'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ショップニュース',
                        'sub_title' => 'お店からのお得な情報をピックアップ',
                        'sort_no' => 2,
                        'event' => 'top_today_work'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長BLOG',
                        'sub_title' => '店長しか知らない㊙︎な情報を配信',
                        'sort_no' => 3,
                        'event' => 'top_manager_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '写メ日記',
                        'sub_title' => '在籍の女の子を日記をリアルタイムで紹介',
                        'sort_no' => 4,
                        'event' => 'top_cast_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '指名数ランキング',
                        'sort_no' => 5,
                        'event' => 'top_ranking'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長おすすめガール',
                        'sort_no' => 6,
                        'event' => 'top_recommend'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '天国ネット動画',
                        'sort_no' => 7,
                        'event' => 'top_movie'
                    ],
                ],
                20 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'today_work'
                    ],
                ],
                21 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'cast'
                    ],
                ],
                22 =>[
                    [
                        'created_at' => time(),
                        'title' => '料金システム',
                        'sort_no' => 1,
                        'event' => 'base_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '延長システム',
                        'sort_no' => 2,
                        'event' => 'extension_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'その他',
                        'sort_no' => 3,
                        'event' => 'other_price'
                    ],
                ],
                23 =>[
                    [
                        'created_at' => time(),
                        'title' => '店舗情報',
                        'sort_no' => 1,
                        'event' => 'shop_info'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'アクセスマップ',
                        'sort_no' => 2,
                        'event' => 'shop_access'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ギャラリー',
                        'sort_no' => 3,
                        'event' => 'shop_gallery'
                    ],
                ],
                24 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'event'
                    ],
                ],
                25 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'cast_news'
                    ],
                ],
                26 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'manager_news'
                    ],
                ],
                27 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'recruit'
                    ],
                ],
            ],
            4 =>[
                28 => [
                    [
                        'created_at' => time(),
                        'title' => '本日の出勤',
                        'sort_no' => 1,
                        'event' => 'top_today_work'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ショップニュース',
                        'sub_title' => 'お店からのお得な情報をピックアップ',
                        'sort_no' => 2,
                        'event' => 'top_shop_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長BLOG',
                        'sub_title' => '店長しか知らない㊙︎な情報を配信',
                        'sort_no' => 3,
                        'event' => 'top_manager_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '写メ日記',
                        'sub_title' => '在籍の女の子を日記をリアルタイムで紹介',
                        'sort_no' => 4,
                        'event' => 'top_cast_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '指名数ランキング',
                        'sort_no' => 5,
                        'event' => 'top_ranking'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長おすすめガール',
                        'sort_no' => 6,
                        'event' => 'top_recommend'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '天国ネット動画',
                        'sort_no' => 7,
                        'event' => 'top_movie'
                    ],
                ],
                29 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'today_work'
                    ],
                ],
                30 =>[
                    [
                        'created_at' => time(),
                        // 'title' => null,
                        'sort_no' => 1,
                        'event' => 'cast'
                    ],
                ],
                31 =>[
                    [
                        'created_at' => time(),
                        'title' => '料金システム',
                        'sort_no' => 1,
                        'event' => 'base_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '延長システム',
                        'sort_no' => 2,
                        'event' => 'extension_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'その他',
                        'sort_no' => 3,
                        'event' => 'other_price'
                    ],
                ],
                32 =>[
                    [
                        'created_at' => time(),
                        'title' => '店舗情報',
                        'sort_no' => 1,
                        'event' => 'shop_info'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'アクセスマップ',
                        'sort_no' => 2,
                        'event' => 'shop_access'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ギャラリー',
                        'sort_no' => 3,
                        'event' => 'shop_gallery'
                    ],
                ],
                33 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'event',
                    ],
                ],
                34 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'cast_news'
                    ],
                ],
                35 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'manager_news'
                    ],
                ],
                36 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'recruit'
                    ],
                ],
            ],
            5 =>[
                37 => [
                    // [
                    //     'created_at' => time(),
                    //     'title' => '本日の出勤',
                    //     'sort_no' => 1
                    // ],
                    [
                        'created_at' => time(),
                        'title' => 'ショップニュース',
                        'sub_title' => 'お店からのお得な情報をピックアップ',
                        'sort_no' => 1,
                        'event' => 'top_shop_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長BLOG',
                        'sub_title' => '店長しか知らない㊙︎な情報を配信',
                        'sort_no' => 2,
                        'event' => 'top_manager_news'
                    ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => '写メ日記',
                    //     'sub_title' => '在籍の女の子を日記をリアルタイムで紹介',
                    //     'sort_no' => 4
                    // ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => '指名数ランキング',
                    //     'sort_no' => 3
                    // ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => '店長おすすめガール',
                    //     'sort_no' => 6,
                    //     'event' => 'top_recommend'
                    // ],
                    [
                        'created_at' => time(),
                        'title' => '天国ネット動画',
                        'sub_title' => '今日はどこで遊ぶ？遊びたい地域から選択できます',
                        'sort_no' => 3,
                        'event' => 'top_movie'
                    ],
                ],
                38 =>[
                    [
                        'created_at' => time(),
                        'title' => 'お品書き',
                        'sort_no' => 1,
                        'event' => 'base_price'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '飲み物',
                        'sort_no' => 2,
                        'event' => 'extension_price'
                    ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => 'その他',
                    //     'sort_no' => 3
                    // ],
                ],
                39 =>[
                    [
                        'created_at' => time(),
                        'title' => '店舗情報',
                        'sort_no' => 1,
                        'event' => 'shop_info'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'アクセスマップ',
                        'sort_no' => 2,
                        'event' => 'shop_access'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ギャラリー',
                        'sort_no' => 3,
                        'event' => 'shop_gallery'
                    ],
                ],
                40 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'event'
                    ],
                ],
                42 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'manager_news'
                    ],
                ],
                42 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'recruit'
                    ],
                ],
            ],
            6 =>[
                43 => [
                    // [
                    //     'created_at' => time(),
                    //     'title' => '本日の出勤',
                    //     'sort_no' => 1
                    // ],
                    [
                        'created_at' => time(),
                        'title' => 'ショップニュース',
                        'sub_title' => 'お店からのお得な情報をピックアップ',
                        'sort_no' => 1,
                        'event' => 'top_shop_news'
                    ],
                    [
                        'created_at' => time(),
                        'title' => '店長BLOG',
                        'sub_title' => '店長しか知らない㊙︎な情報を配信',
                        'sort_no' => 2,
                        'event' => 'top_manager_news'
                    ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => '指名数ランキング',
                    //     'sort_no' => 6
                    // ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => '店長おすすめガール',
                    //     'sort_no' => 6,
                    //     'event' => 'top_recommend'
                    // ],
                    [
                        'created_at' => time(),
                        'title' => '天国ネット動画',
                        'sub_title' => '今日はどこで遊ぶ？遊びたい地域から選択できます',
                        'sort_no' => 3,
                        'event' => 'top_movie'
                    ],
                ],
                44 =>[
                    [
                        'created_at' => time(),
                        'title' => '料金システム',
                        'sort_no' => 1,
                        'event' => 'base_price'
                    ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => '延長システム',
                    //     'sort_no' => 2
                    // ],
                    // [
                    //     'created_at' => time(),
                    //     'title' => 'その他',
                    //     'sort_no' => 3
                    // ],
                ],
                45 =>[
                    [
                        'created_at' => time(),
                        'title' => '店舗情報',
                        'sort_no' => 1,
                        'event' => 'shop_info'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'アクセスマップ',
                        'sort_no' => 2,
                        'event' => 'shop_access'
                    ],
                    [
                        'created_at' => time(),
                        'title' => 'ギャラリー',
                        'sort_no' => 3,
                        'event' => 'shop_gallery'
                    ],
                ],
                46 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'event'
                    ],
                ],
                47 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'manager_news'
                    ],
                ],
                48 =>[
                    [
                        'created_at' => time(),
                        'sort_no' => 1,
                        'event' => 'recruit'
                    ],
                ],
            ],
        ];
        if(isset($tabs[$template][$masterId])) {
            return $tabs[$template][$masterId];
        }
        return [];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $tabMaster = M_Site_Tab::fetchFilteringData(['template' => 0]);
        // $loop = 0;
        // foreach($tabMaster as $master) {
        //     // $formatParameter = [];
        //     $tabs = $this->getTab($master->id,$master->template);
        //     foreach($tabs as $index => $t) {
        //         $formatParameter[$loop]['created_at'] = $t['created_at'];
        //         $formatParameter[$loop]['master_id'] = $master->id;
        //         $formatParameter[$loop]['sort_no'] = $t['sort_no'];
        //         $formatParameter[$loop]['event'] = $t['event'];
        //         if(isset($t['title'])) {
        //             $formatParameter[$loop]['title'] = $t['title'];
        //         } else {
        //             $formatParameter[$loop]['title'] = null;
        //         }
        //         if(isset($t['sub_title'])) {
        //             $formatParameter[$loop]['sub_title'] = $t['sub_title'];
        //         } else {
        //             $formatParameter[$loop]['sub_title'] = null;
        //         }
        //         if(isset($t['content'])) {
        //             $formatParameter[$loop]['content'] = $t['content'];
        //         } else {
        //             $formatParameter[$loop]['content'] = null;
        //         }
        //         $loop++;
        //     }
        // }
        $formatParameter = [
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => '本日の出勤',
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'top_today_work'
            ],
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => 'ショップニュース',
                'sub_title' => 'お店からのお得な情報をピックアップ',
                'sort_no' => 2,
                'event' => 'top_shop_news'
                
            ],
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => '店長BLOG',
                'sub_title' => '店長しか知らない㊙︎な情報を配信',
                'sort_no' => 3,
                'event' => 'top_manager_news'
            ],
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => '写メ日記',
                'sub_title' => '在籍の女の子を日記をリアルタイムで紹介',
                'sort_no' => 4,
                'event' => 'top_cast_news'
            ],
            // [
            //     'created_at' => time(),
            //     'master_id' => 55,
            //     'title' => '指名数ランキング',
            //     'sort_no' => 5,
            //     'event' => 'top_ranking'
            // ],
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => '店長おすすめガール',
                'sub_title' => null,
                'sort_no' => 6,
                'event' => 'top_recommend'
            ],
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => '天国ネット動画',
                'sub_title' => null,
                'sort_no' => 7,
                'event' => 'top_movie'
            ],
            [
                'created_at' => time(),
                'master_id' => 55,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'shop_news'
            ],
            [
                'created_at' => time(),
                'master_id' => 56,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'today_work'
            ],
            [
                'created_at' => time(),
                'master_id' => 57,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'cast'
            ],
            [
                'created_at' => time(),
                'master_id' => 58,
                'title' => '料金システム',
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'base_price'
            ],
            [
                'created_at' => time(),
                'master_id' => 58,
                'title' => '延長システム',
                'sub_title' => null,
                'sort_no' => 2,
                'event' => 'extension_price'
            ],
            [
                'created_at' => time(),
                'master_id' => 58,
                'title' => 'その他',
                'sub_title' => null,
                'sort_no' => 3,
                'event' => 'other_price'
            ],
            [
                'created_at' => time(),
                'master_id' => 59,
                'title' => '店舗情報',
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'shop_info'
            ],
            [
                'created_at' => time(),
                'master_id' => 59,
                'title' => 'アクセスマップ',
                'sub_title' => null,
                'sort_no' => 2,
                'event' => 'shop_access'
            ],
            [
                'created_at' => time(),
                'master_id' => 59,
                'title' => 'ギャラリー',
                'sub_title' => null,
                'sort_no' => 3,
                'event' => 'shop_gallery'
            ],
            [
                'created_at' => time(),
                'master_id' => 60,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'event'
            ],
            [
                'created_at' => time(),
                'master_id' => 61,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'cast_news'
            ],
            [
                'created_at' => time(),
                'master_id' => 62,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'manager_news'
            ],
            [
                'created_at' => time(),
                'master_id' => 63,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'recruit'
            ],
            [
                'created_at' => time(),
                'master_id' => 64,
                'title' => null,
                'sub_title' => null,
                'sort_no' => 1,
                'event' => 'shop_news'
            ],
        ];
        if($formatParameter) {
            M_Site_Detail_Tab::insert($formatParameter);
        }
    }
}
