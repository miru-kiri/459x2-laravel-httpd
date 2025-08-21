<?php

namespace App\Http\Controllers;

use App\Models\D_Site_Detail_Tab;
use App\Models\D_Site_Tab;
use App\Models\M_Area;
use App\Models\M_Genre;
use App\Models\M_Shop;
use App\Models\M_Site;
use App\Models\Site_Info;
use App\Models\M_Site_Detail_Tab;
use App\Models\M_Site_Tab;
use App\Models\Site_Area;
use App\Models\Site_Genre;
use App\Models\X459x_Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MasterSiteController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->parameter = config("parameter.site.create");
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
        // $response = \Http::get('https://tengoku-test.com/_mrt_/csm/_config/class/define_class_api.php');
        // Curlを初期化
        $ch = curl_init();

        // Curlの設定
        curl_setopt($ch, CURLOPT_URL, 'https://tengoku-test.com/_mrt_/csm/_config/class/define/define_class_api.php');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Curlの実行
        $response = curl_exec($ch);
        // Curlの終了
        curl_close($ch);

        $templateAry = json_decode($response,true);
        $oldTemplate = [];
        if($templateAry['success']) {
            foreach($templateAry['data'] as $key => $val) {
                $oldTemplate[] = [
                    'value' => $key,
                    'name' => $val,
                ];
            }
        }
        //
        $title = 'サイト設定';
        $defaultUrl = route('master.site');
        $headers = [
            'id' => 'ID',
            'shop_name' => '店舗名',
            'name' => 'サイト名',
            'url' =>'URL',
            // 'genre_name' => '業態',
            'is_public' =>'公開フラグ',
        ];
        
        $formColums = [
            ['label' => '店舗名','name' => 'shop_id','type' => 'select'],
            
            ['label' => 'サイト名称','name' => 'name','type' => 'text'],
            ['label' => 'サイト形態','name' => 'style','type' => 'select'],
            ['label' => 'サイト詳細','name' => 'content','type' => 'textarea'],
            ['label' => 'テンプレート設定','name' => 'template','type' => 'select'],
            ['label' => '関連グループ','name' => 'is_cosmo','type' => 'select'],
            ['label' => 'エリア','name' => 'area_id','type' => 'multiySelect'],
            ['label' => 'ジャンル','name' => 'genre_id','type' => 'multiySelect'],
            ['label' => 'サイトURL','name' => 'url','type' => 'text'],
            ['label' => 'パソコン版トップベージ','name' => 'pc_top_url','type' => 'text'],
            ['label' => 'スマホ版トップベージ','name' => 'sp_top_url','type' => 'text'],
            ['label' => '携帯版トップベージ','name' => 'p_top_url','type' => 'text'],
            ['label' => 'グループ求人誌記号(重複なし)','name' => 'recruit_key','type' => 'text'],
            ['label' => 'ポータルテンプレート(サイト形態/ユーザー/any/...html..)','name' => 'old_template','type' => 'select'],
            ['label' => '出勤切り替え時間(分)24:00より何分後?','name' => 'switching_time','type' => 'text'],
            // ['label' => 'OPEN時間','name' => 'open_time','type' => 'select'],
            // ['label' => 'CLOSE時間','name' => 'close_time','type' => 'select'],
            ['label' => 'OPEN時間[記入例 09:00]','name' => 'open_time','type' => 'text'],
            ['label' => 'CLOSE時間[記入例 00:00]','name' => 'close_time','type' => 'text'],
            ['label' => 'システム(=,;の配列形式)','name' => 'system_text','type' => 'text'],

            ['label' => 'ブログ店長メール投稿設定(ホスト)','name' => 'blog_owner_host','type' => 'text'],
            ['label' => 'ブログ店長メール投稿設定(ユーザー)','name' => 'blog_owner_user','type' => 'text'],
            ['label' => 'ブログ店長メール投稿設定(パスワード)','name' => 'blog_owner_pass','type' => 'text'],
            ['label' => 'ブログスタッフメール投稿設定(ホスト)','name' => 'blog_staff_host','type' => 'text'],
            ['label' => 'ブログスタッフメール投稿設定(ユーザー)','name' => 'blog_staff_user','type' => 'text'],
            ['label' => 'ブログスタッフメール投稿設定(パスワード)','name' => 'blog_staff_pass','type' => 'text'],

            ['label' => 'メルマガ設定登録用URL','name' => 'mail_magazine_url','type' => 'text'],
            ['label' => 'メルマガ設定登録用キー(ハイフン区切り)','name' => 'mail_magazine_key','type' => 'text'],
            ['label' => 'メルマガ設定登録用メールアドレス','name' => 'mail_magazine_create_mail','type' => 'text'],
            ['label' => 'メルマガ設定削除用メールアドレス','name' => 'mail_magazine_delete_mail','type' => 'text'],
            ['label' => '求人用LINE設定友達追加URL','name' => 'recruit_line_url','type' => 'text'],
            ['label' => '求人用LINE設定ID','name' => 'recruit_line_id','type' => 'text'],
            ['label' => 'アナリティクス設定トラッキングコード','name' => 'analytics_code','type' => 'text'],
            ['label' => 'アナリティクス設定API用プロファイルID','name' => 'analytics_api','type' => 'text'],
            ['label' => 'ポータルショップテンプレート(/459x/***)','name' => 'portal_template_url','type' => 'text'],
            ['label' => 'ポータルショップページ指定カンマ区切り(top,staff,***)','name' => 'portal_tab','type' => 'text'],
            ['label' => '非公開サイトID','name' => 'site_hidden','type' => 'text'],
            // ['label' => '女の子一覧ソート設定','name' => 'staff_sort','type' => 'select'],
            ['label' => '並び順','name' => 'sort','type' => 'text'],
            ['label' => '備考','name' => 'remarks','type' => 'textarea'],
            ['label' => 'Httpsか','name' => 'is_https','type' => 'switch'],
            ['label' => '対外サーバー参照','name' => 'is_externally_server','type' => 'switch'],
            ['label' => '公開','name' => 'is_public','type' => 'switch']
        ];
        
        // 時間の配列
        $openTimeMinutesAry = [];
        for($i=0; $i < 24;  $i++){
            $time = sprintf('%02d', $i);
            for($j=0; $j < 60; $j = $j + 30){
                $minutes = sprintf('%02d', $j);
                $openTimeMinutesAry[] = [
                    'name' => $time.":".$minutes,
                    'value' => $time.$minutes,
                ];
            }
        }
        // 時間の配列
        $closeTimeMinutesAry = [];
        for($i=0; $i < 27;  $i++){
            $time = sprintf('%02d', $i);
            for($j=0; $j < 60; $j = $j + 30){
                $minutes = sprintf('%02d', $j);
                $closeTimeMinutesAry[] = [
                    'name' => $time.":".$minutes,
                    'value' => $time.$minutes,
                ];
            }
        }
        $selectColums = [
            'shop_id' => [],
            'style' => config('constant.site.style'),
            'template' => config('constant.site.template'),
            'is_cosmo' => config('constant.is_cosmo'),
            'area_id' => [],
            'genre_id' => [],
            'open_time' => $openTimeMinutesAry,
            'close_time' => $closeTimeMinutesAry,
            'old_template' => $oldTemplate,
            // 'staff_sort' => $staffSort,
        ];
        $fetchArea = M_Area::fetchAll();
        foreach($fetchArea as $index => $area) {
            $selectColums['area_id'][$index]['value'] = $area->id;
            $selectColums['area_id'][$index]['name'] = $area->name;
        }
        $fetchGenre = M_Genre::fetchAll();
        foreach($fetchGenre as $index => $genre) {
            $selectColums['genre_id'][$index]['value'] = $genre->id;
            $selectColums['genre_id'][$index]['name'] = $genre->name;
        }
        
        $defaultColums = [
            'id' => 0,
            'shop_id' => 1,
            'name' => '',
            'style' => 1,
            'content' => '',
            'template' => 1,
            'is_cosmo' => 1,
            'area_id' => [],
            'genre_id' => [],
            'url' => '',
            // 'top_url' => '',
            'pc_top_url' => '',
            'sp_top_url' => '',
            'p_top_url' => '',
            'recruit_key' => '',
            'old_template' => 0,
            'switching_time' => 0,
            'open_time' => '00:00',
            'close_time' => '00:00',
            'system_text' => "",
            'blog_owner_host' => '',
            'blog_owner_user' => '',
            'blog_owner_pass' => '',
            'blog_staff_host' => '',
            'blog_staff_user' => '',
            'blog_staff_pass' => '',
            'mail_magazine_url' => '',
            'mail_magazine_key' => '',
            'mail_magazine_create_mail' => '',
            'mail_magazine_delete_mail' => '',
            'recruit_line_url' => '',
            'recruit_line_id' => '',
            'analytics_code' => '',
            'analytics_api' => '',
            'portal_template_url' => '',
            'portal_tab' => '',
            'site_hidden' => "",
            // 'staff_sort' => 1,
            'sort' => '',
            'remarks' => '',
            'is_https' => 0,
            'is_externally_server' => 0,
            'is_public' => 0,
        ];
        $validateRules = [
            'name'  => [
                'required' => true,
            ],
            'short_name'  => [
                'required' => true,
            ],
            'short_kana'  => [
                'required' => true,
            ],
            'style'  => [
                'required' => true,
            ],
            'genre_id'  => [
                'required' => true,
            ],
            'postal_code'  => [
                'number' => true,
            ],
            'tel'  => [
                'number' => true,
            ],
            'fax'  => [
                'number' => true,
            ],
            'sort'  => [
                'number' => true,
            ],
        ];

        $bodys = [];
        $fetchSiteAdmin = [];
        $formatFetchData = [];
        $formatAreaSite = [];
        $formatGenreSite = [];
        $formatFetchOldData = [];
        $fetchOldData = X459x_Site::fetchAll();
        foreach($fetchOldData as $oldData) {
            $formatFetchOldData[$oldData->siteid] = $oldData;
        }
        $fetchData = M_Site::fetchJoinAll()->toArray();
        $fetchCorpData = M_Shop::fetchAll();
        $fetchAreaSiteData = Site_Area::fetchAll();
        $fetchGenreSiteData = Site_Genre::fetchAll();

        foreach($fetchCorpData as $index => $corp) {
            $selectColums['shop_id'][$index]['value'] = $corp->id;
            $selectColums['shop_id'][$index]['name'] = $corp->name;
        }

        foreach($fetchAreaSiteData as $fetchSiteArea) {
            $formatAreaSite[$fetchSiteArea->site_id][] = $fetchSiteArea->area_id;
        }
        foreach($fetchGenreSiteData as $fetchSiteGenre) {
            $formatGenreSite[$fetchSiteGenre->site_id][] = $fetchSiteGenre->genre_id;
        }        
        foreach($fetchData as $data){
            $formatFetchData[$data['id']] = $data;
            $formatFetchData[$data['id']]['area_id'] = isset($formatAreaSite[$data['id']]) ? $formatAreaSite[$data['id']] :[];
            $formatFetchData[$data['id']]['genre_id'] = isset($formatGenreSite[$data['id']]) ? $formatGenreSite[$data['id']] :[];
            // 旧データベースのデータを入れ込む
            $formatFetchData[$data['id']]['pc_top_url'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd5 :"";
            $formatFetchData[$data['id']]['sp_top_url'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd6 :"";
            $formatFetchData[$data['id']]['p_top_url'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd7 :"";
            $formatFetchData[$data['id']]['recruit_key'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd8 :"";
            $formatFetchData[$data['id']]['old_template'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd9 :"";
            $formatFetchData[$data['id']]['system_text'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd10 :"";
            // $formatFetchData[$data['id']]['is_cosmo'] = isset($formatFetchOldData[$data['id']]) ? $formatGenreSite[$data['id']]->fd11 :"";
            // $formatFetchData[$data['id']]['sort'] = isset($formatFetchOldData[$data['id']]) ? $formatGenreSite[$data['id']]->fd12 :"";
            // $formatFetchData[$data['id']]['remarks'] = isset($formatFetchOldData[$data['id']]) ? $formatGenreSite[$data['id']]->fd13 :"";
            // $formatFetchData[$data['id']]['content'] = isset($formatFetchOldData[$data['id']]) ? $formatGenreSite[$data['id']]->fd14 :"";
            $formatFetchData[$data['id']]['switching_time'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd15 :"";
            $formatFetchData[$data['id']]['blog_owner_host'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd16 :"";
            $formatFetchData[$data['id']]['blog_owner_user'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd17 :"";
            $formatFetchData[$data['id']]['blog_owner_pass'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd18 :"";
            $formatFetchData[$data['id']]['blog_staff_host'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd19 :"";
            $formatFetchData[$data['id']]['blog_staff_user'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd20 :"";
            $formatFetchData[$data['id']]['blog_staff_pass'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd21 :"";
            $formatFetchData[$data['id']]['mail_magazine_url'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd22 :"";
            $formatFetchData[$data['id']]['mail_magazine_key'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd23 :"";
            $formatFetchData[$data['id']]['mail_magazine_create_mail'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd24 :"";
            $formatFetchData[$data['id']]['mail_magazine_delete_mail'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd25 :"";
            $formatFetchData[$data['id']]['recruit_line_url'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd26 :"";
            $formatFetchData[$data['id']]['recruit_line_id'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd27 :"";
            $formatFetchData[$data['id']]['analytics_code'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd28 :"";
            $formatFetchData[$data['id']]['analytics_api'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd29 :"";
            $formatFetchData[$data['id']]['is_https'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd30 :"";
            if($formatFetchData[$data['id']]['is_https'] == 'YES') {
                $formatFetchData[$data['id']]['is_https'] = 1;
            } else {
                $formatFetchData[$data['id']]['is_https'] = 0;
            }
            $formatFetchData[$data['id']]['portal_template_url'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd31 :"";
            $formatFetchData[$data['id']]['portal_tab'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd32 :"";
            // $formatFetchData[$data['id']]['staff_sort'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd33 :"";
            $formatFetchData[$data['id']]['open_time'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd34 :"";
            $formatFetchData[$data['id']]['close_time'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd35 :"";
            $formatFetchData[$data['id']]['site_hidden'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd73 :"";
            $formatFetchData[$data['id']]['is_externally_server'] = isset($formatFetchOldData[$data['id']]) ? $formatFetchOldData[$data['id']]->fd74 :"";
            if($formatFetchData[$data['id']]['is_externally_server'] == 'YES') {
                $formatFetchData[$data['id']]['is_externally_server'] = 1;
            } else {
                $formatFetchData[$data['id']]['is_externally_server'] = 0;
            }
            // $formatFetchData[$data['id']]['site_genre'] = isset($formatFetchOldData[$data['id']]) ? $formatGenreSite[$data['id']]->fd75 :"";
            // $formatFetchData[$data['id']]['site_area'] = isset($formatFetchOldData[$data['id']]) ? $formatGenreSite[$data['id']]->fd76 :"";
        }
        
        foreach($formatFetchData as $data){
            $row = [];
            foreach($headers as $key => $value) {
                $bodyValues = $data[$key];
                if($key == 'id') {
                    $i = $data[$key];
                } 
                if($key == 'is_public') {
                    $bodyValues = $data[$key] == 1 ? '公開中' : '非公開';
                }
                $row[$key] = $bodyValues;
            }
            $bodys[$i] = $row;
        }
        return view('admin.master.template',compact('title','defaultUrl','headers','bodys','formColums','selectColums','fetchData','formatFetchData','defaultColums','validateRules','formatAreaSite','formatGenreSite'));
    }
    public function getColor($template) 
    {
        $color = [
            1 => '#F1747E',
            2 => '#6D66AA',
            3 => '#F684A6',
            4 => '#B66497',
            5 => '#F27847',
            6 => '#C02639',
            7 => '#917961',//https://refresh-bld.com/ここから取得
        ];
        if(isset($color[$template])) {
            $color = $color[$template];
        }
        return $color;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parameter = $request->only($this->parameter);
        $areaIds = $request->area_id;
        $genreIds = $request->genre_id;
        $resArray = $this->resArray;
        try {
            \DB::beginTransaction();
            $time = time();
            if (empty($parameter["id"])) {
                // 作成
                unset($parameter['id']);
                $parameter['created_at'] = $time;
                $siteId = M_Site::insertGetId($parameter);
                //サイトの編集の機能のため追加
                $tmp = $parameter['template'];
                if($parameter['template'] < 0) {
                    $tmp = 1;
                }
                $siteInfoParameter = [
                    'created_at' => $parameter['created_at'],
                    'site_id' => $siteId,
                    'title' => $parameter['name'],
                    'color' => $this->getColor($tmp),
                ];
                Site_Info::insert($siteInfoParameter);
                $siteTab = M_Site_Tab::fetchFilteringData(['template' => $tmp]);
                $tabParameter = [];
                $tabDetailParameter = [];
                $loop = 0;
                foreach($siteTab as $index => $tab) {
                    $tabParameter[$index] = $this->getActiveTabColor($tmp);
                    $tabParameter[$index]['created_at'] = $time;
                    $tabParameter[$index]['master_id'] = $tab->id;
                    $tabParameter[$index]['site_id'] = $siteId;
                    $tabParameter[$index]['name'] = $tab->name;
                    $tabParameter[$index]['url'] = $tab->url;
                    $tabParameter[$index]['sort_no'] = $index + 1;
                    $tabIdAry[] = $tab->id;
                }
                $siteDetailTab = M_Site_Detail_Tab::fetchMasterIdAryData($tabIdAry);
                foreach($siteDetailTab as $detailTab) {
                    $tabDetailParameter[$loop]['created_at'] = $time;
                    $tabDetailParameter[$loop]['master_id'] = $detailTab->master_id;
                    $tabDetailParameter[$loop]['master_detail_id'] = $detailTab->id;
                    $tabDetailParameter[$loop]['site_id'] = $siteId;
                    $tabDetailParameter[$loop]['title'] = $detailTab->title;
                    $tabDetailParameter[$loop]['sub_title'] = $detailTab->sub_title;
                    $tabDetailParameter[$loop]['content'] = $detailTab->content;
                    $tabDetailParameter[$loop]['sort_no'] = $detailTab->sort_no;
                    $tabDetailParameter[$loop]['is_display'] = $detailTab->is_display;
                    $tabDetailParameter[$loop]['event'] = $detailTab->event;
                    // $tabDetailParameter[$loop]['color'] = $color;
                    $loop++;
                }
                D_Site_Tab::insert($tabParameter);
                D_Site_Detail_Tab::insert($tabDetailParameter);

            } else {
                //編集
                M_Site::saveData($parameter);
                //サイトの編集の機能のため追加
                $siteId = $parameter['id'];
                if($parameter['template'] > 0) {
                    $isSiteTab = D_Site_Tab::FetchFilteringSiteData(['site_id' => $parameter['id'],'master_id' => 0,'is_display' => 0]);
                    if(!$isSiteTab) {
                        $siteTab = M_Site_Tab::fetchFilteringData(['template' => $parameter['template']]);
                        $tabParameter = [];
                        $tabDetailParameter = [];
                        $loop = 0;
                        foreach($siteTab as $index => $tab) {
                            $tabParameter[$index] = $this->getActiveTabColor($parameter['template']);
                            $tabParameter[$index]['created_at'] = $time;
                            $tabParameter[$index]['master_id'] = $tab->id;
                            $tabParameter[$index]['site_id'] = $siteId;
                            $tabParameter[$index]['name'] = $tab->name;
                            $tabParameter[$index]['url'] = $tab->url;
                            $tabParameter[$index]['sort_no'] = $index + 1;
                            $tabIdAry[] = $tab->id;
                        }
                        D_Site_Tab::insert($tabParameter);
                    }
                    $isSiteDetailTab = D_Site_Detail_Tab::FetchFilteringSiteData(['site_id' => $parameter['id'],'master_id' => 0,'is_display' => 0]);
                    if(!$isSiteDetailTab) {
                        $siteDetailTab = M_Site_Detail_Tab::fetchMasterIdAryData($tabIdAry);
                        foreach($siteDetailTab as $detailTab) {
                            $tabDetailParameter[$loop]['created_at'] = $time;
                            $tabDetailParameter[$loop]['master_id'] = $detailTab->master_id;
                            $tabDetailParameter[$loop]['master_detail_id'] = $detailTab->id;
                            $tabDetailParameter[$loop]['site_id'] = $siteId;
                            $tabDetailParameter[$loop]['title'] = $detailTab->title;
                            $tabDetailParameter[$loop]['sub_title'] = $detailTab->sub_title;
                            $tabDetailParameter[$loop]['content'] = $detailTab->content;
                            $tabDetailParameter[$loop]['sort_no'] = $detailTab->sort_no;
                            $tabDetailParameter[$loop]['is_display'] = $detailTab->is_display;
                            $tabDetailParameter[$loop]['event'] = $detailTab->event;
                            // $tabDetailParameter[$loop]['color'] = $color;
                            $loop++;
                        }
                        D_Site_Detail_Tab::insert($tabDetailParameter);
                    }
                }
            }
            $areaIdColums = "";
            if ($areaIds) {
                Site_Area::where('site_id', $siteId)
                    ->where('deleted_at', '>', 0)
                    ->delete();
                $existingAreaIds = Site_Area::where('site_id', $siteId)
                    ->pluck('area_id')
                    ->toArray();

                foreach ($areaIds as $areaId) {
                    $existing = Site_Area::where('site_id', $siteId)
                        ->where('area_id', $areaId)
                        ->first();

                    if (!$existing) {
                        Site_Area::create([
                            'site_id' => $siteId,
                            'area_id' => $areaId,
                            'created_at' => $time,
                        ]);
                    }
                }

                $toDelete = array_diff($existingAreaIds, $areaIds);
                if (!empty($toDelete)) {
                    Site_Area::where('site_id', $siteId)
                        ->whereIn('area_id', $toDelete)
                        ->delete();
                }
            }
            $genreIdColums = "";
            if($genreIds) {
                Site_Genre::where('site_id', $siteId)
                    ->where('deleted_at', '>', 0)
                    ->delete();
                $existingGenreIds = Site_Genre::where('site_id', $siteId)
                    ->pluck('genre_id')
                    ->toArray();

                foreach ($genreIds as $genreId) {
                    $existing = Site_Genre::where('site_id', $siteId)
                        ->where('genre_id', $genreId)
                        ->first();

                    if (!$existing) {
                        Site_Genre::create([
                            'site_id' => $siteId,
                            'genre_id' => $genreId,
                            'created_at' => $time,
                        ]);
                    }
                }

                $genreToDelete = array_diff($existingGenreIds, $genreIds);
                if (!empty($genreToDelete)) {
                    Site_Genre::where('site_id', $siteId)
                        ->whereIn('genre_id', $genreToDelete)
                        ->delete();
                }
            }
            // $siteHiddenIdColums = "";
            // if($request->site_hidden) {
            //     foreach($request->site_hidden as $siteId) {
            //         if(empty($siteHiddenIdColums)) {
            //             $siteHiddenIdColums = $siteId;
            //         } else {
            //             $siteHiddenIdColums .= ",".$siteId;
            //         }
            //     }
            // }
            $addParameter =[
                // 'top_url' => $request->top_url,
                "pc_top_url" => $request->pc_top_url,
                "sp_top_url" => $request->sp_top_url,
                "p_top_url" => $request->p_top_url,
                'recruit_key'  => $request->recruit_key,
                'old_template'  => $request->old_template,
                'switching_time'  => $request->switching_time,
                'open_time'  => $request->open_time,
                'close_time'  => $request->close_time,
                'system_text'  => $request->system_text,
                'blog_owner_host'  => $request->blog_owner_host,
                'blog_owner_user'   => $request->blog_owner_user,
                'blog_owner_pass'   => $request->blog_owner_pass,
                'blog_staff_host'   => $request->blog_staff_host,
                'blog_staff_user'   => $request->blog_staff_user,
                'blog_staff_pass'   => $request->blog_staff_pass,
                'mail_magazine_url'   => $request->mail_magazine_url,
                'mail_magazine_key'   => $request->mail_magazine_key,
                'mail_magazine_create_mail'   => $request->mail_magazine_create_mail,
                'mail_magazine_delete_mail'   => $request->mail_magazine_delete_mail,
                'recruit_line_url'   => $request->recruit_line_url,
                'recruit_line_id'   => $request->recruit_line_id,
                'analytics_code'   => $request->analytics_code,
                'analytics_api'   => $request->analytics_api,
                'is_https'   => $request->is_https == 1 ? 'YES' : 'NO',
                'portal_template_url'   => $request->portal_template_url,
                'portal_tab'   => $request->portal_tab,
                'site_hidden' => $request->site_hidden, //ID
                'is_externally_server' => $request->is_externally_server == 1 ? 'YES' : 'NO', //ID
                // 'site_genre' => $genreIdColums, //英文字(ワンチャンいらん)
                // 'site_area' => $areaIdColums, //英文字(ワンチャンいらん)
                // 'staff_sort'   => $request->staff_sort,
            ];
            $parameter = array_merge($parameter, $addParameter);
            X459x_Site::upsertData($parameter);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        $resArray = $this->resArray;
        try {
            M_Site::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time()])->save();
        } catch (\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
    public function getActiveTabColor($template) {
        $tabs = [
            1 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#EF747D",
            ],
            2 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#6D66AA",
            ],
            3 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#F684A6",
            ],
            4 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#B66497",
            ],
            5 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#F27847",
            ],
            6 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#C02639",
            ],
            7 => [
                'active_color' => "#FFFFFF",
                'active_background' => "#917961",
            ],
        ];
        if(!isset($tabs[$template])) {
            return [];
        }
        return $tabs[$template];
    }
}
