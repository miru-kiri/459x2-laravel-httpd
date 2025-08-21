<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//エステポータルの共通クラス呼び出し
use App\Services\EstsServices;
use App\Services\EstsTagServices;
use Carbon\Carbon;

use App\Models\D_Cast_Blog;
//use App\Models\D_Cast_Blog_Image;



class Ests_SiteController extends Controller
{
   /**************************************************************
    * ############################################################
    * ショップページコントローラー
    * [14]愛媛県松山市
    * [5]新居浜市
    * [6]四国中央市
    *                    areaname/siteid/index
    * 
    * 
    * 
    * @return サイト（ショップ）トップページ　index
    * ############################################################
    *************************************************************/
   public function index(Request $request, $url_area, $url_siteid)
   {
      //リダイレクト処理
      $inf_ary = EstsServices::index_Rdct($url_area, $url_siteid);
      if($inf_ary['area_rdct']){
         return redirect('/');
      }
      if($inf_ary['site_rdct']){
         return redirect('/'.$url_area);
      }
      //リダイレクト関数からの引数引継ぎ
      //エステポータル定義
      $def_mens_ary = config('EstheSite');
      //エリア
      $area_id_ary = $inf_ary['area_id_ary'];
      //urlパラメータよりidのみにした
      $site_id = $inf_ary['site_id'];
      //サイト情報
      $sites = $inf_ary['sites'];
      /*************
       * 表示取得処理
       *************/
      //このエリアで表示するショップ情報
      $shops = EstsServices::AreaAnyShops($area_id_ary);
      //このエリア情報
      $areap = EstsServices::AreaAny($area_id_ary);
      //html 共用meta
      $meta_tag = EstsTagServices::indexMeta();
      $css_tag = EstsTagServices::rdCss();
      //tab menu
      /*
      (10)トップ (11)出勤情報 (12)在籍一覧 (13)料金システム (14)店舗情報 (15)イベント情報
      (16)写メ日記 (17)店長ブログ (18)求人情報 (50)ショップニュース
      */
      //page id
      $page_name_id = 10; //top page
      //menu button
      $m_tab = EstsServices::MenuTab($site_id);
      //page in contents sort
      $d_tab = EstsServices::DetailTab($site_id, 10);
      /************
       * キャスト関連
       ************/
      //キャストID初期化
      $cast_id_ary = [];
      //キャスト画像情報
      $sid_cid_imgs = [];
      $sid_cid_imgs = EstsServices::Cast_Images([$site_id]);   
      //キャスト情報※本日出勤の並び替対応版25-07-22mrt
      $casts = [];
      $casts = EstsServices::sites_CastsId_ScdSort([$site_id]);
      //cast_id array
      foreach($casts as $cid => $cary){
         $cast_id_ary[$cid] = $cid;
      }
      //動画情報
      $mvs = [];
      $mvs = EstsServices::Movies([$site_id]);
      /**************
       * 出勤処理関連
       **************/
      $scd = [];
      //閉店切り替え日時処理
      $close_time = $sites[$site_id]['close_time'] ?? 0000;
      //日付の切り替わり処理取得
      $day_change = EstsServices::ScdDayChange($close_time);
      //時間処理済み 現在の日付
      $def_date = $day_change['def_date'];
      //日時フォーマットY-m-dへ
      $ymd_date = $def_date->copy()->format('Y-m-d');
      //取得日付から
      $sdate = $def_date->copy()->format('Y-m-d');
      //取得日付まで
      $edate = $def_date->copy()->format('Y-m-d');
      //出勤情報
      $scd = EstsServices::Schedule($cast_id_ary,$sdate, $edate);
      /***************
       * ショップニュース
       **************/
      $sp_news = [];
      $sp_news = EstsServices::Shop_News([$site_id],$def_date,'5');
      /***************
       * 店長おすすめ
       **************/
      $reco_casts = EstsServices::Reco_Casts([$site_id]);
      /***************
       * 店長ブログ
       **************/
      //$def_date,$def_mens_ary['memo']['per']['tencho_blog']
      $mng_blog = EstsServices::ManageBlog([$site_id],$def_date,'5');
      /***************
       * 写メ日記 [cb][cbid]
       **************/
      $cblog = EstsServices::CastBlog($cast_id_ary,$def_date,config('EstheSite.memo.per.cast_blog'));
      //サムネ？添付画像
      $cbimg = EstsServices::CastBlogImage($cblog['cbid'] ?? []);


      //$conf = config('EstheSite');
      $ggg = '';
      return view('ests.site',compact(
                                        'sites'         //サイトマスター
                                       ,'shops'         //ショップ情報
                                       ,'meta_tag'      //共通メタタグ
                                       ,'url_area'      //URLエリア
                                       ,'url_siteid'    //URLサイトID
                                       ,'site_id'       //サイトID
                                       ,'m_tab'         //サイトメニュー
                                       ,'d_tab'         //サイトメニュー詳細
                                       ,'page_name_id'  //サイトページID
                                       ,'css_tag'       //css
                                       ,'mvs'           //動画情報
                                       ,'casts'         //キャスト情報
                                       ,'scd'           //スケジュール情報
                                       ,'ymd_date'      //日付
                                       ,'sid_cid_imgs'  //キャスト画像情報
                                       ,'sp_news'       //店舗ニュース
                                       ,'reco_casts'    //おすすめキャスト
                                       ,'mng_blog'      //店長ブログ
                                       ,'cblog'         //写メ日記
                                       ,'cbimg'         //写メ日記添付画像
                                     ));
   }
   /***************************************************************************
   *　########################################################################## 
   * ショップ（site）の各コンテンツページ
   * 
   * https://domain/$url_area/$url_siteid/$url_contents/kobetu?query-param

   //////////一覧/////////////////////
             '10' => 'top' //indexへリダイレクト
            ,'11' => 'scd'
            ,'12' => 'cast'
            ,'13' => 'system'
            ,'14' => 'info'
            ,'15' => 'event'
            ,'16' => 'cast_blog'
            ,'17' => 'tencho_blog'
            ,'18' => 'cast_recruit'
            ,'50' => 'site_news'
   * ##########################################################################
   ***************************************************************************/
   public function contents(Request $request, $url_area, $url_siteid, $url_contents)
   {
      /*////////////////////////////////////
   |--[リダイレクトセクション]
      ////////////////////////////////////*/
      //リダイレクト処理
      $inf_ary = EstsServices::index_Rdct($url_area, $url_siteid);
      //エステポータル定義
      $def_mens_ary = config('EstheSite');;
      //1＃エリア名が存在するかどうか
      if($inf_ary['area_rdct']){ return redirect('/'); }
      //2＃site_idは存在するかどうか
      if($inf_ary['site_rdct']){ return redirect('/'.$url_area); }
      //3＃コンテンツページは存在するか
      if(!in_array($url_contents,$def_mens_ary['tab'])){
         //サイト（ショップ）トップにリダイレクト？
         return redirect('/'.$url_area.'/'.$url_siteid);
      }
      //4＃サイト(ショップ)トップページはコンテンツページじゃないのでリダイレクト？
      if($url_contents == $def_mens_ary['tab']['10']){
         return redirect('/'.$url_area.'/'.$url_siteid);
      }
      //リダイレクト関数からの引数引継ぎ
      //urlパラメータよりidのみにした
      $site_id = $inf_ary['site_id'];
      //４段階のリダイレクトここまで
      /*////////////////////////
   |--[リダイレクトセクションここまで]
      // /////////////////////// */
      //html 共用meta
      $meta_tag = EstsTagServices::indexMeta();
      $css_tag = EstsTagServices::rdCss();
      //コンテンツID取得
      $conid = '0';
      foreach($def_mens_ary['tab'] as $cnid => $cnvl){
         if($url_contents == $cnvl){
            $conid = $cnid;
         }
      }
      //page in contents sort
      $d_tab = EstsServices::DetailTab($site_id, $conid);
      //サイト情報
      $sites = $inf_ary['sites'];
      //m_tabコンテンツ名
      $m_tab = EstsServices::MenuTab($site_id);
      //コンテンツページIDを取得
      $m_tab_id = '';
      foreach($def_mens_ary['tab'] as $tid => $tkey){
         if($url_contents == $tkey){
            $m_tab_id = $tid;
         }
      }
      /*/////////////////////////////
   |--[コンテンツページ振り分け]
      //////////////////////////// */
      //変数
      $casts = [];
      $sid_cid_imgs = [];
      $cast_id_ary = [];
      $dts = [];
      $scd = [];
      $close_time = 0000;
      $shops = [];
      $sys = []; $sysex = [];
      $cb = []; $cbim = []; $cb_ary = [];
      $now_date_time = '';
      $sp_news = [];

      //コンテンツ共通グループ
      //casts,image
      if(in_array($url_contents, ['scd','cast','cast_blog','tencho_blog','site_news'])){
         //キャスト画像情報
         $sid_cid_imgs = EstsServices::Cast_Images([$site_id]);   
         //キャスト情報
         $casts = EstsServices::Site_Casts([$site_id]);
         //cast_id array
         foreach($casts as $cid => $cary){
            $cast_id_ary[$cid] = $cid;
         }
         //閉店切り替え日時処理
         $close_time = $sites[$site_id]['close_time'] ?? 0000;
         //日付切り替え取得
         $day_change = EstsServices::ScdDayChange($close_time);
         //現在の日付
         $now_date = $day_change['now_date'];
         //現在の日付
         $now_date_time = $day_change['now_date_time'];
         //時間の処理済み
         $def_date = $day_change['def_date'];
      }
      //コンテンツ切り分け
      if('scd' == $url_contents){
         //設定日数分の日付を取得
         $dts = $day_change['def_date_ary'];
         //取得日付から
         $sdate = $day_change['start_date'];
         //取得日付まで
         $edate = $day_change['end_date'];
         //出勤ページ[d>cid] [no>cid]
         $scd = EstsServices::Schedule($cast_id_ary,$sdate, $edate);
      } else 
      if('cast' == $url_contents){
         //キャスト一覧

      } else 
      if('info' == $url_contents){
         //店舗情報

      }else
      if('system' == $url_contents){
         //bace_price コース料金　+ fee + nomination_fee
         $sys = EstsServices::Sites_System([$site_id]);
         //extention_price 延長料金　分と料金
         $sysex = EstsServices::Sites_SystemEx([$site_id]);
         //other_price 手入力なのでそのまま
      } else 
      if('event' == $url_contents){
         //イベント

      } else 
      if('cast_blog' == $url_contents){
         //キャストブログ
         $cb_ary = EstsServices::CastBlog($cast_id_ary,$now_date_time,config('EstheSite.,memo.per.cast_blog'));
         $cbim = EstsServices::CastBlogImage($cb_ary['cbid'] ?? []);
      
      } else 
      if('tencho_blog' == $url_contents){
         //店長ブログ
         //$def_date,$def_mens_ary['memo']['per']['tencho_blog']
         $mng_blog = EstsServices::ManageBlog([$site_id],$def_date,$def_mens_ary['memo']['per']['tencho_blog']);
      } else 
      if('cast_recruit' == $url_contents){
         //キャスト求人
      } else 
      if('site_news' == $url_contents){
         //ショップニュース
         $sp_news = EstsServices::Shop_News([$site_id],$def_date,$def_mens_ary['memo']['per']['site_news']);
      }
      /*///////////////////////////
   |--[コンテンツページ振り分けここまで]
      ///////////////////////////*/

      //casts>source_name

      //page contents define
      $pgcon = $def_mens_ary['pgcon'];
      //return
      return view('ests.site_contents'
                    ,compact(
                         'site_id'      //サイトID
                        ,'meta_tag'     //共通メタタグ
                        ,'css_tag'      //共通css
                        ,'def_mens_ary' //エステポータル定義
                        ,'casts'        //キャスト情報
                        ,'sid_cid_imgs' //キャスト画像情報
                        ,'now_date_time'
                        ,'dts'          //出勤の日付
                        ,'scd'          //出勤情報
                        ,'sites'        //サイト情報
                        ,'close_time'   //閉店時刻
                        ,'sys'          //料金情報 
                        ,'sysex'
                        ,'url_siteid'
                        ,'url_area'
                        ,'url_contents' //コンテンツページWEBキー
                        ,'m_tab'        //コンテンツページ名
                        ,'m_tab_id'     //カテゴリーページマスターID
                        ,'cb_ary'
                        ,'cbim'
                        ,'url_area'
                        ,'d_tab'        //ページ内コンテンツ管理画面調整用
                        ,'pgcon'
                        ,'conid'
                        ,'sp_news'
                  ));
   }
   /***************************************************************************
   *　########################################################################## 
   * ショップ（site）の各コンテンツ個別ページ
   * 
   * https://domain/$url_area/$url_siteid/$url_contents/kobetu?query-param

   //////////一覧/////////////////////
             '10' => 'top' //indexへリダイレクト
            ,'11' => 'scd'
            ,'12' => 'cast'
            ,'13' => 'system'
            ,'14' => 'info'
            ,'15' => 'event'
            ,'16' => 'cast_blog'
            ,'17' => 'tencho_blog'
            ,'18' => 'cast_recruit'
            ,'50' => 'site_news'
   * ##########################################################################
   ***************************************************************************/
   public function contentId(Request $request, $url_area, $url_siteid, $url_contents, $url_contentid)
   {
      /*////////////////////////////////////
   |--[リダイレクトセクション]
      ////////////////////////////////////*/
      //リダイレクト処理
      $inf_ary = EstsServices::index_Rdct($url_area, $url_siteid);
      //エステポータル定義
      $def_mens_ary = config('EstheSite');;
      //1＃エリア名が存在するかどうか
      if($inf_ary['area_rdct']){ return redirect('/'); }
      //2＃site_idは存在するかどうか
      if($inf_ary['site_rdct']){ return redirect('/'.$url_area); }
      //3＃コンテンツページは存在するか
      if(!in_array($url_contents,$def_mens_ary['tab'])){
         //サイト（ショップ）トップにリダイレクト？
         return redirect('/'.$url_area.'/'.$url_siteid);
      }
      //4＃サイト(ショップ)トップページはコンテンツページじゃないのでリダイレクト？
      if($url_contents == $def_mens_ary['tab']['10']){
         return redirect('/'.$url_area.'/'.$url_siteid);
      }
      //5 #許可したコンテンツの個別ページかどうか
      if(!in_array($url_contents, ['cast_blog','site_news','cast'])){
         return redirect('/'.$url_area.'/'.$url_siteid.'/'.$url_contents);
      }
      //リダイレクト関数からの引数引継ぎ
      //urlパラメータよりidのみにした
      $site_id = $inf_ary['site_id'];
      //コンテンツ共通グループ
      //変数
      $casts = []; $sid_cid_imgs = []; $cast_id_ary = [];
      $dts = []; $scd = []; $close_time = 0000;
      $shops = []; $sys = [];
      $cb = []; $cbim = []; $cb1im = []; $cb_ary = []; $cb1_ary = []; $cbid_ary = [];
      $now_date_time = ''; $nxtcb = []; $prvcb = [];
      $spn = []; $spnext = []; $spprev = [];
      $mv = []; $qu = []; $an = []; $cbid = ''; $npg = 0; $ctotal = 0;
      //個別ページ適用コンテンツ名
      if(in_array($url_contents, ['scd','cast','cast_blog','site_news'])){
         //キャスト画像情報
         $sid_cid_imgs = EstsServices::Cast_Images([$site_id]);   
         //キャスト情報
         $casts = EstsServices::Site_Casts([$site_id]);
         //cast_id array
         foreach($casts as $cid => $cary){
            $cast_id_ary[$cid] = $cid;
         }
         //6 #存在してるコンテンツIDかどうか
         if('cast_blog' == $url_contents){
            $cbid = $request->query('id');
            if(!in_array($url_contentid, $cast_id_ary)){
               return redirect('/'.$url_area.'/'.$url_siteid.'/'.$url_contents);
            }
            if(!empty($cbid)){ //ブログIDがあったとき
               $cb_ary = EstsServices::CastBlogId($cbid);
               if(count($cb_ary) == 0){
                  return redirect('/'.$url_area.'/'.$url_siteid.'/'.$url_contents.'/'.$url_contentid);
               }
            }
         } else
         if('site_news' == $url_contents){
            //ショップニュース個別展開
            $spn = EstsServices::ShopNewsId([$site_id],$url_contentid);
            if(!$spn){
               return redirect('/'.$url_area.'/'.$url_siteid.'/'.$url_contents);
            }
         } else
         if('cast' == $url_contents){
            //キャスト個別
            if(!in_array($url_contentid, $cast_id_ary)){
               return redirect('/'.$url_area.'/'.$url_siteid.'/'.$url_contents);
            }
         }
         //閉店切り替え日時処理
         $close_time = $sites[$site_id]['close_time'] ?? 0000;
         //日付切り替え取得
         $day_change = EstsServices::ScdDayChange($close_time);
         //現在の日付
         $now_date = $day_change['now_date'];
         //現在の日付
         $now_date_time = $day_change['now_date_time'];
      }
      //6段階のリダイレクトここまで

      /*////////////////////////
   |--[リダイレクトセクションここまで]
      // /////////////////////// */
      //html 共用meta
      $meta_tag = EstsTagServices::indexMeta();
      $css_tag = EstsTagServices::rdCss();
      //サイト情報
      $sites = $inf_ary['sites'];
      //m_tabコンテンツ名
      $m_tab = EstsServices::MenuTab($site_id);
      //コンテンツページIDを取得
      $m_tab_id = '';
      foreach($def_mens_ary['tab'] as $tid => $tkey){
         if($url_contents == $tkey){
            $m_tab_id = $tid;
         }
      }
      /*/////////////////////////////
   |--[コンテンツページ振り分け]
      //////////////////////////// */

      //コンテンツ切り分け
      //if('scd' == $url_contents){
         //設定日数分の日付を取得
         $dts = $day_change['def_date_ary'];
         //取得日付から
         $sdate = $day_change['start_date'];
         //取得日付まで
         $edate = $day_change['end_date'];
         //出勤ページ[d>cid] [no>cid]
         $scd = EstsServices::Schedule($cast_id_ary,$sdate, $edate);
      //} else 
      if('cast_blog' == $url_contents){
         //キャストブログ
         if(!isset($cbid)){ //ブログIDが無かったとき
            $cb_ary = EstsServices::CastIdBlog($url_contentid,$def_mens_ary['memo']['per']['cast_blog']);
            $cb1_ary['data'][0] = $cb_ary['data'][0];
         } else { //あったとき
            $cb1_ary['data'] = EstsServices::CastBlogId($cbid);
            //現在の記事は何ページ目か計算
            $ctotal = EstsServices::CastBlogIdPgCount($url_contentid,$cb1_ary['data'][0]['published_at']);
            $npg = intval(floor($ctotal / $def_mens_ary['memo']['per']['cast_blog'])) + 1;
            $cb_ary = EstsServices::CastIdBlogPage($url_contentid,$def_mens_ary['memo']['per']['cast_blog'],$npg);
         }
         //次のブログ
         $nxtcb = EstsServices::CastBlogIdNext($cb1_ary['data'][0]['cast_id'],$cb1_ary['data'][0]['published_at']);
         //前のブログ
         $prvcb = EstsServices::CastBlogIdPrev($cb1_ary['data'][0]['cast_id'],$cb1_ary['data'][0]['published_at']);
         //cast blogid array
         foreach($cb_ary['data'] as $cbno => $cbary){
            $cbid_ary[$cbary['id']] = $cbary['id'];
         }
         //ブログ添付画像
         $cbim = EstsServices::CastBlogImage($cbid_ary);
         $cb1im = EstsServices::CastBlogImage([$cb1_ary['data'][0]['id']]);


      } else
      if('site_news' == $url_contents){
         //ショップニュース個別
         $spnext = EstsServices::ShopNewsIdNext([$site_id],$spn[0]['published_at']);
         $spprev = EstsServices::ShopNewsIdPrev([$site_id],$spn[0]['published_at']);
      } else 
      if('cast' == $url_contents){
         $cb_ary = EstsServices::CastBlog([$url_contentid], $now_date_time, 5);
         if($cb_ary['defo']['total'] >= 1){
            foreach($cb_ary['defo']['data'] as $cbno => $cbary){
               $cbid_ary[$cbary['id']] = $cbary['id'];
            }
            $cbim = EstsServices::CastBlogImage($cbid_ary);
         }
         //movie
         $mv = EstsServices::MoviesCastId($url_contentid);
         //question
         $qu = EstsServices::SiteIdQuestion($site_id);
         //answer
         $an = EstsServices::CastIdAnswer($url_contentid);
      }
      //page contents define
      $pgcon = $def_mens_ary['pgcon'];
      /*///////////////////////////
   |--[コンテンツページ振り分けここまで]
      ///////////////////////////*/

      //casts>source_name
        
      return view('ests.site_contentid'
                    ,compact(
                         'site_id'      //サイトID
                        ,'meta_tag'     //共通メタタグ
                        ,'css_tag'      //共通css
                        ,'def_mens_ary' //エステポータル定義
                        ,'casts'        //キャスト情報
                        ,'sid_cid_imgs' //キャスト画像情報
                        ,'now_date_time'
                        ,'dts'          //出勤の日付
                        ,'scd'          //出勤情報
                        ,'sites'        //サイト情報
                        ,'close_time'   //閉店時刻
                        ,'sys'          //料金情報 
                        ,'url_siteid'
                        ,'url_area'
                        ,'url_contents' //コンテンツページWEBキー
                        ,'url_contentid'
                        ,'m_tab'        //コンテンツページ名
                        ,'m_tab_id'     //カテゴリーページマスターID
                        ,'cb'
                        ,'cb_ary'
                        ,'cbim'
                        ,'cb1im'
                        ,'cbid'
                        ,'nxtcb'
                        ,'prvcb'
                        ,'pgcon'
                        ,'spn'          //記事本体
                        ,'spnext'       //次の記事
                        ,'spprev'       //前の記事
                        ,'mv'
                        ,'qu'           //アンケートお題
                        ,'an'           //アンケート回答
                        ,'npg'
                        ,'ctotal'
                        ,'cb1_ary'
                  ));
   }
}
