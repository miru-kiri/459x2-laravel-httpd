<?php

namespace Database\Seeders;

use App\Models\M_Genre_Group;
use Illuminate\Database\Seeder;

class MGenreGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            //高収入 1
            [
                'created_at' => time(),
                'category_id' => 1,
                'name' => 'アダルト',
            ],
            //乾杯系 2
            [
                'created_at' => time(),
                'category_id' => 2,
                'name' => 'バー・ラウンジ・キャバクラ',
            ],
            [
                'created_at' => time(),
                'category_id' => 2,
                'name' => '宴会・ホテル',
            ],
            [
                'created_at' => time(),
                'category_id' => 2,
                'name' => '案内所・フロント',
            ],
            //一般系 3
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '事務・経理',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '不動産・建物',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '美容・エステ',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '健康・マッサージ',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '福祉・弁護',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '仲介・コンサルタント',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => '広告宣伝',
            ],
            [
                'created_at' => time(),
                'category_id' => 3,
                'name' => 'その他・サービス',
            ]
        ];
        M_Genre_Group::insert($params);
    }
}
