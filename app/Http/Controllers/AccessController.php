<?php

namespace App\Http\Controllers;

use App\Models\Cast_Image;
use App\Models\D_Site_Blog_Log;
use App\Models\D_Site_Cast_Log;
use App\Models\D_Site_Detail_Log;
use App\Models\M_Site;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    /**
     * アクセス統計
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        } 
        $siteId = $request->site_id ?? 0;
        $categoryId = $request->category_id ?? 1;
        // $inputMonth = $request->month ?? date('Ym');
        $startDate = $request->start_date ? date('Ymd',strtotime($request->start_date)) : date('Ymd');
        $endDate = $request->end_date ? date('Ymd',strtotime($request->end_date)) : date('Ymd');
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $NOW_YMD = strtotime(date('Ym' . '01'));
        for ($i = 0; $i <= 36; $i++) {
            $NowYM1 = date("Ym", strtotime("-" . $i . " month", $NOW_YMD));
            $monthAry[$NowYM1] = substr($NowYM1, 0, 4) . '年' . substr($NowYM1, 4) . '月';
        }
        $categoryData = config('constant.site.category');
        $accessData = [];
        if($categoryId == 1) {
            $accessData['count'][0] = D_Site_Detail_Log::FetchFilteringDateCount(['site_id' => $siteId,'device' => 0,'start_date' => $startDate,'end_date' => $endDate]);
            $accessData['count'][1] = D_Site_Detail_Log::FetchFilteringDateCount(['site_id' => $siteId,'device' => 1,'start_date' => $startDate,'end_date' => $endDate]);
            $accessData['count'][2] = D_Site_Detail_Log::FetchFilteringDateCount(['site_id' => $siteId,'device' => 2,'start_date' => $startDate,'end_date' => $endDate]);
            $accessData['count'][-1] = $accessData['count'][0] + $accessData['count'][1] + $accessData['count'][2];
            
            $accessData['last'][0] = D_Site_Detail_Log::FetchFilteringDateCount(['site_id' => $siteId,'device' => 0,'start_date' => date('Ymd',strtotime('-1 month' . $startDate)),'end_date' => date('Ymd',strtotime('-1 month' . $endDate))]);
            $accessData['last'][1] = D_Site_Detail_Log::FetchFilteringDateCount(['site_id' => $siteId,'device' => 1,'start_date' => date('Ymd',strtotime('-1 month' . $startDate)),'end_date' => date('Ymd',strtotime('-1 month' . $endDate))]);
            $accessData['last'][2] = D_Site_Detail_Log::FetchFilteringDateCount(['site_id' => $siteId,'device' => 2,'start_date' => date('Ymd',strtotime('-1 month' . $startDate)),'end_date' => date('Ymd',strtotime('-1 month' . $endDate))]);
            $accessData['last'][-1] = $accessData['last'][0] + $accessData['last'][1] + $accessData['last'][2];


            $accessData['last_ratio'][0] = $accessData['count'][0] - $accessData['last'][0];
            $accessData['last_ratio'][1] = $accessData['count'][1] - $accessData['last'][1];
            $accessData['last_ratio'][2] = $accessData['count'][2] - $accessData['last'][2];
            $accessData['last_ratio'][-1] = $accessData['count'][-1] - $accessData['last'][-1];

            $pcPersent = 0;
            if($accessData['count'][0] > 0) {
                $pcPersent = round($accessData['count'][0] / $accessData['count'][-1] * 100,1);
            }
            $mobilePersent = 0;
            if($accessData['count'][1] > 0) {
                $mobilePersent = round($accessData['count'][1] / $accessData['count'][-1] * 100,1);
            }
            $phonePersent = 0;
            if($accessData['count'][2] > 0) {
                $phonePersent = round($accessData['count'][2] / $accessData['count'][-1] * 100,1);
            }
            $accessData['persent'] = [$pcPersent,$mobilePersent,$phonePersent];
            
        }
        $castHeaders = [
            'source_name' => 'キャスト名',
            'name' => 'サイト名',
            'total' => '閲覧数',
        ];
        $accessData['cast'] = [];
        if($categoryId == 2) {
            $logData = D_Site_Cast_Log::FetchFilteringDateCount(['site_id' => $siteId,'start_date' => $startDate,'end_date' => $endDate]);
            $castIdAry = [];
            foreach($logData as $data) {
                $castIdAry[] = $data->cast_id;
                $accessData['cast'][] = $data;
                $accessData['chartLabel'][] = $data['source_name'];
                $accessData['chartData'][] = $data['total'];
            }
            //データを合わすために
            $accessData['chartData'][] = 0;
        }
        if($categoryId == 3) {
            $logData = D_Site_Blog_Log::FetchFilteringDateCount(['site_id' => $siteId,'start_date' => $startDate,'end_date' => $endDate]);
            $castIdAry = [];
            foreach($logData as $data) {
                $castIdAry[] = $data->cast_id;
                $accessData['cast'][] = $data;
                $accessData['chartLabel'][] = $data['source_name'];
                $accessData['chartData'][] = $data['total'];
            }
            //データを合わすために
            $accessData['chartData'][] = 0;
        }
        
        return view('admin.access.index',compact('siteId','siteData','categoryData','categoryId','startDate','endDate','monthAry','accessData','castHeaders'));
    }
}
