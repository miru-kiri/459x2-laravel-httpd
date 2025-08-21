<?php

namespace Database\Seeders;

use App\Models\Site_Image;
use App\Models\Site_Info;
use Illuminate\Database\Seeder;

class SiteImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameter = [];
        $siteData = Site_Info::FetchAll();
        foreach($siteData as $site) {
            if(empty($site->image)) {
                continue;
            }
            $parameter[] = [
                'created_at' => time(),
                'site_id' => $site->site_id,
                'category_id' => 1,
                'image' => $site->image,
                'sort_no' => 1,
            ];
        }
        Site_Image::insert($parameter);
    }
}
