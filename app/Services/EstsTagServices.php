<?php

namespace App\Services;

use Illuminate\Http\Request;

/********************************
 * 
 * html tag
 * 
 * 
 */
class EstsTagServices
{
   public static function indexMeta()
   {
      $meta = '<meta charset="utf-8" />'."\n"
         .'<META NAME="robots" content="noindex, nofollow, noarchive" />'."\n"
         //.'<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />'."\n"
         .'<meta name="viewport" content="width=device-width, initial-scale=1.0" />'."\n"
         .'<meta name="csrf-token" content="'.csrf_token().'" />'."\n";
      return $meta;
   }
   /**
    * CSS
    * まとめて使いたいときは便利かも？
    * @return string
    */
   public static function rdCss()
   {
      $tag = '<link href="'.asset('css/ests/bstrp530.css').'" rel="stylesheet">'."\n"
      .'<link rel="stylesheet" href="'.asset('css/site/menz_est.css?'.time()).'" />'."\n"
      .'<link rel="stylesheet" href="'.asset('css/ests/any.css?'.time()).'" />'."\n"
      //ブートストラップアイコン追加　テスト krn
      .'<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />'."\n";

      return $tag;
   }
   /**
    * JS
    * まとめて使いたいときは便利かも？
    * @return string
    */
   public static function rdJs()
   {
      $tag = '<script src="'.asset('js/ests/bstrp530.js').'"></script>'."\n"
      .'<script src="'.asset('js/ests/jq371.min.js').'"></script>'."\n"
      .'<script src="'.asset('js/ests/any.js?'.time()).'" charset="utf-8"></script>'."\n";
      
      return $tag;
   }
   
}