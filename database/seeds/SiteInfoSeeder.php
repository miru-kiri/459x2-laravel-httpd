<?php

namespace Database\Seeders;

use App\Models\M_Site;
use App\Models\Site_Info;
use Illuminate\Database\Seeder;

class SiteInfoSeeder extends Seeder
{
    public function getColor($template) 
    {
        $color = [
            1 => '#F1747E',
            2 => '#6D66AA',
            3 => '#F684A6',
            4 => '#B66497',
            5 => '#F27847',
            6 => '#C02639',
        ];
        if(isset($color[$template])) {
            $color = $color[$template];
        }
        return $color;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameter = [];
        $siteData = M_Site::FetchAll();
        foreach($siteData as $site) {
            // $formatParameter = [];
            if($site->template == 0) {
                continue;
            }
            $parameter[] = [
                'created_at' => time(),
                'site_id' => $site->id,
                'title' => $site->name,
                'color' => $this->getColor($site->template),
            ];
        }
        Site_Info::insert($parameter);
    }
}
