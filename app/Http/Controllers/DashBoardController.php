<?php

namespace App\Http\Controllers;

use App\Models\Cast_Schedule;
use App\Models\D_Notice_Admin_Header;
use App\Models\D_Reserve;
use App\Models\D_Review;
use App\Models\M_Site;
use Illuminate\Http\Request;

use function Psy\sh;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $siteControl = [];
        $siteId = $request->site_id ?? 0;
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'cast' => '本日出勤中の女の子',
            'reserve' => '本日の予約',
            'not-reserve' => '本日予約がない女の子',
            'price' => '本日の売上',
        ];
        $bodys = [];
        $siteData = M_Site::fetchFilterAryId($siteControl);
        if($siteData->isNotEmpty()){
            foreach($siteData as $index => $site){
                if($siteId > 0 && $site->id != $siteId) {
                    continue;
                }
                $bodys[$site->id]['id'] = $site->id;
                $bodys[$site->id]['site_name'] = $site->name;
            }
            $sheduleData = Cast_Schedule::FetchFilteringBetWeenData(['date'=> date('Ymd'),'first_date' => 0,'end_date' => 0,'cast_id' => 0,'site_id' => $siteId,'is_work' => 1]);
            $formatShedule = [];
            $sheduleCastId[] = 0;
            foreach($sheduleData as $shedule) {
                $formatShedule[$shedule->site_id][$shedule->cast_id] = true;
                $sheduleCastId[] = $shedule->cast_id;
            }
            $reserveData = D_Reserve::FetchFilterCastData(['cast_id' => $sheduleCastId,'start_time' => date('Y-m-d 00:00:00'),'end_time' => date('Y-m-d 04:00:00',strtotime('+1 day')),'site_id' => $siteId,'status' => [1,2,3,4]]);
            $formatReserve = [];
            foreach($reserveData as $reserve) {
                $formatReserve[$reserve->site_id_reserve][$reserve->cast_id][] = $reserve;
            }
        }
        $totalWorkCast = 0;
        $totalReserveCast = 0;
        $totalReserveNotCast = 0;
        $totalPrice = 0;
        foreach($bodys as $key => $body) {
            $workCast = 0;
            if(isset($formatShedule[$key])) {
                $workCast = count($formatShedule[$key]);
            }
            $reserveCast = 0;
            $reserveNotCast = $workCast;
            $price = 0;
            if(isset($formatReserve[$key])) {
                foreach($formatReserve[$key] as $castId => $reserve) {
                    $reserveCast += count($reserve);
                    $reserveNotCast--;
                    foreach($reserve as $r) {
                        $price += $r['amount'];
                    }
                }
            }
            $bodys[$key]['cast'] = $workCast."人";
            $bodys[$key]['reserve'] = $reserveCast."本";
            $bodys[$key]['not-reserve'] = $reserveNotCast."人";
            $bodys[$key]['price'] = number_format($price)."円";
            $totalWorkCast += $workCast;
            $totalReserveCast += $reserveCast;
            $totalReserveNotCast += $reserveNotCast;
            $totalPrice += $price;
        }

        // お知らせ取得
        $formatNoticeData = [];
        if(session('role') != 1) {
            $fetchNoticeDatas = D_Notice_Admin_Header::fetchJoinFilterSiteData(['site_id' => $siteControl,'display_date' => date('Y-m-d H:i:s')]);
            foreach($fetchNoticeDatas as $fetchNoticeData) {
                if(!isset($formatNoticeData[$fetchNoticeData->header_id])) {
                    $formatNoticeData[$fetchNoticeData->header_id] = [
                        'id' => $fetchNoticeData->header_id,
                        'title' => $fetchNoticeData->title,
                        'content' => $fetchNoticeData->content,
                        'type' => $fetchNoticeData->type,
                        'type_text' => $fetchNoticeData->type == 0 ? '記事' : '機能',
                        'display_date' => $fetchNoticeData->display_date,
                    ];
                }
            }
        }
        return view('admin.dashBoard.index',compact('headers','bodys','totalWorkCast','totalReserveCast','totalReserveNotCast','totalPrice','siteData','siteId','formatNoticeData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop()
    {
        //店舗用
        return view('admin.dashBoard.shop');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast()
    {
        //キャスト用
        $siteControl = [];
        
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'cast' => '本日出勤中の女の子',
            'reserve' => '本日の予約',
            'not-reserve' => '本日予約がない女の子',
            'price' => '本日の売上',
        ];
        $bodys = [];
        $siteData = M_Site::fetchFilterAryId($siteControl);
        if($siteData->isNotEmpty()){
            foreach($siteData as $index => $site){
                $bodys[$site->id]['id'] = $site->id;
                $bodys[$site->id]['site_name'] = $site->name;
            }
            $sheduleData = Cast_Schedule::FetchFilteringBetWeenData(['date'=> date('Ymd'),'first_date' => 0,'end_date' => 0,'cast_id' => 0,'site_id' => $siteControl,'is_work' => 1]);
            $formatShedule = [];
            $sheduleCastId = [];
            foreach($sheduleData as $shedule) {
                $formatShedule[$shedule->site_id][$shedule->cast_id] = true;
                $sheduleCastId[] = $shedule->cast_id;
            } 
            $reserveData = [];
            if($sheduleCastId){
                $reserveData = D_Reserve::FetchFilterCastData(['cast_id' => $sheduleCastId,'start_time' => date('Y-m-d 00:00:00'),'end_time' => date('Y-m-d 23:59:59'),'site_id' => 0,'status' => [1,2,3,4]]);
            }
            $formatReserve = [];
            foreach($reserveData as $reserve) {
                $formatReserve[$reserve->site_id_reserve][] = $reserve;
                $reserveCastId[] = $reserve->cast_id;
            }
        }
        $totalWorkCast = 0;
        $totalReserveCast = 0;
        $totalReserveNotCast = 0;
        $totalPrice = 0;
        foreach($bodys as $key => $body) {
            $workCast = 0;
            if(isset($formatShedule[$key])) {
                $workCast = count($formatShedule[$key]);
            }
            $reserveCast = 0;
            $reserveNotCast = 0;
            $price = 0;
            if(isset($formatReserve[$key])) {
                $reserveCast = count($formatReserve[$key]);
                foreach($formatReserve[$key] as $r) {
                    $price += $r['amount'];
                }
            }
            if(!empty($reserveCast) && !empty($workCast)){
                $reserveNotCast = $workCast - $reserveCast;
            }
            $bodys[$key]['cast'] = $workCast."人";
            $bodys[$key]['reserve'] = $reserveCast."本";
            $bodys[$key]['not-reserve'] = $reserveNotCast."人";
            $bodys[$key]['price'] = number_format($price)."円";
            $totalWorkCast += $workCast;
            $totalReserveCast += $reserveCast;
            $totalReserveNotCast += $reserveNotCast;
            $totalPrice += $price;
        }
        // お知らせ取得
        $formatNoticeData = [];
        $fetchNoticeDatas = D_Notice_Admin_Header::fetchJoinFilterCastData(['cast_id' => session('id'),'display_date' => date('Y-m-d H:i:s')]);
        foreach($fetchNoticeDatas as $fetchNoticeData) {
            if(!isset($formatNoticeData[$fetchNoticeData->header_id])) {
                $formatNoticeData[$fetchNoticeData->header_id] = [
                    'id' => $fetchNoticeData->header_id,
                    'title' => $fetchNoticeData->title,
                    'content' => $fetchNoticeData->content,
                    'type' => $fetchNoticeData->type,
                    'type_text' => $fetchNoticeData->type == 0 ? '記事' : '機能',
                    'display_date' => $fetchNoticeData->display_date,
                ];
            }
        }
        return view('admin.dashBoard.cast',compact('headers','bodys','totalWorkCast','totalReserveCast','totalReserveNotCast','totalPrice','formatNoticeData'));
    }
}
