<?php

namespace Database\Seeders;

use App\Models\D_Site_Detail_Tab;
use App\Models\D_Site_Tab;
use App\Models\M_Site;
use App\Models\M_Site_Detail_Tab;
use Illuminate\Database\Seeder;

class DSiteDetailTabSeeder extends Seeder
{
    public function getColor($template) {
        $tabs = [
            1 => "#EF747D",
            2 => "#6D66AA",
            3 => "#F684A6",
            4 => "#B66497",
            5 => "#F27847",
            6 => "#C02639",
        ];
        if(!isset($tabs[$template])) {
            return null;
        }
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
        $loop = 0;
        //特定のサイトに入れ込みたい場合はこちらを使ったらいける
        // $id = [115,147];
        $siteTabs = D_Site_Tab::fetchData();
        
        foreach($siteTabs as $tab) {
            //特定のサイトに入れ込みたい場合はこちらを使ったらいける
            // if(!in_array($tab->site_id,$id)) {
            //     continue;
            // }
            $siteDetailTab = M_Site_Detail_Tab::fetchFilteringData(['master_id' => $tab->master_id,'is_display' => 0]);
            $masterIdAry = D_Site_Detail_Tab::fetchFilteringDataPluckId(['site_id' => $tab->site_id]);
            // $color = $this->getColor($site->template);
            foreach($siteDetailTab as $detailTab) {
                if(!in_array($detailTab->id,$masterIdAry)) {
                    $formatParameter[$loop]['created_at'] = time();
                    $formatParameter[$loop]['master_id'] = $detailTab->master_id;
                    $formatParameter[$loop]['master_detail_id'] = $detailTab->id;
                    $formatParameter[$loop]['site_id'] = $tab->site_id;
                    $formatParameter[$loop]['title'] = $detailTab->title;
                    $formatParameter[$loop]['sub_title'] = $detailTab->sub_title;
                    $formatParameter[$loop]['content'] = $detailTab->content;
                    $formatParameter[$loop]['sort_no'] = $detailTab->sort_no;
                    $formatParameter[$loop]['is_display'] = $detailTab->is_display;
                    $formatParameter[$loop]['event'] = $detailTab->event;
                    // $formatParameter[$loop]['color'] = $color;
                    $loop++;
                }
            }
        }
        $parameter = collect($formatParameter);
        foreach ($parameter->chunk(1000) as $chunkParams) {
            D_Site_Detail_Tab::insert($chunkParams->toArray());
        }
    }
}
