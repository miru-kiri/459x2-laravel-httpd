<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//エステポータルの共通クラス呼び出し
use App\Services\EstsServices;
use App\Services\EstsTagServices;
use Carbon\Carbon;

class Ests_AreaController extends Controller
{
    /*******************************************
     * エリアトップページコントローラー
     * [14]愛媛県松山市
     * [5]新居浜市
     * [6]四国中央市
     * 
     * 
     * 
     * 
     * @return 
     *******************************************/
   public function index(Request $request, $url_area)
   {
      //定義配列呼び出し
      $def_mens_ary = config('EstheSite');
      //ルートエリア名を取得
      $path_area = $url_area;
      //定義配列のエリア名に存在するかどうか
      if(array_key_exists($path_area, $def_mens_ary['area'])){
         //存在したのでエリアの配列IDに置き換え
         $area_id_ary = $def_mens_ary['area'][$path_area];
      } else {
         //存在しない許可してないエリアの場合はトップへリダイレクト
         return redirect('/');
      }
      //メタタグ html
      $tag_meta = EstsTagServices::indexMeta();
      //cssタグ
      $css_tag = EstsTagServices::rdCss();
      //jsタグ
      $js_tag = EstsTagServices::rdJs();
      //このエリアで表示するサイト情報
      $sites = EstsServices::AreaAnySites($area_id_ary);
      $sidary = [];
      foreach($sites as $sid => $sary){
         $sidary[$sid] = $sid;
      }
      //このエリアで表示するショップ情報
      $shops = EstsServices::AreaAnyShops($area_id_ary);
      //このエリア情報
      $areap = EstsServices::AreaAny($area_id_ary);
      //サイトIDキーでジャンルの配列
      $SidGidAry = EstsServices::SiteIdKey_GenreIdAry();
      //ジャンル情報
      $genres = EstsServices::genres();
      //サイトIDでキャストIDを取得site_id>[cast_id]
      $scid = EstsServices::sites_CastsId($sidary);
      /*************
       * キャスト情報
       *************/
      //キャスト画像情報
      $cimgs = EstsServices::Cast_Images($sidary);   
      //キャスト情報
      $casts = EstsServices::sites_CastsId_ScdSort($sidary);
      /**********************
      * 出勤情報をサイトごとに配列
      **********************/
      $scary = [];
      foreach($scid as $sid => $ary){
         //サイト閉店切り替え時刻
         $scary[$sid]['close_time'] = $sites[$sid]['close_time'] ?? 0000;
         //計算処理取得
         $scary[$sid]['day_change'] = EstsServices::ScdDayChange($scary[$sid]['close_time']);
         //現在の日時
         $scary[$sid]['def_date'] = $scary[$sid]['day_change']['def_date'];
         //Y-m-dフォーマット変更
         $dymd = date('Y-m-d', strtotime($scary[$sid]['def_date']));
         //出勤情報をサイトごとに取得していく
         $scary[$sid]['scd'] = EstsServices::Schedule($ary, $dymd, $dymd);
      }
      
      //閉店切り替え日時処理
      //$close_time = $sites[$site_id]['close_time'] ?? 0000;
      //取得
      //$day_change = EstsServices::ScdDayChange($close_time);
      //現在の日付
      //$def_date = $day_change['def_date'];
      //出勤のキャストID site_id>[cast_id=>cast_id]
      //$sid_cid = EstsServices::Schedule($CastIdAry,$startDate, $endDate);
      //bladeへ返却
      return view('ests.area'
                    ,compact(
                          'areap'
                        , 'tag_meta'
                        , 'sites'
                        , 'shops'
                        , 'SidGidAry'
                        , 'genres'
                        , 'path_area'
                        , 'def_mens_ary'
                        , 'css_tag'
                        , 'js_tag'
                        , 'scid'
                        , 'casts'
                        , 'cimgs'
                        , 'scary'
                        ,'dymd'
                        ,'url_area'
                ));
    }
}
