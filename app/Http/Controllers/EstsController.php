<?php

namespace App\Http\Controllers;

//エステポータルの共通クラス呼び出し
use App\Services\EstsServices;
use App\Services\EstsTagServices;

use Illuminate\Http\Request;


class EstsController extends Controller
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
         /*************
         * ジャンル情報
         *************/
         $genres = EstsServices::genres();
         $genre_sitesAry = EstsServices::Ests_sitesAry();
         //サイトIDをキーにしてジャンルを配列化
         $site_genidAry = EstsServices::SiteIdKey_GenreIdAry();
         //サイトと紐づけられたジャンルのみ配列
         $mens_genres = EstsServices::Site_GenreIdAry();
        /*****************************
         * エリア情報
         *****************************/
         $areas = EstsServices::Areas();
         //サイトと紐づけられたエリアのみ配列
         $mens_areas = EstsServices::Site_AreaIdAry();
         /***********
         * ショップ情報
         * opening_text=オープンラスト時刻
         ***********/
         $shops = EstsServices::shops();
         /************
         * サイト情報
         ************/
         $sites = EstsServices::sites();
        

        //meta tag
        $meta_tag = EstsTagServices::indexMeta();
        //css
        $css_tag = EstsTagServices::rdCss();
        $js_tag  = EstsTagServices::rdJs();
         //bladeへ返却する変数
         return view('ests.index',compact(
                                  'areas'
                                 ,'sites'
                                 , 'genres'
                                 , 'genre_sitesAry'
                                 , 'site_genidAry'
                                 , 'shops'
                                 , 'mens_genres'
                                 , 'mens_areas'
                                 , 'meta_tag'
                                 , 'css_tag'
                                 , 'js_tag'
                                
                                 ));
    }
}
