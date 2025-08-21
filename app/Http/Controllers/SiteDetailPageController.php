<?php

namespace App\Http\Controllers;

use App\Models\Cast_Answer;
use App\Models\Cast_Image;
use App\Models\Cast_Schedule;
use App\Models\D_Cast_Blog;
use App\Models\D_Cast_Blog_Image;
use App\Models\D_Movie;
use App\Models\D_Reserve;
use App\Models\D_Review;
use App\Models\D_Shop_Blog;
use App\Models\D_Shop_Manager_Blog;
use App\Models\D_Site_Detail_Tab;
use App\Models\D_Site_Tab;
use App\Models\M_Area;
use App\Models\M_Cast;
use App\Models\M_Cast_Option;
use App\Models\M_Cast_Question;
use App\Models\M_Shop;
use App\Models\M_Site;
use App\Models\Site_Area;
use App\Models\Site_Course;
use App\Models\Site_Genre;
use App\Models\Site_Image;
use App\Models\Site_Info;
use App\Models\Site_Nomination_Fee;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Helpers\TimeHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Helpers\CacheHtmlHelper;

class SiteDetailPageController extends Controller
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
    public function getGenre($genreId)
    {
        $genre = [
            1 =>
            [
                'color' => '#F1747E',
                'text' => '風俗',
            ],
            2 =>
            [
                'color' => '#6D66AA',
                'text' => 'メンズエステ',
            ],
            3 =>
            [
                'color' => '#F684A6',
                'text' => 'キャバクラ',
            ],
            4 =>
            [
                'color' => '#B66497',
                'text' => 'セクキャバ',
            ],
            5 =>
            [
                'color' => '#F27847',
                'text' => '飲食店',
            ],
            6 =>
            [
                'color' => '#C02639',
                'text' => '宴会コンパニオン',
            ],
            7 =>
            [
                'color' => '#917961',
                'text' => 'もみほぐし',
            ],
        ];
        if (isset($genre[$genreId])) {
            $genre = $genre[$genreId];
        }
        return $genre;
    }
    // マスタから動的に取得するよう変更
    // public function getArea($genreId,$areaId) 
    // {
    //     $area = [
    //         1 =>
    //         [
    //             1 => [
    //                 'color' => '#F784A6',
    //                 'text' => '松山',
    //                 'url' => route('site.detail.area',['genre_id' => 1,'area_id' => 1])
    //             ],
    //             2 => [
    //                 'color' => '#F1747E',
    //                 'text' => '琴平',
    //                 'url' => route('site.detail.area',['genre_id' => 1,'area_id' => 2])
    //             ],
    //             3 => [
    //                 'color' => '#5699C4',
    //                 'text' => '高松',
    //                 'url' => route('site.detail.area',['genre_id' => 1,'area_id' => 3])
    //             ]
    //         ],
    //         2 =>
    //         [
    //             1 => [
    //                 'color' => '#F2625E',
    //                 'text' => '松山',
    //                 'url' => route('site.detail.area',['genre_id' => 2,'area_id' => 1])
    //             ],
    //             2 => [
    //                 'color' => '#F784A6',
    //                 'text' => '道後',
    //                 'url' => route('site.detail.area',['genre_id' => 2,'area_id' => 2])
    //             ],
    //             3 => [
    //                 'color' => '#76C66B',
    //                 'text' => '新居浜',
    //                 'url' => route('site.detail.area',['genre_id' => 2,'area_id' => 3])
    //             ],
    //             4 => [
    //                 'color' => '#996FAE',
    //                 'text' => '四国中央',
    //                 'url' => route('site.detail.area',['genre_id' => 2,'area_id' => 4])
    //             ],
    //         ],
    //         3 =>
    //         [
    //             1 => [
    //                 'color' => '#F1625D',
    //                 'text' => '松山',
    //                 'url' => route('site.detail.area',['genre_id' => 3,'area_id' => 1])
    //             ],
    //             2 => [
    //                 'color' => '#6C66AC',
    //                 'text' => '西条',
    //                 'url' => route('site.detail.area',['genre_id' => 3,'area_id' => 2])
    //             ],
    //             3 => [
    //                 'color' => '#996FAF',
    //                 'text' => '四国中央',
    //                 'url' => route('site.detail.area',['genre_id' => 3,'area_id' => 3])
    //             ],
    //         ],
    //         4 =>
    //         [
    //             1 => [
    //                 'color' => '#F2625E',
    //                 'text' => '松山',
    //                 'url' => route('site.detail.area',['genre_id' => 4,'area_id' => 1])
    //             ],
    //             2 => [
    //                 // 'color' => '#F784A6',
    //                 // 'text' => '道後',
    //                 // 'url' => route('site.detail.area',['genre_id' => 4,'area_id' => 2])
    //                 'color' => '#6C66AC',
    //                 'text' => '西条',
    //                 'url' => route('site.detail.area',['genre_id' => 4,'area_id' => 2])
    //             ],
    //             3 => [
    //                 'color' => '#76C66B',
    //                 'text' => '新居浜',
    //                 'url' => route('site.detail.area',['genre_id' => 4,'area_id' => 3])
    //             ],
    //             4 => [
    //                 'color' => '#996FAE',
    //                 'text' => '四国中央',
    //                 'url' => route('site.detail.area',['genre_id' => 4,'area_id' => 4])
    //             ],
    //         ],
    //         5 =>
    //         [
    //             1 => [
    //                 'color' => '#F2625E',
    //                 'text' => '松山',
    //                 'url' => route('site.detail.area',['genre_id' => 5,'area_id' => 1])
    //             ],
    //         ],
    //         6 =>
    //         [
    //             1 => [
    //                 'color' => '#F784A6',
    //                 'text' => '道後',
    //                 'url' => route('site.detail.area',['genre_id' => 6,'area_id' => 1])
    //             ],
    //         ],
    //     ];
    //     if(isset($area[$genreId])) {
    //         $area = $area[$genreId];
    //     }
    //     if(isset($area[$areaId])) {
    //         $area = $area[$areaId];
    //     }
    //     return $area;
    // }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $genreId = $request->genre_id ?? 0;
        if (!isset($genreId)) {
            return redirect()->route('site');
        }

        $cacheKey = "cache.detail.genre.{$genreId}";
        $cachedHtml = Cache::get($cacheKey);
        if ($cachedHtml) {
            return CacheHtmlHelper::renderCachedHtml($cachedHtml, $request);
        }

        $genre = $this->getGenre($genreId);
        if (!isset($genre['color'])) {
            return redirect()->route('site');
        }


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

        $mainColor = $genre['color'];
        $mainText = $genre['text'];
        // $cards = $this->getArea($genreId,0);

        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail', ['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail', ['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail', ['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail', ['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail', ['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail', ['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail', ['genre_id' => 7]), 'name' => 'もみほぐし'],
            // ['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];
        $breadCrumbs = [
            ['label' => 'トップ', 'url' => route('site')],
            ['label' => $mainText, 'url' => '']
        ];

        $siteIdAry = M_Site::fetchFilterIdAry(['template' => $genreId]);
        $siteAreas = [];
        if ($siteIdAry) {
            $siteAreas = Site_Area::fetchFilterData(['site_id' => $siteIdAry]);
        }
        $keywords = '四国,天国ネット,' . $mainText;
        $cards = [];
        foreach ($siteAreas as $siteArea) {
            $cards[$siteArea->area_id] = [
                'color' => $siteArea->color,
                'text' => $siteArea->name,
                'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $siteArea->area_id])
            ];
        }
        foreach ($cards as $areaId => $card) {
            $keywords .= "," . $card['text'];
        }
        $shopBlogs = [];
        $managerBlogs = [];
        $castBlogs = [];
        if ($siteIdAry) {
            //ショップニュース
            $shopBlogs = D_Shop_Blog::fetchSiteIdLimitData($siteIdAry, 3);
            //店長ブログ
            $managerBlogs = D_Shop_Manager_Blog::fetchSiteIdLimitData($siteIdAry, 3);
            //キャストブログ
            $castBlogs = D_Cast_Blog::fetchSiteIdLimitData($siteIdAry, 6);
        }

        foreach ($castBlogs as $index => $castBlog) {
            $castBlogs[$index]->image = '/no-image.jpg';
            $isImage = D_Cast_Blog_Image::fetchFilterIdFirstData($castBlog->id);
            if ($isImage) {
                $castBlogs[$index]->image = $isImage->image_url;
                continue;
            }
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castBlog->cast_id]);
            if ($isCastImage) {
                $castBlogs[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }
        $filterDate = 0;
        //アクセスランキング(打ち合わせにより、非表示)
        $castAccessRanks = [];
        // if ($siteIdAry) {
        //     $castAccessRanks = M_Cast::fetchAccessCountCast(['site_id' => $siteIdAry, 'limit' => 3, 'date' => $filterDate]);
        // }
        // foreach ($castAccessRanks as $index => $castAccessRank) {
        //     $castAccessRanks[$index]->image = '/no-image.jpg';
        //     $castAccessRanks[$index]->age =  $castAccessRank->age ?? '-';
        //     $castAccessRanks[$index]->bust =  $castAccessRank->bust ??  '-';
        //     $castAccessRanks[$index]->cup =  $castAccessRank->cup ?? '-';
        //     $castAccessRanks[$index]->waist =  $castAccessRank->waist ?? '-';
        //     $castAccessRanks[$index]->hip =  $castAccessRank->hip ?? '-';
        //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castAccessRank->id]);
        //     if ($isCastImage) {
        //         $castAccessRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
        //     }
        // }
        //お気に入りランキング(打ち合わせにより、非表示)
        $castFavoriteRanks = [];
        // if ($siteIdAry) {
        //     $castFavoriteRanks = M_Cast::fetchLikeCountCast(['site_id' => $siteIdAry, 'limit' => 3, 'date' => $filterDate]);
        // }
        // foreach ($castFavoriteRanks as $index => $castFavoriteRank) {
        //     $castFavoriteRanks[$index]->image = '/no-image.jpg';
        //     $castFavoriteRanks[$index]->age =  $castFavoriteRank->age ?? '-';
        //     $castFavoriteRanks[$index]->bust =  $castFavoriteRank->bust ??  '-';
        //     $castFavoriteRanks[$index]->cup =  $castFavoriteRank->cup ?? '-';
        //     $castFavoriteRanks[$index]->waist =  $castFavoriteRank->waist ?? '-';
        //     $castFavoriteRanks[$index]->hip =  $castFavoriteRank->hip ?? '-';
        //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castFavoriteRank->id]);
        //     if ($isCastImage) {
        //         $castFavoriteRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
        //     }
        // }
        //写メ日記ランキング(打ち合わせにより、非表示)
        $castDiaryRanks = [];
        // if ($siteIdAry) {
        //     $castDiaryRanks = M_Cast::fetchBlogCountCast(['site_id' => $siteIdAry, 'limit' => 3, 'date' => $filterDate, 'category_id' => 3]);
        // }
        // foreach ($castDiaryRanks as $index => $castDiaryRank) {
        //     $castDiaryRanks[$index]->image = '/no-image.jpg';
        //     $castDiaryRanks[$index]->age =  $castDiaryRank->age ?? '-';
        //     $castDiaryRanks[$index]->bust =  $castDiaryRank->bust ??  '-';
        //     $castDiaryRanks[$index]->cup =  $castDiaryRank->cup ?? '-';
        //     $castDiaryRanks[$index]->waist =  $castDiaryRank->waist ?? '-';
        //     $castDiaryRanks[$index]->hip =  $castDiaryRank->hip ?? '-';
        //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castDiaryRank->id]);
        //     if ($isCastImage) {
        //         $castDiaryRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
        //     }
        // }
        $movieData = [];
        if ($siteIdAry) {
            $movieData = D_Movie::fetchFilterData(['site_id' => $siteIdAry, 'cast_id' => 0, 'limit' => 6]);
        }
        /*
        ジャンルのテンプレ
        genre_id
        area_id
        site_id
        tab_id
        cast_id
        category_id
        id
        */
        // kio_20250215
        $pgttl_ary = [
            1 => ['ttl1' => '愛媛県香川県で人気・おすすめの', 'ttl2' => '店をまとめてご紹介｜コスモ天国ネット'],
            2 => ['ttl1' => '愛媛県で人気・おすすめの', 'ttl2' => '店をまとめてご紹介｜コスモ天国ネット'],
            3 => ['ttl1' => '愛媛県四国中央市で人気・おすすめの', 'ttl2' => '店をまとめてご紹介｜コスモ天国ネット'],
            4 => ['ttl1' => '愛媛県松山市で人気・おすすめの', 'ttl2' => '店をまとめてご紹介｜コスモ天国ネット'],
            5 => ['ttl1' => '愛媛県松山市で人気・おすすめの', 'ttl2' => 'をまとめてご紹介｜コスモ天国ネット'],
            6 => ['ttl1' => '愛媛県松山市道後で人気・おすすめの', 'ttl2' => '店をまとめてご紹介｜コスモ天国ネット'],
            7 => ['ttl1' => '愛媛県松山市道後で人気・おすすめの', 'ttl2' => '店をまとめてご紹介｜コスモ天国ネット']
        ];
        $pgttl = $pgttl_ary[$request ->genre_id]['ttl1'].$mainText.$pgttl_ary[$request ->genre_id]['ttl2'];
        $genreId = $request->genre_id ?? 0;
        if(!isset($genreId)){
            $pgttl = "愛媛県香川県で人気・おすすめのナイトアミューズメントをまとめてご紹介｜コスモ天国ネット";
        }
        $pgdesc_ary = [
            1 => ['disc1' => '愛媛県香川県の', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。ソープ、ヘルス、デリヘル店に行きたい時はぜひご覧ください！'],
            2 => ['disc1' => '愛媛県松山市、新居浜市、四国中央市で人気・おすすめの', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。メンズエステ店に行きたい時はぜひご覧ください！'],
            3 => ['disc1' => '愛媛県四国中央市で人気・おすすめの', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。キャバクラ店に行きたい時はぜひご覧ください！'],
            4 => ['disc1' => '愛媛県松山市で人気・おすすめの', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。セクキャバ店に行きたい時はぜひご覧ください！'],
            5 => ['disc1' => '愛媛県松山市で人気・おすすめの', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。飲食店に行きたい時はぜひご覧ください！'],
            6 => ['disc1' => '愛媛県松山市道後で人気・おすすめの', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。ソープ、ヘルス、デリヘル店に行きたい時はぜひご覧ください！'],
            7 => ['disc1' => '愛媛県松山市道後で人気・おすすめの', 'disc2' => 'を探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。もみほぐし店に行きたい時はぜひご覧ください！']
        ];
        $pgdesc = $pgdesc_ary[$request ->genre_id]['disc1'].$mainText.$pgdesc_ary[$request ->genre_id]['disc2'];
        $genreId = $request->genre_id ?? 0;
        if(!isset($genreId)){
            $pgdesc = "愛媛県香川県で人気・おすすめのナイトアミューズメントをを探すならコスモ天国ネット！女の子の写メ、ブログが凄く充実していて、イベント・割引等の店舗情報も満載です。夜の遊び場を探したい時はぜひご覧ください！";
        }

        //2025_05_05 krn index説明
        $fstItem_ttl = [
            1 => ['ttl1' => 'ソープ・ファッションヘルスとは'],
            2 => ['ttl1' => 'メンズエステとは'],
            3 => ['ttl1' => 'キャバクラとは'],
            4 => ['ttl1' => 'セクキャバとは'],
            5 => ['ttl1' => 'コスモ天国ネットが紹介する飲食店'],
            6 => ['ttl1' => '宴会コンパニオンとは'],
            7 => ['ttl1' => 'コスモ天国ネットが紹介するマッサージ店']
        ];
        $fstItem = $fstItem_ttl[$request ->genre_id]['ttl1'];
        $genreId = $request->genre_id ?? 0;
        if(!isset($genreId)){
            $fstItem = "コスモ天国ネットが紹介する業種一覧";
        }

        $secItem_ttl = [
            1 => ['ttl1' => '<p>ソープランドは、泡、ローション、マットを使った究極のリラクゼーション体験を提供します。入浴でリラックスし、泡やローションに包まれる肌の感触でスタッフが緊張をほぐし、心身ともに快楽の波に飲み込まれます。その後、押し寄せる快感により、自身の欲望を解放させ、至福の瞬間が訪れます。</P><p>さらに、マットを使った密着マッサージで身体全体をリズムよく解きほぐし、快感とリラックスを同時に感じることができます。ソープランドでは、これらの施術に加え、最後に待ち受ける究極のサービスで、あなたの全てが解き放たれる特別な体験が待っています。</p><p>ヘルスは、男性専用のリラクゼーションサービスを提供する施設で、ファッションヘルス、ホテヘル、デリヘルなどさまざまなスタイルがあります。リラックスしながら、心地よい刺激と癒しを同時に楽しむことができます。</p><p>オイルやローションを使ったマッサージで身体の緊張をほぐし、リズム感のある密着マッサージで快感とリラックスを提供します。さらに、コスプレやイメプレなどの視覚的楽しみも加わり、完全個室で周囲を気にせず、個別の要望に応じた特別な体験を楽しめます。</p>'],
            2 => ['ttl1' => '<p>メンズエステは、男性専用のリラクゼーションサービスで、主に心身のリフレッシュと癒しを提供するマッサージを行います。オイルトリートメントやアロママッサージを用いて、日々の疲れやストレスを解消し、特にビジネスマンや忙しい方々におすすめです。</p><p>すべての施術はリラクゼーションを目的としており、風俗的なサービスや性的行為は一切行いません。安心してリラックスできる環境が整っており、清潔な施設と資格を持ったセラピストによる丁寧な施術が提供されています。心と体のバランスを整え、日常の喧騒から解放される時間をお楽しみいただけます。<p>'],
            3 => ['ttl1' => '<p>コスモ天国ネットが紹介するキャバクラは、上質な時間と心地よい会話を楽しめる、大人のための社交空間です。</p><p>お酒を片手に、女性キャストとの自然なやりとりを通じて、リラックスしながら過ごす特別なひとときが広がります。</p><p>ここで提供されるのは、丁寧な接客と心地よい雰囲気。</p><p>視覚や会話を楽しみながら、喧騒を忘れて穏やかな時間を味わえる、エンターテインメント性に富んだサービスです。</p><p>もちろん、風俗的なサービスや性的な行為は一切なく、どなたでも安心してご利用いただける健全な空間です。</p><p>夜が深まるほどに心が満たされる、大人のくつろぎ。あなた自身をゆったりと解き放つような、とっておきの時間をお楽しみください。</p>'],
            4 => ['ttl1' => '<p>セクシーキャバクラは、大人の男性向けのエンターテイメント施設で、リラックスした雰囲気の中で、楽しい会話やお酒を楽しむことができます。女性キャストとのやりとりを通じて、心地よい空間で楽しいひとときをお過ごしいただけます。</p><p>ここでは、セクシーな衣装や魅力的なキャストの接客が提供されますが、あくまで視覚的な楽しさやエンターテイメント性に重点を置いています。性的なサービスや行為は一切含まれていませんので、安心してお楽しみいただけます。</p><p>セクキャバでは、会話を楽しみながらリラックスした時間を過ごすことができ、非日常的な雰囲気を満喫しつつ、リフレッシュできる特別な空間が広がっています。心ゆくまでお酒を楽しみながら、気分が高まるひとときをお過ごしください。</p>'],
            5 => ['ttl1' => '<p>コスモ天国ネットが紹介する飲食店は、地元の旬食材を活かし、味・香り・見た目すべてにこだわった料理を提供する、大人のための美食空間です。</p><p>洗練されたインテリアと落ち着きある照明が、日常から少し距離を置いた、上質なひとときを演出。料理に寄り添う地酒を片手に、ゆったりと過ごす時間が流れます。</p><p>大切な人と語らう夜も、ひとり静かにくつろぐ夜も。どの店も“食”を通じて、心まで満たす時間をお届けします。</p><p>※紹介する店舗はいずれも非風俗営業であり、安心してお楽しみいただけるお店のみ掲載しています。</p>'],
            6 => ['ttl1' => '<p>コスモ天国ネットが紹介する宴会コンパニオンは、飲み会や歓送迎会、親睦会、忘年会・新年会などの宴席を華やかに盛り上げます。</p><p>コンパニオンは、上品な接客で場を和やかにし、参加者全員が心地よく楽しめる空間を提供します。お酒を楽しみながら、軽いゲームや会話で宴席を盛り上げ、ちょっぴり大人な遊び心も演出します。</p><p>サービスは全て健全で、性的なサービスは一切提供しません。安心して楽しめる時間をサポートし、特別なひとときを演出します。</p><p>忘年会や新年会など、大切な宴席をより魅力的にするため、質の高いエンターテイメントを提供します。</p>'],
            7 => ['ttl1' => '<p>コスモ天国ネットが紹介するマッサージ店は、純粋にリラクゼーションを目的とした、身体と心を癒す場所です。</p><p>ここでは、オイルトリートメントやアロママッサージなど、リラックス効果が高い施術を提供し、日々の疲れやストレスをしっかりと解消できます。</p><p>すべての施術は、リラクゼーションと癒しに徹し、性的サービスは一切提供しておらず、安心して利用できる環境を整えています。</p><p>清潔感があり、リラックスできる空間で、プロフェッショナルなセラピストが心を込めて施術します。心身のバランスを整え、日常の喧騒から解放される至福の時間をお楽しみいただけます。</p><p>初めてのお客様でも安心して訪れることができ、心身ともにリフレッシュできる最高の体験を提供します。</p>']
        ];
        $secItem = $secItem_ttl[$request ->genre_id]['ttl1'];
        $genreId = $request->genre_id ?? 0;
        if(!isset($genreId)){
            $secItem = "どの業種も最高のナイトエンターテインメントの店です！";
        }
        $krn_url = config('app.url');
        // D_Cast_Blog::fetchSiteIdLimitData($siteIdAry,3);
        // return view('sitePages.detail.index', compact('genreId', 'mainColor', 'mainText', 'cards', 'tabs', 'shopBlogs', 'managerBlogs', 'castBlogs', 'castAccessRanks', 'castFavoriteRanks', 'castDiaryRanks', 'movieData', 'breadCrumbs', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'kio_gen', 'gnr_name','fstItem_ttl','fstItem','secItem_ttl','secItem'));

        $viewContent = view('sitePages.detail.index', compact('genreId', 'mainColor', 'mainText', 'cards', 'tabs', 'shopBlogs', 'managerBlogs', 'castBlogs', 'castAccessRanks', 'castFavoriteRanks', 'castDiaryRanks', 'movieData', 'breadCrumbs', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'kio_gen', 'gnr_name', 'fstItem_ttl', 'fstItem', 'secItem_ttl', 'secItem','krn_url'))->render();
        return response($viewContent);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function area(Request $request)
    {
        $genreId = $request->genre_id ?? 0;
        $areaId = $request->area_id ?? 0;

        $cacheKey = "sitePages.detail.area.{$genreId}.{$areaId}";
        $cachedHtml = Cache::get($cacheKey);
        if ($cachedHtml) {
            return CacheHtmlHelper::renderCachedHtml($cachedHtml, $request);
        }


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


        if (empty($areaId) && empty($genreId)) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        // 道後 = $id = 1,琴平 = $id = 2,高松 = $id = 3

        $tabs = [
            ['url' => route('site'), 'name' => 'Top'],
            ['url' => route('site.detail', ['genre_id' => 1]), 'name' => '風俗'],
            ['url' => route('site.detail', ['genre_id' => 2]), 'name' => 'メンズエステ'],
            ['url' => route('site.detail', ['genre_id' => 3]), 'name' => 'キャバクラ'],
            ['url' => route('site.detail', ['genre_id' => 4]), 'name' => 'セクキャバ'],
            ['url' => route('site.detail', ['genre_id' => 5]), 'name' => '飲食店'],
            ['url' => route('site.detail', ['genre_id' => 6]), 'name' => '宴会コンパニオン'],
            ['url' => route('site.detail', ['genre_id' => 7]), 'name' => 'もみほぐし'],
            // ['url' => route('site.manual'), 'name' => 'サイトの使い方'],
            ['url' => route('site.search'), 'name' => '現在地から検索'],
        ];

        $genre = $this->getGenre($genreId);
        if (!isset($genre['color'])) {
            return redirect()->route('site');
        }
        $mainColor = $genre['color'];
        $mainText = $genre['text'];

        $fetchAreas = M_Area::findOrFail($areaId);
        if ($fetchAreas->deleted_at !== 0) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        $areaColor = $fetchAreas->color;
        $areaText = $fetchAreas->name;
        // $fetchAreas = $this->getArea($genreId,$areaId);
        // $areaColor = $fetchAreas['color'];
        // $areaText = $fetchAreas['text'];
        $keywords = '四国,天国ネット,' . $mainText . ',' . $areaText;
        $breadCrumbs = [
            ['label' => 'トップ', 'url' => route('site')],
            ['label' => $mainText, 'url' => route('site.detail', ['genre_id' => $genreId])],
            ['label' => $areaText, 'url' => '']
        ];

        // $siteIdAry = M_Site::fetchFilterAreaNameIdAry(['template' => $genreId,'area_name' => $fetchAreas['text'],'is_public' => 1]);
        $siteIdAry = Site_Area::FetchFilterSiteIdAry(['site_id' => 0, 'area_id' => $areaId, 'template' => $genreId]);
        //サイト情報を取得
        $sites = [];
        $siteInfos = [];
        if ($siteIdAry) {
            $sites = M_Site::fetchShopFilterAryId($siteIdAry);
            $siteInfo = Site_Info::fetchFilterSiteAll(['site_id' => $siteIdAry]);
            foreach ($siteInfo as $info) {
                $siteInfos[$info->site_id] = $info;
            }
        }
        $workCasts = [];
        foreach ($sites as $index => $site) {
            // $sites[$index]->image = '/no-image.jpg';
            // $sites[$index]->main_cast_id = 0;
            // $mainCast = M_Cast::fetchFilteringFirstData(['site_id' => $site->id]);
            // if($mainCast) {
            //     $sites[$index]->main_cast_id = $mainCast->id;
            //     $sites[$index]->main_cast_name = $mainCast->source_name;
            //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $mainCast->id]);
            //     if($isCastImage) {
            //         $sites[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            //     }
            // }
            $sites[$index]->course = Site_Course::fetchFilterSiteFirstData($site->id, 2);
            $sites[$index]->thumbnail = '/no-image.jpg';
            if (isset($siteInfos[$site->id])) {
                $sites[$index]->thumbnail = $siteInfos[$site->id]->image ?? '/no-image.jpg';
            }
            //今日出勤のキャストデータ
            $date = date('Y-m-d');
            if (!empty($site->close_time)) {
                $closeTime = $site->close_time;
                if ($site->close_time >= 2400) {
                    $closeTime = $closeTime - 2400;
                    if ($closeTime >= date('Hi')) {
                        $date = date('Y-m-d', strtotime('-1 day'));
                    }
                }
            }
            $filter = [
                'date' => $date,
                'site_id' => $site->id,
                'is_work' => 1,
                // 'sort' => 'ASC',
                'sokuhime_sort' => 'ASC',
                'is_public' => 1,
            ];
            $workCasts[$site->id] = Cast_Schedule::fetchFilteringData($filter);
            $keywords .= "," . $site->name;
        }
        $exclusiveStatus = config('constant.cast.exclusive_status');
        //今日出勤のキャストデータ
        $castIdAry = [];
        $formatWorkCasts = [];
        foreach ($workCasts as $siteId => $workCast) {
            foreach ($workCast as $index => $wc) {
                if (in_array($wc->cast_id, $castIdAry)) {
                    continue;
                }
                array_push($castIdAry, $wc->cast_id);
                $wc->sokuhime_comment = str_replace("\r\n", '<br>', $wc->sokuhime_comment);
                $wc->exclusive_status_text = '';
                $wc->exclusive_status_image = '';
                foreach ($exclusiveStatus as $exclusiveStatu) {
                    if ($exclusiveStatu['value'] == $wc->exclusive_status) {
                        $wc->exclusive_status_text = $exclusiveStatu['name'];
                        $wc->exclusive_status_image = $exclusiveStatu['image'];
                    }
                }
                $formatWorkCasts[$wc->site_id][$wc->cast_id] = $wc;
                $formatWorkCasts[$wc->site_id][$wc->cast_id]->image = '/no-image.jpg';
                $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $wc->cast_id]);
                if ($castImage) {
                    $formatWorkCasts[$wc->site_id][$wc->cast_id]->image = $castImage->directory . "SM_" . $castImage->path;
                }
            }
        }
        $shopBlogs = [];
        $managerBlogs = [];
        $castBlogs = [];
        if ($siteIdAry) {
            //ショップニュース
            $shopBlogs = D_Shop_Blog::fetchSiteIdLimitData($siteIdAry, 3);
            //店長ブログ
            $managerBlogs = D_Shop_Manager_Blog::fetchSiteIdLimitData($siteIdAry, 3);
            //キャストブログ
            $castBlogs = D_Cast_Blog::fetchSiteIdLimitData($siteIdAry, 6);
        }

        foreach ($castBlogs as $index => $castBlog) {
            $castBlogs[$index]->image = '/no-image.jpg';
            $isImage = D_Cast_Blog_Image::fetchFilterIdFirstData($castBlog->id);
            if ($isImage) {
                $castBlogs[$index]->image = $isImage->image_url;
                continue;
            }
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castBlog->cast_id]);
            if ($isCastImage) {
                $castBlogs[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }
        $filterDate = 0;
        //アクセスランキング(打ち合わせにより、非表示)
        $castAccessRanks = [];
        // if ($siteIdAry) {
        //     $castAccessRanks = M_Cast::fetchAccessCountCast(['site_id' => $siteIdAry, 'limit' => 3, 'date' => $filterDate]);
        // }
        // foreach ($castAccessRanks as $index => $castAccessRank) {
        //     $castAccessRanks[$index]->image = '/no-image.jpg';
        //     $castAccessRanks[$index]->age =  $castAccessRank->age ?? '-';
        //     $castAccessRanks[$index]->bust =  $castAccessRank->bust ??  '-';
        //     $castAccessRanks[$index]->cup =  $castAccessRank->cup ?? '-';
        //     $castAccessRanks[$index]->waist =  $castAccessRank->waist ?? '-';
        //     $castAccessRanks[$index]->hip =  $castAccessRank->hip ?? '-';
        //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castAccessRank->id]);
        //     if ($isCastImage) {
        //         $castAccessRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
        //     }
        // }
        //お気に入りランキング(打ち合わせにより、非表示)
        $castFavoriteRanks = [];
        // if ($siteIdAry) {
        //     $castFavoriteRanks = M_Cast::fetchLikeCountCast(['site_id' => $siteIdAry, 'limit' => 3, 'date' => $filterDate]);
        // }
        // foreach ($castFavoriteRanks as $index => $castFavoriteRank) {
        //     $castFavoriteRanks[$index]->image = '/no-image.jpg';
        //     $castFavoriteRanks[$index]->age =  $castFavoriteRank->age ?? '-';
        //     $castFavoriteRanks[$index]->bust =  $castFavoriteRank->bust ??  '-';
        //     $castFavoriteRanks[$index]->cup =  $castFavoriteRank->cup ?? '-';
        //     $castFavoriteRanks[$index]->waist =  $castFavoriteRank->waist ?? '-';
        //     $castFavoriteRanks[$index]->hip =  $castFavoriteRank->hip ?? '-';
        //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castFavoriteRank->id]);
        //     if ($isCastImage) {
        //         $castFavoriteRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
        //     }
        // }
        //写メ日記ランキング(打ち合わせにより、非表示)
        $castDiaryRanks = [];
        // if ($siteIdAry) {
        //     $castDiaryRanks = M_Cast::fetchBlogCountCast(['site_id' => $siteIdAry, 'limit' => 3, 'date' => $filterDate, 'category_id' => 3]);
        // }
        // foreach ($castDiaryRanks as $index => $castDiaryRank) {
        //     $castDiaryRanks[$index]->image = '/no-image.jpg';
        //     $castDiaryRanks[$index]->age =  $castDiaryRank->age ?? '-';
        //     $castDiaryRanks[$index]->bust =  $castDiaryRank->bust ??  '-';
        //     $castDiaryRanks[$index]->cup =  $castDiaryRank->cup ?? '-';
        //     $castDiaryRanks[$index]->waist =  $castDiaryRank->waist ?? '-';
        //     $castDiaryRanks[$index]->hip =  $castDiaryRank->hip ?? '-';
        //     $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castDiaryRank->id]);
        //     if ($isCastImage) {
        //         $castDiaryRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
        //     }
        // }

        $movieData = [];
        if ($siteIdAry) {
            $movieData = D_Movie::fetchFilterData(['site_id' => $siteIdAry, 'cast_id' => 0, 'limit' => 6]);
        }

        $formatSiteGenre = [];
        if ($siteIdAry) {
            $siteGenre = Site_Genre::fetchFilteringData(['site_id' => $siteIdAry]);
            foreach ($siteGenre as $sg) {
                $formatSiteGenre[$sg->site_id][] = $sg;
            }
        }
        $pgttl_ary = [
            1 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 松山市
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 今治市
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 東予市
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 西条市
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 新居浜市
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 四国中央市
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 伊予市
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 大洲市
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 宇和島市
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 善通寺・琴平
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 高松城東町
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 丸亀市
                        13 => ['ttl1' => '松山市道後歌舞伎通りソープ・ヘルスの人気・おすすめ店をご紹介｜コスモ天国ネット', 'ttl2' => ''], // 松山市道後歌舞伎通り
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'], // 愛媛県松山市内
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'] // 高松市城東町
                     ],
            2 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        13 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット']
                     ],
            3 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        13 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット']
                     ],
            4 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        13 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット']
                     ],
            5 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        13 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット'],
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => 'をご紹介｜コスモ天国ネット']
                     ],
            6 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        13 => ['ttl1' => '松山市道後歌舞伎通りで人気・おすすめの宴会コンパニオン店をご紹介｜コスモ天国ネット', 'ttl2' => ''],
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット']
            ],
            7 => [ 
                        1 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        2 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        3 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        4 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        5 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        6 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        7 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        8 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        9 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        10 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        11 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        12 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        13 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        14 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット'],
                        15 => ['ttl1' => 'で人気・おすすめの', 'ttl2' => '店をご紹介｜コスモ天国ネット']
            ]
        ];
        $pgttl = $areaText.$pgttl_ary[$request ->genre_id][$request ->area_id]['ttl1'].$mainText.$pgttl_ary[$request ->genre_id][$request ->area_id]['ttl2'];
        if($areaId == 13 ){
            $pgttl = $pgttl_ary[$request ->genre_id][$request ->area_id]['ttl1'];
        } if(!isset($genreId)){
            $pgttl = "愛媛県香川県で人気・おすすめのナイトアミューズメントをご紹介｜コスモ天国ネット";
        }
        $pgdesc_ary = [
            1 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 松山市
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 今治市
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 東予市
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 西条市
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 新居浜市
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 四国中央市
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 伊予市
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 大洲市
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 宇和島市
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 善通寺・琴平
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 高松城東町
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 丸亀市
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 松山市道後歌舞伎通り
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'], // 愛媛県松山市内
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'] // 高松市城東町
                     ],
            2 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。']
                     ],
            3 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。']
                     ],
            4 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。']
                     ],
            5 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '一覧をご紹介いたします。'],
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。']
                     ],
            6 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。']
                    ],
                     7 => [ 
                        1 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        2 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        3 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        4 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        5 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        6 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        7 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        8 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        9 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        10 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        11 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        12 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        13 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        14 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。'],
                        15 => ['ttl1' => '情報サイト「コスモ天国ネット」が', 'ttl2' => 'の', 'ttl3' => '店一覧をご紹介いたします。']
                    ]
        ];
        $mgenre_ary = [];
        $mgenre_ary = data_get($siteGenre, '*.name');
        $mgenre_ary = array_unique($mgenre_ary);
        $mg_txt = "";
        $p = '1';
        if(is_array($mgenre_ary)){
           foreach($mgenre_ary as $k => $v){
              if($p > '1'){
                 $mg_txt .= "・".$v;
              } else{
                  $mg_txt .= $v;
              }
              $p++;
           }
        } else {
            $mg_txt = $mgenre_ary;
        }
        $pgdesc = $mainText.$pgdesc_ary[$request ->genre_id][$request ->area_id]['ttl1'].$areaText.$pgdesc_ary[$request ->genre_id][$request ->area_id]['ttl2'].$mg_txt.$pgdesc_ary[$request ->genre_id][$request ->area_id]['ttl3'];
        if(!isset($genreId)){
            $pgdesc = "ナイト情報サイト「コスモ天国ネット」が愛媛県香川県のナイトレジャー一覧をご紹介いたします。";
        }
        //2025_05_05 krn テスト-------------------------------------------------------------------------
        $testAra_ttl = [
            1 => [ 
                        1 => ['ttl1' => ''], // 松山市
                        2 => ['ttl1' => ''], // 今治市
                        3 => ['ttl1' => ''], // 東予市
                        4 => ['ttl1' => ''], // 西条市
                        5 => ['ttl1' => ''], // 新居浜市
                        6 => ['ttl1' => ''], // 四国中央市
                        7 => ['ttl1' => ''], // 伊予市
                        8 => ['ttl1' => ''], // 大洲市
                        9 => ['ttl1' => ''], // 宇和島市
                        10 => ['ttl1' => '善通寺・琴平で心身ともにリラックス。香川の本格ソープ体験'], // 善通寺・琴平
                        11 => ['ttl1' => '高松城東町で心と体を癒す本格ソープ体験'], // 高松城東町
                        12 => ['ttl1' => ''], // 丸亀市
                        13 => ['ttl1' => '愛媛県・道後で心と体を癒す本格ソープ・ヘルス体験'], // 松山市道後歌舞伎通り
                        14 => ['ttl1' => ''], // 愛媛県松山市内
                        15 => ['ttl1' => ''] // 高松市城東町
                     ],
            2 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => '新居浜で見つかる、安心・健全なメンズエステ。心と体を癒す人気店舗を紹介！'],
                        6 => ['ttl1' => '四国中央市でリラックス！川之江の健全なメンズエステで心身ともに癒される'],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '愛媛・松山で健全に癒されるメンズエステ。安心・本格施術の店舗を紹介！'],
                        15 => ['ttl1' => '']
                     ],
            3 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => '四国中央市で楽しむ大人の社交空間｜上質な時間を提供するキャバクラ体験'],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => ''],
                        15 => ['ttl1' => '']
                     ],
            4 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '松山市で楽しむ大人の社交空間｜会話と癒しが広がるセクキャバ体験'],
                        15 => ['ttl1' => '']
                     ],
            5 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => 'コスモ天国ネットが紹介する飲食店'],
                        15 => ['ttl1' => '']
                     ],
            6 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => '道後での飲み会に。華やかに盛り上げる宴会コンパニオンを紹介！'],
                        14 => ['ttl1' => '松山市での飲み会に。艶やかに盛り上げる宴会コンパニオンをご紹介！'],
                        15 => ['ttl1' => '']
            ],
            7 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '松山市のマッサージ店で心と体をリフレッシュ！'],
                        15 => ['ttl1' => '']
            ]
        ];
        $testAra = $testAra_ttl[$request ->genre_id][$request ->area_id]['ttl1'];
        if(!isset($genreId)){
            $testAra = "愛媛県香川県で人気・おすすめのナイトアミューズメントをご紹介｜コスモ天国ネット";
        }

        $krnAra_ttl = [
            1 => [ 
                        1 => ['ttl1' => ''], // 松山市
                        2 => ['ttl1' => ''], // 今治市
                        3 => ['ttl1' => ''], // 東予市
                        4 => ['ttl1' => ''], // 西条市
                        5 => ['ttl1' => ''], // 新居浜市
                        6 => ['ttl1' => ''], // 四国中央市
                        7 => ['ttl1' => ''], // 伊予市
                        8 => ['ttl1' => ''], // 大洲市
                        9 => ['ttl1' => ''], // 宇和島市
                        10 => ['ttl1' => '<p>香川県の善通寺市や琴平町には、観光名所の金刀比羅宮（こんぴらさん）や善通寺が点在し、観光客にとっては絶好の訪問先です。観光の後には、善通寺・琴平エリアのソープランドで、心と体を癒す特別な時間を過ごすのがオススメです。</p><p>善通寺・琴平エリアのソープでは、リズム感のある泡やローションを使った施術で、体の疲れをしっかりと解消し、心地よい快感とリラクゼーションを提供します。観光の疲れを癒しながら、極上のリラックス体験を楽しむことができます。</p><p>この地域は観光名所にも近く、観光後に訪れるのに最適な場所です。善通寺　風俗や琴平　風俗を検索すれば、地域ならではの質の高いサービスが提供されている施設を見つけることができます。</p><p>金刀比羅宮（こんぴらさん）や善通寺で心を癒した後、さらにリラックスできる至福の時間が待っています。</p>'], // 善通寺・琴平
                        11 => ['ttl1' => '<p>香川県高松市に位置する城東町は、地域に根差した静かなエリアでありながら、大人のための特別なリラクゼーションスポットが集まっています。観光名所としても有名な高松ですが、観光の合間に、心と体を癒す本格的なソープ体験が待っています。</p><p>城東町エリアのソープランドでは、泡やローションを使用したリズム感のある全身マッサージが、疲れた体をじっくりとほぐします。マット施術と組み合わせることで、心地よい快感が広がり、全身の緊張を解き放つことができます。まさに、日常の喧騒を忘れ、極上のリラックスを手に入れる瞬間です。</p><p>さらに、ファッションヘルスでは、視覚的な楽しみを提供するナースや制服、人妻のコスチュームが充実しており、オイルマッサージやイメージプレイも豊富に取り入れています。完全個室で自分だけの時間を楽しみ、欲望を遠慮なく解放できる空間が広がっています。心身のリフレッシュと共に、非日常的な快楽を体験することができます。</p><p>「高松　風俗」や「城東町　風俗」を検索したなら、城東町のこのエリアは外せません。観光の後に、心身ともに癒されるリラクゼーションと快楽を求めるなら、高松城東町での体験が最適です。</p>'], // 高松城東町
                        12 => ['ttl1' => ''], // 丸亀市
                        13 => ['ttl1' => '<p>愛媛県松山市にある道後温泉は、古くから多くの人々に親しまれる名湯として有名です。観光地としても知られ、多くの旅行者が訪れます。その道後温泉の近く、「道後歌舞伎通り」は、観光地とは異なる大人の世界が広がるエリア。ここでは、日常の疲れを癒すリラクゼーションを提供する風俗店が軒を連ねており、ゆったりとした時間を過ごすことができます。</p><p>道後エリアのソープランドでは、泡やローションを使った施術で、全身をしっかりほぐしながら、心地よい快感を体感できます。リズム感のあるマッサージと、マット施術を交えて、体の緊張を解き放つことができ、リラックス効果は抜群です。肌と肌が触れ合う瞬間に感じる解放感は、日々の疲れを忘れさせてくれるでしょう。</p><p>さらに、ファッションヘルスでは、ナースや制服、人妻といった視覚的な刺激に加え、オイルマッサージやイメージプレイが充実。完全個室の中で、自由に自分の願望を解放できる空間が広がっています。まさに、心と体をリフレッシュさせる最適な時間が過ごせる場所です。</p><p>「道後　風俗」や「松山市　風俗」を検索したなら、道後歌舞伎通りは外せません。観光とリラクゼーション、その両方を楽しめる場所がここにあります。観光後に、さらに癒しを求めるなら、道後エリアで極上の時間を体験してみてください。</p>'], // 松山市道後歌舞伎通り
                        14 => ['ttl1' => ''], // 愛媛県松山市内
                        15 => ['ttl1' => ''] // 高松市城東町
                     ],
            2 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => '<p>「新居浜 メンズエステ」「愛媛県 メンズエステ」「メンズエステ 新居浜市」でお探しの方へ。</p><p>愛媛県新居浜市には、男性専用の本格リラクゼーション施設が点在しています。完全個室で受けられるアロマオイル・リンパ・洗体エステなど、豊富な施術内容が揃っており、地元のビジネスマンや観光客からも高い支持を集めています。</p><p>在籍セラピストは、接遇と施術技術を備えた女性スタッフのみ。「肩こりを和らげたい」「全身をすっきり整えたい」など、目的に合わせた施術コースが選べるのも魅力です。</p><p>コスモ天国ネットで紹介する新居浜市のメンズエステは風俗店ではありません。性的サービスの提供は一切なく、純粋にリラクゼーションに特化した健全なサービスを提供しています。</p><p>毎年10月に開催される新居浜太鼓祭りで知られるこの街は、活気にあふれた地域性が魅力。そんなエネルギッシュな土地だからこそ、日々をがんばる男性に向けた癒しの空間が求められているのです。今ここでは、安心・健全で信頼できる新居浜市の人気メンズエステ店舗を紹介！</p>'],
                        6 => ['ttl1' => '<p>「四国中央市 メンズエステ」「川之江 メンズエステ」「三島 メンズエステ」をお探しの方へ。</p><p>四国中央市、川之江エリアは、川之江城の桜が有名で、春には美しい桜が咲き誇ります。訪れる人々を魅了するこの景色と共に、川之江エリアのメンズエステでは、心身のリフレッシュを目的とした施術を提供しています。ここでは、リラクゼーションとリフレッシュを重視した健全なサービスが受けられ、日々の疲れを解消できます。</p><p>コスモ天国ネットで紹介する四国中央市・川之江エリアのメンズエステは、風俗的なサービスや性的な行為は一切行いません。安心してリラックスできる空間を提供しており、完全個室でのプライベートな時間をお楽しみいただけます。セラピストによる丁寧でプロフェッショナルな施術は、あなたの身体と心をしっかりと癒します。</p><p>観光やグルメを楽しんだ後は、川之江城の桜を眺めながらリラックスできるメンズエステで、心と体をリフレッシュする時間を過ごしませんか？川之江のメンズエステで、健全でリフレッシュできる至福のひとときをご堪能ください。</p><p>四国中央市の川之江エリアで、リラクゼーションと癒しを提供するメンズエステ店舗を紹介！ リラックスを目的とした施術がしっかりと整い、心身ともにリフレッシュできる時間が待っています。</p>'],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '<p>「愛媛県 メンズエステ」「松山市 メンズエステ」「メンズエステ 松山」で検索中の方へ。</p><p>松山市には、男性専用のリラクゼーションサービスとして人気のメンズエステがあります。完全個室の落ち着いた空間で、オイルトリートメントやアロママッサージを通して、心と体の疲れを深く癒す本格施術が受けられます。</p><p>在籍セラピストは、接遇マナーや施術技術をしっかりと身につけた女性スタッフのみ。肩こりや全身の疲労に的確に対応し、ビジネスマンを中心に多くのリピーターから支持されています。</p><p>コスモ天国ネットで紹介する松山のメンズエステは風俗店ではありません。性的サービスやそれに類する行為は一切行わず、リラクゼーションに特化した健全なサービスのみを提供しています。</p><p>「安心して癒されたい」「真面目に疲れを取りたい」そんな方にこそおすすめできる、愛媛県・松山市の信頼できるメンズエステ。ここでは、安心・清潔・本格施術がそろった店舗を紹介！</p>'],
                        15 => ['ttl1' => '']
                     ],
            3 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => '<p>愛媛県四国中央市には、上質な時間と心地よい会話を楽しめる大人のための社交空間があります。お酒を片手に、女性キャストとの自然なやりとりを通じて、リラックスしながら過ごす特別なひとときが広がります。</p><p>ここで提供されるのは、丁寧な接客と心地よい雰囲気。視覚や会話を楽しみながら、喧騒を忘れて穏やかな時間を味わえるエンターテインメント性に富んだサービスです。</p><p>もちろん、風俗的なサービスや性的な行為は一切なく、どなたでも安心してご利用いただける健全な空間です。</p><p>夜が深まるほどに心が満たされる、大人のくつろぎ。あなた自身をゆったりと解き放つような、とっておきの時間をお楽しみください。</p>'],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => ''],
                        15 => ['ttl1' => '']
                     ],
            4 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '<p>愛媛県松山市には、気軽に立ち寄れて会話とお酒を楽しめる大人のためのエンタメ空間があります。キャストの魅力的な衣装と気さくな接客で、非日常の癒しを感じられる場所、それがこのジャンルの最大の魅力です。</p><p>「松山市 セクキャバ」「愛媛 セクキャバ」といったキーワードで探している方も多く、コミュニケーションを中心に楽しめる健全なエンターテインメント施設として注目されています。身体的な接触や性的サービスは一切なく、安心して利用できるリラックス空間です。</p><p>完全個室で、肩ひじ張らずにおしゃべりとお酒を楽しめるこの空間は、日々の疲れをそっと癒してくれます。視覚的な刺激や軽やかなトークが、仕事終わりや休日にちょうどいい気分転換となるでしょう。</p><p>完全個室で、おしゃべりとお酒を楽しめるこの空間は、日々の疲れをそっと癒してくれます。視覚的な刺激や軽やかなトークが、仕事終わりや休日にちょうどいい気分転換となるでしょう。松山市で健全に楽しめるセクキャバ系のお店をご紹介！</p>'],
                        15 => ['ttl1' => '']
                     ],
            5 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '<p>コスモ天国ネットが紹介する飲食店は、地元の旬食材を活かし、味・香り・見た目すべてにこだわった料理を提供する、大人のための美食空間です。</p><p>松山で楽しめるしゃぶしゃぶ店では、愛媛のブランド豚「甘とろ豚」を使用。豊かな旨味と柔らかな肉質を堪能できるしゃぶしゃぶを、特製の和風タレと胡麻タレでお楽しみいただけます。タレの深い味わいが、肉の旨味を引き立て、一口ごとに贅沢な味わいを堪能できます。</p><p>落ち着いたインテリアと、リラックスした空間で、ゆったりと過ごすひととき。大切な人と語らいながら、またはひとりで静かにくつろぎながら、食の時間を心ゆくまで楽しんでいただけます。</p><p>また、モリンガそばも置いてあり、ヘルシーな一品としてお楽しみいただけます。しゃぶしゃぶの後にさっぱりとした一皿をどうぞ。</p><p>※紹介する店舗はいずれも非風俗営業であり、安心してお楽しみいただけるお店のみ掲載しています。</p>'],
                        15 => ['ttl1' => '']
                     ],
            6 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => '<p>道後での飲み会や宴席を、ただの集まりでは終わらせたくないあなたに。コスモ天国ネットが紹介する道後エリアの宴会コンパニオンは、品のある接客と色気で、宴席に華やかさをプラスします。</p><p>新年会や歓送迎会、親睦会はもちろん、少人数での飲み会にも最適。女性コンパニオンは、心地よい会話を通じてリラックスした雰囲気を作り、場を一層温かく盛り上げます。</p><p>道後の風情あふれる空間にぴったりな、心安らぐ時間を提供。完全に健全なサービスで、風俗的な行為は一切ありませんので、どなたでも安心してお楽しみいただけます。</p><p>道後温泉の近くで、心ゆくまでお酒を楽しみながら、大人の夜を演出する宴会コンパニオンをご利用ください。大切な飲み会や特別な時間に、上品なエンターテイメントをご提供します。</p>'],
                        14 => ['ttl1' => '<p>松山市での飲み会や宴席を、ただの集まりで終わらせたくない方へ。コスモ天国ネットが紹介する宴会コンパニオンは、華やかで気品ある接客で、宴の空間にさりげない艶を添えます。</p><p>忘年会・新年会・歓送迎会・親睦会はもちろん、少人数の飲み会にも対応可能。女性コンパニオンが自然な会話や軽いゲームで場を和ませ、リラックスした雰囲気の中、心地よく過ごせる時間を演出します。</p><p>サービスは完全に健全で、風俗的なサービスや性的行為は一切なし。公的・社用の宴席でも安心してご利用いただける内容です。</p><p>松山市内の飲食店や旅館など、多様なシーンに対応。ただの飲み会が、少し背伸びした大人の夜に変わる――そんな時間をぜひご体験ください。</p>'],
                        15 => ['ttl1' => '']
            ],
            7 => [ 
                        1 => ['ttl1' => ''],
                        2 => ['ttl1' => ''],
                        3 => ['ttl1' => ''],
                        4 => ['ttl1' => ''],
                        5 => ['ttl1' => ''],
                        6 => ['ttl1' => ''],
                        7 => ['ttl1' => ''],
                        8 => ['ttl1' => ''],
                        9 => ['ttl1' => ''],
                        10 => ['ttl1' => ''],
                        11 => ['ttl1' => ''],
                        12 => ['ttl1' => ''],
                        13 => ['ttl1' => ''],
                        14 => ['ttl1' => '<p>松山市のマッサージ店は、日々の疲れやストレスを解消し、身体と心のリフレッシュを提供する場所です。特に、肩や腰、脚などの筋肉の緊張をほぐすための本格的な施術が特徴で、リラックスしたい方々にぴったりです。</p><p>施術はすべて、身体を根本から癒やすことを目的としており、リラクゼーション効果が高いと評判。経験豊富な整体師や柔道整復師が、個々の状態に合わせたオーダーメイドのケアを行い、全身のコリや疲れをしっかりとほぐします。</p><p>清潔で落ち着いた空間での施術は、心地よい空間でリラックスできる時間を提供します。忙しい日常を忘れ、ゆったりとしたひとときを過ごせる、まさに心身のバランスを整える特別な時間がここにあります。</p><p>また、マッサージにより、血行が促進され、肩こりや腰痛、むくみの解消に効果が期待できます。自分へのご褒美として、または仕事帰りに一息つきたい時にも最適です。</p><p>疲れた身体を癒し、心からリフレッシュできるマッサージ店をご紹介。</p>'],
                        15 => ['ttl1' => '']
            ]
        ];
        $krnAra = $krnAra_ttl[$request ->genre_id][$request ->area_id]['ttl1'];
        if(!isset($genreId)){
            $testAra = "最高の店をご紹介＿＿テスト";
        }




        $viewContent = view('sitePages.detail.area', compact('genreId', 'tabs', 'mainText', 'mainColor', 'areaId', 'areaColor', 'areaText', 'sites', 'formatWorkCasts', 'shopBlogs', 'managerBlogs', 'castBlogs', 'castAccessRanks', 'castFavoriteRanks', 'castDiaryRanks', 'movieData', 'breadCrumbs', 'formatSiteGenre', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'mg_txt', 'mgenre_ary', 'kio_gen', 'gnr_name','testAra_ttl','testAra','krnAra_ttl','krnAra'))->render();

        Cache::put($cacheKey, $viewContent, now()->addMinutes(8));
        return response($viewContent);
        // area_return
        // return view('sitePages.detail.area', compact('genreId', 'tabs', 'mainText', 'mainColor', 'areaId', 'areaColor', 'areaText', 'sites', 'formatWorkCasts', 'shopBlogs', 'managerBlogs', 'castBlogs', 'castAccessRanks', 'castFavoriteRanks', 'castDiaryRanks', 'movieData', 'breadCrumbs', 'formatSiteGenre', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'mg_txt', 'mgenre_ary'));
    }
    public function getTabs($siteId, $activeId, $areaId = 0, $genreId = 0)
    {
        $fetchTabs = D_Site_Tab::fetchFilteringData(['site_id' => $siteId, 'is_display' => 1]);
        $tabs = [];
        foreach ($fetchTabs as $index => $tab) {
            $tabs[$index]['id'] = $tab->id;
            $tabs[$index]['master_id'] = $tab->master_id;
            $tabs[$index]['name'] = $tab->name;
            //URLの統一
            $tabs[$index]['url'] = route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId, 'tab_id' => $tab->master_id,]);
            $tabs[$index]['active'] = $activeId == $tab->master_id ? true : false;
        }
        return $tabs;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function top(Request $request)
    {
        ini_set('memory_limit', -1);
        $tabId = $request->tab_id;
        $genreId = $request->genre_id ?? 0;
        $areaId = $request->area_id ?? 0;
        $siteId = $request->site_id ?? 0;

        if(!$request->tab_id || $request->tab_id == 1) {
            $cacheKey = "site.detail.{$genreId}.{$areaId}.{$siteId}";
            $cachedHtml = Cache::get($cacheKey);
            if ($cachedHtml) {
                return CacheHtmlHelper::renderCachedHtml($cachedHtml, $request);
            }
        }

        $previousUrl = app('url')->previous();
        //  ID表示可チェック
        $siteIds = M_Site::where('is_public', 1) ->pluck('id')->toArray();
        $siteIddiff = array_diff($siteIds, array(''));
        // if (empty($siteId) || !preg_match('/^[0-9]+$/', $siteId)) {
        if (empty($siteId) || !in_array($siteId, $siteIddiff)) {
            return redirect()->route('site');
        }
        if(!in_array($request ->site_id, $siteIddiff)){
            return redirect()->route('site');
        }


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

        if ($request->has('genre_id')) {
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
        
        //サイト情報を取得
        $sites = M_Site::findOrFail($siteId);
		if(empty($genreId)) {
            $genreId = $sites->template;
        }
        $shops = M_Shop::findOrFail($sites->shop_id);
        $siteInfo = Site_Info::fetchSiteData($siteId);
        $template = $this->getGenre($genreId);
        $mainColor = "#F1747E"; //初期は風俗カラー
        if(isset($template['color'])) {
            $mainColor = $template['color'];
        }
        if($siteInfo) {
            $mainColor = $siteInfo->color;
        }
        $mainImage = Site_Image::fetchSiteCategoryData($siteId, 1);
        // $mainImage = $siteInfo ? $siteInfo->image : false;
        $siteTabData = D_Site_Tab::fetchFilteringData(['site_id' => $siteId, 'is_display' => 1]);
        $isMap = false;
        $tabName = '';
        $tabs = $this->getTabs($siteId, $tabId, $areaId, $genreId);
        if (empty($tabId)) {
            if (isset($tabs[0]['master_id'])) {
                $tabId = $tabs[0]['master_id'];
                $tabs[0]['active'] = true;
                $tabName = $tabs[0]['name'];
                $tabUrl = route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId, 'tab_id' => $tabId]);
            }
        } else {
            foreach ($tabs as $tab) {
                if ($tab['master_id'] == $tabId) {
                    $tabName = $tab['name'];
                    $tabUrl = $tab['url'];
                    break;
                }
            }
        }
        $siteTabDetail = D_Site_Detail_Tab::fetchFilteringData(['master_id' => $tabId, 'site_id' => $siteId, 'is_display' => 1]);
        // $formatTabDetail = [];
        // if($siteTabDetail->isNotEmpty()) {
        //     foreach($siteTabDetail as $detail) {
        //         $formatTabDetail[$detail->event] = $detail;
        //     }
        // }
        $contents = [];
        foreach ($siteTabDetail as $tabDetail) {
            //Color書き換えるならここで
            if ($tabDetail->event == 'shop_access') {
                $isMap = true;
            }
            // リダイレクト
            if ($tabDetail->event == 'shop_news') {
                $shopLastData = D_Shop_Blog::fetchSiteIdFristData([$siteId]);
                if($shopLastData) {
                    return redirect()->route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId, 'tab_id' => $tabId,'category_id' => 1,'id' => $shopLastData->id]);
                }
            }
            //eventによって他の関数を見に行って、htmlを生成する。
            $contents[$tabDetail->id] = $this->{$tabDetail->event}($tabDetail, $sites, $shops, $mainColor, $request);
        }
        $breadCrumbs = [
            ['label' => 'トップ', 'url' => route('site')],
        ];
        if (!empty($genreId)) {
            $genre = $this->getGenre($genreId);
            array_push($breadCrumbs, ['label' => $genre['text'] ?? "", 'url' => route('site.detail', ['genre_id' => $genreId])]);
            if (!empty($areaId)) {
                // $areas = $this->getArea($genreId,$areaId);
                $areas = M_Area::findOrFail($areaId);
                array_push($breadCrumbs, ['label' => $areas->name, 'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $areas->id])]);
            } else {
                $areas = Site_Area::fetchFilterFirstData(['site_id' => $siteId]);
                array_push($breadCrumbs, ['label' => $areas->name, 'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $areas->area_id])]);
            }
        }
        array_push($breadCrumbs, ['label' => $sites->name, 'url' => route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId])]);
        if ($tabName) {
            array_push($breadCrumbs, ['label' => $tabName, 'url' => $tabUrl]);
        }
        $keywords = '四国,天国ネット,' . $sites->name . ',' . $tabName;
        $siteAreas = Site_Area::fetchFilterData(['site_id' => $siteId]);
        foreach ($siteAreas as $siteArea) {
            $keywords .= ',' . $siteArea->name;
        }
        $siteGenres = Site_Genre::FetchFilteringData(['site_id' => $siteId]);
        foreach ($siteGenres as $siteGenre) {
            $keywords .= ',' . $siteGenre->name;
        }

        /*
        $shopIds = M_Shop::whereHas('sites', function ($query) {
            $query->where('is_public', 1);
        })->pluck('id')->toArray();
        */
    $pgttl_ary = [
        2 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // トレビの泉
        10 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // バニラリップ
        24 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 赤と黒
        26 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // エリザベス
        28 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 膝麻久庵
        31 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // レイチェル
        33 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 道後宴会屋
        55 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // ジャックと豆の木
        98 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // club菜の花
        101 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 高松しらゆき姫
        104 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 金瓶梅
        110 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 愛ドル学園
        111 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 高松トレビの泉
        114 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // しゃぶしゃぶ田中
        115 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // レイチェル新居浜店
        118 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 道後しらゆきひめ
        122 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // U-S-A
        123 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // アメイジングビル
        128 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 俺のシンデレラ
        130 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 優しいひとづま
        133 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // はちみつ
        136 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 回春エステ魂
        137 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // 英乃國屋
        147 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // SPAアロマショコラ
        153 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'], // ロイヤル
        154 => ['ttl1' => 'ページ：', 'ttl2' => ' - ', 'ttl3' => '/', 'ttl4' => ' | コスモ天国ネット'] // ハイブリッドLIPS
    ];
    if(array_key_exists($request ->site_id, $pgttl_ary)){
        $pgttl = $tabName.$pgttl_ary[$request ->site_id]['ttl1'].$sites->name.$pgttl_ary[$request ->site_id]['ttl2'].$areas->name.$pgttl_ary[$request ->site_id]['ttl3'].$siteGenre->name.$pgttl_ary[$request ->site_id]['ttl4'];
    } else{
        $pgttl = $tabName."ページ：".$sites->name." - ".$areas->name."/".$siteGenre->name." | コスモ天国ネット";
    }
    $pgdesc_ary = [
        2 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // トレビの泉
        10 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // バニラリップ
        24 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 赤と黒
        26 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // エリザベス
        28 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 膝麻久庵
        31 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // レイチェル
        33 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 道後宴会屋
        55 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // ジャックと豆の木
        98 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // club菜の花
        101 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 高松しらゆき姫
        104 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 金瓶梅
        110 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 愛ドル学園
        111 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 高松トレビの泉
        114 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // しゃぶしゃぶ田中
        115 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // レイチェル新居浜店
        118 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 道後しらゆきひめ
        122 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // U-S-A
        123 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // アメイジングビル
        128 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 俺のシンデレラ
        130 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 優しいひとづま
        133 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // はちみつ
        136 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 回春エステ魂
        137 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // 英乃國屋
        147 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // SPAアロマショコラ
        153 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'], // ロイヤル
        154 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」の', 'ttl4' => 'ページをご案内。'] // ハイブリッドLIPS
    ];
    if(array_key_exists($request ->site_id, $pgdesc_ary)){
        $pgdesc = $areas->name.$pgdesc_ary[$request ->site_id]['ttl1'].$siteGenre->name.$pgdesc_ary[$request ->site_id]['ttl2'].$sites->name.$pgdesc_ary[$request ->site_id]['ttl3'].$tabName.$pgdesc_ary[$request ->site_id]['ttl4'];
    } else{
        $pgdesc = $areas->name."の".$siteGenre->name."店「".$sites->name."」の".$tabName."ページをご案内";
    }
        // return view('sitePages.detail.detail.index', compact('mainColor', 'mainImage', 'tabs', 'tabName', 'siteId', 'sites', 'shops', 'contents', 'isMap', 'breadCrumbs', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'siteIds', 'siteIddiff', 'kio_gen'));
        $viewContent = view('sitePages.detail.detail.index', compact('mainColor', 'mainImage', 'tabs', 'tabName', 'siteId', 'sites', 'shops', 'contents', 'isMap', 'breadCrumbs', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'siteIds', 'siteIddiff', 'kio_gen'))->render();
        return response($viewContent);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function castDetail(Request $request)
    {
        $castId = $request->cast_id;
        if (empty($castId)) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        $casts = M_Cast::findOrFail($castId);
        if ($casts->deleted_at != 0) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }

        
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

        $casts->figure_text = config('constant.cast.figure_text')[$casts->figure];
        $casts->image = Cast_Image::fetchFilteringData(['cast_id' => $castId]);
        $casts->option_text = "";
        $siteId = $casts->site_id ?? 0;
        $tabId = $request->tab_id ?? 0;
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $eventName = 'cast';
        $castOption = [];
        $optionDatas = M_Cast_Option::fetchFilterData(['site_id' => $siteId]);
        if($optionDatas->isNotEmpty()) {
            if($casts->option) {
                $castOption = explode(',',$casts->option); 
            }
            foreach($optionDatas as $optionData) {
                if(in_array($optionData->id,$castOption)) {
                    if(empty($casts->option_text)) {
                        $casts->option_text = $optionData->name;
                    } else {
                        $casts->option_text .=  ',' . $optionData->name;
                    }
                }
            }
        }

        $tabData = D_Site_Detail_Tab::fetchFilteringSiteEventData(['site_id' => $siteId, 'event' => $eventName]);
        if ($tabData) {
            $tabId = $tabData->master_id;
        }
        $tabs = $this->getTabs($siteId, $tabId, $areaId, $genreId);
        if (empty($tabId)) {
            if (isset($tabs[0]['master_id'])) {
                $tabId = $tabs[0]['master_id'];
                $tabs[0]['active'] = true;
                $tabName = $tabs[0]['name'];
                $tabUrl = route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId, 'tab_id' => $tabId]);
            }
        } else {
            foreach ($tabs as $tab) {
                if ($tab['master_id'] == $tabId) {
                    $tabName = $tab['name'];
                    $tabUrl = $tab['url'];
                    break;
                }
            }
        }
        $sites = M_Site::findOrFail($casts->site_id);
        if ($sites->deleted_at != 0) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        if(empty($genreId)) {
            $genreId = $sites->template;
        }
        $shops = M_Shop::findOrFail($sites->shop_id);
        $siteInfo = Site_Info::fetchSiteData($siteId);
        $template = $this->getGenre($genreId);
        $mainColor = "#F1747E"; //初期は風俗カラー
        if(isset($template['color'])) {
            $mainColor = $template['color'];
        }
        if($siteInfo) {
            $mainColor = $siteInfo->color;
        }
        $mainImage = Site_Image::fetchSiteCategoryData($siteId, 1);
        // $mainImage = $siteInfo ? $siteInfo->image : false;
        //スケジュール
        $currentDate = time();
        //今日出勤のキャストデータ
        if (!empty($sites->close_time)) {
            $closeTime = $sites->close_time;
            if ($sites->close_time >= 2400) {
                $closeTime = $closeTime - 2400;
                if ($closeTime >= date('Hi')) {
                    $currentDate = strtotime('-1 day', $currentDate); // 1日前のタイムスタンプ
                }
            }
        }
        $weeks = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        // 1週間後の日付を計算
        $oneWeekLater = strtotime('+1 week', $currentDate);

        // 1日ずつ増やしながら日付の配列を作成
        $dateArray = array();
        $loop = 0;
        $filterdate = [];
        while ($currentDate < $oneWeekLater) {
            $dateArray[$loop]['Ymd'] = date('Ymd', $currentDate);
            $dateArray[$loop]['date'] = date('n月j日', $currentDate);
            $dateArray[$loop]['week'] = $weeks[date('w', $currentDate)];
            $dateArray[$loop]['active'] = $loop == 0 ? true : false;
            $dateArray[$loop]['schedule'] = Cast_Schedule::fetchFilteringFirstData(['cast_id' => $castId, 'date' => date('Ymd', $currentDate), 'is_work' => 1]);
            $currentDate = strtotime('+1 day', $currentDate);
            $loop++;
        }
        //写メ日記
        $blogs = D_Cast_Blog::fetchFilteringData(['cast_id' => $castId, 'limit' => 6]);
        foreach ($blogs as $index => $blog) {
            $isBlogCastImage = D_Cast_Blog_Image::fetchFilterIdFirstData($blog->id);
            if ($isBlogCastImage) {
                $blogs[$index]->image = $isBlogCastImage->image_url;
                continue;
            }
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $blog->cast_id]);
            if ($isCastImage) {
                $blogs[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
            $blogs[$index]->image = '/no-image.jpg';
        }
        //口コミ
        $reviewData = D_Review::FetchFilterPublicCastData(['cast_id' => $castId, 'site_id' => 0, 'limit' => 3]);
        //アンケート
        $questionDatas = M_Cast_Question::fetchFilteringSiteId($siteId);
        $answerDatas = Cast_Answer::fetchFilteringCastId($castId);
        $formatAnswerData = [];
        foreach ($answerDatas as $answerData) {
            $formatAnswerData[$answerData->question_id]['answer'] = $answerData->answer;
            $formatAnswerData[$answerData->question_id]['is_public'] = $answerData->is_public;
        }
        $formatQuestionData = [];
        // $isAnswer = false;
        foreach ($questionDatas as $index => $questionData) {
            $formatQuestionData[$index]['question_id'] = $questionData->id;
            $formatQuestionData[$index]['answer'] = $questionData->default_answer;
            $formatQuestionData[$index]['question'] = $questionData->question;
            $formatQuestionData[$index]['sort_no'] = $questionData->sort_no;
            $formatQuestionData[$index]['is_public'] = $questionData->is_public;
            if (isset($formatAnswerData[$questionData->id])) {
                if ($formatAnswerData[$questionData->id]['answer']) {
                    // if(!$isAnswer) {
                    //     $isAnswer = true;
                    // }
                }
                $formatQuestionData[$index]['answer'] = $formatAnswerData[$questionData->id]['answer'];
                $formatQuestionData[$index]['is_public'] = $formatAnswerData[$questionData->id]['is_public'];
            }
        }
        //動画
        $movieData = D_Movie::fetchFilterData(['site_id' => [], 'cast_id' => $castId, 'limit' => 6]);

        $breadCrumbs = [
            ['label' => 'トップ', 'url' => route('site')],
        ];
        if (!empty($genreId)) {
            $genre = $this->getGenre($genreId);
            array_push($breadCrumbs, ['label' => $genre['text'], 'url' => route('site.detail', ['genre_id' => $genreId])]);
            if (!empty($areaId)) {
                // $areas = $this->getArea($genreId,$areaId);
                $areas = M_Area::findOrFail($areaId);
                array_push($breadCrumbs, ['label' => $areas->name, 'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $areas->id])]);
                // array_push($breadCrumbs,['label' => $areas['text'],'url' => $areas['url']]);
            } else {
                $areas = Site_Area::fetchFilterFirstData(['site_id' => $siteId]);
                array_push($breadCrumbs, ['label' => $areas->name, 'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $areas->area_id])]);
            }
        }
        array_push($breadCrumbs, ['label' => $sites->name, 'url' => route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId])]);
        if ($tabName) {
            array_push($breadCrumbs, ['label' => $tabName, 'url' => $tabUrl]);
        }
        array_push($breadCrumbs, ['label' => $casts->source_name, 'url' => '']);
        $keywords = '四国,天国ネット,' . $sites->name . ',' . $casts->source_name;
        $siteGenres = Site_Genre::FetchFilteringData(['site_id' => $siteId]);
        foreach ($siteGenres as $siteGenre) {
            $keywords .= ',' . $siteGenre->name;
        }
        $pgttl_ary = [
            2 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // トレビの泉
            10 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // バニラリップ
            24 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 赤と黒
            26 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // エリザベス
            28 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 膝麻久庵
            31 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // レイチェル
            33 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 道後宴会屋
            55 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // ジャックと豆の木
            98 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // club菜の花
            101 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 高松しらゆき姫
            104 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 金瓶梅
            110 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 愛ドル学園
            111 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 高松トレビの泉
            114 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // しゃぶしゃぶ田中
            115 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // レイチェル新居浜店
            118 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 道後しらゆきひめ
            122 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // U-S-A
            123 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // アメイジングビル
            128 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 俺のシンデレラ
            130 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 優しいひとづま
            133 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // はちみつ
            136 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 回春エステ魂
            137 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // 英乃國屋
            147 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // SPAアロマショコラ
            153 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'], // ロイヤル
            154 => ['ttl1' => '「', 'ttl2' => '」：', 'ttl3' => ' - ', 'ttl4' => ' / ', 'ttl5' => ' | コスモ天国ネット'] // ハイブリッドLIPS
        ];
        if(array_key_exists($request ->site_id, $pgttl_ary)){
            $pgttl = $pgttl_ary[$request ->site_id]['ttl1'].$casts->source_name.$pgttl_ary[$request ->site_id]['ttl2'].$sites->name.$pgttl_ary[$request ->site_id]['ttl3'].$areas->name.$pgttl_ary[$request ->site_id]['ttl4'].$siteGenre->name.$pgttl_ary[$request ->site_id]['ttl5'];
        } else{
            $pgttl = "「".$casts->source_name."」：".$sites->name." - ".$areas->name." / ".$siteGenre->name." | コスモ天国ネット";
        }
        $pgdesc_ary = [
            2 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // トレビの泉
            10 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // バニラリップ
            24 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 赤と黒
            26 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // エリザベス
            28 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 膝麻久庵
            31 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // レイチェル
            33 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 道後宴会屋
            55 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // ジャックと豆の木
            98 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // club菜の花
            101 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 高松しらゆき姫
            104 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 金瓶梅
            110 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 愛ドル学園
            111 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 高松トレビの泉
            114 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // しゃぶしゃぶ田中
            115 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // レイチェル新居浜店
            118 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 道後しらゆきひめ
            122 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // U-S-A
            123 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // アメイジングビル
            128 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 俺のシンデレラ
            130 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 優しいひとづま
            133 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // はちみつ
            136 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 回春エステ魂
            137 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // 英乃國屋
            147 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // SPAアロマショコラ
            153 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'], // ロイヤル
            154 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」に在籍する「', 'ttl4' => '」のプロフィールをご案内。スリーサイズや基本情報を確認できます。'] // ハイブリッドLIPS
        ];
        if(array_key_exists($request ->site_id, $pgdesc_ary)){
            $pgdesc = $areas->name.$pgdesc_ary[$request ->site_id]['ttl1'].$siteGenre->name.$pgdesc_ary[$request ->site_id]['ttl2'].$sites->name.$pgdesc_ary[$request ->site_id]['ttl3'].$casts->source_name.$pgdesc_ary[$request ->site_id]['ttl4'];
        } else{
            $pgdesc = $areas->name."の".$siteGenre->name."店「".$sites->name."」に在籍する「".$casts->source_name."」のプロフィールをご案内。スリーサイズや基本情報を確認できます。";
        }
        $reviewCriterials = config('constant.cast.criterials');
        return view('sitePages.detail.detail.castDetail', compact('genreId','areaId','siteId', 'mainImage', 'mainColor', 'dateArray', 'tabs', 'sites', 'shops', 'casts', 'formatAnswerData', 'formatQuestionData', 'blogs', 'reviewData', 'breadCrumbs', 'movieData', 'keywords', 'pgttl_ary', 'pgttl', 'pgdesc_ary', 'pgdesc', 'reviewCriterials', 'sites', 'kio_gen'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function blogDetail(Request $request)
    {
        $html = "";
        $categoryId = $request->category_id; //1 = 店舗ニュース,2 =店長ニュース　,3=キャストニュース
        $blogId = $request->id;
        $tabId = $request->tab_id;
        $genreId = $request->genre_id;
        $areaId = $request->area_id;
        if (empty($blogId)) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        $siteId = 0;
        $castId = null;
        $blogImage = false;
        $blogCateogryName = null;
        $castName = null;
        $eventName = null;
        $newBlogDatas = null;
        $cast = ''; //キャスト変数初期値
        if ($categoryId == 1) {
            // $blogCateogryName = 'ショップニュース';
            $blog = D_Shop_Blog::findOrFail($blogId);
            if ($blog->delete_flg != 0) {
                $previousUrl = app('url')->previous();
                return redirect()->to($previousUrl);
            }
            $blogCateogryName = $blog->title;
            $siteId = $blog->site_id;
            $categoryBranchId = $siteId;
            $eventName = 'shop_news';
            $newBlogDatas = D_Shop_Blog::fetchSiteIdLimitData([$siteId], 5);
        }
        if ($categoryId == 2) {
            // $blogCateogryName = '店長ブログ';
            $blog = D_Shop_Manager_Blog::findOrFail($blogId);
            if ($blog->delete_flg != 0) {
                $previousUrl = app('url')->previous();
                return redirect()->to($previousUrl);
            }
            $blogCateogryName = $blog->title;
            $siteId = $blog->site_id;
            $categoryBranchId = $siteId;
            $eventName = 'manager_news';
            $newBlogDatas = D_Shop_Manager_Blog::fetchSiteIdLimitData([$siteId], 5);
        }
        if ($categoryId == 3) {
            // $blogCateogryName = 'キャストブログ(詳細)';
            $blog = D_Cast_Blog::findOrFail($blogId);
            if (!empty($blog->deleted_by)) {
                $previousUrl = app('url')->previous();
                return redirect()->to($previousUrl);
            }
            $blogCateogryName = $blog->title;
            $cast = M_Cast::findOrFail($blog->cast_id);
            if ($cast->deleted_at != 0) {
                $previousUrl = app('url')->previous();
                return redirect()->to($previousUrl);
            }
            $siteId = $cast->site_id;
            $castId = $blog->cast_id;
            $castName = $cast->source_name;
            $blogImage = D_Cast_Blog_Image::fetchFilterIdFirstData($blogId);
            if ($blogImage) {
                $blogImage = $blogImage->image_url;
            } else {
                //キャスト画像入れる
                $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $blog->cast_id]);
                if ($isCastImage) {
                    $blogImage = $isCastImage->directory . "SM_" . $isCastImage->path;
                }
            }
            $eventName = 'cast_news';

            // kio_test_20250716
            $html = "";
            $perPage = 12;
            $areaId = $request->area_id ?? 0;
            $genreId = $request->genre_id ?? 0;
            $casts = M_Cast::findOrFail($castId);
            $sites = M_Site::findOrFail($casts->site_id);
            $castBlogs = D_Cast_Blog::fetchSiteDataJoinImagePaginated(['site_id' => $sites->id, 'cast_id' => $castId]);
            $currentPage = $request->page ?? 1;
            $castBlogsPaginated = $this->paginateArray($castBlogs->toArray(), $perPage, $currentPage);
            $castBlogAryId = [];
            $castIdAry = [];
            foreach ($castBlogsPaginated as $index =>  $castBlog) {
                $castBlogAryId[] = $castBlog['id'];
                if (!in_array($castBlog['cast_id'], $castIdAry)) {
                    $castIdAry[] = $castBlog['cast_id'];
                }
            }
            $castImageAry = [];
            foreach ($castIdAry as $castIdA) {
                $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castIdA]);
                if ($castImage) {
                    $castImageAry[$castIdA] = $castImage->directory . "SM_" . $castImage->path;
                }
            }
            // 現在のページの最初のデータの'id'を取得して固定
            $fixedId = $request->id ?? null;
            // 現在のページ番号を取得
            $currentPage = $request->page ?? 1;
            $castBlogsPaginated->appends(['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $sites->id, 'cast_id' => $castId, 'category_id' => 3, 'id' => $fixedId, 'page' => $request->page]);
            $formatBlogImages = [];
            if ($castBlogAryId) {
                $castBlogImages = D_Cast_Blog_Image::fetchFilterIdData($castBlogAryId);
                foreach ($castBlogImages as $images) {
                    $formatBlogImages[$images->article_id] = $images->image_url;
                }
            }
            $html .= "
            <section>
                <div class='container'>
                <div class='row my-3'>";
            foreach ($castBlogsPaginated as $castBlog) {
                $url = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $sites->id, 'tab_id' => $request->tab_id, 'cast_id' => $castId, 'category_id' => 3, 'id' => $castBlog['id'], 'page' => $request->page]);
                $imageUrl = asset('storage/no-image.jpg');
                if (isset($castImageAry[$castBlog['cast_id']])) {
                    $imageUrl = asset('/storage' . $castImageAry[$castBlog['cast_id']]);
                }
                if (isset($formatBlogImages[$castBlog['id']])) {
                    $imageUrl = asset('/storage' . $formatBlogImages[$castBlog['id']]);
                }
                $castName = $castBlog['source_name'];
                if ($castBlog['age']) {
                    $castName = $castBlog['source_name'] . "(" . $castBlog['age'] . ")";
                }
                $html .= "
                            <div class='col-4 col-md-2 mb-3'>
                                <div class='image-container'>
                                <a href='$url'>
                                    <img src='$imageUrl' alt='" . $castBlog['source_name'] . $castBlog['title'] . "'>
                                    <div class='text-overlay'>
                                    <p class='cast-blog-title'>" . $castBlog['title'] . "</p>
                                    <p class='cast-blog-name'>$castName</p>
                                    <p class='cast-blog-date'>" . date('Y年m月d日 H:i', strtotime($castBlog['published_at'])) . "</p>
                                    </div>
                                </a>
                                </div>
                            </div>";
            }
            $html .= "</div>
                </div>";
            if ($castBlogsPaginated) {
                $html .= $castBlogsPaginated->links();
            }
            $html .= "</section>";
            //return $html;
        }
        $publicDate = $blog->published_at;
        if (empty($tabId)) {
            $tabData = D_Site_Detail_Tab::fetchFilteringSiteEventData(['site_id' => $siteId, 'event' => $eventName]);
            if ($tabData) {
                $tabId = $tabData->master_id;
            }
        }
        $tabs = $this->getTabs($siteId, $tabId, $areaId, $genreId);
        $tabName = "";
        $tabUrl = "";
        if (empty($tabId)) {
            if (isset($tabs[0]['master_id'])) {
                $tabId = $tabs[0]['master_id'];
                $tabs[0]['active'] = true;
                $tabName = $tabs[0]['name'];
                $tabUrl = route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId, 'tab_id' => $tabId]);
            }
        } else {
            foreach ($tabs as $tab) {
                if ($tab['master_id'] == $tabId) {
                    $tabName = $tab['name'];
                    $tabUrl = $tab['url'];
                    break;
                }
            }
        }
        $sites = M_Site::findOrFail($siteId);
        if ($sites->deleted_at != 0) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        $shops = M_Shop::findOrFail($sites->shop_id);
        $siteInfo = Site_Info::fetchSiteData($siteId);
        $template = $this->getGenre($genreId);
        $mainColor = "#F1747E"; //初期は風俗カラー
        if (isset($template['color'])) {
            $mainColor = $template['color'];
        }
        if ($siteInfo) {
            $mainColor = $siteInfo->color;
        }
        // $mainImage = $siteInfo ? $siteInfo->image : false;
        $mainImage = Site_Image::fetchSiteCategoryData($siteId, 1);
        $breadCrumbs = [
            ['label' => 'トップ', 'url' => route('site')],
        ];
        if (!empty($genreId)) {
            $genre = $this->getGenre($genreId);
            array_push($breadCrumbs, ['label' => $genre['text'] ?? "", 'url' => route('site.detail', ['genre_id' => $genreId])]);
            if (!empty($areaId)) {
                // $areas = $this->getArea($genreId,$areaId);
                $areas = M_Area::findOrFail($areaId);
                array_push($breadCrumbs, ['label' => $areas->name, 'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $areas->id])]);
                // array_push($breadCrumbs,['label' => $areas['text'],'url' => $areas['url']]);
            } else {
                $areas = Site_Area::fetchFilterFirstData(['site_id' => $siteId]);
                array_push($breadCrumbs, ['label' => $areas->name, 'url' => route('site.detail.area', ['genre_id' => $genreId, 'area_id' => $areas->area_id])]);
            }
        }
        array_push($breadCrumbs, ['label' => $sites->name, 'url' => route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $siteId])]);
        if ($tabName) {
            array_push($breadCrumbs, ['label' => $tabName, 'url' => $tabUrl]);
        }
        array_push($breadCrumbs, ['label' => $blogCateogryName, 'url' => '']);
        $keywords = '四国,天国ネット,' . $sites->name . ',' . $blogCateogryName;
        $title = '四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット';
        $description = '有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！';
        // ショップニュース
        if ($categoryId == 1) {
            $title .= ' ショップニュース「' . $blogCateogryName . '」「' . $sites->name . '」';
            $description .= '「' . $sites->name . '」が' . date('Y年m月d日H:i', strtotime($publicDate)) . 'に投稿したショップニュース「' . $blog->title . '」をご紹介。';
        }
        // 店長ブログ
        if ($categoryId == 2) {
            $title .= ' 店長ブログ「' . $blogCateogryName . '」「' . $sites->name . '」';
            $description .= '「' . $sites->name . '」が' . date('Y年m月d日H:i', strtotime($publicDate)) . 'に投稿した店長ブログ「' . $blog->title . '」をご紹介。';
        }
        // 写メ日記
        if ($categoryId == 3) {
            $title .=  ' 写メ日記「' . $blogCateogryName . '」「' . $castName . '」';
            $description .= '「' . $sites->name . '」に在籍する「' . $castName . '」が' . date('Y年m月d日H:i', strtotime($publicDate)) . 'に投稿した写メ日記「' . $blog->title . '」をご紹介。';
        }
        $siteGenres = Site_Genre::FetchFilteringData(['site_id' => $siteId]);
        foreach ($siteGenres as $siteGenre) {
            $keywords .= ',' . $siteGenre->name;
        }
        $pgttl_ary = [
            2 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // トレビの泉
            10 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // バニラリップ
            24 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 赤と黒
            26 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // エリザベス
            28 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 膝麻久庵
            31 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // レイチェル
            33 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 道後宴会屋
            55 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // ジャックと豆の木
            98 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // club菜の花
            101 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 高松しらゆき姫
            104 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 金瓶梅
            110 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 愛ドル学園
            111 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 高松トレビの泉
            114 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // しゃぶしゃぶ田中
            115 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // レイチェル新居浜店
            118 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 道後しらゆきひめ
            122 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // U-S-A
            123 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // アメイジングビル
            128 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 俺のシンデレラ
            130 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 優しいひとづま
            133 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // はちみつ
            136 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 回春エステ魂
            137 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // 英乃國屋
            147 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // SPAアロマショコラ
            153 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'], // ロイヤル
            154 => ['ttl1' => '『', 'ttl2' => '』', 'ttl3' => '：', 'ttl4' => ' - ', 'ttl5' => ' / ', 'ttl6' => ' | コスモ天国ネット'] // ハイブリッドLIPS
        ];
        $stname = "";
        if ($castName == "" or is_null($castName)) {
            $stname = "";
        } else {
            $stname = $castName;
        }
        if (array_key_exists($request->site_id, $pgttl_ary)) {
            $pgttl = $tabName . $pgttl_ary[$request->site_id]['ttl1'] . $blog->title . $pgttl_ary[$request->site_id]['ttl2'] . $stname . $pgttl_ary[$request->site_id]['ttl3'] . $sites->name . $pgttl_ary[$request->site_id]['ttl4'] . $areas->name . $pgttl_ary[$request->site_id]['ttl5'] . $siteGenre->name . $pgttl_ary[$request->site_id]['ttl6'];
        } else {
            $pgttl = $tabName . "『" . $blog->title . "』" . $stname . "：" . $sites->name . " - " . $areas->name . " / " . $siteGenre->name . " | コスモ天国ネット";
        }
        $pgdesc_ary = [
            2 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // トレビの泉
            10 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // バニラリップ
            24 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 赤と黒
            26 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // エリザベス
            28 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 膝麻久庵
            31 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // レイチェル
            33 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 道後宴会屋
            55 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // ジャックと豆の木
            98 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // club菜の花
            101 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 高松しらゆき姫
            104 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 金瓶梅
            110 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 愛ドル学園
            111 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 高松トレビの泉
            114 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // しゃぶしゃぶ田中
            115 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // レイチェル新居浜店
            118 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 道後しらゆきひめ
            122 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // U-S-A
            123 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // アメイジングビル
            128 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 俺のシンデレラ
            130 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 優しいひとづま
            133 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // はちみつ
            136 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 回春エステ魂
            137 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // 英乃國屋
            147 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // SPAアロマショコラ
            153 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'], // ロイヤル
            154 => ['ttl1' => 'の', 'ttl2' => '店「', 'ttl3' => '」', 'ttl4' => 'が投稿した', 'ttl5' => '『', 'ttl6' => '』をご紹介。'] // ハイブリッドLIPS
        ];
        if (array_key_exists($request->site_id, $pgdesc_ary)) {
            $pgdesc = $areas->name . $pgdesc_ary[$request->site_id]['ttl1'] . $siteGenre->name . $pgdesc_ary[$request->site_id]['ttl2'] . $sites->name . $pgdesc_ary[$request->site_id]['ttl3'] . $stname . $pgdesc_ary[$request->site_id]['ttl4'] . $tabName . $pgdesc_ary[$request->site_id]['ttl5'] . $blog->title . $pgdesc_ary[$request->site_id]['ttl6'];
        } else {
            $pgdesc = $areas->name . "の" . $siteGenre->name . "店「" . $sites->name . "」" . $stname . "が投稿した" . $tabName . "『" . $blog->title . "』をご紹介。";
        }
        //ジャンルでの切り分け
        $kio_genmst = [
            'fzk' => [ //風俗 
                '1' => '風俗'
            ],
            'gnr' => [ //一般
                '2' => 'メンズエステ',
                '3' => 'キャバクラ',
                '4' => 'セクキャバ',
                '5' => '飲食',
                '6' => '宴会',
                '7' => 'もみほぐし'
            ]
        ];
        //page のgenre_idを取得
        $kio_gen = '';
        $kio_gid = "";

        if ($request->has('genre_id')) {
            $kio_gid = $request->genre_id;
            foreach ($kio_genmst as $gky => $gary) {
                if (array_key_exists($kio_gid, $gary)) {
                    $kio_gen = $gky;
                    $gnr_name = $gary[$kio_gid];
                }
            }
            if ($kio_gen == "") {
                $kio_gen = 'fzk';
            }
        } else {
            $kio_gid = "";
            $kio_gen = 'fzk';
        }
        return view('sitePages.detail.detail.blogDetail', compact('cast', 'mainColor', 'mainImage', 'tabs', 'sites', 'shops', 'blogId', 'blog', 'blogImage', 'categoryId', 'genreId', 'areaId', 'siteId', 'castId', 'breadCrumbs', 'title', 'description', 'keywords', 'blogCateogryName', 'pgttl_ary', 'pgttl', 'stname', 'pgdesc_ary', 'pgdesc', 'newBlogDatas', 'kio_gen', 'html'));
    }
    /**
     * ページネーションを返す
     *
     * @param [type] $items
     * @param [type] $perPage
     * @param [type] $currentPage
     * @return void
     */
    function paginateArray($items, $perPage, $currentPage)
    {
        $offset = ($currentPage - 1) * $perPage;
        return new LengthAwarePaginator(
            array_slice($items, $offset, $perPage, true),
            count($items),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_today_work($detailData, $sites, $shops, $mainColor, $request)
    {
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        // $sites = M_Site::findOrFail($siteId);
        //出勤キャストを取得
        $date = date('Y-m-d');
        if (!empty($sites->close_time)) {
            $closeTime = $sites->close_time;
            if ($sites->close_time >= 2400) {
                $closeTime = $closeTime - 2400;
                if ($closeTime >= date('Hi')) {
                    $date = date('Y-m-d', strtotime('-1 day'));
                }
            }
        }
        $filter = [
            'date' => $date,
            'site_id' => $sites->id,
            'is_work' => 1,
            // 'sort' => 'ASC',
            'sokuhime_sort' => 'ASC',
            'is_public' => 1,
        ];
        $casts = Cast_Schedule::fetchFilteringData($filter);
        $imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        //今日出勤のキャストデータ
        $castIdAry = [];
        foreach ($casts as $index => $cast) {
            if (in_array($cast->cast_id, $castIdAry)) {
                unset($casts[$index]);
                continue;
            }
            array_push($castIdAry, $cast->cast_id);
            $casts[$index]->image = '/no-image.jpg';
            if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $casts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $casts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $casts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $casts[$index]->sokuhime_status = "次回" . $cast->sokuhime_status . "~";
                    }
                }
            }
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->cast_id]);
            if ($castImage) {
                $casts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
            $casts[$index]->exclusive_status_text = '';
            $casts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $casts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $casts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
        }

        // <p class='fw-bold fs-5 mb-0 mt-3' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>本日の出勤</p>

        // <div class='mb-3'>
        $html .=
            "<section>
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        $html .= "<div class='row mt-3'>";
        foreach ($casts as $cast) {
            // genre_id=1&area_id=1
            $url = route('site.detail.cast.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $cast->site_id, 'cast_id' => $cast->cast_id]);
            $imageUrl = asset('/storage' . $cast->image);
            $castName = $cast->source_name;
            if ($cast->age) {
                $castName = $cast->source_name . "(" . $cast->age . ")";
            }
            $castStyleHtml = "";
            if ($cast->bust || $cast->cup || $cast->waist || $cast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($cast->bust) {
                    $castStyleHtml .= "B $cast->bust";
                }
                if ($cast->cup) {
                    $castStyleHtml .= "($cast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$sites->name $cast->source_name'>";
            if ($cast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $cast->exclusive_status_image);
                $castImageHtml = "
                <div style='position: relative; display: inline-block;'>
                    <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='$sites->name $cast->source_name'>
                    <img src='$stayStatusImage' class='stay-status-img'></img>
                </div>
                ";
            }

            $sokuhimeComment = str_replace("\r\n", '<br>', $cast->sokuhime_comment);
            // $castImageHtml
            // <img class='pt-3 text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$sites->name $cast->source_name'>
            $html .= "
            <div class='col-6 col-sm-4 col-md-3 col-lg-2'>
                <a href='$url' class='cast_list_card'>
                    <div class='card px-0' data-toggle='tooltip' title='$sokuhimeComment'>
                        $castImageHtml
                        <p class='card-text text-white text-center mb-1' style='background: $mainColor'>$cast->sokuhime_status</p>
                        <div class='text-center' style='height: 5rem'>
                        <p class='mb-1'>$castName</p>
                        $castStyleHtml
                        </div>
                        <button class='btn btn-block cast-schedule-btn'>$cast->start_time ~ $cast->end_time</button>
                    </div>
                </a>  
            </div>";
        }
        if ($casts->isEmpty()) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "</div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_shop_news($detailData, $sites, $shops, $mainColor, $request)
    {
        // //ショップニュース
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        $shopBlogs = D_Shop_Blog::fetchSiteIdLimitData([$sites->id], 3);

        $html .= "
        <section>
            <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        foreach ($shopBlogs as $shopBlog) {
            $url = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $shopBlog->site_id, 'tab_id' => $request->tab_id, 'category_id' => 1, 'id' => $shopBlog->id]);
            $html .= "<ul>
            <li class='list_content'>
                <p class='mb-2 text-muted'> " . date('Y年m月d日 H:i', strtotime($shopBlog->published_at)) . "</p>                    
                <a href='$url' class='list_title fw-bold'>$shopBlog->title</a>
            </li>
            </ul>";
        }
        if ($shopBlogs->isEmpty()) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_manager_news($detailData, $sites, $shops, $mainColor, $request)
    {
        // //店長ブログ
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        $managerBlogs = D_Shop_Manager_Blog::fetchSiteIdLimitData([$sites->id], 3);

        $html .= "
        <section>
            <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        foreach ($managerBlogs as $managerBlog) {
            $url  = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $managerBlog->site_id, 'tab_id' => $request->tab_id, 'category_id' => 2, 'id' => $managerBlog->id]);
            $html .= "<ul>
                <li class='list_content'>
                    <p class='mb-2 text-muted'>" . date('Y年m月d日 H:i', strtotime($managerBlog->published_at)) . "</p>
                    <a href='$url' class='list_title fw-bold'>$managerBlog->title</a>
                </li>
            </ul>";
        }
        if ($managerBlogs->isEmpty()) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "
                </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_cast_news($detailData, $sites, $shops, $mainColor, $request)
    {
        // //キャストブログ
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        $castBlogs = D_Cast_Blog::fetchSiteIdLimitData([$sites->id], 6);

        foreach ($castBlogs as $index => $castBlog) {
            $castBlogs[$index]->image = '/no-image.jpg';
            $isImage = D_Cast_Blog_Image::fetchFilterIdFirstData($castBlog->id);
            if ($isImage) {
                $castBlogs[$index]->image = $isImage->image_url;
                continue;
            }
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castBlog->cast_id]);
            if ($isCastImage) {
                $castBlogs[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }

        $html .= "
        <section>
            <div class='container'>
            <div class='headline mb-3'>";
        if ($detailData->title) {
            $html .= "
                <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
                <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        $html .= "<div class='row my-3'>";
        foreach ($castBlogs as $castBlog) {
            $url = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $castBlog->site_id, 'tab_id' => $request->tab_id, 'category_id' => 3, 'id' => $castBlog->id]);
            $imageUrl = asset('/storage' . $castBlog->image);
            // $html .= "
            // <div class='col-12 col-md-4'>
            //     <div class='card text-center my-2'>
            //         <span class='pt-2'>$castBlog->site_name</span>
            //         <a href='$url'>
            //             <img class='py-3' src='$imageUrl' style='height: 200px; object-fit: contain;' alt='$sites->name $castBlog->source_name $castBlog->title'>
            //         </a>
            //         <p>$castBlog->source_name</p>
            //         <p>$castBlog->title</p>
            //         <p><small class='text-muted'> ".date('Y年m月d日',strtotime($castBlog->published_at)) ."</small></p>
            //     </div>
            // </div>";
            $html .= "
                    <div class='col-4 col-md-2 mb-3'>
                        <div class='image-container'>
                        <a href='$url'>
                            <img src='$imageUrl' alt='$sites->name $castBlog->source_name $castBlog->title'>              
                            <div class='text-overlay'>
                            <p class='cast-blog-title'>$castBlog->title</p>
                            <p class='cast-blog-name'>$castBlog->source_name</p>
                            <p class='cast-blog-date'>" . date('Y年m月d日 H:i', strtotime($castBlog->published_at)) . "</p>
                            </div>
                        </a>
                        </div>
                    </div>";
            // href="{{ route('site.detail.blog.detail',['category_id' => 3,'id' => $castBlog->id]) }}"
        }
        if ($castBlogs->isEmpty()) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "
                </div>
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_ranking($detailData, $sites, $shops, $mainColor, $request)
    {
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        $loop = 1;
        // //指名数ランキング
        $castReserveRanks = M_Cast::fetchReserveCountCast(['site_id' => $sites->id, 'limit' => 1]);
        foreach ($castReserveRanks as $index => $castAccessRank) {
            $castReserveRanks[$index]->image = '/no-image.jpg';
            // $castReserveRanks[$index]->age =  $castAccessRank->age ?? '-' ;
            // $castReserveRanks[$index]->bust =  $castAccessRank->bust ??  '-';
            // $castReserveRanks[$index]->cup =  $castAccessRank->cup ?? '-';
            // $castReserveRanks[$index]->waist =  $castAccessRank->waist ?? '-';
            // $castReserveRanks[$index]->hip =  $castAccessRank->hip ?? '-';
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castAccessRank->id]);
            if ($isCastImage) {
                $castReserveRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            }
        }
        // <p class='headline_title fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>指名数ランキング</p>
        $html .= "
        <section>
            <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        foreach ($castReserveRanks as $castReserveRank) {
            $url = route('site.detail.cast.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $castReserveRank->site_id, 'cast_id' => $castReserveRank->id]);
            $imageUrl = asset('/storage' . $castReserveRank->image);
            $castName = $castReserveRank->source_name;
            if ($castReserveRank->age) {
                $castName = $castReserveRank->source_name . "(" . $castReserveRank->age . ")";
            }
            $castStyleHtml = "";
            if ($castReserveRank->bust || $castReserveRank->cup || $castReserveRank->waist || $castReserveRank->hip) {
                $castStyleHtml = "<small class='text-muted mb-3'>";
                if ($castReserveRank->bust) {
                    $castStyleHtml .= "B $castReserveRank->bust";
                }
                if ($castReserveRank->cup) {
                    $castStyleHtml .= "($castReserveRank->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $html .= "
                <a href='$url' class='cast_list_card'>
                    <div class='card my-3'>
                    <div class='row g-0'>
                    <div class='col-md-2 text-center'>
                    <div class='triangle-number-$loop'></div>
                    <div class='triangle-number-text'>$loop<span class='triangle-number-small-text'>位</span></div>
                        <img src='$imageUrl' style='max-width: 100%;height: 200px; object-fit: contain;' alt='$castReserveRank->source_name'>
                    </div>
                    <div class='col-md-8'>
                        <div class='card-body'>
                            <p class='mb-0'>$castName</p>
                            $castStyleHtml
                            <p class='my-3 pc-area'>$castReserveRank->shop_manager_pr</p>
                        </div>
                    </div>
                    </div>
                    </div>
                    </a>";
            $loop++;
            // <a href="{{ route('site.detail.cast.detail',['cast_id' => $castReserveRank->id]) }}" class="cast_list_card">
        }
        if ($castReserveRanks->isEmpty()) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_recommend($detailData, $sites, $shops, $mainColor, $request)
    {
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $recommendCasts = M_Cast::fetchFilterRecommendCast(['site_id' => $sites->id]);
        $imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');

        foreach ($recommendCasts as $index => $cast) {
            $recommendCasts[$index]->image = '/no-image.jpg';
            if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $recommendCasts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $recommendCasts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $recommendCasts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $recommendCasts[$index]->sokuhime_status = $cast->sokuhime_status . "~";
                    }
                }
            }
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if ($castImage) {
                $recommendCasts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
            $recommendCasts[$index]->exclusive_status_text = '';
            $recommendCasts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $recommendCasts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $recommendCasts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
        }
        $html = "";
        $html .= "
        <section>
            <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        $html .= "<div class='row my-3'>";
        foreach ($recommendCasts as $recommendCast) {
            $url = route('site.detail.cast.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $recommendCast->site_id, 'cast_id' => $recommendCast->id, 'tab_id' => $request->tab_id]);
            $imageUrl = asset('/storage' . $recommendCast->image);
            $castName = $recommendCast->source_name;
            if ($recommendCast->age) {
                $castName = $recommendCast->source_name . "(" . $recommendCast->age . ")";
            }
            $castStyleHtml = "";
            if ($recommendCast->bust || $recommendCast->cup || $recommendCast->waist || $recommendCast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($recommendCast->bust) {
                    $castStyleHtml .= "B $recommendCast->bust";
                }
                if ($recommendCast->cup) {
                    $castStyleHtml .= "($recommendCast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$sites->name $recommendCast->source_name'>";
            if ($recommendCast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $recommendCast->exclusive_status_image);
                $castImageHtml = "
                <div style='position: relative; display: inline-block;'>
                    <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='$sites->name $recommendCast->source_name'>
                    <img src='$stayStatusImage' class='stay-status-img'></img>
                </div>
                ";
            }
            // <img class='pt-3 text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$recommendCast->source_name'>
            // <p class='card-text text-white text-center mb-1' style='background: $mainColor'>$recommendCast->sokuhime_status</p>
            $html .= "
            <div class='col-6 col-sm-4 col-md-3 col-lg-2'>
                <a href='$url' class='cast_list_card'>
                    <div class='card px-0'>
                        $castImageHtml
                        <div class='text-center' style='height: 5rem'>
                        <p class='mb-1'>$castName</p>
                        $castStyleHtml
                        </div>";
            if ($recommendCast->start_time) {
                $html .= "<button class='btn btn-block cast-schedule-btn' type='button'>$recommendCast->start_time ~ $recommendCast->end_time</button>";
            }
            $html .= "</div>
                </a>
            </div>";
        }
        if (!$recommendCasts) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_movie($detailData, $sites, $shops, $mainColor, $request)
    {
        $movieData = D_Movie::fetchFilterData(['site_id' => $sites->id, 'cast_id' => 0, 'limit' => 6]);
        $html = "";
        $html .= "
        <section>
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        $html .= "<div class='row my-3'>";
        foreach ($movieData as $data) {
            $imageUrl = asset('storage' . $data->file_path . '/' . $data->file_name . '.mp4');
            $title = $data->title;
            $content = $data->content;
            if(empty($data->cast_id)) {
                $url = route('site.detail.top', ['site_id' => $data->site_id]);
            } else {
                $url = route('site.detail.cast.detail', ['site_id' => $data->site_id, 'cast_id' => $data->cast_id]);
            }
            $castName = $data->source_name ?? $data->site_name;
            // <div class='col-12 col-md-6'>
            $html .= "
                <div class='col-6 col-md-4'>
                    <div class='video-card'>
                        <video class='cast-video w-100' src='$imageUrl' controlsList='nodownload' oncontextmenu='return false;' autoplay muted playsinline controls></video>
                        <div class='video-content p-3'>
                            <h6 class='video-title fw-bold mb-2' style='color: $mainColor;'>$title</h6>
                            <a href='$url' class='cast-name text-decoration-none'>
                                <span class='d-block mb-2 text-primary'>$castName</span>
                            </a>
                            <p class='video-description text-muted mb-0 small'>$content</p>
                        </div>
                    </div>
                </div>
                ";
        }
        if ($movieData->isEmpty()) {
            $html .= "<p class='text-center'>データがありません。</p>";
        }
        $html .= "
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function today_work($detailData, $sites, $shops, $mainColor, $request)
    {
        $shopWorkingStatus = TimeHelper::isWithinWorkingHours($sites->open_time, $sites->close_time);
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        $filterDate = $request->date;
        $currentDate = time();
        //出勤キャストを取得
        $isLateSite = false;
        if (!empty($sites->close_time)) {
            $closeTime = $sites->close_time;
            if ($sites->close_time >= 2400) {
                $closeTime = $closeTime - 2400;
                if ($closeTime >= date('Hi')) {
                    $currentDate = strtotime('-1 day', $currentDate); // 1日前のタイムスタンプ
                    $isLateSite = true;
                }
            }
        }
        if (empty($filterDate)) {
            if ($isLateSite) {
                $filterDate = date('Y-m-d', strtotime('-1 day'));
            } else {
                $filterDate = date('Y-m-d');
            }
        }
        $filter = [
            'date' => date('Y-m-d', strtotime($filterDate)),
            'site_id' => $sites->id,
            'is_work' => 1,
            // 'sort' => 'ASC',
            'sokuhime_sort' => 'ASC',
            'is_public' => 1,
        ];
        $casts = Cast_Schedule::fetchFilteringData($filter);
        $imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        //今日出勤のキャストデータ
        $castIdAry = [];
        foreach ($casts as $index => $cast) {
            if (in_array($cast->cast_id, $castIdAry)) {
                unset($casts[$index]);
                continue;
            }
            array_push($castIdAry, $cast->cast_id);
            if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $casts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $casts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $casts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $casts[$index]->sokuhime_status = "次回" . $cast->sokuhime_status . "~";
                    }
                }
            }
            $casts[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->cast_id]);
            if ($castImage) {
                $casts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
            $casts[$index]->exclusive_status_text = '';
            $casts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $casts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $casts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
        }
        $weeks = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        // 1週間後の日付を計算
        $oneWeekLater = strtotime('+1 week', $currentDate);
        $dateArray = array();
        $loop = 0;
        $isTodayDisplay = false;
        while ($currentDate < $oneWeekLater) {
            $dateArray[$loop]['Ymd'] = date('Ymd', $currentDate);
            $dateArray[$loop]['date'] = date('n月j日', $currentDate);
            $dateArray[$loop]['week'] = $weeks[date('w', $currentDate)];
            $dateArray[$loop]['active'] = date('Ymd', strtotime($filterDate)) == date('Ymd', $currentDate) ? true : false;
            $currentDate = strtotime('+1 day', $currentDate);
            if ($loop == 0 && $dateArray[$loop]['active']) {
                $isTodayDisplay = true;
            }
            $loop++;
        }
        $html = "<section> 
            <div class='container mt-3'>
            <div class='row'>";
        foreach ($dateArray as $index => $dateAry) {
            $cols = 4;
            $cols_md = 4;
            if ($index == 0) {
                $cols = 12;
                $cols_md = 12;
            }
            $activeClass = '';
            if ($dateAry['active']) {
                $activeClass = 'date_btn_active';
            }
            $title = $dateAry['date'] . "(" . $dateAry['week'] . ")";
            $url = route('site.detail.top', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $sites->id, 'date' => $dateAry['Ymd'], 'tab_id' => $request->tab_id]);
            $html .=  "
                    <div class='col-$cols col-md-$cols_md  text-center mb-3'>
                        <a href='$url' class='btn btn-block date_btn $activeClass'>$title</a>
                    </div>";
            // style='white-space:nowrap;'
        }
        foreach ($casts as $cast) {
            $url = route('site.detail.cast.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $cast->site_id, 'cast_id' => $cast->cast_id, 'tab_id' => $request->tab_id]);
            $imageUrl = asset('/storage' . $cast->image);
            $castName = $cast->source_name;
            if ($cast->age) {
                $castName = $cast->source_name . "(" . $cast->age . ")";
            }
            $castStyleHtml = "";
            if ($cast->bust || $cast->cup || $cast->waist || $cast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($cast->bust) {
                    $castStyleHtml .= "B $cast->bust";
                }
                if ($cast->cup) {
                    $castStyleHtml .= "($cast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$sites->name $cast->source_name'>";
            if ($cast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $cast->exclusive_status_image);
                $castImageHtml = "
                        <div style='position: relative; display: inline-block;'>
                            <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='$sites->name $cast->source_name'>
                            <img src='$stayStatusImage' class='stay-status-img'></img>
                        </div>
                        ";
            }
            // $bust = $cast->bust ? $cast->bust : '-';
            // $cup = $cast->cup ? $cast->cup : '-';
            // $waist = $cast->waist ? $cast->waist : '-';
            // $hip = $cast->hip ? $cast->hip : '-';
            // <img class='pt-3 text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>
            $sokuhimeComment = str_replace("\r\n", '<br>', $cast->sokuhime_comment);
            $html .= "
                    <div class='col-6 col-sm-4 col-md-3 col-lg-2'>
                    <a href='$url' class='cast_list_card'>
                        <div class='card px-0' data-toggle='tooltip' title='$sokuhimeComment'>
                        $castImageHtml
                    ";
            if ($isTodayDisplay) {
                $html .= "
                    <p class='card-text text-white text-center mb-1' style='background: $mainColor'>" .
                    ($cast->sokuhime_status) .
                    "</p>";
            }
            $html .= "
                        <div class='text-center' style='height: 5rem'>
                            <p class='mb-1'>$castName</p>
                            $castStyleHtml
                        </div>
                        <button class='btn btn-block cast-schedule-btn'>$cast->start_time ~ $cast->end_time </button>
                        </div>
                    </a>
                    </div>";
        }
        $html .= "</div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast($detailData, $sites, $shops, $mainColor, $request)
    {
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $html = "";
        $casts = M_Cast::fetchFilterAryId(['site_id' => $sites->id]);
        $date = date('Y-m-d');
        if (!empty($sites->close_time)) {
            $closeTime = $sites->close_time;
            if ($sites->close_time >= 2400) {
                $closeTime = $closeTime - 2400;
                if ($closeTime >= date('Hi')) {
                    $date = date('Y-m-d', strtotime('-1 day'));
                }
            }
        }
        $filter = [
            'date' => $date,
            'site_id' => $sites->id,
        ];
        $formatScheduleDatas = [];
        $scheduleDatas = Cast_Schedule::fetchFilteringData($filter);

        $castIdAry = [];
        foreach ($scheduleDatas as $scheduleData) {
            if (in_array($scheduleData->cast_id, $castIdAry)) {
                continue;
            }
            array_push($castIdAry, $scheduleData->cast_id);
            $formatScheduleDatas[$scheduleData->cast_id] = $scheduleData;
        }
        $imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        //在籍のキャストデータ
        foreach ($casts as $index => $cast) {
            $casts[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if ($castImage) {
                $casts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
            if (isset($formatScheduleDatas[$cast->id])) {
                $casts[$index]->is_work = $formatScheduleDatas[$cast->id]->is_work;
                $casts[$index]->start_time = $formatScheduleDatas[$cast->id]->start_time;
                $casts[$index]->end_time = $formatScheduleDatas[$cast->id]->end_time;
                $casts[$index]->rest_comment = $formatScheduleDatas[$cast->id]->rest_comment;
            }
            $casts[$index]->exclusive_status_text = '';
            $casts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $casts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $casts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
            if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $casts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $casts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $casts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $casts[$index]->sokuhime_status = "次回" . $cast->sokuhime_status . "~";
                    }
                }
            }
        }

        $html .=
            "<section>
            <div class='container mt-3'>";
        if ($detailData->title) {
            $html .= "
                <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
                <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        $html .= "<div class='row'>";
        // <a href="{{ route('site.detail.cast.detail',['cast_id' => $cast->id]) }}" class="cast_list_card">
        foreach ($casts as $cast) {
            $url = route('site.detail.cast.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $cast->site_id, 'cast_id' => $cast->id, 'tab_id' => $request->tab_id]);
            $imageUrl =  asset('/storage' . $cast->image);
            // $bust = $cast->bust ? $cast->bust : '-';
            // $cup = $cast->cup ? $cast->cup : '-';
            // $waist = $cast->waist ? $cast->waist : '-';
            // $hip = $cast->hip ? $cast->hip : '-';
            $castName = $cast->source_name;
            if ($cast->age) {
                $castName = $cast->source_name . "(" . $cast->age . ")";
            }
            $castStyleHtml = "";
            if ($cast->bust || $cast->cup || $cast->waist || $cast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($cast->bust) {
                    $castStyleHtml .= "B $cast->bust";
                }
                if ($cast->cup) {
                    $castStyleHtml .= "($cast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$sites->name $cast->source_name'>";
            if ($cast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $cast->exclusive_status_image);
                $castImageHtml = "
                        <div style='position: relative; display: inline-block;'>
                            <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='$sites->name $cast->source_name'>
                            <img src='$stayStatusImage' class='stay-status-img'></img>
                        </div>
                        ";
            }
            // <img class='pt-3 text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='$sites->name $cast->source_name'>
            $html .=
                "<div class='col-6 col-sm-4 col-md-3 col-lg-2'>
                    <div class='card text-center px-0' style='height: 23rem'>
                        <a href='$url'>
                            $castImageHtml
                        </a>
                            <p class='card-text text-white text-center mb-1' style='background: $mainColor'>";
            if ($cast->is_work == 1) {
                $html .= $cast->sokuhime_status;
            } else {
                $html .=  "お休み";
            }
            $html .=
                "</p>
                            <div class='text-center' style='height: 5rem'>
                                <p class='mb-1'>$castName</span></p>
                                $castStyleHtml
                            </div>";
            if ($cast->is_work == 1) {
                $html .=  "<button class='btn btn-block cast-schedule-btn'>$cast->start_time ~ $cast->end_time</button>";
            }
            $html .= "</div>
                    </div>";
        }
        $html .= "</div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function base_price($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "";
        $courses = Site_Course::fetchFilterSiteData($sites->id, 1); //普通を表示
        $nominationFees = Site_Nomination_Fee::fetchFilterSiteData($sites->id);
        $html .= "
        <section>
            <div class='container mt-3'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($courses->isNotEmpty()) {

            $html .= "<table class='table table-bordered mt-3'>";
            foreach ($courses as $course) {
                $price = number_format($course->fee);
                $html .= "
                <tr>
                    <td class='text-center table-active'>
                        $course->time 分
                    </td>
                    <td class='text-center'>
                        $price 円
                    </td>
                </tr>";
            }
            if ($nominationFees) {
                $html .= "
                <tr>
                    <td class='text-center table-active'>
                        指名料
                    </td>
                    <td class='text-center'>
                        " . number_format($nominationFees->fee) . "円
                    </td>
                </tr>
                <tr>
                    <td class='text-center table-active'>
                        本指名料
                    </td>
                    <td class='text-center'>
                        " . number_format($nominationFees->nomination_fee) . "円
                    </td>
                </tr>
                ";
            }
            $html .= "</table>";
        }
        // <div class='text-center price_sub_text'>
        //     <p class='mt-3 mb-1'>松山市内無料送迎あり</p>
        //     <p class='mb-3'>(時間帯によってはお断りさせて頂く場合がございますので、ご了承ください。)</p>
        // </div>
        $html .= "
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function extension_price($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "";
        $nominationFees = Site_Nomination_Fee::fetchFilterSiteData($sites->id);
        $html .= "
        <section>
        <div class='container mt-3'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($nominationFees) {
            $html .= "
                <table class='table table-bordered mt-3'>";
            if ($nominationFees->extension_time_unit > 0 && $nominationFees->extension_fee > 0) {
                $html .= "
                        <tr>
                        <td class='text-center table-active'>
                        " . number_format($nominationFees->extension_time_unit) . "分
                        </td>
                        <td class='text-center'>
                            " . number_format($nominationFees->extension_fee) . "円
                        </td>
                        </tr>";
            }
            $html .= "</table>";
        }
        $html .= "
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function other_price($detailData, $sites, $shops, $mainColor, $request)
    {
        // $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
        <section>
            <div class='container mt-3'>";
        if ($detailData->title) {
            $html .= "
                <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
                <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($detailData->content) {
            $html .= "$detailData->content";
        }
        $html .= "</div>
        </section>";
        // <div class='text-center my-3'>
        //         <p class='fw-bold'>[ご予約について]</p>
        //         <p>♦︎電話予約</p>
        //         <p>前日予約 AM9:00 ~ 受付</p>
        //         <p>当日予約 AM8:30 ~ 受付</p>
        //         <p class='fw-bold'>[確認電話]</p>
        //         <p>予約時間の30分前に来店確認のお電話をお客様からお願い致します。</p>
        //         <p>予約助教により、ご来店時間が多少前後する場合がございます。</p>
        //         <p>予め、ご了承ください。</p>
        //         <p>♦︎ネット予約</p>
        //         <p>前日 ~ 1週間から受付</p>
        //         <p>※キャストによりネット予約の事前予約日時は変わりますので、ご了承ください。</p>
        //         <p class='fw-bold'>[無料駐車場完備]</p>
        //         <p>近隣に無料駐車場あり。</p>
        //         <p>詳しくはスタッフまでお尋ねください。</p>
        //         <p class='fw-bold'>[無料歓迎]</p>
        //         <p>ご予約の際にお気軽にご利用くださいませ。</p>
        //     </div>
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_info($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "";
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $siteController = app()->make('App\Http\Controllers\SiteDetailPageController');
        $genre = $siteController->getGenre($sites->template);
        // $mainColor = $genre['color'];
        $mainText = $genre['text'];
        $shops = M_Shop::findOrFail($sites->shop_id);
        $html .= "
        <section>
            <div class='container mt-3'>";
        if ($detailData->title) {
            $html .= "
                <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
                <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($shops) {
            $openText = $shops->opening_text ?? '09:00 ~ 24:00';
            $holidayText = $shops->holiday_text ?? '年中無休';
            $html .= "<table class='table table-bordered mt-3'>
              <tr>
                <td class='text-center table-active' width='50%'>
                店舗名
                </td>
                <td class='text-center'>
                  $shops->name
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                業種
                </td>
                <td class='text-center'>
                    $mainText
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                営業時間
                </td>
                <td class='text-center'>
                    $openText
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                定休日
                </td>
                <td class='text-center'>
                    $holidayText
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                住所
                </td>
                <td class='text-center'>
                    $shops->address1 $shops->address2 $shops->address3
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                電話番号
                </td>
                <td class='text-center'>
                    $shops->tel
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                オフィシャルURL
                </td>
                <td class='text-center'>
                    <a href='https://$sites->url' target='_blank'>$sites->url</a>
                </td>
              </tr>
            </table>";
        }
        // <tr>
        //         <td class='text-center table-active'>
        //         駐車場
        //         </td>
        //         <td class='text-center'>
        //           あり
        //         </td>
        //       </tr>
        $html .= "</div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_access($detailData, $sites, $shops, $mainColor, $request)
    {
        // dd($detailData);
        $html = "
        <section>
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        $html .= "  
            <div class='col-12'>
                <div id='map' class='site-map-area my-3'></div>
            </div>
        </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_gallery($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "<section>
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($detailData->content) {
            $html .= "<div class='my-3'>$detailData->content</div>";
        }
        $html .= "</div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function event($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($detailData->content) {
            $html .=  "$detailData->content";
        }
        $html .= "</div>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast_news($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "";
        $perPage = 12;
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $castId = $request->cast_id ?? 0;
        $castBlogs = D_Cast_Blog::fetchSiteDataJoinImagePaginated(['site_id' => $sites->id, 'cast_id' => $castId]);
        $currentPage = $request->page ?? 1;
        $castBlogsPaginated = $this->paginateArray($castBlogs->toArray(), $perPage, $currentPage);
        $castBlogAryId = [];
        $castIdAry = [];
        foreach ($castBlogsPaginated as $index =>  $castBlog) {
            $castBlogAryId[] = $castBlog['id'];
            if (!in_array($castBlog['cast_id'], $castIdAry)) {
                $castIdAry[] = $castBlog['cast_id'];
            }
        }
        $castImageAry = [];
        foreach ($castIdAry as $castIdA) {
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castIdA]);
            if ($castImage) {
                $castImageAry[$castIdA] = $castImage->directory . "SM_" . $castImage->path;
            }
        }

        $castBlogsPaginated->appends(['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $sites->id, 'cast_id' => $castId]);
        $formatBlogImages = [];
        if ($castBlogAryId) {
            $castBlogImages = D_Cast_Blog_Image::fetchFilterIdData($castBlogAryId);
            foreach ($castBlogImages as $images) {
                $formatBlogImages[$images->article_id] = $images->image_url;
            }
        }
        $html .= "
        <section>
            <div class='container'>
            <div class='row my-3'>";
        // <a href="{{ route('site.detail.blog.detail',['category_id' => 3,'id' => $castBlog['id']]) }}" class="blog_card">
        foreach ($castBlogsPaginated as $castBlog) {
            $url = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $sites->id, 'tab_id' => $request->tab_id, 'category_id' => 3, 'id' => $castBlog['id']]);
            $imageUrl = asset('storage/no-image.jpg');
            if (isset($castImageAry[$castBlog['cast_id']])) {
                $imageUrl = asset('/storage' . $castImageAry[$castBlog['cast_id']]);
            }
            if (isset($formatBlogImages[$castBlog['id']])) {
                $imageUrl = asset('/storage' . $formatBlogImages[$castBlog['id']]);
            }
            $castName = $castBlog['source_name'];
            if ($castBlog['age']) {
                $castName = $castBlog['source_name'] . "(" . $castBlog['age'] . ")";
            }
            // $html .="
            // <div class='col-6 col-sm-4 col-md-3 col-lg-2'>
            //     <div class='card text-center px-0'>
            //         <a href='$url'>
            //             <img class='pt-3 text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>
            //         </a>
            //         <p class='card-text text-white text-center mb-1' style='background: $mainColor'>".$castName."</p>
            //         <div class='text-center' style='height: 5rem'>
            //             <p class='pt-3'>".$castBlog['title']."</p>
            //         </div>
            //     </div>
            // </div>";
            // </a>
            $html .= "
                        <div class='col-4 col-md-2 mb-3'>
                            <div class='image-container'>
                            <a href='$url'>
                                <img src='$imageUrl' alt='" . $castBlog['source_name'] . $castBlog['title'] . "'>
                                <div class='text-overlay'>
                                <p class='cast-blog-title'>" . $castBlog['title'] . "</p>
                                <p class='cast-blog-name'>$castName</p>
                                <p class='cast-blog-date'>" . date('Y年m月d日 H:i', strtotime($castBlog['published_at'])) . "</p>
                                </div>
                            </a>
                            </div>
                        </div>";
        }
        $html .= "</div>
            </div>";
        if ($castBlogsPaginated) {
            $html .= $castBlogsPaginated->links();
        }
        $html .= "</section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manager_news($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "";
        $perPage = 12;
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $shopManagerBlogs = D_Shop_Manager_Blog::FetchFilterSiteIdPublicData(['site_id' => [$sites->id]]);
        $currentPage = $request->page ?? 1;
        $shopManagerPaginated = $this->paginateArray($shopManagerBlogs->toArray(), $perPage, $currentPage);
        $shopManagerPaginated->appends(['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $sites->id]);

        $html .= "
        <section>
            <div class='container'>
            <div class='row my-3'>";
        foreach ($shopManagerPaginated as $managerBlog) {
            $url = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $managerBlog['site_id'], 'tab_id' => $request->tab_id, 'category_id' => 2, 'id' => $managerBlog['id']]);
            $html .= "
                <div class='col-12'>
                <ul>
                    <li class='list_content'>
                    <p class='mb-2 text-muted'>" . date('Y年m月d日 H:i', strtotime($managerBlog['published_at'])) . "</p>
                    <a href='$url' class='list_title fw-bold'>" . $managerBlog['title'] . "</a>
                    </li>
                </ul>
                </div>";
            // <a href="{{ route('site.detail.blog.detail',['category_id' => 2,'id' => $managerBlog['id']]) }}" class="list_title fw-bold">{{ $managerBlog['title'] }}</a>
        }
        $html .= "</div>
            </div>";
        if ($shopManagerBlogs) {
            $html .= $shopManagerPaginated->links();
        }
        $html .= "</section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_news($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "";
        $perPage = 12;
        $areaId = $request->area_id ?? 0;
        $genreId = $request->genre_id ?? 0;
        $shopBlogs = D_Shop_Blog::fetchFilterSiteIdPulicData(['site_id' => [$sites->id]]);
        $currentPage = $request->page ?? 1;
        $shopPaginated = $this->paginateArray($shopBlogs->toArray(), $perPage, $currentPage);
        $shopPaginated->appends(['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $sites->id]);

        $html .= "
        <section>
            <div class='container'>
            <div class='row my-3'>";
        foreach ($shopPaginated as $shopBlog) {
            $url = route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'tab_id' => $request->tab_id, 'site_id' => $shopBlog['site_id'], 'category_id' => 1, 'id' => $shopBlog['id']]);
            $html .= "
                <div class='col-12'>
                <ul>
                    <li class='list_content'>
                    <p class='mb-2 text-muted'>" . date('Y年m月d日 H:i', strtotime($shopBlog['published_at'])) . "</p>
                    <a href='$url' class='list_title fw-bold'>" . $shopBlog['title'] . "</a>
                    </li>
                </ul>
                </div>";
            // <a href="{{ route('site.detail.blog.detail',['category_id' => 2,'id' => $managerBlog['id']]) }}" class="list_title fw-bold">{{ $managerBlog['title'] }}</a>
        }
        $html .= "</div>
            </div>";
        if ($shopBlogs) {
            $html .= $shopPaginated->links();
        }
        $html .= "</section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recruit($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($detailData->content) {
            $html .=  "$detailData->content";
        }
        $html .= "</div>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_content($detailData, $sites, $shops, $mainColor, $request)
    {
        $html = "<section>
        <div class='container'>";
        if ($detailData->title) {
            $html .= "
            <h2 class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</h2>";
        }
        if ($detailData->sub_title) {
            $html .= "
            <span class='headline_text fw-bold'>$detailData->sub_title</span>";
        }
        if ($detailData->content) {
            $html .= "<div class='my-3'>$detailData->content</div>";
        }
        $html .= "</div>
        </section>";
        return $html;
    }
}
