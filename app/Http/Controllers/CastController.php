<?php

namespace App\Http\Controllers;

use App\Models\Cast_Answer;
use App\Models\Cast_Image;
use App\Models\Cast_Schedule;
use App\Models\Clone_Cast_Shedule_Log;
use App\Models\D_Cast_Blog;
use App\Models\M_Cast;
use App\Models\M_Cast_Option;
use App\Models\M_Cast_Question;
use App\Models\M_Point_User;
use App\Models\M_Shop;
use App\Models\M_Site;
use App\Models\X459x_Cast;
use App\Models\X459x_Cast_Answer;
use App\Models\X459x_Cast_Image;
use App\Models\X459x_Cast_Shedule;
use App\Models\X459x_Option;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\TimeHelper;

class CastController extends Controller
{
    /**
     * キャスト一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $headers = [
            'id' => 'ID',
            'source_name' => '源氏名',
            'site_name' => 'サイト名',
            'is_public' => '公開フラグ',
        ];
        //新規追加ために
        $bloodType = config('constant.cast.blood_type');
        $constellation = config('constant.cast.constellation');
        $cup = config('constant.cast.cup');
        $figure = config('constant.cast.figure');
        $approvalStatus = config('constant.cast.approval_status');
        $stayStatus = config('constant.cast.stay_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteId = $request->site_id ?? 0;
        $getParameter = $request->tab ?? null;
        $siteData = M_Site::fetchFilterAryId($siteControl);
        foreach ($siteData as $site) {
            if (empty($siteId)) {
                $siteId = $site->id;
                break;
            }
        }

        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $castDatas = M_Cast::filteringMultiSiteData($filter);
        $siteDetail = M_Site::find($siteId);
        foreach ($castDatas as $key => $cast) {
            $castDatas[$key]->image = '/no-image.jpg';
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if ($isCastImage) {
                $castDatas[$key]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            }
            $castDatas[$key]->stay_status_text = isset($stayStatus[$cast->stay_status]) ? $stayStatus[$cast->stay_status] : '';
            $castDatas[$key]->exclusive_status_text = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $castDatas[$key]->exclusive_status_text = $exclusiveStatu['name'];
                    continue;
                }
            }
            // $castDatas[$key]->format_created_at = date('Y/m/d H:i:s',$cast['created_at']);
            // $castDatas[$key]->format_updated_at = date('Y/m/d H:i:s',$cast['updated_at']);
        }
        $targetSite = M_Site::findOrFail($siteId);
        $targetDate = date('Y-m-d');
        if (!empty($targetSite->close_time)) {
            $closeTime = $targetSite->close_time;
            if ($targetSite->close_time >= 2400) {
                $closeTime = $closeTime - 2400;
                if ($closeTime >= date('Hi')) {
                    $targetDate = date('Y-m-d', strtotime('-1 day'));
                }
            }
        }
		$castIdAry = [];
        $todayWorkCast = Cast_Schedule::fetchFilteringData(['cast_id' => 0, 'date' => $targetDate, 'first_date' => 0, 'end_date' => 0, 'site_id' => $siteId, 'is_work' => 1, 'sokuhime_sort' => 'ASC', 'is_public' => 1]);
        foreach ($todayWorkCast as $key => $todayCast) {
			if (in_array($todayCast->cast_id, $castIdAry)) {
                unset($todayWorkCast[$key]);
                continue;
            }
			$castIdAry[] = $todayCast->cast_id;
            $todayWorkCast[$key]->image = '/no-image.jpg';
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $todayCast->cast_id]);
            if ($isCastImage) {
                $todayWorkCast[$key]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }
        $imadakeStatus = config('constant.cast.imadake_status');
        // $startTime = strtotime('10:00');
        $startTime = strtotime('06:00');
        while ($startTime <= strtotime('5:30 +1 day')) {
            $imadakeStatus[date('H:i', $startTime)] = "次回" . date('H:i', $startTime) . "~";
            // $startTime += 1800; // 1時間を追加
            $startTime += 600; // 1時間を追加
        }
        $imadakeStatus['LAST'] = "LAST";
        $optionDatas = [];
        $fetchOptionDatas = M_Cast_Option::FetchFilterData(['site_id' => $siteId]);
        foreach ($fetchOptionDatas as $fetchOptionData) {
            $optionDatas[] = [
                'value' => $fetchOptionData['id'],
                'name' => $fetchOptionData['name']
            ];
        }

        return view('admin.cast.index', compact('headers', 'siteId', 'siteData', 'castDatas', 'todayWorkCast', 'imadakeStatus', 'bloodType', 'constellation', 'cup', 'figure', 'approvalStatus', 'getParameter', 'stayStatus', 'exclusiveStatus', 'optionDatas'));
    }
    /**
     * キャスト一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function castProfile(Request $request)
    {
        $castId = session('id');
        $data = M_Cast::fetchFilterId($castId)->toArray();
        $formColums = [
            ['label' => '源氏名', 'name' => 'source_name', 'type' => 'text'],
            ['label' => '投稿メール', 'name' => 'post_email', 'type' => 'disabled'],
            // ['label' => 'キャッチコピー','name' => 'catch_copy','type' => 'textarea'],
            // ['label' => 'サイト名','name' => 'site_id','type' => 'select'],
            ['label' => '年齢', 'name' => 'age', 'type' => 'select'],
            ['label' => '血液型', 'name' => 'blood_type', 'type' => 'select'],
            ['label' => '星座', 'name' => 'constellation', 'type' => 'select'],
            ['label' => '身長', 'name' => 'height', 'type' => 'select'],
            ['label' => 'バスト', 'name' => 'bust', 'type' => 'select'],
            ['label' => 'カップ', 'name' => 'cup', 'type' => 'select'],
            ['label' => 'ウェスト', 'name' => 'waist', 'type' => 'select'],
            ['label' => 'ヒップ', 'name' => 'hip', 'type' => 'select'],
            // ['label' => '体型','name' => 'figure','type' => 'select'],
            // ['label' => '体型コメント','name' => 'figure_comment','type' => 'textarea'],
            ['label' => '自己PR', 'name' => 'self_pr', 'type' => 'textarea'],
            // ['label' => '店長PR','name' => 'shop_manager_pr','type' => 'textarea'],
            ['label' => '転送先設定', 'name' => 'transfer_mail', 'type' => 'textarea'],
            // ['label' => '画像','name' => 'face_image','type' => 'file'],
            // ['label' => 'WEB順','name' => 'sort','type' => 'text'],
            // ['label' => 'おすすめ','name' => 'is_recommend','type' => 'switch'],
            // ['label' => '公開','name' => 'is_public','type' => 'switch'],
        ];
        $getParameter = $request->tab ?? null;

        $ageAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 18; $i <= 60; $i++) {
            $ageAry[] = [
                'value' => $i,
                'name' => $i . "才"
            ];
        }
        $heightAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 140; $i <= 190; $i++) {
            $heightAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }
        $bustAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 70; $i <= 120; $i++) {
            $bustAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }
        $waistAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 50; $i <= 90; $i++) {
            $waistAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }
        $hipAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 70; $i <= 100; $i++) {
            $hipAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }

        $selectColums = [
            'site_id' => [],
            'blood_type' => config('constant.cast.blood_type'),
            'constellation' => config('constant.cast.constellation'),
            'cup' => config('constant.cast.cup'),
            'figure' => config('constant.cast.figure'),
            'age' => $ageAry,
            'height' => $heightAry,
            'bust' => $bustAry,
            'waist' => $waistAry,
            'hip' => $hipAry,
        ];
        $fetchSite = M_Site::fetchAll();
        foreach ($fetchSite as $index => $site) {
            $selectColums['site_id'][$index]['value'] = $site->id;
            $selectColums['site_id'][$index]['name'] = $site->name;
        }

        $questionHeaders = [
            'sort_no' => 'ID',
            'question' => '質問内容',
            'answer' => '回答',
            'is_public' => '公開設定',
        ];

        $questionDatas = M_Cast_Question::fetchFilteringSiteId($data['site_id']);

        $answerDatas = Cast_Answer::fetchFilteringCastId($data['id']);
        $formatAnswerData = [];
        foreach ($answerDatas as $answerData) {
            $formatAnswerData[$answerData->question_id]['answer'] = $answerData->answer;
            $formatAnswerData[$answerData->question_id]['is_public'] = $answerData->is_public;
        }
        $formatQuestionData = [];
        foreach ($questionDatas as $index => $questionData) {
            $formatQuestionData[$index]['question_id'] = $questionData->id;
            $formatQuestionData[$index]['answer'] = $questionData->default_answer;
            $formatQuestionData[$index]['question'] = $questionData->question;
            $formatQuestionData[$index]['sort_no'] = $questionData->sort_no;
            $formatQuestionData[$index]['is_public'] = $questionData->is_public;
            if (isset($formatAnswerData[$questionData->id])) {
                $formatQuestionData[$index]['answer'] = $formatAnswerData[$questionData->id]['answer'];
                $formatQuestionData[$index]['is_public'] = $formatAnswerData[$questionData->id]['is_public'];
            }
        }

        // $castImageDatas = Cast_Image::fetchFilteringData(['cast_id' => $data['id']]);

        return view('admin.cast.profile', compact('castId', 'formColums', 'selectColums', 'data', 'questionHeaders', 'formatQuestionData', 'getParameter'));
    }
    /**
     * キャスト一覧を条件に応じて取得
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFilteringData(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        if (session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $data = M_Cast::filteringMultiSiteData($filter);
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * キャスト詳細
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $castId = $request->id;
        $data = M_Cast::fetchFilterId($castId)->toArray();
        $castOption = explode(',', $data['option']);
        $formColums = [
            ['label' => '源氏名', 'name' => 'source_name', 'type' => 'text'],
            ['label' => 'キャッチコピー', 'name' => 'catch_copy', 'type' => 'textarea'],
            ['label' => 'サイト名', 'name' => 'site_id', 'type' => 'select'],
            ['label' => 'キャストコード', 'name' => 'cast_cd', 'type' => 'text'],
            ['label' => '年齢', 'name' => 'age', 'type' => 'select'],
            ['label' => '在籍状態', 'name' => 'stay_status', 'type' => 'select'],
            ['label' => '限定状態', 'name' => 'exclusive_status', 'type' => 'select'],
            ['label' => '血液型', 'name' => 'blood_type', 'type' => 'select'],
            ['label' => '星座', 'name' => 'constellation', 'type' => 'select'],
            ['label' => '身長', 'name' => 'height', 'type' => 'select'],
            ['label' => 'バスト', 'name' => 'bust', 'type' => 'select'],
            ['label' => 'カップ', 'name' => 'cup', 'type' => 'select'],
            ['label' => 'ウェスト', 'name' => 'waist', 'type' => 'select'],
            ['label' => 'ヒップ', 'name' => 'hip', 'type' => 'select'],
            ['label' => 'オプション', 'name' => 'option', 'type' => 'multiy_select'],
            ['label' => '体型', 'name' => 'figure', 'type' => 'select'],
            ['label' => '体型コメント', 'name' => 'figure_comment', 'type' => 'textarea'],
            ['label' => '自己PR', 'name' => 'self_pr', 'type' => 'textarea'],
            ['label' => '店長PR', 'name' => 'shop_manager_pr', 'type' => 'textarea'],
            ['label' => '転送先設定', 'name' => 'transfer_mail', 'type' => 'textarea'],
            // ['label' => '画像','name' => 'face_image','type' => 'file'],
            // ['label' => 'WEB順','name' => 'sort','type' => 'text'],
            // ['label' => 'おすすめ','name' => 'is_recommend','type' => 'switch'],
            ['label' => 'メッセージ', 'name' => 'approval_status', 'type' => 'select'],
            ['label' => '公開', 'name' => 'is_public', 'type' => 'switch'],
        ];
        $getParameter = $request->tab ?? null;
        $timeAry = [];
        // $startTime = strtotime('10:00');
        $startTime = strtotime('06:00');
        while ($startTime <= strtotime('5:30 +1 day')) {
            $timeAry[] = date('H:i', $startTime);
            $startTime += 1800; // 1分を追加
        }
        $formatAutoWeek = [];
        $autoWeek = explode(",", $data['auto_week']);
        $weekAry = [
            '日' => 'Sun',
            '月' => 'Mon',
            '火' => 'Tue',
            '水' => 'Wed',
            '木' => 'Thu',
            '金' => 'Fri',
            '土' => 'Sat'
        ];
        foreach ($autoWeek as $aw) {
            $formatAutoWeek[$aw] = true;
        }
        $firstDate = $request->start_date ?? date('Y-m-01');
        $sheduleEvent = $request->shedule_event ?? null;
        if ($sheduleEvent == 'next') {
            $firstDate = date('Y-m-d', strtotime($firstDate . '+1 month'));
        }
        if ($sheduleEvent == 'previous') {
            $firstDate = date('Y-m-d', strtotime($firstDate . '-1 month'));
        }
        $endDate = date('Y-m-d', strtotime($firstDate . '+1 month'));
        $dateArray = array();

        $currentDate = strtotime($firstDate);
        $endTimestamp = strtotime($endDate);


        $weekArray = array();
        $dateArrayWeek = array();
        $weekStartDate = $currentDate;
        $weekEndDate = strtotime('+1 week', $weekStartDate);
        while ($currentDate < $endTimestamp) {
            $dateArray[] = date('Y-m-d', $currentDate);
            $dateArrayWeek[] = date('Y-m-d', $currentDate);
            // 1週間経過したら、週の配列を作成してリセットする
            if ($currentDate >= $weekEndDate || count($dateArrayWeek) >= 7) {
                $weekArray[] = $dateArrayWeek; // 週の配列を保存
                $dateArrayWeek = array(); // 日付の配列をリセット
                $weekStartDate = $weekEndDate; // 週の開始日を更新
                $weekEndDate = strtotime('+1 week', $weekStartDate); // 週の終了日を更新
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // 最後の週の残りの日付を処理する
        if (!empty($dateArrayWeek)) {
            $weekArray[] = $dateArrayWeek;
        }

        $formatCastSheduleDatas = [];
        $sheduleDatas = Cast_Schedule::fetchFilteringBetWeenData(['date' => 0, 'first_date' => $firstDate, 'end_date' => $endDate, 'cast_id' => $castId, 'site_id' => 0]);

        foreach ($sheduleDatas as $sheduleData) {
            $formatCastSheduleDatas[$sheduleData->date] = $sheduleData;
        }
        $ageAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 18; $i <= 60; $i++) {
            $ageAry[] = [
                'value' => $i,
                'name' => $i . "才"
            ];
        }
        $heightAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 140; $i <= 190; $i++) {
            $heightAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }
        $bustAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 70; $i <= 120; $i++) {
            $bustAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }
        $waistAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 50; $i <= 90; $i++) {
            $waistAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }
        $hipAry[] = ['value' => 0, 'name' => '非公開'];
        for ($i = 70; $i <= 100; $i++) {
            $hipAry[] = [
                'value' => $i,
                'name' => $i . "cm"
            ];
        }

        $stayStatusAry = [];
        foreach (config('constant.cast.stay_status') as $value => $name) {
            $stayStatusAry[] = [
                'value' => $value,
                'name' => $name
            ];
        }
        $fetchOptionData = M_Cast_Option::FetchFilterData(['site_id' => $data['site_id']]);
        $optionAry = [];
        foreach ($fetchOptionData as $optionData) {
            $optionAry[] = [
                'value' => $optionData['id'],
                'name' => $optionData['name']
            ];
        }
        $selectColums = [
            'site_id' => [],
            'blood_type' => config('constant.cast.blood_type'),
            'constellation' => config('constant.cast.constellation'),
            'cup' => config('constant.cast.cup'),
            'figure' => config('constant.cast.figure'),
            'age' => $ageAry,
            'height' => $heightAry,
            'bust' => $bustAry,
            'waist' => $waistAry,
            'hip' => $hipAry,
            'approval_status' => config('constant.cast.approval_status'),
            'stay_status' => $stayStatusAry,
            'exclusive_status' => config('constant.cast.exclusive_status'),
            'option' => $optionAry,
        ];

        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $fetchSite = M_Site::fetchFilterAryId($siteControl);
        // $fetchSite = M_Site::fetchAll();
        foreach ($fetchSite as $index => $site) {
            $selectColums['site_id'][$index]['value'] = $site->id;
            $selectColums['site_id'][$index]['name'] = $site->name;
        }

        $questionHeaders = [
            'sort_no' => 'ID',
            'question' => '質問内容',
            'answer' => '回答',
            'is_public' => '公開設定',
        ];

        $questionDatas = M_Cast_Question::fetchFilteringSiteId($data['site_id']);

        $answerDatas = Cast_Answer::fetchFilteringCastId($data['id']);
        $formatAnswerData = [];
        foreach ($answerDatas as $answerData) {
            $formatAnswerData[$answerData->question_id]['answer'] = $answerData->answer;
            $formatAnswerData[$answerData->question_id]['is_public'] = $answerData->is_public;
        }
        $formatQuestionData = [];
        foreach ($questionDatas as $index => $questionData) {
            $formatQuestionData[$index]['question_id'] = $questionData->id;
            $formatQuestionData[$index]['answer'] = $questionData->default_answer;
            $formatQuestionData[$index]['question'] = $questionData->question;
            $formatQuestionData[$index]['sort_no'] = $questionData->sort_no;
            $formatQuestionData[$index]['is_public'] = $questionData->is_public;
            if (isset($formatAnswerData[$questionData->id])) {
                $formatQuestionData[$index]['answer'] = $formatAnswerData[$questionData->id]['answer'];
                $formatQuestionData[$index]['is_public'] = $formatAnswerData[$questionData->id]['is_public'];
            }
        }

        $castImageDatas = Cast_Image::fetchFilteringData(['cast_id' => $data['id'],'site_id' => 0]);

        return view('admin.cast.detail', compact('castId', 'formColums', 'selectColums', 'data', 'castOption', 'questionHeaders', 'formatQuestionData', 'castImageDatas', 'timeAry', 'weekAry', 'dateArray', 'formatCastSheduleDatas', 'formatAutoWeek', 'getParameter', 'weekArray', 'firstDate'));
    }
    public function create(Request $request)
    {
        $previousUrl = app('url')->previous();
        if (empty($request->site_id)) {
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        try {
            DB::beginTransaction();
            $siteData = M_Site::findOrFail($request->site_id);
            while (true) {
                $token = Str::random(20);
                if (!M_Cast::checkToken($token)) {
                    break;
                }
            }
            $parameter = [
                "created_at" => time(),
                "updated_at" => time(),
                "source_name" => $request->source_name ?? null,
                "catch_copy" => $request->catch_copy ?? null,
                "cast_cd" => $request->cast_cd ?? null,
                "shop_id" => $siteData->shop_id,
                "site_id" => $request->site_id,
                "age" => !empty($request->age) ? $request->age : null,
                "blood_type" => !empty($request->blood_type) ? $request->blood_type : null,
                "constellation" => !empty($request->constellation) ? $request->constellation : null,
                "height" => !empty($request->height) ? $request->height  : null,
                "bust" => !empty($request->bust) ? $request->bust : null,
                "cup" => !empty($request->cup) ? $request->cup : null,
                "waist" => !empty($request->waist) ? $request->waist : null,
                "hip" => !empty($request->hip) ? $request->hip : null,
                "figure" => !empty($request->figure) ? $request->figure : 0,
                "figure_comment" => $request->figure_comment ?? null,
                "self_pr" => $request->self_pr ?? null,
                "shop_manager_pr" => $request->shop_manager_pr ?? null,
                "is_public" => isset($request->is_public) ? 1 : 0,
                "transfer_mail" => $request->transfer_mail ?? null,
                // "username" => $request->user_name,
                // "password" => Hash::make($request->password),
                // "post_email" => "$request->user_name@sub0000531330.hmk-temp.com",
                "generate_link_register_at" => now(),
                "token_register" => $token,
                "approval_status" => !empty($request->approval_status) ? $request->approval_status : 1,
                "stay_status" => !empty($request->stay_status) ? $request->stay_status : 1,
                "exclusive_status" => !empty($request->exclusive_status) ? $request->exclusive_status : 1,
            ];
            $castOptions = $request->option;
            $option = "";
            if ($castOptions) {
                foreach ($castOptions as $castOption) {
                    if (empty($option)) {
                        $option = $castOption;
                    } else {
                        $option .= ',' . $castOption;
                    }
                }
            }
            $parameter['option'] = $option;
            $castId = M_Cast::insertGetId($parameter);

            $stayStatusAry = config('constant.cast.stay_status_old');
            $exclusiveStatusAry = config('constant.cast.exclusive_status');
            $figureAry = config('constant.cast.figure');
            $exclusiveStatusText = '50_none';
            foreach ($exclusiveStatusAry as $exclusiveStatus) {
                if ($exclusiveStatus['value'] == $parameter['stay_status']) {
                    $exclusiveStatusText = $exclusiveStatus['old_value'];
                    break;
                }
            }
            $figureText = 'none';
            foreach ($figureAry as $figure) {
                if ($figure['value'] == $parameter['figure']) {
                    $figureText = $figure['old_value'];
                    break;
                }
            }

            $oldParameter = [
                'staffid' => $castId,
                'st1' => $parameter['site_id'],
                'st2' => $parameter['source_name'],
                'st3' => $parameter['catch_copy'],
                'st4' => $parameter['shop_id'],
                'st5' => $stayStatusAry[$parameter['stay_status']],
                'st6' => $exclusiveStatusText,
                'st7' => $parameter['age'] == 0 ? 'none' : $parameter['age'],
                'st8' => $parameter['blood_type'] == 0 ? 'none' : $parameter['blood_type'],
                'st9' => $parameter['constellation'] == 0 ? 'none' : $parameter['constellation'],
                'st10' => $parameter['height'] == 0 ? 'none' : $parameter['height'],
                'st11' => $parameter['bust'] == 0 ? 'none' : $parameter['bust'],
                'st12' => $parameter['cup'] == 0 ? 'none' : $parameter['cup'],
                'st13' => $parameter['waist'] == 0 ? 'none' : $parameter['waist'],
                'st14' => $parameter['hip'] == 0 ? 'none' : $parameter['hip'],
                'st15' => $figureText,
                'st16' => $parameter['figure_comment'],
                'st17' => $parameter['self_pr'],
                'st18' => $parameter['shop_manager_pr'],
                // 'post_mail' => 'st19',
                // 'huzoku_dx_id' => 'st20',
                // 'sokuhime_date' => 'st21',
                // 'sokuhime_status' => 'st22',
                // 'is_sokuhime' => 'st23',
                'st24'  => $parameter['option'],
                'st25'  => $parameter['transfer_mail'],
                // 'recruit_status' => 'st26',
                // 'serch_word' => 'st27',
                // 'is_auto' => 'st61',
                // 'auto_start_time' => 'st62',
                // 'auto_end_time' => 'st63',
                // 'auto_rest_comment' => 'st64',
                // 'auto_week' => 'st65',
                // 'sort' => 'st76',
                'st77' => $parameter['is_public'] == 1 ? 'start' : 'stop',
                'st78' => 'none',
                'st79' => $parameter['updated_at'],
                'st80' => $parameter['created_at']
            ];
            X459x_Cast::insert($oldParameter);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
            return redirect()->to($previousUrl);
        }
        session()->flash('success', '処理が成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return
     */
    public function baseEdit(Request $request)
    {
        $castId = $request->id ?? 0;
        $previousUrl = app('url')->previous();
        if (empty($castId)) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        try {
            DB::beginTransaction();
            $castData = M_Cast::findOrFail($castId);
            $parameter = [
                "updated_at" => time(),
                "source_name" => $request->source_name ?? null,
                "catch_copy" => $request->catch_copy ?? null,
                "cast_cd" => $request->cast_cd ?? null,
                "site_id" => $request->site_id ?? 0,
                "age" => !empty($request->age) ? $request->age : null,
                "blood_type" => !empty($request->blood_type) ? $request->blood_type : null,
                "constellation" => !empty($request->constellation) ? $request->constellation : null,
                "height" => !empty($request->height) ? $request->height : null,
                "bust" => !empty($request->bust) ? $request->bust : null,
                "cup" => !empty($request->cup) ? $request->cup : null,
                "waist" => !empty($request->waist) ? $request->waist : null,
                "hip" => !empty($request->hip) ? $request->hip : null,
                "figure" => !empty($request->figure) ? $request->figure : 0,
                "figure_comment" => $request->figure_comment ?? null,
                "self_pr" => $request->self_pr ?? null,
                "shop_manager_pr" => $request->shop_manager_pr ?? null,
                "transfer_mail" => $request->transfer_mail ?? null,
                // "sort" => $request->sort ?? null,
                "is_public" => isset($request->is_public) ? 1 : 0,
                // "is_recommend" => isset($request->is_recommend) ? 1 : 0,
                "approval_status" => !empty($request->approval_status) ? $request->approval_status : 1,
                "stay_status" => !empty($request->stay_status) ? $request->stay_status : 1,
                "exclusive_status" => !empty($request->exclusive_status) ? $request->exclusive_status : 1,
            ];
            $castOptions = $request->option;
            $option = "";
            if ($castOptions) {
                foreach ($castOptions as $castOption) {
                    if (empty($option)) {
                        $option = $castOption;
                    } else {
                        $option .= ',' . $castOption;
                    }
                }
            }
            $parameter['option'] = $option;
            $castData->fill($parameter)->save();

            $stayStatusAry = config('constant.cast.stay_status_old');
            $exclusiveStatusAry = config('constant.cast.exclusive_status');
            $figureAry = config('constant.cast.figure');
            $exclusiveStatusText = '50_none';
            foreach ($exclusiveStatusAry as $exclusiveStatus) {
                if ($exclusiveStatus['value'] == $parameter['stay_status']) {
                    $exclusiveStatusText = $exclusiveStatus['old_value'];
                    break;
                }
            }
            $figureText = 'none';
            foreach ($figureAry as $figure) {
                if ($figure['value'] == $parameter['figure']) {
                    $figureText = $figure['old_value'];
                    break;
                }
            }
            $siteData = M_Site::findOrFail($parameter['site_id']);
            $oldParameter = [
                // 'staffid' => $castId,
                'st1' => $parameter['site_id'],
                'st2' => $parameter['source_name'],
                'st3' => $parameter['catch_copy'],
                'st4' => $siteData->shop_id,
                'st5' => $stayStatusAry[$parameter['stay_status']],
                'st6' => $exclusiveStatusText,
                'st7' => $parameter['age'] == 0 ? 'none' : $parameter['age'],
                'st8' => $parameter['blood_type'] == 0 ? 'none' : $parameter['blood_type'],
                'st9' => $parameter['constellation'] == 0 ? 'none' : $parameter['constellation'],
                'st10' => $parameter['height'] == 0 ? 'none' : $parameter['height'],
                'st11' => $parameter['bust'] == 0 ? 'none' : $parameter['bust'],
                'st12' => $parameter['cup'] == 0 ? 'none' : $parameter['cup'],
                'st13' => $parameter['waist'] == 0 ? 'none' : $parameter['waist'],
                'st14' => $parameter['hip'] == 0 ? 'none' : $parameter['hip'],
                'st15' => $figureText,
                'st16' => $parameter['figure_comment'],
                'st17' => $parameter['self_pr'],
                'st18' => $parameter['shop_manager_pr'],
                // 'post_mail' => 'st19',
                // 'huzoku_dx_id' => 'st20',
                // 'sokuhime_date' => 'st21',
                // 'sokuhime_status' => 'st22',
                // 'is_sokuhime' => 'st23',
                'st24'  => $parameter['option'],
                'st25'  => $parameter['transfer_mail'],
                // 'recruit_status' => 'st26',
                // 'serch_word' => 'st27',
                // 'is_auto' => 'st61',
                // 'auto_start_time' => 'st62',
                // 'auto_end_time' => 'st63',
                // 'auto_rest_comment' => 'st64',
                // 'auto_week' => 'st65',
                // 'sort' => 'st76',
                'st77' => $parameter['is_public'] == 1 ? 'start' : 'stop',
                'st78' => 'none',
                'st79' => $parameter['updated_at'],
                // 'st80' => $parameter['created_at']
            ];
            X459x_Cast::findOrFail($castId)->fill($oldParameter)->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('status', '登録に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return
     */
    public function castProfileEdit(Request $request)
    {
        $castId = $request->id ?? 0;
        $previousUrl = app('url')->previous();
        if (empty($castId)) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        try {
            DB::beginTransaction();
            $castData = M_Cast::findOrFail($castId);
            $parameter = [
                "updated_at" => time(),
                "source_name" => $request->source_name ?? null,
                // "catch_copy" => $request->catch_copy ?? null,
                // "site_id" => $request->site_id ?? 0,
                "age" => !empty($request->age) ? $request->age : null,
                "blood_type" => !empty($request->blood_type) ? $request->blood_type : null,
                "constellation" => !empty($request->constellation) ? $request->constellation : null,
                "height" => !empty($request->height) ? $request->height : null,
                "bust" => !empty($request->bust) ? $request->bust : null,
                "cup" => !empty($request->cup) ? $request->cup : null,
                "waist" => !empty($request->waist) ? $request->waist : null,
                "hip" => !empty($request->hip) ? $request->hip : null,
                // "figure" => !empty($request->figure) ? $request->figure : 1,
                // "figure_comment" => $request->figure_comment ?? null,
                "self_pr" => $request->self_pr ?? null,
                // "shop_manager_pr" => $request->shop_manager_pr ?? null,
                "transfer_mail" => $request->transfer_mail ?? null,
                // "sort" => $request->sort ?? null,
                // "is_public" => isset($request->is_public) ? 1 : 0,
                // "is_recommend" => isset($request->is_recommend) ? 1 : 0,
            ];
            $castData->fill($parameter)->save();

            $oldParameter = [
                // 'staffid' => $castId,
                // 'st1' => $parameter['site_id'],
                'st2' => $parameter['source_name'],
                // 'st3' => $parameter['catch_copy'],
                // 'st4' => $parameter['shop_id'],
                // 'st5' => $stayStatusAry[$parameter['stay_status']],
                // 'st6' => $exclusiveStatusText,
                'st7' => $parameter['age'] == 0 ? 'none' : $parameter['age'],
                'st8' => $parameter['blood_type'] == 0 ? 'none' : $parameter['blood_type'],
                'st9' => $parameter['constellation'] == 0 ? 'none' : $parameter['constellation'],
                'st10' => $parameter['height'] == 0 ? 'none' : $parameter['height'],
                'st11' => $parameter['bust'] == 0 ? 'none' : $parameter['bust'],
                'st12' => $parameter['cup'] == 0 ? 'none' : $parameter['cup'],
                'st13' => $parameter['waist'] == 0 ? 'none' : $parameter['waist'],
                'st14' => $parameter['hip'] == 0 ? 'none' : $parameter['hip'],
                // 'st15' => $figureText,
                // 'st16' => $parameter['figure_comment'],
                'st17' => $parameter['self_pr'],
                // 'st18' => $parameter['shop_manager_pr'],
                'st25'  => $parameter['transfer_mail'],
                // 'st77' => $parameter['is_public'] == 1 ? 'start' : 'stop',
                'st79' => $parameter['updated_at']
            ];
            X459x_Cast::findOrFail($castId)->fill($oldParameter)->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('status', '登録に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return
     */
    public function questionEdit(Request $request)
    {
        $isAdmin = session('is_admin') ?? 0;
        $castId = $request->cast_id ?? 0;
        $siteId = $request->site_id ?? 0;
        $previousUrl = route('cast.profile', ['id' => $castId, 'tab' => 'activity']);
        if (!empty($isAdmin)) {
            $previousUrl = route('cast.detail', ['id' => $castId, 'tab' => 'activity']);
        }
        if (empty($castId) || empty($siteId)) {
            session()->flash('error', '登録に失敗しました。');
            return redirect($previousUrl);
        }
        $siteQuestion = M_Cast_Question::fetchFilteringSiteId($siteId);
        try {
            DB::beginTransaction();
            $parameter = [];
            $oldParameter = [];
            $time = time();
            foreach ($siteQuestion as $question) {
                $answer = $request->input("answer-" . $question['id']) ?? null;
                $isPublic = $request->input("is_public-" . $question['id']) ? 1 : 0;
                $answerData = Cast_Answer::fetchFilteringData(['cast_id' => $castId, 'question_id' => $question['id']]);
                if ($answerData) {
                    Cast_Answer::where(['cast_id' => $castId, 'question_id' => $question['id']])->update(['answer' => $answer, 'is_public' => $isPublic, 'updated_stamp' => $time]);
                    X459x_Cast_Answer::where(['prf1' => $castId, 'prf2' => $question['id']])->update(['prf3' => $answer, 'prf4' => $isPublic == 1 ? 'start' : 'stop', 'prf20' => $time]);
                } else {
                    $parameter[] = [
                        'cast_id' => $castId,
                        'question_id' => $question['id'],
                        'answer' => $answer,
                        'is_public' => $isPublic,
                        'created_stamp' => $time,
                    ];
                    $oldParameter[] = [
                        'prf1' => $castId,
                        'prf2' => $question['id'],
                        'prf3' => $answer,
                        'prf4' => $isPublic == 1 ? 'start' : 'stop',
                        'prf20' => $time,
                    ];
                }
            }
            if ($parameter) {
                Cast_Answer::insert($parameter);
            }
            if ($oldParameter) {
                X459x_Cast_Answer::insert($oldParameter);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', '登録に失敗しました。');
            return redirect($previousUrl);
        }
        session()->flash('status', '登録に成功しました。');
        return redirect($previousUrl);
    }
    /**
     * キャストのアバター画像の追加
     *
     * @param Request $request
     * @return
     */
    public function castAvatar(Request $request)
    {
        $file = $request->file;
        $castId = $request->cast_id;
        if (!$file) {
            return response()->json([
                'result' => 1,
                'message' => 'ファイルが指定されていないです。'
            ]);
        }
        if (empty($castId)) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }

        $fileExtension = $file->getClientOriginalExtension();
        $formatFile = ['jpeg', 'png', 'gif', 'jpg', 'bmp', 'webp', 'tiff'];

        if (!in_array(strtolower($fileExtension), $formatFile)) {
			return response()->json([
                'result' => 1,
                'message' => 'ファイルは形式が正しくありません。'
            ]);
        }
        $defaultPath = "casts/{$castId}";
        try {
            $path = $defaultPath . "/$castId-" . date('Y-m-d-H:i:s') . '.' . $fileExtension; // 圧縮された画像を保存するパス
            $filePath = $file->path(); // もしくは $file->getRealPath();
            $fileSize = filesize($filePath);
            $image = \Image::make($file)->orientate(); // Exifデータを読み取り画像の向きを修正
            if ($fileSize > 1000000) { // 100MBより大きい場合100 * 
                if (strtolower($fileExtension) === 'jpeg' || strtolower($fileExtension) === 'jpg') {
                    $image->encode('jpg', 50); // フォーマットを取得して指定
                } else {
                    $image->encode(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), 50); // フォーマットを取得して指定
                }
                \Storage::disk('public')->put($path, $image->stream()); // 画像をストレージに保存
            } else {
                \Storage::disk('public')->put($path, file_get_contents($file)); // 画像をストレージに保存
            }

            $castData = M_Cast::findOrFail($castId);
            $castAvatar = $castData->avatar;
            $castData->fill(['updated_at' => time(), 'avatar' => $path])->save();
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json([
                'result' => 1,
                'message' => $e->getMessage()
            ]);
        }
        try {
            //unlink
            if ($castAvatar) {
                if (file_exists($castAvatar)) {
                    unlink($castAvatar);
                }
            }
        } catch (\Exception $e) {
            \Log::debug($e);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
        // return response()->json(asset('/storage/'.$path));
    }
    /**
     * 即姫データ
     *
     * @return \Illuminate\Http\Response
     */
    public function castSokuhime(Request $request)
    {
        $previousUrl = app('url')->previous();
        $sokuhimeDate = $request->sokuhime_date;
        $sokuhimeStatus = $request->sokuhime_status;
        $isSokuhimeAry = $request->is_sokuhime;
        $sokuhimeComment = $request->sokuhime_comment;
        try {
            DB::beginTransaction();
            foreach ($sokuhimeStatus as $castId => $val) {
                $isSokuhime = 0;
                if (isset($isSokuhimeAry[$castId])) {
                    $isSokuhime = 1;
                }
                $castData = M_Cast::findOrFail($castId);
                if ($castData->sokuhime_status !== $val) {
                    $castData->last_updated_status_at = now();
                }
                $castData->fill([
                    'sokuhime_date' => $sokuhimeDate[$castId],
                    'sokuhime_status' => $val,
                    'is_sokuhime' => $isSokuhime,
                    'sokuhime_comment' => $sokuhimeComment[$castId],
                ])->save();
                // 'sokuhime_date' => 'st21',
                // 'sokuhime_status' => 'st22',
                // 'is_sokuhime' => 'st23',
                if ($request->getHost() != 'develop.459x.com') {
                $castOldData = X459x_Cast::findOrFail($castId);
                    $castOldData->fill([
                        'st21' => $sokuhimeDate[$castId],
                        'st22' => $val,
                        'st23' => $isSokuhime == 1 ? 'YES' : 'NO',
                    ])->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::debug($e);
        }
        session()->flash('status', '登録に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * 自動スケジュール登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castAutoEdit(Request $request)
    {
        $castId = $request->cast_id ?? 0;
        $previousUrl = route('cast.detail', ['id' => $castId, 'tab' => 'shedule']);
        // $previousUrl = app('url')->previous();
        if (empty($castId)) {
            session()->flash('error', '不正なパラメータです。');
            return redirect($previousUrl);
        }
        $castData = M_Cast::findOrFail($castId);
        $isAuto = $request->is_auto ? 1 : 0;
        $autoStartTime = $request->auto_start_time;
        $autoEndTime = $request->auto_end_time;
        $autoComment = $request->auto_comment ?? null;
        $weekAry = config('constant.cast.shedule_week');
        $autoWeek = null;
        $time = time();
        foreach ($weekAry as $label => $val) {
            if ($request->{"is_auto_week_$val"}) {
                if (!empty($autoWeek)) {
                    $autoWeek .= "," . $val;
                } else {
                    $autoWeek = $val;
                }
            }
        }
        $castData->fill([
            "updated_at" => $time,
            'is_auto' => $isAuto,
            'auto_start_time' => $autoStartTime,
            'auto_end_time' => $autoEndTime,
            'auto_rest_comment' => $autoComment,
            'auto_week' => $autoWeek,
        ])->save();

        $castOldData = X459x_Cast::findOrFail($castId);
        $castOldData->fill([
            "st79" => $time,
            'st61' => $isAuto == 1 ? 'auto' : 'none',
            'st62' => $autoStartTime,
            'st63' => $autoEndTime,
            'st63' => $autoComment,
            'st65' => $autoWeek ?? 'none',
        ])->save();

        // 自動出勤のクーロンまでのデータをインサート
        if ($isAuto == 1) {
            $time = time();
            $cloneData = Clone_Cast_Shedule_Log::findOrFail(1);
            $nowDate = date('Y-m-d');
            $targetDate = $cloneData->date;
            $parameter = [];
            $oldParameter = [];
            $weekAry = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
            $currentSheduleData = Cast_Schedule::FetchFilteringAryData($castId, $nowDate);
            while ($nowDate != $targetDate) {
                $castAutoWeekAry = explode(',', $autoWeek);
                if (in_array($weekAry[date('w', strtotime($nowDate))], $castAutoWeekAry)) {
                    if (!in_array($nowDate, $currentSheduleData)) {
                        $parameter[] = [
                            'created_at' => $time,
                            'cast_id' => $castId,
                            'date' => date('Y-m-d', strtotime($nowDate)),
                            'is_work' => 1,
                            'start_time' => $autoStartTime,
                            'end_time' => $autoEndTime,
                            'comment' => $autoComment,
                        ];
                        $oldParameter[] = [
                            'scdid' => date('Y-m-d', strtotime($nowDate)) . '_' . $castId,
                            'scd1' => $castId,
                            'scd2' => date('Y-m-d', strtotime($nowDate)),
                            'scd3' => 'work',
                            'scd4' => $autoStartTime,
                            'scd5' => $autoEndTime,
                            'scd6' => $autoComment,
                            'scd30' => $time,
                        ];
                    }
                }
                // 1日ずつ増やす
                $nowDate = date('Y-m-d', strtotime($nowDate . ' +1 day'));
            }
            if ($parameter) {
                Cast_Schedule::insert($parameter);
            }
            if ($oldParameter) {
                X459x_Cast_Shedule::insert($oldParameter);
            }
        }

        session()->flash('status', '登録に成功しました。');
        return redirect($previousUrl);
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function targetCastSheduleUpsert(Request $request)
    {
        $castId = $request->cast_id;
        $startTimeAry = $request->start_time;
        $endTimeAry = $request->end_time;
        $commentAry = $request->comment;
        $isWorkAry = $request->is_work;
        $time = time();
        $parameter = [];
        $oldParameter = [];
        foreach ($startTimeAry as $date => $val) {
            $isWork = 0;
            if (isset($isWorkAry[$date])) {
                $isWork = 1;
            }
            $isShedule = Cast_Schedule::fetchFilteringFirstData(['cast_id' => $castId, 'date' => $date]);
            if ($isShedule) {
                Cast_Schedule::where(['cast_id' => $castId, 'date' => $date])->update([
                    'updated_at' => $time,
                    'start_time' => $val,
                    'end_time' => $endTimeAry[$date] ?? null,
                    'comment' => $commentAry[$date] ?? null,
                    'is_work' => $isWork
                ]);
                X459x_Cast_Shedule::where('scdid', $date . '_' . $castId)->update([
                    // 'updated_at' => $time,
                    'scd3' => $isWork == 1 ? 'work' : 'dayoff',
                    'scd4' => $val,
                    'scd5' => $endTimeAry[$date] ?? "",
                    'scd6' => $commentAry[$date] ?? "",
                ]);
            } else {
                $parameter[] = [
                    'created_at' => $time,
                    'cast_id' => $castId,
                    'date' => $date,
                    'start_time' => $val,
                    'end_time' => $endTimeAry[$date] ?? null,
                    'comment' => $commentAry[$date] ?? null,
                    'is_work' => $isWork,
                ];
                $oldParameter[] = [
                    'scdid' => date('Y-m-d', strtotime($date)) . '_' . $castId,
                    'scd1' => $castId,
                    'scd2' => $date,
                    'scd3' => $isWork == 1 ? 'work' : 'dayoff',
                    'scd4' => $val,
                    'scd5' => $endTimeAry[$date] ?? "",
                    'scd6' => $commentAry[$date] ?? "",
                    'scd30' => $time,
                ];
            }
        }

        if ($parameter) {
            Cast_Schedule::insert($parameter);
        }

        if ($oldParameter) {
            X459x_Cast_Shedule::insert($oldParameter);
        }
        $previousUrl = route('cast.detail', ['id' => $castId, 'tab' => 'shedule']);
        // $previousUrl = app('url')->previous();
        session()->flash('status', '登録に成功しました。');
        return redirect($previousUrl);
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castImageCreate(Request $request)
    {
        $file = $request->file;
        $castId = $request->cast_id;
        $siteId = $request->site_id;

        if (!$file) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }
        if (empty($castId) || empty($siteId)) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }
		$castImageDatas = Cast_Image::fetchFilteringData(['cast_id' => $castId,'site_id' => 0]);
        if (count($castImageDatas) >= 20) {
            return response()->json([
                'result' => 2,
                'message' => '最大登録枚数は、20枚です。'
            ]);
        }
        try {
            $sortNo = 1;
            $fetchLastCastImage = Cast_Image::fetchFilteringLastSortNoData(['cast_id' => $castId]);
            if ($fetchLastCastImage) {
                $sortNo = $fetchLastCastImage + 1;
            }

            $fileName = $file->getClientOriginalName();
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // 拡張子によって適切な関数で画像を読み込む
            // $originalImage = null;
            // if (strtolower($fileExtension) === 'jpeg' || strtolower($fileExtension) === 'jpg') {
            //     $originalImage = imagecreatefromjpeg($file->getPathname());
            // } elseif (strtolower($fileExtension) === 'png') {
            //     $originalImage = imagecreatefrompng($file->getPathname());
            // } elseif (strtolower($fileExtension) === 'gif') {
            //     $originalImage = imagecreatefromgif($file->getPathname());
            // } elseif (strtolower($fileExtension) === 'webp') {
            //     $originalImage = imagecreatefromwebp($file->getPathname());
            // }
            // if (!$originalImage) {
            //     return response()->json([
            //         'result' => 1,
            //         'message' => '対応していない画像形式です。'
            //     ]);
            // }
            $time = time();
            $defaultPath = "__image__/staff/$siteId/$castId";
            if (Storage::missing("public/__image__/staff/$siteId")) {
                Storage::makeDirectory("public/__image__/staff/$siteId");
            }
            if (Storage::missing("public/__image__/staff/$siteId/$castId")) {
                Storage::makeDirectory("public/__image__/staff/$siteId/$castId");
            }
            // $originalImageName = 'ORG_' . $time . '_' . $file->getClientOriginalName();
            // $file->storeAs($defaultPath, $originalImageName, 'public');
            //画像のリサイズと保存
            $sizeAry = [
                'LM'  => array('H' => '1920', 'W' => '1280'),
                'ML'  => array('H' => '1020',  'W' => '680'),
                'MS'  => array('H' => '600',  'W' => '400'),
                'SM'  => array('H' => '450',  'W' => '300')
            ];

            foreach ($sizeAry as $sizeName => $size) {
                //gifファイルはそのまま
                if ($fileExtension == 'gif') {
                    $file->storeAs('public/' . $defaultPath, $sizeName . "_" . $castId . "-" . $time . "." .  $fileExtension);
                } else {
                    $width = $size['W']; // 新しい幅
                    $height = $size['H']; // 新しい高さ
                    $image = \Image::make($file)->orientate(); // Exifデータを読み取り画像の向きを修正
                    // $image->resize($width, $height, function ($constraint) {
                    //     $constraint->aspectRatio();
                    //     $constraint->upsize();
                    // });
                    //アスペクト比ガン無視
                    $image->fit($width, $height);

                    $newImagePath = storage_path("app/public/" . $defaultPath);
                    // リサイズした画像を保存
                    $image->save($newImagePath . '/' . $sizeName . "_" . $castId . "-" . $time . "." .  $fileExtension);
                }
                // if (filesize($file->getPathname()) > 50 * 1024) { // 50KBより大きい場合
                //     // リサイズした画像を保存 (品質を50に指定)
                // } else {
                //     $image->save($newImagePath .'/' .$sizeName . "_" . $castId . "-" . $time . "." .  $fileExtension,50);
                // }

                // 元の画像の幅と高さを取得
                // $originalWidth = imagesx($originalImage);
                // $originalHeight = imagesy($originalImage);

                // 新しいサイズにリサイズする画像を作成
                // $newImage = imagecreatetruecolor($newWidth, $newHeight);

                // 元の画像を新しいサイズにリサイズして新しい画像にコピー
                // imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

                // // 新しい画像を保存（JPEG形式で保存する例）




                // if (strtolower($fileExtension) === 'jpeg' || strtolower($fileExtension) === 'jpg') {
                //     imagejpeg($newImage,$newImagePath);
                // } elseif (strtolower($fileExtension) === 'png') {
                //     imagepng($newImage,$newImagePath);
                // } elseif (strtolower($fileExtension) === 'gif') {
                //     imagegif($newImage,$newImagePath);
                // } elseif (strtolower($fileExtension) === 'webp') {
                //     imagewebp($newImage,$newImagePath);
                // }
                // // 作成した画像リソースを解放
                // imagedestroy($newImage);
            }
            $parameter = [
                'created_at' => $time,
                'site_id' => $siteId,
                'cast_id' => $castId,
                'directory' => "/" . $defaultPath . "/",
                'path' => $castId . "-" . $time . "." . $fileExtension,
                'is_direction' => 0,
                'sort_no' => $sortNo,
            ];
            $lastId = X459x_Cast_Image::max('stim3');
            $lastId = $lastId + 1;
            $oldParameter = [
                'stimgid' => $castId . '-' . $lastId,
                'stim1' => $siteId,
                'stim2' => $castId,
                'stim3' => $lastId,
                'stim4' => $fileExtension,
                'stim5' => $castId . "-" . $time . "." . $fileExtension,
                'stim6' => 'tate',
                'stim7' => $fileExtension == 'gif' ? 'anime' : 'static',
                'stim8' => "/" . $defaultPath . "/",
                'stim9' => $sortNo,
                // 'stim10' => ,//コメント
                'stim29' => 'yes',
                'stim30' => $time,
            ];
            Cast_Image::insert($parameter);
            X459x_Cast_Image::insert($oldParameter);
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castImageDelete(Request $request)
    {
        $imageId = $request->image_id;
        if (empty($imageId)) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }
        try {
            $castImage = Cast_Image::findOrFail($imageId);
            $sizeAry = [
                'LM',
                'ML',
                'MS',
                'SM'
            ];
            foreach ($sizeAry as $size) {
                Storage::disk('public')->delete($castImage->directory . $size . "_" . $castImage->path);
            }
            $castImage->fill(['deleted_at' => 1, 'updated_at' => time()])->save();
            X459x_Cast_Image::where(['stim8' => $castImage->directory, 'stim5' => $castImage->path])->delete();
        } catch (\Exception $e) {
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castImageSort(Request $request)
    {
        $castId = $request->cast_id;
        if (empty($castId)) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }
        $id = explode(',', $request->id);
        if (count($id) <= 0) {
            return response()->json([
                'result' => 1,
                'message' => '画像の登録がないです。'
            ]);
        }
        $sortNo = 1;
        $castImageFirstSort = Cast_Image::fetchFilteringFirstSortNoData(['cast_id' => $castId]);
        if ($castImageFirstSort) {
            $sortNo = $castImageFirstSort;
        }
        try {
            DB::beginTransaction();
            foreach ($id as $i) {
                $castImageData = Cast_Image::findOrFail($i);
                X459x_Cast_Image::where(['stim8' => $castImageData->directory, 'stim5' => $castImageData->path])->update(['stim9' => $sortNo]);
                $castImageData->fill(['sort_no' => $sortNo, 'updated_at' => time()])->save();
                $sortNo++;
            }
            DB::commit();
        } catch (\Exception $e) {
            \Log::debug($e);
            DB::rollBack();
            return response()->json([
                'result' => 1,
                'message' => '処理が失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * スケジュール登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castShedule(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        $firstDate = $request->start_date ?? date('Y-m-d');
        $sheduleEvent = $request->shedule_event ?? null;
        if ($sheduleEvent == 'next') {
            $firstDate = date('Y-m-d', strtotime($firstDate . '+1 week'));
        }
        if ($sheduleEvent == 'previous') {
            $firstDate = date('Y-m-d', strtotime($firstDate . '-1 week'));
        }
        $endDate = date('Y-m-d', strtotime($firstDate . '+1 week'));
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $firstTime = 0;
        $endTime = 0;
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        foreach ($siteDatas as $siteData) {
            if (empty($siteId)) {
                $siteId = $siteData->id;
                // $shopData = M_Shop::findOrFail($siteData->shop_id);
                break;
            }
        }
        $timeAry = [];
        // $startTime = strtotime('10:00');
        $startTime = strtotime('06:00');
        while ($startTime <= strtotime('5:30 +1 day')) {
            $timeAry[] = date('H:i', $startTime);
            $startTime += 1800; // 1分を追加
        }

        $dateArray = array();

        $currentDate = strtotime($firstDate);
        $endTimestamp = strtotime($endDate);

        while ($currentDate < $endTimestamp) {
            $dateArray[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }

        $weekAry = [
            '日' => 'Sun',
            '月' => 'Mon',
            '火' => 'Tue',
            '水' => 'Wed',
            '木' => 'Thu',
            '金' => 'Fri',
            '土' => 'Sat'
        ];
        // $formatCastDatas = [];
        $castDatas = M_Cast::FetchFilterAllSortNo(['site_id' => $siteId, 'cast_id' => 0]);

        foreach ($castDatas as $key => $cast) {
            $castDatas[$key]->image = '/no-image.jpg';
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if ($isCastImage) {
                $castDatas[$key]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }
        //とりま１週間とする。
        $formatCastSheduleDatas = [];
        $sheduleDatas = Cast_Schedule::fetchFilteringBetWeenData(['date' => 0, 'first_date' => $firstDate, 'end_date' => $endDate, 'cast_id' => 0, 'site_id' => $siteId]);

        foreach ($sheduleDatas as $sheduleData) {
            $formatCastSheduleDatas[$sheduleData->cast_id][$sheduleData->date] = $sheduleData;
        }

        return view('admin.cast.shedule', compact('firstDate', 'siteId', 'siteDatas', 'castDatas', 'sheduleDatas', 'timeAry', 'weekAry', 'dateArray', 'formatCastSheduleDatas'));
    }
    /**
     * スケジュール登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castSheduleList(Request $request)
    {
        $dateBranch = $request->date_branch ?? 0;
        $firstDate = $request->start_date ?? date('Y-m-01');
        //月指定
        if ($dateBranch == 0) {
            $firstDate = date('Y-m-01', strtotime($firstDate));
            $endDate = date('Y-m-t', strtotime($firstDate));
        }
        //週指定
        if ($dateBranch == 1) {
            $endDate = date('Y-m-d', strtotime($firstDate . '+1 week'));
        }
        //日付指定
        if ($dateBranch == 2) {
            $endDate = $firstDate;
        }
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
            if (!session('site_control')) {
                $siteControl = [-1];
            }
        }
        $firstTime = 0;
        $endTime = 0;
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        // foreach($siteDatas as $siteData) {
        //     if(empty($siteId)) {
        //         $siteId = $siteData->id;
        //         // $shopData = M_Shop::findOrFail($siteData->shop_id);
        //         break;
        //     }
        // }
        $dateArray = array();
        $timeAry = array();
        if ($dateBranch == 2) {
            $startTime = strtotime('08:00');
            while ($startTime <= strtotime('23:59')) {
                $timeAry[] = date('H:i', $startTime);
                $startTime += 3600; // 1分を追加
            }
            array_push($timeAry, '00:00');
            array_push($timeAry, 'LAST');
        } else {
            $currentDate = strtotime($firstDate);
            $endTimestamp = strtotime($endDate);
            $weekAry = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
            while ($currentDate <= $endTimestamp) {
                $dateArray[date('Ymd', $currentDate)]['date'] = date('Y-m-d', $currentDate);
                $dateArray[date('Ymd', $currentDate)]['saturday'] = date('w', strtotime($dateArray[date('Ymd', $currentDate)]['date'])) == 6 ? true : false;
                $dateArray[date('Ymd', $currentDate)]['sunday'] = date('w', strtotime($dateArray[date('Ymd', $currentDate)]['date'])) == 0 ? true : false;
                $currentDate = strtotime('+1 day', $currentDate);
            }
        }

        $formatCastDatas = [];
        // $castDatas = [];
        $castDatas = M_Cast::FetchFilterAllSortNo(['site_id' => $siteControl, 'cast_id' => 0]);
        foreach ($castDatas as $key => $cast) {
            $formatCastDatas[$cast->site_name][] = $cast;
        }
        //とりま１週間とする。
        $sheduleDatas = [];
        // if($siteControl) {
        $sheduleDatas = Cast_Schedule::fetchFilteringBetWeenData(['date' => 0, 'first_date' => $firstDate, 'end_date' => $endDate, 'cast_id' => 0, 'site_id' => $siteControl, 'is_work' => 1]);
        // }
        $formatCastSheduleDatas = [];
        foreach ($sheduleDatas as $sheduleData) {
            // dd($sheduleData);    
            if ($dateBranch == 2) {
                $isStart = false;
                foreach ($timeAry as $timeAr) {
                    $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['start_time'] = false;
                    $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['end_time'] = false;
                    $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['interval_time'] = false;
                    if (date('H:00', strtotime($sheduleData->start_time)) == $timeAr) {
                        $isStart = true;
                        $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['start_time'] = date('i', strtotime($sheduleData->start_time));
                    }
                    if (date('H:i', strtotime($sheduleData->start_time)) <= $timeAr && date('H:i', strtotime($sheduleData->end_time)) >= $timeAr || $sheduleData->end_time == 'LAST') {
                        if ($isStart) {
                            $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['interval_time'] = true;
                        }
                    }
                    if (date('H:00', strtotime($sheduleData->end_time)) == $timeAr) {
                        if ($sheduleData->end_time !== 'LAST') {
                            $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['end_time'] = date('i', strtotime($sheduleData->end_time));
                        }
                    }
                    if ($sheduleData->end_time == 'LAST' && $timeAr == 'LAST') {
                        $formatCastSheduleDatas[$sheduleData->cast_id][$timeAr]['end_time'] = '00';
                    }
                }
            } else {
                $formatCastSheduleDatas[$sheduleData->cast_id][$sheduleData->date] = $sheduleData;
            }
        }
        return view('admin.cast.shedule_list', compact('firstDate', 'siteDatas', 'castDatas', 'sheduleDatas', 'timeAry', 'dateArray', 'formatCastSheduleDatas', 'formatCastDatas', 'dateBranch'));
    }
    /**
     * スケジュール登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castImadake(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $firstTime = 0;
        $endTime = 0;
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        foreach ($siteDatas as $siteData) {
            if (empty($siteId)) {
                $siteId = $siteData->id;

                break;
            }
        }
        $castDatas = M_Cast::FetchFilterAllSortNo(['site_id' => $siteId, 'cast_id' => 0]);

        foreach ($castDatas as $key => $cast) {
            $castDatas[$key]->image = '/no-image.jpg';
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if ($isCastImage) {
                $castDatas[$key]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }
        return view('admin.cast.imadake', compact('siteId', 'siteDatas', 'castDatas'));
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castSort(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = explode(',', $request->id);
            $isSokuhime = $request->is_sokuhime ?? 0;

            if (count($id) <= 0) {
                return response()->json([
                    'result' => 1,
                    'message' => '不正なパラメータです。'
                ]);
            }

            $sortNo = 1;
            foreach ($id as $i) {
                if (empty($i)) {
                    continue;
                }

                $updateData = [
                    'sort' => $sortNo,
                    'updated_at' => time()
                ];
                $oldUpdateData = [
                    'st76' => $sortNo,
                    'st79' => time()
                ];

                if ($isSokuhime == 1) {
                    unset($updateData['sort']);
                    $updateData['sokuhime_sort'] = $sortNo;
                }

                M_Cast::findOrFail($i)->fill($updateData)->save();
                X459x_Cast::findOrFail($i)->fill($oldUpdateData)->save();

                $sortNo++;
            }
            DB::commit();
            return response()->json([
                'result' => 0,
                'message' => '処理が成功しました。'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'result' => 1,
                'message' => 'エラーが発生しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function castSheduleUpsert(Request $request)
    {
        // $startTimeAry = $request->start_time;
        // $endTimeAry = $request->end_time;
        // $commentAry = $request->comment;
        // $isWorkAry = $request->is_work;
        $datas = $request->data;
        $time = time();
        $parameter = [];
        $oldParameter = [];
        // foreach($startTimeAry as $castId => $startVal) {
        //     foreach($startVal as $date => $val) {
        //         $isWork = 0;
        //         if(isset($isWorkAry[$castId][$date])) {
        //             $isWork = 1;
        //         }
        //         $isShedule = Cast_Schedule::fetchFilteringFirstData(['cast_id' => $castId,'date' => $date]);
        //         if($isShedule) {
        //             Cast_Schedule::findOrFail($isShedule->cast_schedule_id)->fill([
        //                     'updated_at' => $time,
        //                     'start_time' => $val,
        //                     'end_time' => $endTimeAry[$castId][$date] ?? null,
        //                     'comment' => $commentAry[$castId][$date] ?? null,
        //                     'is_work' => $isWork
        //             ])->save();
        //         } else {
        //             $parameter[]= [
        //                 'created_at' => $time,
        //                 'cast_id' => $castId,
        //                 'date' => $date,
        //                 'start_time' => $val,
        //                 'end_time' => $endTimeAry[$castId][$date] ?? null,
        //                 'comment' => $commentAry[$castId][$date] ?? null,
        //                 'is_work' => $isWork,
        //             ];
        //         }
        //     }
        // }
        DB::beginTransaction();
        try {
            foreach ($datas as $index => $data) {
                $isShedule = Cast_Schedule::fetchFilteringFirstData(['cast_id' => $data['cast_id'], 'date' => $data['date']]);
                if ($isShedule) {
                    // Cast_Schedule::findOrFail($isShedule->cast_schedule_id)->fill([
                    Cast_Schedule::where(['cast_id' => $data['cast_id'], 'date' => $data['date']])->update([
                        'updated_at' => $time,
                        'start_time' => $data['start_time'] ?? null,
                        'end_time' => $data['end_time'] ?? null,
                        'comment' => $data['comment'] ?? null,
                        'is_work' => $data['is_work'] ?? 0,
                    ]);
                    X459x_Cast_Shedule::where('scdid', $data['date'] . '_' . $data['cast_id'])->update([
                        // 'updated_at' => $time,
                        'scd3' => $data['is_work'] ? 'work' : 'dayoff',
                        'scd4' => $data['start_time'] ?? null,
                        'scd5' => $data['end_time'] ?? null,
                        'scd6' => $data['comment'] ?? null,
                    ]);
                } else {
                    $parameter[] = [
                        'created_at' => $time,
                        'cast_id' => $data['cast_id'],
                        'date' => $data['date'],
                        'start_time' => $data['start_time'] ?? null,
                        'end_time' => $data['end_time'] ?? null,
                        'comment' => $data['comment'] ?? null,
                        'is_work' => $data['is_work'] ?? 0,
                    ];
                    $oldParameter[] = [
                        'scdid' => $data['date'] . '_' . $data['cast_id'],
                        'scd1' => $data['cast_id'],
                        'scd2' => $data['date'],
                        'scd3' => $data['is_work'] ? 'work' : 'dayoff',
                        'scd4' => $data['start_time'] ?? null,
                        'scd5' => $data['end_time'] ?? null,
                        'scd6' => $data['comment'] ?? null,
                        'scd30' => $time,
                    ];
                }
            }

            if ($parameter) {
                Cast_Schedule::insert($parameter);
            }

            if ($oldParameter) {
                X459x_Cast_Shedule::insert($oldParameter);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::debug($e);
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
        // $previousUrl = app('url')->previous();
        // session()->flash('status', '登録に成功しました。');
        // return redirect()->to($previousUrl);
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $castId = $request->cast_id;
        try {
            //キャストデータ削除
            M_Cast::findOrFail($castId)->fill(['deleted_at' => time()])->save();
            X459x_Cast::findOrFail($castId)->fill(['st78' => 'del'])->save();
            // Cast_Image::where('cast_id',$castId)->update(['deleted_at' => time()]);
            // D_Cast_Blog::where('cast_id',$castId)->update(['deleted_at' => now()]);
            //画像形の削除
            \Storage::deleteDirectory("public/__image__/staff/$castId");
            // \Storage::deleteDirectory("public/articles/content/$castId");
            // $blogDataId = D_Cast_Blog::filteringCastIdAryId($castId);
            // foreach($blogDataId as $blogId) {
            //     \Storage::deleteDirectory("public/articles/$blogId");
            // }
        } catch (\Exception $e) {
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * おすすめキャストの登録
     *
     * @return \Illuminate\Http\Response
     */
    public function recommendCheck(Request $request)
    {
        $data = $request->data;
        try {
            DB::beginTransaction();
            foreach ($data as $d) {
                M_Cast::findOrFail($d['cast_id'])->fill(['updted_at' => time(), 'is_recommend' => $d['is_recommend']])->save();
            }
            DB::commit();
            //キャストデータ削除
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * 画像の登録
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordReset(Request $request)
    {
        $castId = $request->id;
        $previousUrl = route('cast', ['id' => $castId, 'tab' => 'password']);
        while (true) {
            $token = Str::random(20);
            if (!M_Cast::checkPasswordToken($token)) {
                break;
            }
        }
        try {
            DB::beginTransaction();
            M_Cast::findOrFail($castId)->fill(['updated_at' => time(), 'password_reset_token' => $token])->save();
            DB::commit();
            //キャストデータ削除
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('status', 'パスワードの再発行に失敗しました。');
            return redirect($previousUrl);
        }
        session()->flash('status', 'パスワードの再発行に成功しました。');
        return redirect($previousUrl);
    }
}
