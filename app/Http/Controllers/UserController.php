<?php

namespace App\Http\Controllers;

use App\Models\D_Reserve;
use App\Models\D_User;
use App\Models\M_Cast;
use App\Models\M_Point_User;
use App\Models\M_Shop;
use App\Models\M_Site;
use App\Models\User_Like;
use App\Models\User_Message;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->editParameter = config("parameter.user.edit");
    }
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
    public function index()
    {
        $headers = [
            'id' => 'ID',
            'name' => '名前',
            // 'nickname' => 'ログインID',
            'site_name' => 'サイト名',
            'email' => 'メールアドレス',
            'phone' => '電話番号',
            'rank' => 'ランク',
            'block' => '種別',
            'created_at' => '登録日',
        ];
        $siteControl = [];
        // 全会員見れるように
        // if(session('role') != 1) {
        //     $siteControl = session('site_control');
        // }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $shopData = M_Shop::fetchFilterAryId($siteControl);
        return view('admin.user.index',compact('headers','siteData','shopData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFilteringData(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        // 全会員見れるように
        // if(session('role') != 1 && $siteId == 0) {
        //     $siteId = session('site_control');
        // }
        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $RANKCLASS = [
            1 => 'success',
            2 => 'primary',
            3 => 'danger',
            4 => 'warning',
            5 => 'secondary',
            6 => 'secondary',
            7 => 'secondary',
        ];
        $NGCLASS = [
            0 => 'primary',
            1 => 'danger',
            2 => 'warning',
            3 => 'danger',
        ];
        $rank_text = config('constant.user.rank_text');
        $block_text = config('constant.user.block_text');
        $data = D_User::filteringMultiSiteData($filter);
        foreach($data as $index => $d) {
            $data[$index]['rank_class'] = "light";
            if(isset($RANKCLASS[$d['rank']])) {
                $data[$index]['rank_class'] = $RANKCLASS[$d['rank']];
            }
            $data[$index]['rank_text'] = "-";
            if(isset($rank_text[$d['rank']])) {
                $data[$index]['rank_text'] = $rank_text[$d['rank']];
            }
            $data[$index]['block_class'] = "light";
            if(isset($NGCLASS[$d['block']])) {
                $data[$index]['block_class'] = $NGCLASS[$d['block']];
            }
            $data[$index]['block_text'] = "-";
            if(isset($block_text[$d['block']])) {
                $data[$index]['block_text'] = $block_text[$d['block']];
            }
            $data[$index]['phone'] = str_replace('+81',0,$d['phone']);
        }
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $userId = $request->id;
        $data = D_User::findOrFail($userId)->toArray();
        $RANKCLASS = [
            1 => 'success',
            2 => 'primary',
            3 => 'danger',
            4 => 'warning',
            5 => 'secondary',
            6 => 'secondary',
            7 => 'secondary',
        ];
        $NGCLASS = [
            0 => 'primary',
            1 => 'danger',
            2 => 'warning',
            3 => 'danger',
        ];
        $data['rank_class'] = "light";
        if(isset($RANKCLASS[$data['rank']])) {
            $data['rank_class'] = $RANKCLASS[$data['rank']];
        }
        $data['block_class'] = "light";
        if(isset($NGCLASS[$data['block']])) {
            $data['block_class'] = $NGCLASS[$data['block']];
        }
        // $siteDatas = M_Site::fetchAll();
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        foreach($siteDatas as $site) {
            if($site->id == $data['site_id']) {
                $data['site_name'] = $site->name;
            }
        }
        
        $formColums = [
            ['label' => '名前','name' => 'name','type' => 'text'],
            // ['label' => 'フリガナ','name' => 'name_furigana','type' => 'text'],
            // ['label' => 'ニックネーム','name' => 'name_show','type' => 'text'],
            // ['label' => 'ログインID','name' => 'nickname','type' => 'text'],
            // ['label' => 'サイト名','name' => 'site_id','type' => 'select'],
            // ['label' => '電話番号','name' => 'phone','type' => 'text'],
            ['label' => 'メールアドレス','name' => 'email','type' => 'text'],
            ['label' => '住所','name' => 'address','type' => 'text'],
            ['label' => '生年月日','name' => 'birth_day','type' => 'date'],
            // ['label' => '顔画像','name' => 'avatar','type' => 'text'],
            ['label' => 'ランク','name' => 'rank','type' => 'select'],
            ['label' => '種別','name' => 'block','type' => 'select'],
            ['label' => '備考','name' => 'memo','type' => 'textarea'],
        ];
        
        $selectColums = [
            // 'site_id' => [],
            'rank' => config('constant.user.rank'),
            'block' => config('constant.user.block'),
        ];

        $reserveHeaders = [
            // 'id' => 'ID',
            'status_badge' => 'ステータス',
            'site_name' => 'サイト名',
            'source_name' => 'キャスト名',
            'start_time' => '予約開始時間',
            'end_time' => '予約終了時間',
            'amount' => '金額',
        ];
        // 仮予約 = 1,確定 = 2; 事前確認= 3,完了= 4,キャンセル = 5	
        $badge = config('constant.user.status_badge');
        $rank_text = config('constant.user.rank_text');
        $data['rank_text'] = "-";
        if(isset($rank_text[$data['rank']])) {
            $data['rank_text'] = $rank_text[$data['rank']];
        }
        $block_text = config('constant.user.block_text');
        $data['block_text'] = "-";
        if(isset($block_text[$data['block']])) {
            $data['block_text'] = $block_text[$data['block']];
        }
        $reserveDatas = D_Reserve::fetchFilterUserData($userId)->toArray();
        foreach($reserveDatas as $index => $value) {
            $reserveDatas[$index] = $value;
            $reserveDatas[$index]['amount'] = number_format($value['amount'])."円";
            $reserveDatas[$index]['status_badge'] = isset($badge[$value['status']]) ? $badge[$value['status']] : '-';
        }
        // NG
        $ngHeaders = [
            'id' => 'ID',
            'source_name' => 'キャスト名',
            // 'source_name' => 'キャスト名',
        ];
        $castDatas = M_Cast::fetchAll($userId);
        $ngDatas = [];
        // お気に入りサイト
        $bookMarkHeaders = [
            'site_id' => 'サイトID',
            'site_name' => 'サイト名',
            'url' => 'URL',
        ];
        $bookMarkDatas = User_Like::fetchJoinUserData($userId);

        return view('admin.user.detail',compact('userId','siteDatas','formColums','selectColums','data','reserveHeaders','reserveDatas','ngHeaders','ngDatas','castDatas','bookMarkHeaders','bookMarkDatas'));
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return 
     */
    public function baseEdit(Request $request)
    {
        $parameter = $request->only($this->editParameter);
        if(empty($parameter['id'])) {
            $previousUrl = app('url')->previous();
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        //  || !$parameter['name_furigana']  || !$parameter['name_show']
        if(!$parameter['name']) {
            $previousUrl = app('url')->previous();
            session()->flash('error', '入力項目に不備があります。');
            return redirect()->to($previousUrl);
        }
        try {
            \DB::beginTransaction();
            D_User::saveData($parameter);
            M_Point_User::where('user_id',$parameter['id'])->update(['updated_at' => time(),'name'=>$parameter['name']]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
			\Log::debug($e);
            $previousUrl = app('url')->previous();
            session()->flash('error', 'データ更新に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $previousUrl = app('url')->previous();
        session()->flash('success', 'データを更新しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return 
     */
    public function deleteUser($id)
    {
        $resArray = $this->resArray;
        if(empty($id)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        try {
            \DB::beginTransaction();
            D_User::findOrFail($id)->fill(['updated_at' => now(),'deleted_at' => now()])->save();
            M_Point_User::where('user_id',$id)->update(['deleted_at' => time()]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return 
     */
    public function sendMessageAdmin(Request $request)
    {
        
        $userIds = $request->user_id;
        $siteId = $request->site_id;
        $title = $request->title;
        $content = $request->content;
        $authorFlag = $request->author_flag;
        if(empty($title)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', 'タイトルを入力してください');
            return redirect()->to($previousUrl);
        }
        if(empty($content)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', '内容を入力してください');
            return redirect()->to($previousUrl);
        }
        try {
            \DB::beginTransaction();
            foreach ($userIds as $userId) {
                $data[] = [
                    'user_id' => $userId,
                    'site_id' => $siteId,
                    'title' => $title,
                    'content' => $content,
                    'author_flag' => $authorFlag,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $result = User_Message::insert($data);
            if (!$result) {
                \DB::rollBack();
                $previousUrl = app('url')->previous();
                session()->flash('error', 'データ更新に失敗しました。');
                return redirect()->to($previousUrl);
            }
            \DB::commit();
        } catch (\Exception $ex) {
            $previousUrl = app('url')->previous();
            session()->flash('error', 'メッセージの送信に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $previousUrl = app('url')->previous();
        session()->flash('success', 'メッセージの送信に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * 基本情報編集
     *
     * @param Request $request
     * @return 
     */
    public function sendCheckMessageAdmin(Request $request)
    {
        $userIds = $request->user_id ? explode(',',$request->user_id) : [];
        $siteId = $request->site_id;
        $title = $request->title;
        $content = $request->content;
        $authorFlag = $request->author_flag;
        if(empty($userIds)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', 'ユーザーを一人以上選択してください。');
            return redirect()->to($previousUrl);
        }
        if(empty($title)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', 'タイトルを入力してください');
            return redirect()->to($previousUrl);
        }
        if(empty($content)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', '内容を入力してください');
            return redirect()->to($previousUrl);
        }
        try {
            \DB::beginTransaction();
            foreach ($userIds as $userId) {
                $data[] = [
                    'user_id' => $userId,
                    'site_id' => $siteId,
                    'title' => $title,
                    'content' => $content,
                    'author_flag' => $authorFlag,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $result = User_Message::insert($data);
            if (!$result) {
                \DB::rollBack();
                $previousUrl = app('url')->previous();
                session()->flash('error', 'データ更新に失敗しました。');
                return redirect()->to($previousUrl);
            }
            \DB::commit();
        } catch (\Exception $ex) {
            $previousUrl = app('url')->previous();
            session()->flash('error', 'メッセージの送信に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $previousUrl = app('url')->previous();
        session()->flash('success', 'メッセージの送信に成功しました。');
        return redirect()->to($previousUrl);
    }

}
