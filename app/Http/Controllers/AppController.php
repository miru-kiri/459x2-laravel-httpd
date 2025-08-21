<?php

namespace App\Http\Controllers;

use App\Models\M_Admin;
use App\Models\M_Point_User;
use App\Models\M_Site;
use App\Models\Point_User;
use App\Models\Site_Admin;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * デフォルトメッセージ
     *
     * @var array
     */
    protected $resArray = [
        'error' => 0,
        'message' => '処理に成功しました',
    ];
    /**
     * ログインレスポンス
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $result = $this->resArray;
        $loginId = $request->shop_id;
        $password = $request->password;

        $admin = M_Admin::AdminLogin($loginId,$password);
        if(!$admin) {
            $result = [
                'error' => 1,
                'message' => 'ログインに失敗しました。',
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $siteControl = [];
        if($admin->role != 1) {
            $siteControl = Site_Admin::FetchFilterAdminId($admin->id)->pluck('site_id')->toArray();
        }
		$siteDatas = [];
        $siteAdmin = M_Site::fetchFilterAryId($siteControl);
        foreach($siteAdmin as $index => $data) {
            if($data->is_public == 0) {
                continue;
            }
            $siteDatas[$data->id] = $data;
        }
        $result = [
            'error' => 0,
            'message' => 'ログインに成功しました。',
            'name' => $admin->name,
            'site' => $siteDatas
        ];
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * 認証SMS送信
     *
     * @param Request $request
     * @return void
     */
    public function sendSms(Request $request)
    {
        $result = $this->resArray;
        //ハイフンなしの数字できた想定
        $phoneCorrect = substr($request->tel, 0, 1) == '0' ? "+81" . substr($request->tel, 1) : "+81" . $request->tel;
        $result = sendCode($phoneCorrect);
        if(!$result['result']) {
            $result = [
                'error' => 1,
                'message' => 'SMS送信に失敗しました。',
            ];
        } else {
            $result = [
                'error' => 0,
                'code' => $result['code'],
                'message' => 'SMS送信に成功しました。'
            ];
        }
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * カード登録
     *
     * @param Request $request
     * @return void
     */
    public function createCard(Request $request)
    {
        $result = $this->resArray;
        //Reuqest値
        if(empty($request->card_no)) {
            $result = [
                'error' => 1,
                'message' => 'カード番号がありません。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }

        if(M_Point_User::where(['card_no' => $request->card_no,'deleted_at' => 0])->exists()){
            $result = [
                'error' => 1,
                'message' => '既に登録されているカード番号です。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $phoneCorrect = substr($request->tel, 0, 1) == '0' ? "+81" . substr($request->tel, 1) : "+81" . $request->tel;
        $parameter = [
            'created_at' => time(),
            'name' => $request->name ?? null, //String 名前
            'site_id' => $request->site_id ?? null, //Int サイト
            'year' => $request->year ?? null, //Int yyyy 生年月日 年
            'month' => $request->month ?? null, //Int mm 生年月日 月
            'day' => $request->day ?? null,  //Int mm 生年月日 月
            'sex' => $request->sex ?? 1, //Int 1.男性 2.女性
            'tel' => $phoneCorrect ?? null, //String ハイフン要るかいらないかまた教えて
            'card_no' => $request->card_no, //カード番号
        ];
        M_Point_User::insert($parameter);
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * カード情報取得
     *
     * @param Request $request
     * @return void
     */
    public function fetchCardInfo(Request $request)
    {
        $cardNo = $request->card_no;
        //select文
        $fetchData = M_Point_User::fetchFilteringFirstData(['card_no' => $cardNo]);
        if(!$fetchData) {
            $result = [
                'error' => 1,
                'message' => '登録されていないカードです。'
            ];
        } else {
            $limitPoint = Point_User::fetchValidPoint($cardNo);
            $result = [
                'error' => 0,
				'user_no' => $fetchData->card_no,
                'site_no' => $fetchData->site_id,
                'card_no' => $fetchData->card_no,
                'name' => $fetchData->name,
                'limitPoint' => (int)$limitPoint,
            ];
        }
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * ポイント使用
     *
     * @param Request $request
     * @return void
     */
    public function usePoint(Request $request)
    {
        $userNo = $request->user_no;
        $usePoint = $request->use_point;
        $givePoint = $request->give_point;
        $totalPrice = $request->total_price;
		$siteId = $request->site_id ?? null;
        if(empty($userNo)) {
            $result = [
                'error' => 1,
                'message' => 'ユーザー番号がありません。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $fetchData = M_Point_User::fetchFilteringFirstData(['card_no' => $userNo]);
        $limitPoint = Point_User::fetchValidPoint($fetchData->card_no);
        if($limitPoint < $usePoint) {
            $result = [
                'error' => 1,
                'message' => '保有ポイントより、使用ポイントの方が大きいため利用ができません。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
		if ((int)$usePoint % 1000 !== 0) {
			$result = [
                'error' => 1,
                'message' => '使用ポイントは、1000pt単位でないと使用できません'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
		}
        $parameter = [];
        if(!empty($usePoint)) {
            //使用
            $parameter[] = [
                'created_at' => time(),
                'user_id' => $fetchData->user_id,
				'site_id' => $siteId,
                'date' => date('Ymd'),
                'time' => date('Hi'),
                'category_id' => 2,
                'point' => -$usePoint,
                'card_no' => $fetchData->card_no,
                'is_app' => 1
            ];
        }
        if(!empty($givePoint)) {
            //取得
            $parameter[] = [
                'created_at' => time(),
                'user_id' => $fetchData->user_id,
				'site_id' => $siteId,
                'date' => date('Ymd'),
                'time' => date('Hi'),
                'category_id' => 1,
                'point' => $givePoint,
                'card_no' => $fetchData->card_no,
                'is_app' => 1
            ];
        }
        //ポイント付与のロジック(site_idごとに)
        // if($totalPrice > 0) {
        //     //ここの値がサイトのイベントによって変動
        //     $conversionPoint = round($totalPrice / 100);
        //     $parameter[] = [
        //         'created_at' => time(),
        //         'user_id' => $fetchData->user_id,
        //         'date' => date('Ymd'),
        //         'time' => date('Hi'),
        //         'category_id' => 4, //還元
        //         'point' => $conversionPoint,
        //         'card_no' => $fetchData->card_no,
        //         'is_app' => 1
        //     ];
        // }
        if($parameter) {
            Point_User::insert($parameter);
        }
        


        $limitPoint = Point_User::fetchValidPoint($fetchData->card_no);
        //使用可能ポイントの更新のために返す。
        $result = [
            'error' => 0,
            'card_no' => $fetchData->card_no,
            'limitPoint' => (int)$limitPoint,
        ];
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * カード情報引き継ぎ
     *
     * @param Request $request
     * @return void
     */
    public function cardTransfer(Request $request)
    {
        $oldCardNo = $request->old_card_no;
        $cardNo = $request->card_no;
        if(empty($oldCardNo) || empty($cardNo)) {
            $result = [
                'error' => 1,
                'message' => 'カード番号がありません。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        //前のカードのpointを今回のカードへ付与
        $fetchOldData = M_Point_User::fetchFilteringFirstData(['card_no' => $oldCardNo]);
		if(!$fetchOldData) {
            $result = [
                'error' => 1,
                'message' =>  '引き継ぎする' . $oldCardNo . '番のカード登録がありません。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $fetchData = M_Point_User::fetchFilteringFirstData(['card_no' => $cardNo]);
        if(!$fetchData) {
            $result = [
                'error' => 1,
                'message' =>  '引き継ぎされる' . $cardNo . '番のカード登録がありません。'
            ];
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $oldPoint = Point_User::fetchValidPoint($oldCardNo);
        $parameter[] = [
            'created_at' => time(),
            'user_id' => $fetchOldData->user_id,
            'date' => date('Ymd'),
            'time' => date('Hi'),
            'category_id' => 3,//引き継ぎ
            'branch_id' => $cardNo,
            'point' => -$oldPoint,
            'card_no' => $oldCardNo,
            'is_app' => 1
        ];
        $parameter[] = [
            'created_at' => time(),
            'user_id' => $fetchData->user_id,
            'date' => date('Ymd'),
            'time' => date('Hi'),
            'category_id' => 3,//引き継ぎ
            'branch_id' => $oldCardNo,
            'point' => $oldPoint,
            'card_no' => $cardNo,
            'is_app' => 1
        ];
        Point_User::insert($parameter);
        $limitPoint = Point_User::fetchValidPoint($oldCardNo);
        //利用可能ポイントの更新のために。
        $result = [
            'error' => 0,
            'card_no' => $cardNo,
            'limitPoint' => (int)$limitPoint,
        ];
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
