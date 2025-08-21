<?php

namespace App\Http\Controllers;

use App\Models\Cast_Image;
use App\Models\Cast_Schedule;
use App\Models\Cast_Schedule_Setting;
use App\Models\D_Reserve;
use App\Models\D_Review;
use App\Models\D_User;
use App\Models\M_Cast;
use App\Models\M_Site;
use App\Models\Site_Course;
use App\Models\Site_Nomination_Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Jobs\UpdateCastSchedule;
use ReflectionClass;
use App\Models\M_SiteBreak;
use App\Http\Controllers\MyPageController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ReserveController extends Controller
{
    /**
     * デフォルトメッセージ
     *
     * @var array
     */
    protected $resArray = [
        'result' => 0,
        'message' => '処理に成功しました',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $siteId = $request->site_id ?? 0;
        $date = $request->date ?? date('Ymd');
        $isCansel = $request->is_cansel ?? 0;
        $isSearch = $request->is_search ?? 0;
        $searchWord = $request->search_word ?? null;
        $searchWordTarget = $request->search_word_target ? explode(',',$request->search_word_target) : [];
        $searchStatusTarget = $request->search_status_target ? explode(',',$request->search_status_target) : [];
        // 検索の値保持
        $isSearchWord = [
            'all' => true,
            'cast_id' => true,
            'source_name' => true,
            'user_id' => true,
            'user_name' => true,
            'phone' => true,
        ];
        if(!empty($isSearch)) {
            $isSearchWord = [
                'cast_id' => false,
                'source_name' => false,
                'user_id' => false,
                'user_name' => false,
                'phone' => false,
            ];
            $isSearchWordAll = true;
            foreach($isSearchWord as $target => $isValue) {
                if(in_array($target,$searchWordTarget)) {
                    $isSearchWord[$target] = true;
                }
                if(!$isSearchWord[$target]) {
                    $isSearchWordAll = false;
                }
            }
            $isSearchWord['all'] = $isSearchWordAll;
        }
        $isSearchStatus = [];
        $isSearchStatusAll = true;
        for($i=1;$i<6;$i++) {
            if(in_array($i,$searchStatusTarget)) {
                $isSearchStatus[$i] = true;
            } else {
                $isSearchStatus[$i] = false;
                $isSearchStatusAll = false;
            }
        }
        $isSearchStatus['all'] = $isSearchStatusAll;

        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        } 
        $status = [
            [
                'id' => 1,
                'name' => '仮予約',
            ],
            [
                'id' => 2,
                'name' => '確定',
            ],
            [
                'id' => 3,
                'name' => '事前確認',
            ],
            [
                'id' => 4,
                'name' => '完了',
            ],
            [
                'id' => 5,
                'name' => 'キャンセル',
            ],
        ];
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $userData = [];
        $resourceData = [];
        $eventData = [];
        $reserveFreeData = [];
        $firstCast= 0;
        $firstUser= 0;
        $defaultStartDate= date('Y-m-d H:i:00');
        $defaultEndDate= date('Y-m-d H:i:00',strtotime('+1 hour'));
        // $selectSiteId = $siteId;
        if($siteData->isNotEmpty()){
            $userData = D_User::fetchAllData();
            foreach($userData as $data) {
                if(empty($firstUser)) {
                    $firstUser = $data->id;
                    break;
                }
            }
            foreach($siteData as $data) {
                if(empty($siteId)) {
                    $siteId = $data->id;
                    break;
                }
            }
            //キャストの取得
            // $castData = M_Cast::fetchFilterAryId($selectSiteId);
            
            // foreach($castData as $data) {
            //     if(empty($data->is_public)) {
            //         continue;
            //     }
            //     if(empty($firstCast)) {
            //         $firstCast = $data->id;
            //     }
            //     // $resourceData[] = [
            //     //     'id' => $data->id,
            //     //     'title' => $data->source_name,
            //     // ];
            // }
            $filter = [
                // 'site_id' => $request->site_id ?? [],
                'site_id' => $siteId,
                'date' => date('Y-m-d',strtotime($date)),
                'is_work' => 1
            ];
            $selectSiteData = M_Site::findOrFail($siteId);

            $selectSiteBreaksRaw = M_SiteBreak::where('site_id', $siteId)->get();
            $selectSiteBreaks = [];

            foreach ($selectSiteBreaksRaw as $break) {
                $weekday = (int) $break->weekday;
                $selectSiteBreaks[$weekday] = [
                    'start' => $break->break_start,
                    'end' => $break->break_end,
                ];
            }
            $applyToAllDays = false;
            $commonBreakStart = '';
            $commonBreakEnd = '';

            if (count($selectSiteBreaks) === 7) {
                $first = reset($selectSiteBreaks);
                $allSame = true;

                foreach ($selectSiteBreaks as $day => $breakTime) {
                    if (
                        $breakTime['start'] !== $first['start'] ||
                        $breakTime['end'] !== $first['end']
                    ) {
                        $allSame = false;
                        break;
                    }
                }

                if ($allSame) {
                    $applyToAllDays = true;
                    $commonBreakStart = $first['start'];
                    $commonBreakEnd = $first['end'];
                }
            }
            //ネットのコースだけ取得
            $siteCourceData = Site_Course::fetchFilterSiteData($selectSiteData->id,0);
            //指名料取得
            $siteNominationFee = Site_Nomination_Fee::fetchFilterSiteData($selectSiteData->id);
            $castSheduleData = Cast_Schedule::fetchFilteringData($filter);
            $siteOpenTime = $selectSiteData->open_time;
            $siteCloseTime = $selectSiteData->close_time;
            if(!empty($siteOpenTime)) {
                if($siteCloseTime < $siteOpenTime) {
                    $siteCloseTime = $siteCloseTime + 2400;
                }
                $siteOpenTime = date('H:i',strtotime('2001-01-01'.$siteOpenTime));
            } else {
                $siteOpenTime = false;
            }
            if(!empty($siteCloseTime)) {
                $hours = floor($siteCloseTime / 100); // 時間部分
                $minutes = $siteCloseTime % 100; // 分部分
                $siteCloseTime = sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes);
            } else {
                $siteCloseTime = false;
            }
            $castIdAry = [];
            $eventData = [];
            foreach($castSheduleData as $data) {
                if(in_array($data->cast_id,$castIdAry)) {
                    continue;
                }
                $start = date('Y-m-d H:i',strtotime($data->date . $data->start_time));
                $endTime = $data->end_time;
                $end = date('Y-m-d H:i',strtotime($data->date . $endTime));
                //次の日にまたぐ処理
                if($data->start_time > $endTime) {
                    $end = date('Y-m-d H:i',strtotime($end . '+1 days'));
                }
                if($data->end_time == 'LAST') {
                    // $endTime = $siteCloseTime;
                    $end = $data->date ." ".$siteCloseTime;
                }
                if(empty($firstCast)) {
                    $firstCast = $data->cast_id;
                }
                // if($endTime < $data->start_time) {
                //     $endTime = $siteCloseTime;
                // }
                // $end = date('Y-m-d H:i',strtotime($data->date . $endTime));
                $castIdAry[] = $data->cast_id;
                $eventData[] = [
                    "id" => $data->cast_schedule_id,
                    "resourceId" => $data->cast_id,
                    "display" => 'background',
                    // 'title' => '出勤('. $data->start_time .'~' . $data->end_time.')',
                    "start" => $start,
                    "end" => $end
                ];
                $resourceData[] = [
                    'id' => $data->cast_id,
                    'title' => $data->source_name,
                ];
            }
            
            $filter = [
                // 'site_id' => $request->site_id ?? [],
                'cast_id' => $castIdAry,
                'start_time' => date('Y-m-d 00:00:00',strtotime($date)),
                'end_time' => date('Y-m-d 23:59:59',strtotime($date. "+1 day")),
                'site_id' => 0,
                'status' => [1,2,3,4]
            ];
            if(!empty($isCansel)) {
                $filter['status'] = [5];
            }
            if(!empty($searchStatusTarget)) {
                $filter['status'] = $searchStatusTarget;
            }
            $ReserveData = [];
            if($castIdAry) {
                $ReserveData = D_Reserve::fetchFilterCastData($filter);
            }
            // id: '1', resourceId: castId, start: '2023-01-07T02:00:00', end: '2023-01-07T07:00:00', title: 'event 1' },
            $reserveStatusColor = [
                1 => '#F06292',
                2 => '#42A5F5',
                3 => '#FDD835',
                4 => '#66BB6A',
                5 => '#F44336',
            ];
            foreach($ReserveData as $data) {
                if(!empty($searchWord)) {
                    if(!empty($searchWordTarget)) {
                        $isSearchFilter = false;
                        foreach($searchWordTarget as $wordTarget) {
                            //キャストIDでの検索
                            if($wordTarget == 'cast_id') {
                                if(strpos($data->cast_id,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                            }
                            //キャスト名での検索
                            if($wordTarget == 'source_name') {
                                if(strpos($data->source_name,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                            }
                            //会員IDでの検索
                            if($wordTarget == 'user_id') {
                                if(strpos($data->user_id,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                            }
                            //会員名での検索
                            if($wordTarget == 'user_name') {
                                if(strpos($data->user_name,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                                if(strpos($data->name_furigana,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                                if(strpos($data->name_show,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                            }
                            //電話番号での検索
                            if($wordTarget == 'phohe') {
                                if(strpos($data->phone,$searchWord) !== false) {
                                    $isSearchFilter = true;
                                }
                            }
                        }
                        if(!$isSearchFilter) {
                            continue;
                        }
                    }
                }
                $eventData[] = [
                    "id" => $data->id,
                    "resourceId" => $data->cast_id,
                    "title" => $data->course_name,
                    "start" => $data->start_time,
                    "end" => $data->end_time,
                    "status" => $data->status,
                    "type_reserve" => $data->type_reserve,
                    "indicate_fee1" => $data->indicate_fee1,
                    "indicate_fee1_flg" => $data->indicate_fee1_flg,
                    "indicate_fee2" => $data->indicate_fee2,
                    "indicate_fee2_flg" => $data->indicate_fee2_flg,
                    "site_id_reserve" => $data->site_id_reserve,
                    "amount" => $data->amount,
                    "memo" => $data->memo,
                    "address" => $data->address,
                    "course_money" => $data->course_money,
                    "course_time" => $data->course_time,
                    "course_name" => $data->course_name,
                    "user_id" => $data->user_id,
                    "cast_id" => $data->cast_id,
                    "start_time" => $data->start_time,
                    "end_time" => $data->end_time,
                    "backgroundColor" => isset($reserveStatusColor[$data->status]) ? $reserveStatusColor[$data->status] : '',
                    "borderColor" => isset($reserveStatusColor[$data->status]) ? $reserveStatusColor[$data->status] : '',
                    "is_guest" => $data->is_guest,
                    "guest_name" => $data->guest_name,
                    "guest_phone" => $data->guest_phone,
                ];
                //出勤していないけど、予約があれば
                if(!in_array($data->cast_id,$castIdAry)) {
                    $resourceData[] = [
                        'id' => $data->cast_id,
                        'title' => $data->source_name,
                    ];
                }
            }
            $filter = [
                // 'site_id' => $request->site_id ?? [],
                // 'cast_id' => 0,
                // 'start_time' => date('Y-m-d 00:00:00',strtotime('-1 days')),
                'start_time' => 0,
                'end_time' => 0,//とりあえず上限はなし
                // 'site_id' => $siteControl,
                'site_id' => $siteId,
                'status' => 1
            ];
            $reserveFreeData = D_Reserve::fetchFilterStatusUserData($filter);
        }
        //ネット予約設定
        $openTimeAry = [];
        for($i=0; $i < 24;  $i++){
            $time = sprintf('%02d', $i);
            for($j=0; $j < 60; $j = $j + 30){
                $minutes = sprintf('%02d', $j);
                $openTimeAry[] = [
                    'name' => $time.":".$minutes,
                    'value' => $time.$minutes,
                ];
            }
        }
        $closeTimeAry = [];
        for($i=0; $i <= 27;  $i++){
            $time = sprintf('%02d', $i);
            for($j=0; $j < 60; $j = $j + 30){
                $minutes = sprintf('%02d', $j);
                $closeTimeAry[] = [
                    'name' => $time.":".$minutes,
                    'value' => $time.$minutes,
                ];
            }
        }
        $userData;
        return view('admin.reserve.index',compact('siteId','status','userData','siteData','date','resourceData','eventData','firstCast','firstUser','defaultStartDate','defaultEndDate','reserveFreeData','siteOpenTime','siteCloseTime','siteCourceData','siteNominationFee','selectSiteData','openTimeAry','closeTimeAry','isCansel','searchWord','searchWordTarget','searchStatusTarget','isSearchWord','isSearchStatus', 'selectSiteBreaks', 'applyToAllDays', 'commonBreakStart', 'commonBreakEnd'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $parameter = [
                "cast_id" => $request->cast_id ?? null,
                "type" => $request->cast_id ? 0 : 1,
                "start_time" => $request->start_time ? date('Y-m-d H:i:s',strtotime($request->start_time)) : null,
                "end_time" => $request->end_time ? date('Y-m-d H:i:s',strtotime($request->end_time)) : null,
                "course_name" => $request->course_name ?? null,
                "course_time" => $request->course_time ?? 0,
                "course_money" => $request->course_money ?? 0,
                "indicate_fee1" => $request->indicate_fee1 ?? 0,
                "indicate_fee1_flg" => $request->indicate_fee1_flg ?? 0,
                "indicate_fee2" => $request->indicate_fee2 ?? 0,
                "indicate_fee2_flg" => $request->indicate_fee2_flg ?? 0,
                "amount" => $request->amount ?? 0,
                "status" => $request->status ?? 0,
                "user_id" => $request->user_id ?? null,
                "site_id_reserve" => $request->site_id_reserve ?? 0,
                "id" => $request->id ?? 0,
            ];
            if(!empty($parameter['id'])) {
                $parameter['updated_at'] = now();
                D_Reserve::saveData($parameter);
            } else {
                unset($parameter['id']);
                $parameter['created_at'] = now();
                D_Reserve::insert($parameter);
            }
        } catch (\Exception $e) {
            session()->flash('error', '処理に失敗しました。');
        }
        session()->flash('success', '処理に成功しました。');
        $previousUrl = app('url')->previous();
        return redirect()->to($previousUrl);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if(!empty($id)) {
            $parameter = [
                'id' => $id,
                'updated_at' => now(),
                'deleted_at' => now(),
            ];
            D_Reserve::saveData($parameter);
        }
        $previousUrl = app('url')->previous();
        return redirect()->to($previousUrl);
    }
    public function statistics(Request $request)
    {
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        } 
        $siteId = $request->site_id ?? 0;
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');
        $siteData = M_Site::fetchFilterAryId($siteControl);

        $a = date('Y-m-d 00:00:00',strtotime($startDate));
        $b = date('Y-m-d 23:59:59',strtotime($endDate));

        $reserveData = D_Reserve::FetchFilterStatusCastData(['cast_id' => 0,'site_id' => $siteId,'start_time' => date('Y-m-d 00:00:00',strtotime($startDate)),'end_time' => date('Y-m-d 23:59:59',strtotime($endDate)),'status' => 4]);
        
        $totalPrice = 0;
        $averagePrice = 0;
        $reserveCount = 0;
        $averageSite = 0;
        $formatData = [];
        foreach($reserveData as $reserve) {
            $formatData[$reserve->site_id_reserve][] = $reserve;
            $totalPrice += $reserve->amount;
        }
        if(count($reserveData) > 0) {
            $reserveCount = count($reserveData);
            $averagePrice = round($totalPrice / $reserveCount,1);
            $averageSite = round($reserveCount / count($siteData),2);
        }
        //前年比
        $lastReserveData = D_Reserve::FetchFilterStatusCastData(['cast_id' => 0,'site_id' => $siteId,'start_time' => date('Y-m-d 00:00:00',strtotime('-1 month' . $startDate)),'end_time' => date('Y-m-d 23:59:59',strtotime('-1 month' . $endDate)),'status' => 4]);
        $lastTotalPrice = 0;
        $lastReserveCount = 0;
        $lastAveragePrice = 0;
        $lastAverageSite = 0;
        // $lastFormatData = [];
        foreach($lastReserveData as $lastReserve) {
            // $formatData[$reserve->site_id_reserve][] = $reserve;
            $lastTotalPrice += $lastReserve->amount;
        }
        $lastTotalPrice = $totalPrice - $lastTotalPrice;
        if(count($lastReserveData) > 0) {
            $lastReserveCount = count($lastReserveData);
            $lastAveragePrice = round($lastTotalPrice / $lastReserveCount,1);
            $lastAverageSite = round($lastReserveCount / count($siteData),2);
        }
        $lastReserveCount = $reserveCount - $lastReserveCount;
        $lastAveragePrice = $averagePrice - $lastAveragePrice;
        $lastAverageSite = $averageSite - $lastAverageSite;

        $chartLabel = [];
        $chartData = [];
        // $formatPrice = 0;
        foreach($formatData as $key => $val) {
            $formatPrice = 0;
            $siteName = '';
            foreach($val as $v) {
                $formatPrice += $v->amount;
                $siteName = $v->site_name;
            }
            $chartLabel[] = $siteName;
            $chartData[] = $formatPrice;
        }
        $chartData[] = 0;
        return view('admin.reserve.statistics',compact('siteId','siteData','totalPrice','averagePrice','reserveCount','averageSite','startDate','endDate','chartLabel','chartData','lastTotalPrice','lastReserveCount','lastAveragePrice','lastAverageSite'));
    }
    /**
     * 
     * TEL設定ページ
     * @param Request $request
     * @return void
     */
    public function tel(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        $date = $request->date ? date('Ymd',strtotime($request->date)) : date('Ymd');

        $isDataUpdating = $this->hasPendingJobForSiteAndDate($siteId, $request->date);

        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        } 
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $resourceData = [];

        $defaultStartDate= date('Y-m-d H:i:00');
        $defaultEndDate= date('Y-m-d H:i:00',strtotime('+1 hour'));
        // $selectSiteId = $siteId;
        $formatCastSheduleData = [];
        $siteName = null;

        $weekAry = config('constant.week');
        for($i=0;$i<=6;$i++) {
            $formatDate = date('Y-m-d',strtotime($date. "+$i day"));
            $weekText = $weekAry[date('w',strtotime($formatDate))];
            $dateWeekAry[$formatDate] = date('j',strtotime($formatDate)) ."($weekText)";
        }
        if($siteData->isNotEmpty()){
            foreach($siteData as $data) {
                if(empty($siteId)) {
                    $siteId = $data->id;
                    break;
                }
            }
            $filter = [
                // 'site_id' => $request->site_id ?? [],
                'site_id' => $siteId,
                'date' => date('Y-m-d',strtotime($date)),
                'is_work' => 1
            ];
            $selectSiteData = M_Site::findOrFail($siteId);
            $selectSiteBreaksRaw = M_SiteBreak::where('site_id', $siteId)->get();
            $selectSiteBreaks = [];

            foreach ($selectSiteBreaksRaw as $break) {
                $weekday = (int) $break->weekday;
                $selectSiteBreaks[$weekday] = [
                    'start' => $break->break_start,
                    'end' => $break->break_end,
                ];
            }
            $applyToAllDays = false;
            $commonBreakStart = '';
            $commonBreakEnd = '';

            if (count($selectSiteBreaks) === 7) {
                $first = reset($selectSiteBreaks);
                $allSame = true;

                foreach ($selectSiteBreaks as $day => $breakTime) {
                    if (
                        $breakTime['start'] !== $first['start'] ||
                        $breakTime['end'] !== $first['end']
                    ) {
                        $allSame = false;
                        break;
                    }
                }

                if ($allSame) {
                    $applyToAllDays = true;
                    $commonBreakStart = $first['start'];
                    $commonBreakEnd = $first['end'];
                }
            }

            $siteName = $selectSiteData->name;
            //ネットのコースだけ取得
            $siteCourceData = Site_Course::fetchFilterSiteData($selectSiteData->id,0);
            //指名料取得
            $siteNominationFee = Site_Nomination_Fee::fetchFilterSiteData($selectSiteData->id);
            $castSheduleData = Cast_Schedule::fetchFilteringData($filter);
            $siteOpenTime = $selectSiteData->open_time;
            $siteCloseTime = $selectSiteData->close_time;
            
            // if(!empty($siteCloseTime)) {
            //     $hours = floor($siteCloseTime / 100); // 時間部分
            //     $minutes = $siteCloseTime % 100; // 分部分
            //     $siteCloseTime = sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes);
            // } else {
            //     $siteCloseTime = false;
            // }

            // $formatStartTime = strtotime($siteOpenTime);
            // if(strtotime($siteCloseTime) >= $formatStartTime) {
            //     $formatEndTime = strtotime($siteCloseTime);
            // } else {
            //     $formatEndTime = strtotime($siteCloseTime."+1 day");
            // }
            // $currentStartTime = $formatStartTime; // 初期化
            $formatTime = [];
            // while($currentStartTime <= $formatEndTime) {
            //     $timeSlot = date('H:i', $currentStartTime);
            //     $formatTime[$timeSlot] = $timeSlot;
            //     $currentStartTime += 1800; // 30分 (60秒 × 30分)
            // }
            if($siteOpenTime && $siteCloseTime) {
                for ($time = $siteOpenTime; $time <= $siteCloseTime; $time += 10) {
                    if ($time % 100 >= 60) {
                        $time += 40; // 60 - 20 分を追加して 100 分にする
                    }
                    if($time == $siteCloseTime) {
                        break;
                    }
                    // 時間を"hh:mm"形式にフォーマットして配列に追加
                    $hours = floor($time / 100); // 時間部分
                    $minutes = $time % 100; // 分部分
                    // 時間を"hh:mm"形式にフォーマットして配列に追加
                    $timeSlot = sprintf('%02d:%02d', $hours, $minutes);
                    $formatTime[] = $timeSlot;
                }
            }
            
            $formatSheduleData = [];
            $castIdAry = [];
            foreach($castSheduleData as $sheduleData) {
                if(in_array($sheduleData->cast_id,$castIdAry)) {
                    continue;
                }
                $castIdAry[] = $sheduleData->cast_id;
                $formatSheduleData[$sheduleData->cast_id] = $sheduleData;
            }
            $filter = [
                'cast_id' => $castIdAry,
                'date' => date('Y-m-d',strtotime($date)),
            ];
            $formatTelData = [];
            $telDatas = Cast_Schedule_Setting::fetchFilteringData($filter);
            foreach($telDatas as $telData) {
                // $telTime = date('H:i',strtotime($telData->date_time));
                $telTime = $telData->time;
                $formatTelData[$telData->cast_id][$telTime]['id'] = $telData->id;
                $formatTelData[$telData->cast_id][$telTime]['status'] = $telData->status;
            }
            $statusClass = [
                0 => [
                    'text' => '',
                    'class' => 'gray-tel',  //#9e9e9e,
                ],
                1 => [
                    'text' => '',
                    'class' => 'green-tel', //#219653
                ],
                2 => [
                    'text' => 'TEL',
                    'class' => 'red-tel',   //#eb5757
                ],
                3 => [
                    'text' => '-',
                    'class' => 'default-tel', //#fff
                ],
                4 => [
                    'text' => '△',
                    'class' => 'yellow-tel' //#f2c94c
                ]
            ];
            $allShedules = [];
            foreach($formatSheduleData as $castId => $sheduleData) {
                $formatCastSheduleData[$castId]['source_name'] = $sheduleData->source_name;
                $formatCastSheduleData[$castId]['age'] = $sheduleData->age;
                $formatCastSheduleData[$castId]['image'] = '/no-image.jpg';
                $formatCastSheduleData[$castId]['start_time'] = $sheduleData->start_time;
                $formatCastSheduleData[$castId]['end_time'] = $sheduleData->end_time;
                $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castId]);
                if($isCastImage) {                
                    $formatCastSheduleData[$castId]['image'] = $isCastImage->directory . "SM_" . $isCastImage->path;
                }
                $isStartTime = false;
                foreach($formatTime as $time) {
                    $status = 0; //0=予定なし,1=出勤,2=Tel,3=「-」
                    $canselId = 0;
                    $startTime = $sheduleData->start_time;
                    $endTime = $sheduleData->end_time;
                    
                    if($endTime <= '04:00' && $endTime !== 'LAST'){
                        $endTime = str_replace(':','',$endTime);
                        $hours = floor($endTime / 100); // 時間部分
                        $hours = $hours + 24;
                        $minutes = $endTime % 100; // 分部分
                        // 時間を"hh:mm"形式にフォーマットして配列に追加
                        $endTime = sprintf('%02d:%02d', $hours, $minutes);
                    }
                    $formatTwentyFourTime = $time;
                    // 日付と時間を結合してDateTimeオブジェクトを作成
                    $formattedDateTime = \DateTime::createFromFormat('Ymd H:i', $date . ' ' . $time);
                    // フォーマットした日付を取得
                    $dateTime = $formattedDateTime->format('Y-m-d H:i:s');
                    // $dateTime = date('Y-m-d H:i:s',strtotime($date.$time));
                    if($startTime <= $time && $endTime > $time ) {
                        if(!$isStartTime) {
                            $isStartTime = true;
                        }
                        $status = 1;
                    }
                    //LASTまでの場合
                    if($isStartTime && $endTime == 'LAST') {
                        $status = 1;
                    }
                    if(isset($formatTelData[$castId][$time])) {
                        if($formatTelData[$castId][$time]['status'] == 1) {
                            $status = 2; //TEl
                        }
                        if($formatTelData[$castId][$time]['status'] == 2) {
                            $status = 3; //Not Work
                        }
                        $canselId = $formatTelData[$castId][$time]['id'];
                    }
                    $allShedules[$dateTime]['time'] = $time;
                    $allShedules[$dateTime]['status'][] = $status;
                    $formatCastSheduleData[$castId]['time'][$dateTime] = [
                        // 'cast_id' => $castId,
                        // 'source_name' => $sheduleData->source_name,
                        'status' => $status,
                        'status_class' => isset($statusClass[$status]) ? $statusClass[$status]['class'] : '',
                        'status_text' => isset($statusClass[$status]) ? $statusClass[$status]['text'] : '',
                        'cansel_id' => $canselId,
                        'time' => $time
                    ];
                }
            }
            $formatAllSheduleDatas = [];
            foreach ($allShedules as $allDateTime => $allShedule) {
                $isWork = true;
                $isTel = true;
                $isNotWork = true;
                $hasTwoOrThree = false;
            
                foreach ($allShedule['status'] as $allStatusAry) {
                    if ($allStatusAry !== 1) {
                        $isWork = false;
                    }
                    if ($allStatusAry !== 2) {
                        $isTel = false;
                    }
                    if ($allStatusAry !== 3) {
                        $isNotWork = false;
                    }
                    if ($allStatusAry === 2 || $allStatusAry === 3) {
                        $hasTwoOrThree = true;
                    }
                }
                $formatAllSheduleDatas[$allDateTime]['status'] = 0;
                if ($hasTwoOrThree) {
                    $formatAllSheduleDatas[$allDateTime]['status'] = 4;
                }
                if ($isWork) {
                    $formatAllSheduleDatas[$allDateTime]['status'] = 1;
                }
                if ($isTel) {
                    $formatAllSheduleDatas[$allDateTime]['status'] = 2;
                }
                if ($isNotWork) {
                    $formatAllSheduleDatas[$allDateTime]['status'] = 3;
                }
                
                $formatAllSheduleDatas[$allDateTime]['status_class'] = isset($statusClass[$formatAllSheduleDatas[$allDateTime]['status']]) ? $statusClass[$formatAllSheduleDatas[$allDateTime]['status']]['class'] : '';
                $formatAllSheduleDatas[$allDateTime]['status_text'] = isset($statusClass[$formatAllSheduleDatas[$allDateTime]['status']]) ? $statusClass[$formatAllSheduleDatas[$allDateTime]['status']]['text'] : '';
                $formatAllSheduleDatas[$allDateTime]['time'] = $allShedule['time'];
            }
        }
        //ネット予約設定
        $openTimeAry = [];
        for($i=0; $i < 24;  $i++){
            $time = sprintf('%02d', $i);
            for($j=0; $j < 60; $j = $j + 30){
                $minutes = sprintf('%02d', $j);
                $openTimeAry[] = [
                    'name' => $time.":".$minutes,
                    'value' => $time.$minutes,
                ];
            }
        }
        $closeTimeAry = [];
        for($i=0; $i <= 27;  $i++){
            $time = sprintf('%02d', $i);
            for($j=0; $j < 60; $j = $j + 30){
                $minutes = sprintf('%02d', $j);
                $closeTimeAry[] = [
                    'name' => $time.":".$minutes,
                    'value' => $time.$minutes,
                ];
            }
        }
        // dd($formatAllSheduleDatas);
        return view('admin.reserve.tel',compact('siteId','siteData','date','resourceData','defaultStartDate','defaultEndDate','siteOpenTime','siteCloseTime','siteCourceData','siteNominationFee','formatTime','formatCastSheduleData','siteName','dateWeekAry','formatAllSheduleDatas','castIdAry','openTimeAry','closeTimeAry','selectSiteData', 'isDataUpdating', 'selectSiteBreaks', 'applyToAllDays', 'commonBreakStart', 'commonBreakEnd'));
    }
    /**
     * 
     * TELデータの更新ページ
     * @param Request $request
     * @return
     */
    public function telUpdate(Request $request)
    {
        $castId = $request->cast_id;
        $status = $request->status;
        $dateTime = $request->date_time;
        $date = $request->date;
        $time = $request->time;
        $canselId = $request->cansel_id;
        $resArray = $this->resArray;
        if(empty($castId) || empty($status) || empty($time) ) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        try {
            if(!empty($canselId)) {
                Cast_Schedule_Setting::findOrFail($canselId)->delete();
            }
            // -1はリセット
            if($status != -1) {
                $parameter = [
                    'date_time' => $dateTime,
                    'date' => date('Y-m-d',strtotime($date)),
                    'time' => $time,
                    'cast_id' => $castId,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                Cast_Schedule_Setting::insert($parameter);
            }
        } catch(\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
    /**
     * 
     * TELデータの更新ページ
     * @param Request $request
     * @return
     */
    public function telUpdateAll(Request $request)
    {
        $siteId = $request->site_id;
        $castIdAry = $request->cast_id;
        $status = $request->status;
        $dateTime = $request->date_time;
        $date = $request->date;
        $time = $request->time;
        $resArray = $this->resArray;
        if(empty($castIdAry) || empty($status)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        try {
            
            $parameter = [];
            if($dateTime == -1) {
                //時間指定なし
                Cast_Schedule_Setting::whereIn('cast_id',$castIdAry)->where('date',date('Y-m-d',strtotime($date)))->delete();
                $siteData = M_Site::findOrFail($siteId);
                $siteOpenTime = $siteData->open_time;
                $siteCloseTime = $siteData->close_time;
                if(empty($siteOpenTime) || empty($siteCloseTime)){
                    $resArray = [
                        'result' => 1,
                        'message' => 'サイトの出勤時間と退勤時間の設定を行なってください。',
                    ];
                    return response()->json($resArray);
                }
                $filter = [
                    'cast_id' => $castIdAry,
                    'date' => date('Y-m-d',strtotime($date)),
                    'is_work' => 1,
                ];
                $castWorkDatas = Cast_Schedule::fetchFilteringCastData($filter);
                $formatWorkCasts = [];
                foreach($castWorkDatas as $castWorkData) {
                    $castWorkData->start_time = str_replace(":", "", $castWorkData->start_time);
                    if($castWorkData->end_time == 'LAST') {
                        $castWorkData->end_time = $siteCloseTime;
                    } else {
                        $castWorkData->end_time = str_replace(":", "", $castWorkData->end_time);
                    }
                    if($castWorkData->end_time <= '04:00' && $castWorkData->end_time !== 'LAST'){
                        $endTime = str_replace(':','',$castWorkData->end_time);
                        $hours = floor($endTime / 100); // 時間部分
                        $hours = $hours + 24;
                        $minutes = $endTime % 100; // 分部分
                        // 時間を"hh:mm"形式にフォーマットして配列に追加
                        $castWorkData->end_time = sprintf('%02d%02d', $hours, $minutes);
                    }
                    $formatWorkCasts[$castWorkData->cast_id] = $castWorkData;
                }
                $now = now();
                foreach($formatWorkCasts as $castId => $workData) {
                    for ($i = $workData->start_time; $i < $workData->end_time; $i += 10) {
                        // 時間と分を取り出して計算する
                        $hours = floor($i / 100); // 時間部分
                        $minutes = $i % 100;      // 分部分
                        if ($minutes >= 60) {
                            $hours += 1;         // 1時間繰り上げ
                            $minutes -= 60;      // 分を0からスタート
                        }
                        // 新しい $i の値を設定
                        $i = $hours * 100 + $minutes;
                        $formatHours = floor($i / 100); // 時間部分
                        $formatMinutes = $i % 100; // 分部分
                        // フォーマットした日付を取得
                        $formattedDateTime = \DateTime::createFromFormat('Ymd H:i', $date . ' ' . sprintf("%02d:%02d", $formatHours, $formatMinutes));
                        $formatDateTime = $formattedDateTime->format('Y-m-d H:i:s');
                        $parameter[] = [
                            'date_time' => $formatDateTime,
                            'date' => date('Y-m-d', strtotime($date)),
                            'time' => sprintf("%02d:%02d", $formatHours, $formatMinutes),
                            'cast_id' => $castId,
                            'status' => $status,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
                }
                // for ($time = $siteOpenTime; $time <= $siteCloseTime; $time += 30) {
                //     if ($time % 100 >= 60) {
                //         $time += 40; // 60 - 20 分を追加して 100 分にする
                //     }
                //     // 時間を"hh:mm"形式にフォーマットして配列に追加
                //     $hours = floor($time / 100); // 時間部分
                //     $minutes = $time % 100; // 分部分
                //     // 時間を"hh:mm"形式にフォーマットして配列に追加
                //     $timeSlot = sprintf('%02d:%02d', $hours, $minutes);
                //     $formatTime[] = $timeSlot;
                // }
                // foreach($formatTime as $time) {
                //     // 日付と時間を結合してDateTimeオブジェクトを作成
                //     $formattedDateTime = \DateTime::createFromFormat('Ymd H:i', $date . ' ' . $time);
                //     // フォーマットした日付を取得
                //     $dateTime = $formattedDateTime->format('Y-m-d H:i:s');
                //     foreach($castIdAry as $castId) {
                //         $parameter[] = [
                //             'date_time' => $dateTime,
                //             'date' => date('Y-m-d',strtotime($date)),
                //             'time' => $time,
                //             'cast_id' => $castId,
                //             'status' => $status,
                //             'created_at' => now(),
                //             'updated_at' => now()
                //         ];
                //     }
                // }

            } else {
                //時間指定あり
                Cast_Schedule_Setting::whereIn('cast_id',$castIdAry)->where('date_time',$dateTime)->delete();
                foreach($castIdAry as $castId) {
                    $parameter[] = [
                        'date_time' => $dateTime,
                        'date' => date('Y-m-d',strtotime($date)),
                        'time' => $time,
                        'cast_id' => $castId,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            // -1はリセットなので削除のみ
            if($status == -1) {
                return response()->json($resArray);
            }
            if($parameter) {
                Cast_Schedule_Setting::insert($parameter);
            }
        } catch(\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }

    public function updateMultiTell(Request $request)
    {
        $siteId = $request->site_id;
        $castIdAry = $request->cast_id;
        $status = $request->status;
        $dateTime = $request->date_time;
        $date = $request->date;
        $time = $request->input('time');

        $isDataUpdating = $this->hasPendingJobForSiteAndDate($siteId, $request->date);

        if($isDataUpdating) {
            return response()->json([
                'result' => 0,
                'type' => 'warning',
                'message' => "データを更新中です。続行する前にお待ちください。"
            ]);
        }

        // Chuẩn hóa lại status
        foreach ($status as $key => $statusArray) {
            foreach ($statusArray as $index => $value) {
                if ($value == 0 || $value == 1) {
                    $status[$key][$index] = -1;
                } elseif ($value == 2) {
                    $status[$key][$index] = 1;
                } else {
                    $status[$key][$index] = 2;
                }
            }
        }

        try {
            $batchData = [];

            foreach ($castIdAry as $key => $castIds) {
                if (!empty($castIds)) {
                    foreach ($castIds as $index => $castId) {
                        if ((int)$castId > 0) {
                            $batchData[] = [
                                'castId' => $castId,
                                'status' => $status[$key][$index] ?? null,
                                'dateTime' => $dateTime[$key][$index] ?? null,
                                'date' => $date,
                                'time' => $time[$key][$index] ?? null,
                            ];
                        }
                    }
                }
            }

            // Chunk mỗi 50 record và dispatch 1 job
            collect($batchData)
                ->groupBy('castId')
                ->each(function ($castGroup) use ($siteId, $date) {
                    dispatch(new \App\Jobs\UpdateCastScheduleBatch(
                        $castGroup->values()->toArray(),
                        $siteId,
                        $date
                    ));
                });

            return response()->json([
                'result' => 0,
                'type' => 'success',
                'message' => 'データを処理中です。すぐに更新されます!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => 1,
                'message' => 'エラーが発生しました: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * 
     * ネット予約の設定編集
     * @param Request $request
     * @return
     */
    public function setting(Request $request)
    {
        $previousUrl = app('url')->previous();
        $siteId = $request->site_id;
        $openTime = $request->open_time;
        $closeTime = $request->close_time;
        // $closedDays = $request->input('closed_days', []);
        $reserveApprovalDate = $request->reserve_approval_date;
        $reserveCloseBranch = $request->reserve_close_branch;
        $reserveClose = $request->reserve_close;
        $reserveBufferTime = $request->reserve_buffer_time;
        $breakTimes = $request->input('break_times', []);

        $applyToAllDays = $request->input('apply_to_all_days');

        if(empty($siteId)){
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        try {
            $siteData = M_Site::findOrFail($siteId);
            $siteData->fill([
                'updated_at' => time(),
                'open_time' => $openTime,
                'close_time' => $closeTime,
                'reserve_approval_date' => $reserveApprovalDate,
                'reserve_buffer_time' => $reserveBufferTime,
                'reserve_close_branch' => $reserveCloseBranch,
                'reserve_close' => $reserveClose,
                // 'closed_days' => $closedDays,
            ])->save();

            M_SiteBreak::where('site_id', $siteId)->delete();

            if ($applyToAllDays) {
                foreach (range(0, 6) as $weekday) {
                    M_SiteBreak::create([
                        'site_id' => $siteId,
                        'weekday' => $weekday,
                        'break_start' => '0000',
                        'break_end' => '2730',
                    ]);
                }
            } else {
                foreach ($breakTimes as $weekday => $timeRange) {
                    if (!empty($timeRange['start']) && !empty($timeRange['end'])) {
                        if ($timeRange['end'] != "0000" ) {
                            M_SiteBreak::create([
                                'site_id' => $siteId,
                                'weekday' => $weekday,
                                'break_start' => $timeRange['start'],
                                'break_end' => $timeRange['end'],
                            ]);
                        }
                    }
                }
            }
        } catch(\Exception $e) {
            Log::debug($e->getMessage());
            session()->flash('error', '処理に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('success', '処理に成功しました。');
        return redirect()->to($previousUrl);
    }
        /**
     * 
     * ネット予約の設定編集
     * @param Request $request
     * @return
     */
    public function reserveCount(Request $request)
    {
        $resArray = [
            'result' => 0,
            'message' => '処理に成功しました',
            'data' => []
        ];
        $siteIdAry = [];
        if(session('role') != 1) {
            $siteIdAry = session('site_control');
        }
        // $resArray['data'] = D_Reserve::FetchFilterStatusCastData(['cast_id' => 0,'site_id' => $siteIdAry,'start_time' => date('Y-m-d H:i:s',),'end_time' => 0,'status' => 1]);
        $reserveData = D_Reserve::FetchFilterStatusCastData(['cast_id' => 0,'site_id' => $siteIdAry,'start_time' => date('Y-m-d 00:00:00'),'end_time' => 0,'status' => 1]);
        if($reserveData->isNotEmpty()) {
            foreach($reserveData as $key => $data) {
                $formatMinute = date('H:i',strtotime($data->start_time));
                $diffDay = 0;
                if($formatMinute < '04:00') {
                    $diffDay = -1;
                }
                $reserveData[$key]['date'] = date('Y/m/d',strtotime($data->start_time . "$diffDay day"));
            }
        }
        $resArray['data'] = $reserveData;
        return response()->json($resArray);
    }

    function hasPendingJobForSiteAndDate($siteId, $date)
    {
        $jobs = DB::table('jobs')->get();

        foreach ($jobs as $job) {
            $payload = json_decode($job->payload, true);

            if (!isset($payload['data']['command'])) {
                continue;
            }

            $command = @unserialize($payload['data']['command']);
            if (!$command) continue;

            if ($command instanceof \App\Jobs\UpdateCastScheduleBatch) {
                $jobSiteId = $this->getPropertyValue($command, 'siteId');
                $jobDate = $this->getPropertyValue($command, 'date');

                if ($jobSiteId == $siteId && $jobDate == $date) {
                    return true;
                }
            }
        }

        return false;
    }

    function getPropertyValue($object, string $property)
    {
        $reflection = new ReflectionClass($object);
        if (!$reflection->hasProperty($property)) {
            return null;
        }
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }

    public function checkUpdateSuccess(Request $request) {
        $siteId = $request->site_id;
        $date = $request->date;
        $isDataUpdating = $this->hasPendingJobForSiteAndDate($siteId, $date);
        if ($isDataUpdating) {
            return response()->json([
                'result' => 1,
                'message' => "データを更新中です。続行する前にお待ちください。"
            ]);
        } else {
            return response()->json([
                'result' => 0,
                'message' => "データの更新に成功しました。"
            ]);
        }
    }

    public function createTelOrder(Request $request) {
        $guestUser = D_User::where('email', 'guest@example.com')->firstOrFail();
        $userId = $guestUser->id;
        $userToken = Hash::make($userId);
        D_User::findOrFail($userId)->fill(['last_login' => now()])->save();
        session([
            "user_id"  => $userId,
            "user_token" => $userToken,
            "redirect_to_admin_order" => true
        ]);
        $castId = $request->cast_id ?? 0;
        $siteId = $request->site_id ?? 0;
        $isFree = false;
        if(empty($castId)) {
            $isFree = true;
        }
        $myPageController = new MyPageController();
        $tabs = $myPageController->getTabs(-1);
        $data = D_User::findOrFail($userId);
        $castData = [];
        $indicateData = [];
        if(!empty($castId)){
            $castData = M_Cast::fetchFilterId($castId);
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castId]);
            $castData->image = '/no-image.jpg';
            if($isCastImage) {                
                $castData->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            }  
            $siteId = $castData->site_id;
            $indicateData = Site_Nomination_Fee::fetchFilterSiteData($siteId);
        }

        $courseData = Site_Course::fetchFilterSiteData($siteId,0);
        return view('mypages.reserveCourse',compact('castId','siteId','userId','data','tabs','castData','courseData','indicateData','isFree'));
    }

    public function setGuestInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);


        session([
            'guest_data' => [
                'name' => $request->name,
                'phone' => $request->phone,
            ],
        ]);

        return response()->json(['status' => 'ok']);
    }
}
