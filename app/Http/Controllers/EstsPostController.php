<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//エステポータルの共通クラス呼び出し
use App\Services\EstsServices;
use App\Services\EstsTagServices;

class EstsPostController extends Controller
{
   public function index(Request $request)
   {
      $kkk = ''; //初期化
      if($request->get('kirino') == '勉強中'){
         $kkk = '<div class="yokokara"><div class="unkoja">
         <button id="close">×</button>
         <br />
         <br />
         <div>さて誰だったかな？</div>
         <br />'
         .'<div>'.$request->get('test').'</div>'
         .'</div></div>';
      } else 
      if($request->get('kirino') == 'どうだ'){
         $kkk = '<div class="yokokara"><div class="unkoja">
         <button id="close">×</button>
         <br />
         <br />
         <div>変わったかな？</div>
         <br />
         </div></div>
         ';
      } else
      if($request->get('kirino') == '要素変更'){
         $kkk = $request->get('test');
      }

      return response()->json($kkk);
   }
}