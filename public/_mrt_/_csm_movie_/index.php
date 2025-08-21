<?php

//このファイルの存在場所定義
   $dir_name = dirname(__FILE__)."/";
   //db tbl list read
   require_once($dir_name."conf/db_tbl_list.php");
   //
   require_once($dir_name."conf/mrt_db_func.php");
   require_once($dir_name."conf/mrt_fnc.mrt");
   require_once($dir_name."conf/mrt_mv_func.php");
   
   $ag = mrt_fnc_huriwake();
   
   if($_GET['ag'] != ""){
      $ag_chk = htmlspecialchars($_GET['ag']);
      $ag_chk_ary = array('pc','smp','ktai');
      if(in_array($ag_chk, $ag_chk_ary)){
         $ag = $ag_chk;
      }
   }
   $ap_swt = "off";
   if($_GET['ap'] != ""){
      $ap_chk = htmlspecialchars($_GET['ap']);
      if($ap_chk == "on"){
         $ap_swt = "on";
      } else {
         $ap_swt = "off";
      }
   }
   unset($ag_chk_ary); unset($ag_chk); unset($ap_chk);
   $chk_ary = array(); $chk_ary['torf'] = false; $chk_ary['val'] = "";
   $mvid_get = htmlspecialchars($_GET['mvid']);
   $mvid = mrt_fnc_hukugouka($mvid_get);
   $mvid = mrt_fnc_hukugouka($mvid);
   if($mvid != "" && mrt_fnc_suzinomi ($mvid)){
      $mv_res = csm_sys_upfile_movie_play(array('id' => $mvid));
   }
   if(count($mv_res) == 0 || $ag == "ktai"){
      if($mvid != "" && mrt_fnc_suzinomi ($mvid) && $ag != "ktai"){
         $chk_ary['torf'] = true;
         $chk_ary['val'] = "削除されたコンテンツです。";
      } else {
         header('location: http://cosmo-group.co.jp');
         exit();
      }
   }
   //swing array
   $mrt_swg_type_ary = array(
                                 'none'            => "",
                                 'play_ue_1res'    => "res1_ary",
                                 'play_ue_all_res' => "enter_del"
                                 
                              );
   $tsw = "none";
   $tsw_tag = "";
   if(is_array($mv_res)){
      if(array_key_exists($mv_res['swing_select'], $mrt_swg_type_ary)){
         $txt_ary = mrt_fnc_text_enter_ary($mv_res['file_come']);
         if("res1_ary" == $mrt_swg_type_ary[$mv_res['swing_select']]){
            if(is_array($txt_ary)){
               foreach($txt_ary as $tx_no => $tx_vl){
                  $tsw_tag .= "<div class=\"mrt_swing_txt_div\">";
                  $tsw_tag .= $tx_vl;
                  $tsw_tag .= "</div>";
               }
               $tsw = "res1_ary";
            }
            
         } else 
         if("enter_del" == $mrt_swg_type_ary[$mv_res['swing_select']]){
            $txt = mrt_fnc_enter_null($mv_res['file_come']);
            if($ag == "pc"){
               $tsw_tag  = "<marquee class=\"mrt_swing_txt_div\">";
               $tsw_tag .= $txt;
               $tsw_tag .= "</marquee>";
            } else {
               $tsw_tag  = "<div id=\"marquee\">";
               $tsw_tag .= "<div class=\"mrt_swing_txt_div\">";
               $tsw_tag .= $txt;
               $tsw_tag .= "</div>";
               $tsw_tag .= "</div>";
            }
            $tsw = "enter_del";
         }
         unset($txt_ary);
      }
   }
   if($tsw_tag == ""){$tsw = "none";}

   $mvurl = str_replace(array("http:"), array("https:"), $mv_res['url']);
if($ag == 'pc'){
   print "<html>\n";
   print "<head>\n";
   print "<META HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=utf-8\" />\n";
   print "<script src=\"./css_js/mrt_jq1112.js\" type=\"text/javascript\"></script>\n";
   print "<script src=\"./css_js/mrt_jq1114-ui.js\" type=\"text/javascript\"></script>\n";
   print "<script src=\"./css_js/mrt_jq_fnc_pc.js\" type=\"text/javascript\"></script>\n";
   print "<!--[if !IE]>-->";
   print "<script src=\"./css_js/mrt_jq_pc2.js\" type=\"text/javascript\"></script>\n";
   print "<!--<![endif]-->";
   print "<!--[if IE]>";
   print "<script src=\"./css_js/mrt_jq_pc9.js\" type=\"text/javascript\"></script>\n";
   print "<![endif]-->";
   print "<link rel=\"stylesheet\" href=\"./player/ifr.css\" type=\"text/css\">\n";
   print "<link rel=\"stylesheet\" type=\"text/css\" href=\"./css_js/mrt_pc_any01.css\" />\n";
   print "<title></title>\n";
   print "</head>\n";
   print "<body>\n";
   
   if($chk_ary['torf']){
      print $chk_ary['val'];
   } else {

      print "<div class=\"mrt_xxx_video_div\" mvnm=\"".$mvid_get."\" mvtm=\"".$mv_res['file_time']."\" mvply=\"".$ap_swt."\" mvcm=\"".$mv_res['cm_select']."\" mvdmn=\"".$mv_res['domain_id']."\" mvshp=\"".$mv_res['shop_id']."\" mvag=\"".$ag."\" mvtsw=\"".$tsw."\" tsw_flg=\"none\">";
      $ck_time = time();
      print "<!--[if !IE]>-->";
      print "<video id=\"mrt_xxx_video\"  poster=\"https://dogo459.com".$mv_res['top_path']."/".$mv_res['image']."?ck=".$ck_time."\" width=\"100%\" height=\"100%\" preload=\"none\">\n";
      print "<source src=\"".$mvurl."\"  type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"' />\n";
      print "<!--<![endif]-->";
      print "<div class=\"mrt_brw_new_mess_div\">最新のブラウザでご覧ください。</div>\n";

print <<<MRT
      <object type="application/x-shockwave-flash" data="./player/mrt_player_mx.swf" id="mrt_mv_obj">
         <param name="movie" value="./player/mrt_player_mx.swf" />
MRT;
      print "<param name=\"FlashVars\" value=\"flv=https://dogo459.com".$mv_res['top_path']."/".$mv_res['file_name'].".flv\" /></object>";

/*
print "<object width=\"100%\" height=\"100%\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0\" data=\"./player/mrt_flv_player.swf\" type=\"application/x-shockwave-flash\" id=\"mrt_obj_movies\">\n";
print "<param name=\"movie\" value=\"./player/mrt_flv_player.swf\" />\n";
print "<param name=\"allowFullScreen\" value=\"true\" />\n";
print "<param name=\"FlashVars\" value=\"flvmov=http://dogo459.com/".$mv_res['top_path']."/".$mv_res['file_name'].".flv\" />\n";
//print "<embed width=\"100%\" height=\"100%\" src=\"./player/mrt_flv_player.swf\" flashvars=\"flvmov=http://dogo459.com".$mv_res['top_path']."/".$mv_res['file_name'].".flv\" allowFullScreen=\"true\" />\n";
print "</object>\n";

      //print "<div id=\"mrt_obj_movie\" mvurl=\"http://dogo459.com/".$mv_res['top_path']."/".$mv_res['file_name'].".flv\"></div>\n";
*/
      print "<!--[if !IE]>-->";
      print "</video>\n";
      print "<!--<![endif]-->";

      //<--swing text
      if($tsw != "none"){
         print "<div id=\"mrt_mv_ue_box_div\">";
         print    "<div id=\"mrt_text_swg_box_div\">";
         print    $tsw_tag;
         print    "</div>\n";
         print "</div>\n";
      }
      //-->

      print "</div>\n";
   }

   print "</body>\n";
   print "</html>\n";
////////////////////
} else 
if($ag == 'smp'){
print <<<MRT
<html>
<head>
<META charset="utf-8" />
<script src="./css_js/mrt_jq1112.js" type="text/javascript"></script>
<script src="./css_js/mrt_jq_fnc_smp.js" type="text/javascript"></script>
<script src="./css_js/mrt_jq_smp2.js" type="text/javascript"></script>
<link rel="stylesheet" href="./player/ifr.css" type="text/css">
<link rel="stylesheet" type="text/css" href="./css_js/mrt_smp_any01.css" />
<title></title>
</head>
<body>
MRT;

   if($chk_ary['torf']){
      print $chk_ary['val'];
   } else {
      print "<div class=\"mrt_xxx_video_div\" mvnm=\"".$mvid_get."\" mvtm=\"".$mv_res['file_time']."\" mvply=\"".$ap_swt."\" mvcm=\"".$mv_res['cm_select']."\" mvdmn=\"".$mv_res['domain_id']."\" mvshp=\"".$mv_res['shop_id']."\" mvag=\"".$ag."\" mvtsw=\"".$tsw."\" tsw_flg=\"none\">";
      $ck_time = time();
      print "<video id=\"mrt_xxx_video\" poster=\"https://dogo459.com".$mv_res['top_path']."/".$mv_res['image']."?ck=".$ck_time."\" width=\"100%\" height=\"100%\">\n";
      print "<source src=\"".$mvurl."\" type=\"video/mp4\" />\n";
      print "<div class=\"mrt_brw_new_mess_div\">最新のブラウザでご覧ください。</div>\n";
      print "</video>\n";


      //<--swing text
      if($tsw != "none"){
         print "<div id=\"mrt_mv_ue_box_div\">";
         print    "<div id=\"mrt_text_swg_box_div\">";
         print    $tsw_tag;
         print    "</div>\n";
         print "</div>\n";
      }
      //-->


      print "</div>\n";
   }
   print "</body>\n";
   print "</html>\n";
} else 
if($ag == 'ktai'){
   
}
   unset($mv_res); unset($mvid); unset($chk_ary); unset($ag); unset($ap_swt); unset($mrt_swg_type_ary);
?>
