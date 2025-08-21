<?php

namespace Database\Seeders;

use App\Models\M_Area_Group;
use Illuminate\Database\Seeder;

class MAreaGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            //香川県 37
            [
                'created_at' => time(),
                'category_id' => 37,
                'name' => '東讃地方',
            ],
            [
                'created_at' => time(),
                'category_id' => 37,
                'name' => '西讃地方',
            ],
            //愛媛県 38 
            [
                'created_at' => time(),
                'category_id' => 38,
                'name' => '中予地方',
            ],
            [
                'created_at' => time(),
                'category_id' => 38,
                'name' => '東予地方',
            ],
            [
                'created_at' => time(),
                'category_id' => 38,
                'name' => '南予地方',
            ],
        ];
        M_Area_Group::insert($params);
    }
}
