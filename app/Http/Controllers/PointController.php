<?php

namespace App\Http\Controllers;

use App\Models\M_Point_Event;
use App\Models\M_Point_User;
use App\Models\M_Site;
use App\Models\Point_User;
use Exception;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'ポイント管理';
        $siteId = $request->site_id ?? 0;
        $is_web = $request->is_web ?? 0;
        $siteControl = [];
        // if(session('role') != 1) {
            // $siteControl = session('site_control');
        // } 
        // ログインユーザーの店舗をデフォルト表示に
        // if(!isset($request->site_id)) {
        //     $siteId = isset(session('site_control')[0]) ? session('site_control')[0] : 0;
        // }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $headers = [
            'card_no' => 'カード番号',
            'site_name' => 'サイト名',
            'name' => 'ユーザー名',
            'birth_day' => '生年月日',
            'tel' => '電話番号',
            'point' => '保有ポイント数',
            'is_web' => 'web会員/カード会員'
        ];
        // $bodys = [];
        // $fetchData = M_Point_User::fetchFilterData(['site_id' => $siteId]);
        // foreach($fetchData as $index => $value) {
        //     if($is_web == 1) {
        //         if(!$value->user_id) {
        //             continue;
        //         }
        //     }
        //     if($is_web == 2) {
        //         if($value->user_id) {
        //             continue;
        //         }
        //     }
        //     $bodys[$index] = $value;
        //     $birth_day = '-';
        //     if($value['year']) {
        //         $age = calculateAge($value['year'].'/'.sprintf('%02d',$value['month']).'/'.sprintf('%02d',$value['day']));
        //         $birth_day = $value['year'].'/'.$value['month'].'/'.$value['day'] . "(".$age."歳)";
        //     }
        //     $bodys[$index]['name'] = "<a href=".route('point.detail',['id' => $value['id']]).">".$value['name']."</a>";
        //     $bodys[$index]['birth_day'] = $birth_day;
        //     $bodys[$index]['point'] = Point_User::fetchValidPoint($value->card_no);
        //     $bodys[$index]['is_web'] = $value->user_id ? 'web会員' : 'カード会員';
        //     $bodys[$index]['tel'] = str_replace('+81',0,$value['tel']);
        // }
        // $json_bodys = json_encode($bodys, JSON_UNESCAPED_UNICODE);
        return view('admin.point.index',compact('siteId','siteData','title','headers','is_web'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFilterData(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        $is_web = $request->is_web ?? 0;
        $bodys = [];
        $cardNoId = [];
        $fetchData = M_Point_User::fetchFilterData(['site_id' => $siteId,'is_web' => $is_web]);
        foreach($fetchData as $index => $value) {
            $birth_day = '-';
            if($value['year']) {
                $age = calculateAge($value['year'].'/'.sprintf('%02d',$value['month']).'/'.sprintf('%02d',$value['day']));
                $birth_day = $value['year'].'/'.$value['month'].'/'.$value['day'] . "(".$age."歳)";
            }
            $cardNoId[] = $value['card_no'];
            $bodys[] = [
                'card_no' => $value['card_no'],
                'site_name' => $value['site_name'],
                'name' => "<a href=".route('point.detail',['id' => $value['id']]).">".$value['name']."</a>",
                'birth_day' => $birth_day,
                'tel' => str_replace('+81',0,$value['tel']),
                'is_web' => $value->user_id ? 'web会員' : 'カード会員'
            ];
        }
        $totalPointDatas = Point_User::fetchFilterSumPoint(['card_no' => $cardNoId]);
        $formatPointDatas = [];
        foreach($totalPointDatas as $pointData) {
            $formatPointDatas[$pointData['card_no']] = $pointData['total_point'];
        }
        foreach($bodys as $index => $body) {
            $bodys[$index]['point'] = 0;
            if(isset($formatPointDatas[$body['card_no']])){
                $bodys[$index]['point'] = $formatPointDatas[$body['card_no']];
            }
        }
        return response()->json($bodys, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $id = $request->id;
        $fetchData = M_Point_User::findOrFail($id);
        $pointHistory = Point_User::fetchFilteringData(['card_no' => $fetchData->card_no]);
        $age = calculateAge($fetchData->year."-".sprintf('%02d', $fetchData->month)."-".sprintf('%02d', $fetchData->day));
        $totalPoint = 0;
        foreach($pointHistory as $index => $history) {
            $time = sprintf('%04d', $history['time']);
            $pointHistory[$index]->date_time = date('Y年m月d日 H時', strtotime($history['date'].$time));
            $category_name = '-';
            $category_color = '';
            if($history['category_id'] == 1) {
                $category_name = '取得';
                $category_color = 'btn-primary';
            }
            if($history['category_id'] == 2) {
                $category_name = '利用';
                $category_color = 'btn-danger';
            }
            if($history['category_id'] == 3) {
                $category_name = '引き継ぎ';
                $category_color = 'btn-info';
            }
            if($history['category_id'] == 4) {
                $category_name = '引き継ぎ';
                $category_color = 'btn-info';
            }
            if($history['category_id'] == 5) {
                $category_name = '追加';
                $category_color = 'btn-primary';
            }
            if($history['category_id'] == 6) {
                $category_name = '削減';
                $category_color = 'btn-danger';
            }
            $pointHistory[$index]->category_name = $category_name;
            $pointHistory[$index]->category_color = $category_color;
            $totalPoint += $history->point;
        }
        return view('admin.point.detail',compact('id','fetchData','pointHistory','totalPoint','age'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $resArray = [
            'result' => 0,
            'message' => '処理に成功しました',
        ];
        $id = $request->id;
        $point = (int)$request->point;
        $category_id = $request->category_id;
        if($point == 0) {
            // session()->flash('warning', '0以上を入力してください。');
            // $previousUrl = app('url')->previous();
            // return redirect()->to($previousUrl);
            $resArray = [
                'result' => 1,
                'message' => '0以上を入力してください。',
            ];
            return response()->json($resArray);
        }
        if(!$category_id) {
            // session()->flash('warning', 'ポイント区分を選択してください。');
            // $previousUrl = app('url')->previous();
            // return redirect()->to($previousUrl);
            $resArray = [
                'result' => 1,
                'message' => 'ポイント区分を選択してください。',
            ];
            return response()->json($resArray);
        }
        // $category_id = 1;
        if($category_id == 6) {
            $point = -$point;
        }
        try {
            $fetchData = M_Point_User::findOrFail($id);
            $parameter = [
                'created_at' => time(),
                'user_id' => $fetchData->user_id,
                'site_id' => $fetchData->site_id,
                'date' => date('Ymd'),
                'time' => date('Hi'),
                'category_id' => $category_id,
                'point' => $point,
                'card_no' => $fetchData->card_no
            ];
            Point_User::insert($parameter);
            $content = "カード番号($fetchData->card_no)".$fetchData->name."に". $point ."ポイント付与しました。";
        } catch (Exception $e) {
            // session()->flash('error', '処理に失敗しました。');
            // $previousUrl = app('url')->previous();
            // return redirect()->to($previousUrl);    
            $resArray = [
                'result' => 1,
                'message' => '処理に失敗しました。',
            ];
            return response()->json($resArray);
        }
        $result = createLog(0,session('id'),2,$content);
        // session()->flash('success', '処理に成功しました。');
        // $previousUrl = app('url')->previous();
        // return redirect()->to($previousUrl);
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function event(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        } 
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $siteName = '';
        foreach($siteData as $site) {
            if(empty($siteId)) {
                $siteId = $site->id;
                $siteName = $site->name;
                break;
            }
        }
        $formatEvent = [];
        $eventData = M_Point_Event::fetchSiteData(['site_id' => $siteId]);
        foreach($eventData as $index => $data) {
            $formatEvent[$index]['id'] = $data->id;
            $formatEvent[$index]['title'] = $data->title ?? '';
            $formatEvent[$index]['start'] = $data->start_date;
            $formatEvent[$index]['end'] = $data->end_date;
            $formatEvent[$index]['start_date'] = $data->start_date;
            $formatEvent[$index]['end_date'] = $data->end_date;
            $formatEvent[$index]['persent'] = $data->persent;            
        }
        return view('admin.point.event',compact('siteId','siteData','formatEvent','siteName'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eventUpsert(Request $request)
    {
        $parameter = [
            'id' => $request->id ?? 0,
            'site_id' => $request->site_id ?? 0,
            'start_date' => $request->start_date ?? 0,
            'end_date' => $request->end_date ?? 0,
            'title' => $request->title ?? null,
            'content' => $request->content ?? null,
            'persent' => $request->persent ?? 1,
        ];
        if($parameter['start_date'] >= $parameter['end_date']) {
            session()->flash('warning', '開始日より終了日の方が早いです。');
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        try {
            $isData = M_Point_Event::siteCheckDate($parameter['id'],$parameter['site_id'],$parameter['start_date'],$parameter['end_date']);
            if($isData) {
                session()->flash('warning', '重複した期間があります。');
                $previousUrl = app('url')->previous();
                return redirect()->to($previousUrl);
            }
            if(!empty($parameter['id'])) {
                //編集
                M_Point_Event::findOrFail($parameter['id'])->fill($parameter)->save();
                $content = "ポイントイベント(".$parameter['id'].")を更新しました。";
            } else {
                //登録
                M_Point_Event::insert($parameter);
                $content = "ポイントイベント(".$parameter['id'].")を登録しました。";
            }
        } catch (Exception $e) {
            session()->flash('error', '処理に失敗しました。');
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);    
        }
        //ログ
        $result = createLog(0,session('id'),2,$content);

        session()->flash('success', '処理に成功しました。');
        $previousUrl = app('url')->previous();
        return redirect()->to($previousUrl);
    }
}
