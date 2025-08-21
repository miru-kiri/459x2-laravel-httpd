<?php

namespace App\Http\Controllers;

use App\Models\D_Notice;
use App\Models\D_Site_Blog_Log;
use App\Models\D_Site_Cast_Log;
use App\Models\D_Site_Detail_Log;
use App\Models\D_Site_Top_Log;
use App\Models\M_Genre;
use App\Models\M_Shop;
use App\Models\M_Site;
use App\Models\M_Site_Tab;
use App\Models\User_Like;
use App\Models\User_Like_Cast;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheHtmlHelper;

class SitePageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('can:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $cacheKey = "cache.home.page";
        $cachedHtml = Cache::get($cacheKey);
        if ($cachedHtml) {
            return CacheHtmlHelper::renderCachedHtml($cachedHtml, $request);
        }

        $cards =[
            [
                'color' => '#E73E90',
                'text' => '風俗',
                'url' => route('site.detail', ['genre_id' => 1]),
                'image' => asset('storage/top/fuzoku_s.jpg')
            ],
            [
                'color' => '#EC7291',
                'text' => 'セクキャバ',
                'url' => route('site.detail', ['genre_id' => 4]),
                'image' => asset('storage/top/sex_cabaret_s.jpg')
            ],
            [
                'color' => '#9860A4',
                'text' => 'キャバクラ',
                'url' => route('site.detail', ['genre_id' => 3]),
                'image' => asset('storage/top/cabaret_s.jpg')
            ],
            [
                'color' => '#00ACA0',
                'text' => 'メンズエステ',
                'url' => route('site.detail', ['genre_id' => 2]),
                'image' => asset('storage/top/men_esthetic_s.jpg')
            ],
            [
                'color' => '#F39800',
                'text' => '宴会コンパニオン',
                'url' => route('site.detail', ['genre_id' => 6]),
                'image' => asset('storage/top/companion_s.jpg')
            ],
            [
                'color' => '#8FC31F',
                'text' => '飲食店',
                'url' => route('site.detail', ['genre_id' => 5]),
                'image' => asset('storage/top/restaurant_s.jpg')
            ],
        ];
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];

        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')]
        ];


        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if($request->has('genre_id')) {
           $kio_gid = $request->genre_id;
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }

        $news = D_Notice::FetchPublicAll();
		$keywords = '四国,天国ネット';
        foreach($cards as $card) {
            $keywords .= ",".$card['text'];
        }
        $krn_url = config('app.url');
        $qrCode = QrCode::size(200)->generate("Hello World");
        // return view('sitePages.index', compact('cards', 'tabs', 'news', 'breadCrumbs', 'keywords', 'kio_gen', 'qrCode'));
        $viewContent = view('sitePages.index', compact('cards', 'tabs', 'news', 'breadCrumbs', 'keywords', 'kio_gen', 'qrCode','krn_url'))->render();
        Cache::put($cacheKey, $viewContent, now()->addMinutes(12));
        return response($viewContent);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function notice(Request $request)
    {
        $id = $request->id;
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];


        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }

        $news = D_Notice::findOrFail($id);
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            ['label' => 'お知らせ','url' => route('site.notice')]
        ];
		$keywords = '四国,天国ネット'. $news->title;
        return view('sitePages.notice',compact('tabs','news','breadCrumbs','keywords', 'kio_gen'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function manual()
    {
        $mainColor = "#FACD01";
        $cards =[
            [
                'text' => '天国ネットとは',
                'url' => '#tengokunet'
            ],
            [
                'text' => 'お得な会員について',
                'url' => '#member'
            ],
            [
                'text' => 'ご予約について',
                'url' => '#reserve'
            ],
            [
                'text' => '料金について',
                'url' => '#price'
            ],
            [
                'text' => '女の子について',
                'url' => '#cast'
            ],
            [
                'text' => 'その他',
                'url' => '#other'
            ],
        ];
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];


        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }

        return view('sitePages.manual',compact('mainColor','cards','tabs','breadCrumbs', 'kio_gen'));
    }
    public function search(Request $request)
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
		$keywords = '四国,天国ネット';
        $genre = config('constant.serch.genre');
        foreach($genre as $g) {
            $keywords .= ",".$g['label'];
        }
        $formatDetail = [];
        $genreDetail = M_Genre::fetchIsSerch();
        foreach($genreDetail as $detail) {
            $formatDetail[$detail->search_category][] = $detail;
			$keywords .= ",".$detail->name;
        }
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            ['label' => '現在地から検索','url' => route('site.search')]
        ];


        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }

        return view('sitePages.search',compact('tabs','genre','formatDetail','breadCrumbs','keywords', 'kio_gen'));
    }
    public function searchDetail(Request $request)
    {
        $genre = config('constant.serch.genre');
        $templateAry = [];
		$keywords = '四国,天国ネット';
        foreach($genre as $g) {
            $name = $g['name'];
            if($request->$name) {
                $templateAry[] = $g['value'];
				$keywords .= ",".$g['label'];
            }
        }
        $genreId = [];
        $detailId = M_Genre::fetchIsSerchPluckAry();
        foreach($detailId as $dId) {
            $name = "detail-$dId";
            if($request->$name) {
                $genreId[] = $dId;
            }
        }
        if(!$templateAry) {
            $templateAry = 0;
        }
        $filter = [
            'template' => $templateAry,
        ];
        $shopIdAry = M_Site::FetchFilterShopIdAry($filter);
        $shopData = [];
        if($shopIdAry) {
            $shopData = M_Shop::fetchFilterData(['shop_id' => $shopIdAry,'genre_id' => $genreId]);
        }
		foreach($shopData as $shopd) {
            $keywords .= ",".$shopd['site_name'];
        }
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            ['label' => '現在地から検索','url' => route('site.search')],
            ['label' => '詳細','url' => route('site.search.detail')]
        ];


        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }

        return view('sitePages.search_detail',compact('tabs','shopData','breadCrumbs','keywords', 'kio_gen'));
    }
    public function topLog(Request $request)
    {
        // $ua = $request->header('User-Agent');
        // if(strpos($ua,'iPhone') !== false or strpos($ua,'Android') !== false){
        //     //スマホの時の処理
        //     $device = 1;
        // } else if((strpos($ua,'DoCoMo') !== false
        //         || strpos($ua,'SoftBank') !== false
        //         || strpos($ua,'KDDI') !== false)
        //         // フルブラウザ、PCブラウザは'Mozilla'が含まれている
        //         && strpos($ua,'Mozilla') === false) {
        //         // ガラケーの時の処理
        //         $device = 2;
        // } else {
        //     // 大雑把な振り分けだとあとはPCの処理
        //     $device = 0;
        // }
        $device = 0;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Opera Mini') !== false) {
            //ガラケー
            $device = 2;
        }
        if (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'Android') !== false) {
            //スマホ
            $device = 1;
        } 
        $parameter = [
            'created_at' => time(),
            'user_id' => session('user_id') ?? 0,
            'date' => date('Ymd'),
            'time' => date('His'),
            'device' => $device
        ];
        D_Site_Top_Log::insert($parameter);
    }
    public function castLog(Request $request)
    {
        // $device = 0;
        // $ua = $request->header('User-Agent');
        // if(strpos($ua,'iPhone') !== false or strpos($ua,'Android') !== false){
        //     //スマホの時の処理
        //     $device = 1;
        // } else if((strpos($ua,'DoCoMo') !== false
        //         || strpos($ua,'SoftBank') !== false
        //         || strpos($ua,'KDDI') !== false)
        //         // フルブラウザ、PCブラウザは'Mozilla'が含まれている
        //         && strpos($ua,'Mozilla') === false) {
        //         // ガラケーの時の処理
        //         $device = 2;
        // } else {
        //     // 大雑把な振り分けだとあとはPCの処理
        //     $device = 0;
        // }
        $device = 0;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Opera Mini') !== false) {
            //ガラケー
            $device = 2;
        }
        if (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'Android') !== false) {
            //スマホ
            $device = 1;
        } 
        $castId = $request->cast_id  ?? 0;
        if(empty($castId)) {
            exit();
        }
        $parameter = [
            'created_at' => time(),
            'user_id' => session('user_id') ?? 0,
            'cast_id' => $castId,
            'date' => date('Ymd'),
            'time' => date('His'),
            'device' => $device
        ];
        D_Site_Cast_Log::insert($parameter);
    }
    public function blogLog(Request $request)
    {
        // $ua = $request->header('User-Agent');
        // if(strpos($ua,'iPhone') !== false or strpos($ua,'Android') !== false){
        //     //スマホの時の処理
        //     $device = 1;
        // } else if((strpos($ua,'DoCoMo') !== false
        //         || strpos($ua,'SoftBank') !== false
        //         || strpos($ua,'KDDI') !== false)
        //         // フルブラウザ、PCブラウザは'Mozilla'が含まれている
        //         && strpos($ua,'Mozilla') === false) {
        //         // ガラケーの時の処理
        //         $device = 2;
        // } else {
        //     // 大雑把な振り分けだとあとはPCの処理
        //     $device = 0;
        // }
        $device = 0;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Opera Mini') !== false) {
            //ガラケー
            $device = 2;
        }
        if (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'Android') !== false) {
            //スマホ
            $device = 1;
        } 
        $categoryId = $request->category_id  ?? 0;
        $blogId = $request->blog_id ?? 0;
        $siteId = $request->site_id ?? null;
        $castId = $request->cast_id ?? null;
        if(empty($categoryId) || empty($blogId)) {
            exit();
        }
        $parameter = [
            'created_at' => time(),
            'user_id' => session('user_id') ?? 0,
            'category_id' => $categoryId,
            'blog_id' => $blogId,
            'site_id' => $siteId,
            'cast_id' => $castId,
            'date' => date('Ymd'),
            'time' => date('His'),
            'device' => $device
        ];
        D_Site_Blog_Log::insert($parameter);
    }
    public function siteLog(Request $request)
    {
        
        // $userAgent = $request->header('User-Agent');
        // if ((strpos($userAgent, 'iPhone') !== false)
        //     || (strpos($userAgent, 'iPod') !== false)
        //     || (strpos($userAgent, 'Android') !== false)) {
        //     $device = 1;
        // } else {
        //     $device = 2;
        // }

        //pc
        $device = 0;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Opera Mini') !== false) {
            //ガラケー
            $device = 2;
        }
        if (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'Android') !== false) {
            //スマホ
            $device = 1;
        } 
        $siteId = $request->site_id  ?? 0;
        if(empty($siteId)) {
            exit();
        }
        $parameter = [
            'created_at' => time(),
            'user_id' => session('user_id') ?? 0,
            'site_id' => $siteId,
            'date' => date('Ymd'),
            'time' => date('His'),
            'device' => $device
        ];
        D_Site_Detail_Log::insert($parameter);
    }
    public function siteLike(Request $request)
    {
        $siteId = $request->site_id  ?? 0;
        if(empty($siteId)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        $userId = session('user_id');
        if(empty($userId)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        $parameter = [
            'user_id' => $userId,
            'site_id' => $siteId,
        ];
        $isData = User_Like::FilterFirstData($parameter);
        if($isData) {
            $resArray = [
                'result' => 2,
                'message' => '既にお気に入り登録されています。',
            ];
            return response()->json($resArray);
        }
        $parameter['created_at'] = now();
        $parameter['updated_at'] = now();
        User_Like::insert($parameter);
        $resArray = [
            'result' => 0,
            'message' => '処理が成功しました。',
        ];
        return response()->json($resArray);
    }
    public function siteCastLike(Request $request)
    {
        $castId = $request->cast_id  ?? 0;
        if(empty($castId)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        $userId = session('user_id');
        if(empty($userId)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        $parameter = [
            'user_id' => $userId,
            'cast_id' => $castId,
        ];
        $isData = User_Like_Cast::FilterFirstData($parameter);
        if($isData) {
            $resArray = [
                'result' => 2,
                'message' => '既にお気に入り登録されています。',
            ];
            return response()->json($resArray);
        }
        $parameter['created_at'] = now();
        $parameter['updated_at'] = now();
        User_Like_Cast::insert($parameter);
        $resArray = [
            'result' => 0,
            'message' => '処理が成功しました。',
        ];
        return response()->json($resArray);
    }
    public function portalIndex(Request $request)
    {
        $siteId = $request->sid ?? 0;
        $pg = $request->pg ?? 0;
        $tabId = 0;
        if(!$siteId) {
            return redirect()->route('site');
        }
        $siteData = M_Site::findOrFail($siteId);
        $template = $siteData->template;
        $whr = 'トップ';
        if($pg) {
            if($pg == 'top') {
                $whr = 'トップ';
            }
            if($pg == 'staff') {
                $whr = '在籍一覧';
            }
            if($pg == 'scd') {
                $whr = '出勤情報';
            }
            if($pg == 'system') {
                $whr = '料金システム';
            }
            if($pg == 'access') {
                $whr = '店舗情報';
            }
            if($pg == 'news') {
                $whr = 'ショップニュース';
            }
            if($pg == 'blog') {
                $whr = '写メ日記';
            }
        }
        $tabData = M_Site_Tab::fetchFirstTemplateData(['template' => $template,'name' => $whr]);
        if($tabData) {
            $tabId = $tabData->id;
        }
        // 'genre_id' => ,'area_id' => ,
        return redirect()->route('site.detail.top',['tab_id'=> $tabId,'site_id' => $siteId]);
    }
    public function portalReview(Request $request)
    {
        $siteId = $request->sid ?? 0;
        return redirect()->route('mypage.contact',['site_id' => $siteId]);
    }
    public function guide()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        return view('sitePages.guide',compact('tabs','breadCrumbs', 'kio_gen'));
    }
    //dogo_kabuki
    public function dogo_kabuki()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        return view('sitePages.dogo_kabuki',compact('tabs','breadCrumbs', 'kio_gen'));
    }
    //krn_health_bill 2025.5.21
    public function health_bill()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        return view('sitePages.health_bill',compact('tabs','breadCrumbs','kio_gen'));
    }
    //2025_6_10 krn_all_movies
    public function all_movies()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        return view('sitePages.all_movies',compact('tabs','breadCrumbs', 'kio_gen'));
    }
    //2025_6_17 krn
    public function recruit_top()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        $krn_url = config('app.url');
        $recruit_title = '愛媛・香川の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様
        return view('sitePages.recruit_top',compact('tabs','breadCrumbs', 'kio_gen', 'recruit_title', 'krn_url'));
    }
    public function recruit_dogo_matuyama()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        $krn_url = config('app.url');
        $krn_gtu = request()->query('mord') ?? 'dogo';
        if('dogo' == $krn_gtu){
            $recruit_title =  '道後の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様
        }else{
            $recruit_title =  '松山の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様
        }
        return view('sitePages.recruit_dogo_matuyama',compact('tabs','breadCrumbs', 'kio_gen' , 'recruit_title' ,'krn_gtu' , 'krn_url'));
    }
    public function recruit_nihama()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        $krn_url = config('app.url');
        $recruit_title = '新居浜の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様

        return view('sitePages.recruit_nihama',compact('tabs','breadCrumbs', 'kio_gen' , 'recruit_title', 'krn_url'));
    }
    public function recruit_sikokutyuou()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        $krn_url = config('app.url');
        $recruit_title = '四国中央市の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様

        return view('sitePages.recruit_sikokutyuou',compact('tabs','breadCrumbs', 'kio_gen' , 'recruit_title' , 'krn_url'));
    }
    public function recruit_kotohira()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        $krn_url = config('app.url');
        $recruit_title = '琴平の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様

        return view('sitePages.recruit_kotohira',compact('tabs','breadCrumbs', 'kio_gen' , 'recruit_title' , 'krn_url'));
    }
    public function recruit_takamatu()
    {
        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail',['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail',['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail',['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail',['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail',['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail',['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail',['genre_id' => 7]), 'name' => 'もみほぐし'],
            //['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ','url' => route('site')],
            //['label' => 'サイトの使い方','url' => route('site.manual')]
        ];
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
               '1' => '風俗'
             ]
          , 'gnr' => [ //一般
               '2' => 'メンズエステ'
             , '3' => 'キャバクラ'
             , '4' => 'セクキャバ'
             , '5' => '飲食'
             , '6' => '宴会'
             , '7' => 'もみほぐし'
           ] 
        ];
        //page のgenre_idを取得
        $kio_gen = ''; 
        $kio_gid = "";
        
        if(request()->has('genre_id')) {
           $kio_gid = request()->query('genre_id');
           foreach($kio_genmst as $gky => $gary){
              if(array_key_exists($kio_gid, $gary)){
                 $kio_gen = $gky;
                 $gnr_name = $gary[$kio_gid];
              }
           }
           if($kio_gen == "") {
              $kio_gen = 'fzk';
           }
        } else {
           $kio_gid = "";
           $kio_gen = 'fzk';
        }
        $krn_url = config('app.url');
        $recruit_title = '高松市の高収入風俗求人情報をご案内！';//layoutのtitleタグに使用 この変数がないものは表示されない仕様
        return view('sitePages.recruit_takamatu',compact('tabs','breadCrumbs', 'kio_gen' , 'recruit_title' , 'krn_url'));
    }
    

}
