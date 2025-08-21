<?php

namespace App\Services;

//mrt::lastupdate:2025/7/11
use App\Models\M_Site;
use App\Models\M_Shop;
use App\Models\D_Shop_Blog;
use App\Models\M_Genre;
use App\Models\Site_Genre;
use App\Models\M_Area;
use App\Models\Site_Area;
use App\Models\D_Site_Tab;
use App\Models\D_Site_Detail_Tab;
use App\Models\M_Cast;
use App\Models\Cast_Image;
use App\Models\Cast_Schedule;
use App\Models\D_Movie;
use App\Models\D_Shop_Manager_Blog;
use App\Models\D_Cast_Blog;
use App\Models\D_Cast_Blog_Image;
//システムらすい・・
use App\Models\Site_Course;
use App\Models\Site_Nomination_Fee;
use App\Models\Site_Option;

use App\Models\M_Cast_Question;
use App\Models\Cast_Answer;

use App\Models\D_Review;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;


class EstsServices
{
   /*************************************************************
   * 
   *   サイト情報　sites()
   * mrt::lastupdate:2025/7/11
   *   
   *
   *
   *************************************************************/
   public static function sites()
   {
      $sites = M_Site::where('is_public', 1)
                     ->where('deleted_at', 0)
                     ->orderBy('sort', 'asc')
                     ->select('id','shop_id','url','name','style','sort','remarks','content') //特定のカラムのみ
                     ->get()
                     ->keyBy('id')
                     ->toArray();
      return $sites;
   }
   /*************************************************************
   * 
   *   ショップ情報 shops()
   * mrt::lastupdate:2025/7/11
   *
   *
   */
   public static function shops()
   {
      $shops = M_Shop::where('is_public', 1)
                     ->where('deleted_at', 0)
                     ->select('id','corporate_id','name','kana','short_name','postal_code','address1','address2','address3','tel','sort','mail','recruit_tel','recruit_mail','opening_text','holiday_text') //特定のカラムのみ
                     ->get()
                     ->keyBy('id')
                     ->toArray();
      return $shops;
   }
   /*************************************************************
   * 
   *  ジャンル情報 genres()
   * mrt::lastupdate:2025/7/11
   * 
   * 
   ************************************************************/
   public static function genres()
   {
      $genres = M_Genre::where('is_public', 1)
                     ->where('deleted_at', 0)
                     ->get()
                     ->keyBy('id')
                     ->toArray();
      return $genres;
   }
   /************************************************************
   * 
   * エステポータルで表示するサイトとジャンルの紐づけ情報を配列にして返す
   *
   *
   *
   *************************************************************/
   public static function Sites_Genres()
   {
      $mens_id_Ary = config('EstheSite');
      $genre_sites = Site_Genre::where('deleted_at', 0)
                     ->whereIn('genre_id', $mens_id_Ary['all']) //ここで特定させる
                     ->select('id', 'genre_id', 'site_id')
                     ->get()
                     ->toArray();
      return $genre_sites;
   }
   /************************************************************
   * 
   *  エステポータルで表示するジャンル設定登録してるサイトIDのみの配列を返す Genre_SitesIdAry()
   * 
   * mrt::lastupdate:2025/7/11
   * 
   *************************************************************/
   public static function Genre_SitesIdAry()
   {
      $genre_sites = self::Sites_Genres();
      $genre_site_id_in = [];
      foreach($genre_sites as $gno => $gary){
         $genre_site_id_in[$gary['site_id']] = $gary['site_id'];
      }
      return $genre_site_id_in;
   }
   /***********************************************************
   *  
   * エステポータルで表示可能なサイト情報を配列にして返す
   *
   *
   *
   ************************************************************/
   public static function Ests_sitesAry()
   {
      $genre_site_id_in = self::Genre_SitesIdAry();
      $genre_sitesAry = M_Site::whereIn('id', $genre_site_id_in)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sort', 'asc') //
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      return $genre_sitesAry;
   }
   /***********************************************************
   * 
   * サイト設定で登録されているジャンル項目をサイトIDをキーに配列にして返す SiteIdKey_GenresIdAry()
   *
   *
   * mrt::lastupdate:2025/7/11
   ************************************************************/
   public static function SiteIdKey_GenreIdAry()
   {
      $genre_sites = self::Sites_Genres();
      $site_genidAry = [];
      foreach($genre_sites as $gsno => $iary){
         $site_genidAry[$iary['site_id']][$iary['genre_id']] = $iary['genre_id'];
      }
      return $site_genidAry;
   }
   /***********************************************************
   * 
   * サイト設定で登録されているジャンルのみを配列で返すSite_GenreIdAry()
   *
   *
   *　mrt::lastupdate:2025/7/11
   ************************************************************/
   public static function Site_GenreIdAry()
   {
      $genre_sites = self::Sites_Genres();
      $mens_genres = [];
      foreach($genre_sites as $gsno => $gsary){
         $mens_genres[$gsary['genre_id']] = $gsary['genre_id'];
      }
      return $mens_genres;
   }
   /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   * :::::::::::::::::::::::エリア周り::::::::::::::::::::::::::::
   * 
   * エリア情報で表示可能なものを配列にして返す
   * 
   *　mrt::lastupdate:2025/7/11
   *
   * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   *:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
   public static function Areas()
   {
      $areas = M_Area::where('is_public', 1)
                     ->where('deleted_at', 0)
                     ->get()
                     ->keyBy('id')
                     ->toArray();
      return $areas;
   }
   /*********************************************************
   * 
   * エステポータルで表示するサイトとエリアの紐づけ情報を配列にして返す
   *
   * mrt::lastupdate:2025/7/11
   **********************************************************/
   public static function Sites_Areas()
   {
      $genre_site_id_in = self::Genre_SitesIdAry();
      $area_sites = Site_Area::where('deleted_at', 0)
                     ->whereIn('site_id', $genre_site_id_in) //ここで特定させる
                     ->select('id', 'area_id', 'site_id')
                     ->get()
                     ->toArray();
      return $area_sites;  
   }
   /***********************************************************
   * 
   * サイト設定で登録されているエリア項目をサイトIDをキーに配列にして返す SiteIdKey_AreasIdAry()
   *
   *
   * mrt::lastupdate:2025/7/11
   ************************************************************/
   public static function SiteIdKey_AreasIdAry()
   {
      $area_sites = self::Sites_Areas();
      $site_areaAry = [];
      foreach($area_sites as $asno => $clmary){
         $site_areaAry[$clmary['site_id']][$clmary['area_id']] = $clmary['area_id'];
      }
      return $site_areaAry;
   }
   /***********************************************************
   * 
   * サイト設定で登録されているエリアのみを配列で返すSite_AreaIdAry()
   *
   *
   *　mrt::lastupdate:2025/7/11
   ************************************************************/
   public static function Site_AreaIdAry()
   {
      $area_sites = self::Sites_Areas();
      $mens_areas = [];
      foreach($area_sites as $asno => $asary){
         $mens_areas[$asary['area_id']] = $asary['area_id'];
      }
      return $mens_areas;
   }
   /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   * エリアページ用各コントローラーでwhereInで配列取得する
   * 
   * 
   * ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   *:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
   public static function AreaSitesIdAry($AreaIdAry)
   {
      $area_sites = self::Sites_Areas();
      $area_siteid = [];
      foreach($area_sites as $asno => $asary){
         if(in_array($asary['area_id'], $AreaIdAry)){
            $area_siteid[$asary['site_id']] = $asary['site_id'];
         }
      }      
      return $area_siteid;
   }
   public static function AreaAnySites($AreaIdAry)
   {
      $area_siteid = self::AreaSitesIdAry($AreaIdAry);
      $sites = M_Site::whereIn('id', $area_siteid)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sort', 'asc')
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      return $sites;
   }
   /*********************************************************************
   * 
   * エリアページ用各コントローラーでwhwreInで配列取得する
   * shop情報
   *
   **********************************************************************/
   public static function AreaAnyShops($AreaIdAry)
   {
      $sites = self::AreaAnySites($AreaIdAry);
      $shops_id = [];
      foreach($sites as $ky => $ary){
         $shops_id[$ary['shop_id']] = $ary['shop_id'];
      }
      $shops = M_Shop::whereIn('id', $shops_id)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->get()
                        ->keyBy('id')
                        ->toArray();

      return $shops;
   }
   /***********************************************************************
   * エリアページ用 
   * area情報
   *
   *
   ***********************************************************************/
   public static function AreaAny($AreaIdAry)
   {
      $areas = self::Areas();
      $areap = [];
      foreach($areas as $ky => $ary){
         if(in_array($ky, $AreaIdAry)){
            $areap[$ky] = $ary;
         }
      }
      return $areap;
   }
   /**********************************************************************
   /////////////////////////サイトページ/////////////////////////////////////
   * ショップページ関連だけどsiteページ関連と表現かな・・
   *　
   * 
   * site.indexページのリダイレクト
   //////////////////////////////////////////////////////////////////////
   ***********************************************************************/
   public static function index_Rdct($url_area, $url_siteid)
   {
      /**************
      * リダイレクト処理　サイトページトップ
      **************/
      $inf_ary = [];
      $inf_ary['area_rdct'] = false;
      $inf_ary['site_rdct'] = false;
      //定義配列呼び出し
      $def_mens_ary = config('EstheSite');;
      $inf_ary['def_mens_ary'] = $def_mens_ary;
      //接頭辞呼び出し
      $mrt_p_string = $def_mens_ary['memo']['site_url_prefix'];
      //ルートエリア名を取得
      $path_area = $url_area;
      //定義配列のエリア名に存在するかどうか
      $area_id_ary = [];
      if(array_key_exists($path_area, $def_mens_ary['area'])){
         //存在したのでエリアの配列IDに置き換え
         $area_id_ary = $def_mens_ary['area'][$path_area];
         $inf_ary['area_id_ary'] = $area_id_ary; 
      } else {
         //存在しない許可してないエリアの場合はトップへリダイレクト
         //return redirect('/');
         $inf_ary['area_rdct'] = true;
      }
      //上のURLパラメーターで問題ない場合はsite_idが存在するかチェック
      //接頭辞が含まれるかどうか
      if(strpos($url_siteid,$mrt_p_string) !== false){
         //含まれているので接頭辞撤去
         $site_id = str_replace($mrt_p_string, '',$url_siteid);
         $inf_ary['site_id'] = $site_id;
      } else {
         $inf_ary['site_rdct'] = true;
      }
      //サイト情報取得
      $sites = self::AreaAnySites($area_id_ary);
      $inf_ary['sites'] = $sites;
      //site_idは配列に存在するかどうか
      if(isset($site_id)){
         if(!array_key_exists($site_id, $sites)){
            //return redirect('/'.$url_area);
            $inf_ary['site_rdct'] = true;
         }
      } else {
         $inf_ary['site_rdct'] = true;
      }
      return $inf_ary;
   }
   /*********************************************************************
    * tab menu link bar 取得
    * @param mixed $SiteId
    ********************************************************************/
   public static function MenuTab($SiteId)
   {
       
      $m_tab = D_Site_Tab::where('site_id', $SiteId)
                        ->where('is_display', 1)
                        ->where('deleted_at', 0)
                        ->get()
                        //->keyBy('id')
                        ->toArray();
      //
      $mtab = [];
      foreach($m_tab as $no => $ary){
         $mtab[$ary['master_id']] = $ary; 
      }
      return $mtab;
   }
   /********************************************************************
   * siteページ内のコンテンツ取得（並び順に注意
   *
   * @param mixed $SiteId
   * @param mixed $MasterId
   *********************************************************************/
   public static function DetailTab($SiteId, $MasterId)
   {
      $d_tab = D_Site_Detail_Tab::where('site_id', $SiteId)
                        ->where('master_id', $MasterId)
                        ->where('is_display', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sort_no', 'asc')
                        ->get()
                        ->toArray();
      return $d_tab;
   }
   /**********************************************************************
   * 
   * サイトのキャスト情報
   *
   **********************************************************************/
   public static function Site_Casts($SiteIdAry)
   {
      //
      $casts = M_Cast::whereIn('site_id', $SiteIdAry)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sort', 'asc') //sort
                        ->get()
                        ->keyBy('id') //cast_idをキー
                        ->toArray();
      return $casts;
   }
   /**********************************************************************
   * 
   * 複数サイトのキャストid
   * site_id>[cast_id:cast_id]
   **********************************************************************/
   public static function sites_CastsId($SiteIdAry)
   {
      //
      $casts = M_Cast::whereIn('site_id', $SiteIdAry)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sort', 'asc') //sort
                        ->get()
                        ->keyBy('id') //cast_idをキー
                        ->toArray();
      //castary
      $castary = [];
      foreach($casts as $cid => $ary){
         $castary[$ary['site_id']][$cid] = $cid;
      }
      return $castary;
   }
   /**********************************************************************
   * 
   * 複数サイトのキャスト情報だけど出勤での並び替え反映用
   * ※管理画面の「本日の出勤」にて並び替えしたソートの情報用
   * site_id>[cast_id:cast_id]
   **********************************************************************/
   public static function sites_CastsId_ScdSort($SiteIdAry)
   {
      //
      $casts = M_Cast::whereIn('site_id', $SiteIdAry)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sokuhime_sort', 'asc') //sort
                        ->get()
                        ->keyBy('id') //cast_idをキー
                        ->toArray();
      return $casts;
   }   
   /**********************************************************************
   * 
   * おすすめのキャスト情報
   * recommend
   **********************************************************************/
   public static function Reco_Casts($SiteIdAry)
   {
      //
      $casts = M_Cast::whereIn('site_id', $SiteIdAry)
                        ->where('is_recommend', 1)
                        ->where('is_public', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('sort', 'asc') //sort
                        ->get()
                        ->toArray();
      $reco = [];
      foreach($casts as $no => $ary){
         $reco[$ary['site_id']][$ary['id']] = $ary;
      }
      return $reco;
   }
   /**********************************************************************
   * 
   * サイトのキャストイメージCast_Image
   * 
   * site_id>cast_id>cast_image
   * domain/storage/....
   ***********************************************************************/
   public static function Cast_Images($SiteIdAry)
   {
      $cimg = Cast_Image::whereIn('site_id', $SiteIdAry)
                        ->where('deleted_at', 0)
                        ->orderBy('sort_no', 'asc') //sort
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      $sid_cid_imgs = [];
      foreach($cimg as $no => $ary){
         $sid_cid_imgs[$ary['site_id']][$ary['cast_id']][] = $ary;
      }
      return $sid_cid_imgs;
   }
   /*********************************************************************
   * 
   *  出勤情報の日付切り替え
   *  予約システムのclose時刻を取得して切り替える
   *
   *********************************************************************/
   public static function ScdDayChange($close_time)
   {
      //返却配列
      $mrtAry = [];
      //定義
      $def_mens_ary = config('EstheSite');
      //本日の日時
      $def_date = Carbon::now();
      //閉店時刻
      $clstim = $close_time ?? 0000; //$sites[$site_id]['close_time'] ?? 0000;
      $nwtim = $def_date->copy()->format('Hi');
      //
      if($clstim >= 2400){
         $sabun = (int)$clstim - 2400; //maxmin 0
         if( $sabun > $nwtim){
            //1日前
            $def_date = Carbon::now()->subDays(1);
         }
      }
      $mrtAry['close_time'] = $clstim;
      //現在日時
      $mrtAry['def_date'] = $def_date;
      //現在日付
      $mrtAry['now_date'] = $def_date->copy()->format('Y-m-d');
      //現在日時
      $mrtAry['now_date_time'] = $def_date->copy()->format('Y-m-d H:i:s');
      //何日分
      $dts = [];
      $d = $def_mens_ary['memo']['scd_days'];
      for($i = 0; $d > $i; $i++){
         $dts[] = $def_date->copy()->addDays($i)->format('Y-m-d');
      }
      //設定してる日数
      $mrtAry['day_count'] = $d;
      //設定してる日数分の日付配列取得
      $mrtAry['def_date_ary'] = $dts;
      //この日付から
      $mrtAry['start_date'] = $def_date->copy()->now()->format('Y-m-d');
      //この日付まで
      $mrtAry['end_date'] = $def_date->copy()->addDays($d-1)->format('Y-m-d');
      return $mrtAry;
   }
   /*********************************************************************
   * 
   * 出勤情報Cast_Schedule
   * 
   * $CastIdAry=cast_idの配列
   * , $startDate=取得開始日
   * , $endDate=取得最終日
   * 
   * @return [d>cid][date][cast_id][*colum name*][value]
   * @return [no>cid][no][cast_id => cast_id]
   **********************************************************************/
   public static function Schedule($CastIdAry,$startDate, $endDate)
   {
      $scd = Cast_Schedule::whereBetween('date', [$startDate, $endDate])
                        ->whereIn('cast_id', $CastIdAry)
                        ->where('is_work', 1)
                        ->where('deleted_at', 0)
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      $scd_cid = [];
      foreach($scd as $no => $sary){
         $scd_cid['d>cid'][$sary['date']][$sary['cast_id']]['cast_id']    = $sary['cast_id'];
         $scd_cid['d>cid'][$sary['date']][$sary['cast_id']]['date']       = $sary['date'];
         $scd_cid['d>cid'][$sary['date']][$sary['cast_id']]['is_work']    = $sary['is_work'];
         $scd_cid['d>cid'][$sary['date']][$sary['cast_id']]['end_time']   = $sary['end_time'];
         $scd_cid['d>cid'][$sary['date']][$sary['cast_id']]['start_time'] = $sary['start_time'];
         $scd_cid['d>cid'][$sary['date']][$sary['cast_id']]['comment']    = $sary['comment'];
         $scd_cid['no>cid'][$sary['cast_id']] = $sary['cast_id'];
      }
      return $scd_cid;
   }
   /********************************************************************
   * 
   * 動画情報D_Movie
   * top_movie# メンズエステ
   * 
   * <video class="cast-video w-100" src="https://459x.com/storage/csm_movie/douga/110/13050/110_13050_2714.mp4" controlslist="nodownload" oncontextmenu="return false;" preload="none" controls="" muted="" playsinline="" loading="lazy"></video>
   *********************************************************************/
   public static function Movies($SiteIdAry)
   {
      $mv = D_Movie::whereIn('site_id', $SiteIdAry)
                        ->where('is_display', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('created_at', 'desc') //sort 投稿順
                        ->get()
                        ->toArray();
      $movies = [];
      foreach($mv as $no => $mary){
         $movies['siteid>no'][$mary['site_id']][] = $mary;
         $movies['siteid>castid>no'][$mary['site_id']][$mary['cast_id']][] = $mary;
      }
      return $movies;
   }
   /********************************************************************
   * 
   * キャスト動画情報D_Movie
   * top_movie# メンズエステ
   * 
   * <video class="cast-video w-100" src="https://459x.com/storage/csm_movie/douga/110/13050/110_13050_2714.mp4" controlslist="nodownload" oncontextmenu="return false;" preload="none" controls="" muted="" playsinline="" loading="lazy"></video>
   *********************************************************************/
   public static function MoviesCastId($CastId)
   {
      $mv = D_Movie::where('cast_id', $CastId)
                        ->where('is_display', 1)
                        ->where('deleted_at', 0)
                        ->orderBy('created_at', 'desc') //sort 投稿順
                        ->get()
                        ->toArray();
      $movies = [];
      foreach($mv as $no => $mary){
         $movies[$mary['cast_id']][] = $mary;
      }
      return $movies;
   }
   /*********************************************************************
   * 
   * ショップニュース
   * D_Shop_Blog (top_shop_news)
   * $SiteIdAry = [site_id,site_id...]
   * $NowDateTime = Y-m-d H:i:s 現在の日時を過ぎてる投稿分のみ
   *
   * 
   * すごく重たいテーブルだけど
   * indexを適切に貼れば快適です
   * published_at index
   * site_id index
   * 上記2つのカラムをindex指定してやればOKです　動作スピードは解決しますよ
   * by mrt 25/08/05
   **********************************************************************/
   
   public static function Shop_News($SiteIdAry,$NowDateTime,$pgint)
   {
         $spn =  D_Shop_Blog::whereIn('site_id', $SiteIdAry)
                        ->where('published_at', '<=', $NowDateTime)
                        ->where('delete_flg', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint)
                        //->get()
                        ->toArray();
      return $spn;
   }
   
  /*　キャッシュの必要はなくなりました
   public static function Shop_News($SiteIdAry,$NowDateTime,$pgint)
   {
      $cacheKey = 'shop_news_' . implode('_', $SiteIdAry) . '_' . substr($NowDateTime, 0, 10) . '_' . $pgint. '_' . request('page', 1);
      return Cache::remember($cacheKey, 600, function () use ($SiteIdAry, $NowDateTime, $pgint) {
         return D_Shop_Blog::whereIn('site_id', $SiteIdAry)
                        ->where('published_at', '<=', $NowDateTime)
                        ->where('delete_flg', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint)
                        //->get()
                        ->toArray();
      });
      //return $spn;
   }
   */
     /*********************************************************************
   * 
   * ショップニュースID
   * D_Shop_Blog (top_shop_news)
   * $SiteIdAry = [site_id,site_id...]
   * $NowDateTime = Y-m-d H:i:s 現在の日時を過ぎてる投稿分のみ
   *
   * 
   * すごく重たいテーブルだけど
   * indexを適切に貼れば快適です
   * published_at index
   * site_id index
   * 上記2つのカラムをindex指定してやればOKです　動作スピードは解決しますよ
   * by mrt 25/08/05
   **********************************************************************/
   
   public static function ShopNewsId($SiteIdAry,$NewsId)
   {
         $spn =  D_Shop_Blog::whereIn('site_id', $SiteIdAry)
                        ->where('id', $NewsId)
                        ->where('published_at', '<=', date('Y-m-d H:i:s'))
                        ->where('delete_flg', 0)
                        ->get()
                        //->keyBy('id')
                        ->toArray();
      return $spn;
   }
   public static function ShopNewsIdNext($SiteIdAry,$LinkDateTime)
   {
         $spn =  D_Shop_Blog::whereIn('site_id', $SiteIdAry)
                        //->where('id', $NewsId)
                        ->where('published_at', '<', $LinkDateTime)
                        ->where('delete_flg', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->limit(1)
                        ->get()
                        //->keyBy('id')
                        ->toArray();
      return $spn;
   }
   public static function ShopNewsIdPrev($SiteIdAry,$LinkDateTime)
   {
         $spn =  D_Shop_Blog::whereIn('site_id', $SiteIdAry)
                        //->where('id', $NewsId)
                        ->where('published_at', '<=', date('Y-m-d H:i:s'))
                        ->where('published_at', '>', $LinkDateTime)
                        ->where('delete_flg', 0)
                        ->orderBy('published_at', 'asc') //sort 投稿順
                        ->limit(1)
                        ->get()
                        //->keyBy('id')
                        ->toArray();
      return $spn;
   }
   /*********************************************************************
   * 
   * 店長ブログ
   * D_Shop_Manager_Blog
   * 
   * 
   **********************************************************************/
   public static function ManageBlog($SiteIdAry,$NowDateTime,$pgint)
   {
      $spb = D_Shop_Manager_Blog::whereIn('site_id', $SiteIdAry)
                        ->where('published_at', '<=', $NowDateTime)
                        ->where('delete_flg', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint) //page
                        //->get()
                        ->toArray();
      return $spb;
   }
   /********************************************************************
   * 
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * @return ['cb'] = ブログ配列　['cbid'] = ブログIDのみ配列
   *********************************************************************/
   public static function CastBlog($CastIdAry,$NowDateTime,$pgint)
   {
      $cb = D_Cast_Blog::whereIn('cast_id', $CastIdAry)
                        ->where('published_at', '<=', $NowDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint) //page
                        //->get()
                        ->toArray();
                        //
      $cb_ary = [];
      $cb_ary['defo'] = $cb ?? [];
      foreach($cb['data'] as $no => $ary){
         $cb_ary['cb'][$ary['id']] = $ary;
         $cb_ary['cbid'][$ary['id']] = $ary['id']; //添付画像の紐づけ用
      }
      return $cb_ary;
   }
   /********************************************************************
   * キャストIDで取得
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * @return ['cb'] = ブログ配列　['cbid'] = ブログIDのみ配列
   *********************************************************************/
   public static function CastIdBlog($CastId,$pgint)
   {
      $cb = D_Cast_Blog::where('cast_id', $CastId)
                        ->where('published_at', '<=', date('Y-m-d H:i:s'))
                        //->where('published_at', '<=', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint) //page
                        //->limit(1) //page per
                        //->get()
                        //->keyBy('id')
                        ->toArray();
      return $cb;
   }
   /********************************************************************
   * キャストIDで取得
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * @return ['cb'] = ブログ配列　['cbid'] = ブログIDのみ配列
   *********************************************************************/
   public static function CastIdBlogPage($CastId,$pgint,$npg)
   {
      $cb = D_Cast_Blog::where('cast_id', $CastId)
                        ->where('published_at', '<=', date('Y-m-d H:i:s'))
                        //->where('published_at', '<=', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint,['*'],'page',$npg) //page
                        //->limit(1) //page per
                        //->get()
                        //->keyBy('id')
                        ->toArray();
      return $cb;
   }
   /********************************************************************
   * キャストIDと日時で取得
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * 
   *********************************************************************/
   public static function CastBlogIdTime($CastId,$LinkDateTime,$pgint)
   {
      $cb = D_Cast_Blog::where('cast_id', $CastId)
                        ->where('published_at', '<=', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->paginate($pgint) //page
                        ->toArray();
      return $cb;
   }
   /********************************************************************
   * キャストIDと日時で記事数取得
   * 写メ日記
   * D_Cast_Blog
   * 
   * 
   *********************************************************************/
   public static function CastBlogIdPgCount($CastId,$LinkDateTime)
   {
      $cb = D_Cast_Blog::where('cast_id', $CastId)
                        ->where('published_at', '<=', date('Y-m-d H:i:s'))
                        ->where('published_at', '>', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        //->orderBy('published_at', 'desc') //sort 投稿順
                        //->paginate($pgint) //page
                        //->toArray();
                        ->count();
      return $cb;
   }
   /********************************************************************
   * キャストIDと日時で今の1件取得
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * 
   *********************************************************************/
   public static function CastBlogId($CbId)
   {
      $cb = D_Cast_Blog::where('id', $CbId)
                        //->where('published_at', '<=', date('Y-m-d H:i:s'))
                        //->where('published_at', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        //->orderBy('published_at', 'desc') //sort 投稿順
                        //->limit(1)
                        ->get()
                        //->keyBy('id')
                        ->toArray();
      return $cb;
   }
   /********************************************************************
   * キャストIDと日時で次の1件取得
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * 
   *********************************************************************/
   public static function CastBlogIdNext($CastId,$LinkDateTime)
   {
      $cb = D_Cast_Blog::where('cast_id', $CastId)
                        //->where('published_at', '<=', date('Y-m-d H:i:s'))
                        ->where('published_at', '<', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        ->orderBy('published_at', 'desc') //sort 投稿順
                        ->limit(1)
                        ->get()
                        ->toArray();
      return $cb;
   }
   /********************************************************************
   * キャストIDと日時で前の1件取得
   * 写メ日記
   * D_Cast_Blog
   * 紐づけ　d_cast_blog_image
   * 
   *********************************************************************/
   public static function CastBlogIdPrev($CastId,$LinkDateTime)
   {
      $cb = D_Cast_Blog::where('cast_id', $CastId)
                        ->where('published_at', '<=', date('Y-m-d H:i:s'))
                        ->where('published_at', '>', $LinkDateTime)
                        ->where('deleted_at', NULL)
                        ->where('is_draft', 0)
                        ->orderBy('published_at', 'asc') //sort 投稿順
                        ->limit(1)
                        ->get()
                        ->toArray();
      return $cb;
   }
   /*******************************************************************
   * 
   * 写メ日記
   * 添付画像
   * D_Cast_Blog_Image
   * 
   ********************************************************************/
   public static function CastBlogImage($cBlogIdAry)
   {
      $cb = D_Cast_Blog_Image::whereIn('article_id', $cBlogIdAry)
                        ->where('deleted_at', NULL)
                        //->orderBy('published_at', 'desc') //sort 投稿順
                        ->get()
                        ->toArray();
      $cbary = [];
      foreach($cb as $no => $ary){
         $cbary[$ary['article_id']][] = $ary;
      }
      return $cbary;
   }
   /********************************************************************
   * 
   * コース料金システム 
   * Site_Course
   * 
   * 
   *********************************************************************/
   public static function Sites_System($SiteIdAry)
   {
      $systems = Site_Course::whereIn('site_id', $SiteIdAry)
                        ->where('deleted_at', NULL)
                        ->orderBy('sort_no', 'asc') //sort asc昇順 desc降順
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      //配列組替え
      $sys_ary = [];
      foreach($systems as $id => $ary){
         $sys_ary[$ary['site_id']][] = $ary; 
      }

      return $sys_ary;
   }
   /********************************************************************
   * 
   * 指名延長料金システム 
   * Site_Nomination_Fee
   * 
   * 
   *********************************************************************/
   public static function Sites_SystemEx($SiteIdAry)
   {
      $systems = Site_Nomination_Fee::whereIn('site_id', $SiteIdAry)
                        ->where('deleted_at', NULL)
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      //配列組替え
      $sys_ary = [];
      foreach($systems as $id => $ary){
         $sys_ary[$ary['site_id']][] = $ary; 
      }

      return $sys_ary;
   }
   /********************************************************************
   * 
   * オプション料金システム 
   * Site_Course
   * 
   * 
   *********************************************************************/
   public static function Sites_SystemOp($SiteIdAry)
   {
      $systems = Site_Option::whereIn('site_id', $SiteIdAry)
                        ->where('deleted_at', NULL)
                        ->orderBy('sort_no', 'asc') //sort asc昇順 desc降順
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      //配列組替え
      $sys_ary = [];
      foreach($systems as $id => $ary){
         $sys_ary[$ary['site_id']][] = $ary; 
      }

      return $sys_ary;
   }
   /********************************************************************
   * 
   * アンケートサイト主題
   * 
   * 
   * 
   *********************************************************************/
   public static function SiteIdQuestion($SiteId)
   {
      $qary = M_Cast_Question::where('site_id', $SiteId)
                        ->where('deleted_at', 0)
                        ->where('is_public', 1)
                        ->orderBy('sort_no', 'asc') //sort asc昇順 desc降順
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      return $qary;
   }
   /********************************************************************
   * 
   * アンケートサイト回答
   * 
   * 
   * 
   *********************************************************************/
   public static function CastIdAnswer($CastId)
   {
      $cqary = Cast_Answer::where('cast_id', $CastId)
                        ->where('deleted_at', 0)
                        ->where('is_public', 1)
                        ->get()
                        ->keyBy('id')
                        ->toArray();
      $qary = [];
      foreach($cqary as $aid => $ary){
         $qary[$ary['cast_id']][$ary['question_id']] = $ary;
      }
      return $qary;
   }
}