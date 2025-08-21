<?php
/*

   movie function


*/


//ショップＩＤと一致するエリアのofficial domainＩＤを配列にして返す
function csm_mv_shopid_areakey_dmnid_ary($shop_id = ""){
   $mrt_ary = array();
   $are_shop_ary = array();
   if(mrt_fnc_suzinomi($shop_id)){
      $ts_area_ary = csm_sys_mv_shopid_areakey_ary(array('sc_fld' => array('cid' => $shop_id)));
      if(is_array($ts_area_ary)){
         foreach($ts_area_ary as $ts_are_no => $ts_are_key){
            $ps_are_ary = csm_sys_mv_shop_area_seek($ts_are_key);
            $are_shop_ary += $ps_are_ary;
         }
         if(is_array($are_shop_ary)){
            $mrt_ary = csm_sys_mv_shop_dmn_ary($are_shop_ary);
         }
      }
   }
   unset($shop_id); unset($ts_area_ary); unset($ps_are_ary); unset($are_shop_ary);
   return $mrt_ary;
}
//ショップＩＤと一致するジャンルのofficial domainＩＤを配列にして返す
function csm_mv_shopid_genrekey_dmnid_ary($shop_id = ""){
   $mrt_ary = array();
   $gen_shop_ary = array();
   if(mrt_fnc_suzinomi($shop_id)){
      $ts_genre_ary = csm_sys_mv_shopid_genrekey_ary(array('sc_fld' => array('cid' => $shop_id)));
      if(is_array($ts_genre_ary)){
         foreach($ts_genre_ary as $ts_gen_no => $ts_gen_key){
            $ps_gen_ary = csm_sys_mv_shop_genre_seek($ts_gen_key);
            $gen_shop_ary += $ps_gen_ary;
         }
         if(is_array($gen_shop_ary)){
            $mrt_ary = csm_sys_mv_shop_dmn_ary($gen_shop_ary);
         }
      }
   }
   unset($shop_id); unset($ts_genre_ary); unset($ps_gen_ary); unset($gen_shop_ary);
   return $mrt_ary;
}


function csm_mv_cm4_area_ary($shop_id = "", $cm4_key = ""){
   $mrt_ary = array();
   if($shop_id != "" && $cm4_key != ""){
      $dmn_id_ary = array();
      $dmn_id_ary = csm_mv_shopid_areakey_dmnid_ary($shop_id);
      $mrt_ary = csm_sys_mv_dmn_cm4_ary($dmn_id_ary, $cm4_key);
   }
   unset($shop_id); unset($cm4_key);
   unset($dmn_id_ary);
   return $mrt_ary;
}
function csm_mv_cm4_genre_ary($shop_id = "", $cm4_key = ""){
   $mrt_ary = array();
   if($shop_id != "" && $cm4_key != ""){
      $dmn_id_ary = array();
      $dmn_id_ary = csm_mv_shopid_genrekey_dmnid_ary($shop_id);
      $mrt_ary = csm_sys_mv_dmn_cm4_ary($dmn_id_ary, $cm4_key);
   }
   unset($shop_id); unset($cm4_key);
   unset($dmn_id_ary);
   return $mrt_ary;
}

?>
