<?php

namespace App\Http\Controllers;

class MiruTestController extends Controller
{
   public function miru_test(Request $request){
      $areaId = $request->area_id ?? 0;
      if ($areaId == 1){
         $html .= "<div>成功</div>";
      }
   }
}