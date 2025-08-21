<?php
/*


*/

require_once("../rd_post.php");

function mrt_xhr_post_4cm_select(){
   global $mrt_cm4_title_ary;
   $cm4_ext_area_ary = array(
                                  'shop_area_new'           => "終了後-&gt;エリア内新着順",
                                  'shop_area_rnd'           => "終了後-&gt;エリア内ランダム",
                                  'shop_area_free_new'      => "終了後-&gt;エリア内フリー新着順",
                                  'shop_area_girls_new'     => "終了後-&gt;エリア内女子新着順",
                                  'shop_area_free_rnd'      => "終了後-&gt;エリア内フリーランダム",
                                  'shop_area_girls_rnd'     => "終了後-&gt;エリア内女子ランダム"
                                  
                             );
   $cm4_ext_genre_ary = array(
                                  'shop_genre_new'          => "終了後-&gt;ジャンル内新着順",
                                  'shop_genre_rnd'          => "終了後-&gt;ジャンル内ランダム",
                                  'shop_genre_free_rnd'     => "終了後-&gt;ジャンル内フリーランダム",
                                  'shop_genre_girls_rnd'    => "終了後-&gt;ジャンル内女子ランダム",
                                  'shop_genre_free_rnd'     => "終了後-&gt;ジャンル内フリーランダム",
                                  'shop_genre_girls_rnd'    => "終了後-&gt;ジャンル内女子ランダム"
                                  
                             );
   $cm4_ext_ary = $cm4_ext_area_ary + $cm4_ext_genre_ary;

   $cm4_key = htmlspecialchars($_POST['mvcm']);
   $cm4_dmn_id = htmlspecialchars($_POST['mvdmn']);
   $cm4_shp_id = htmlspecialchars($_POST['mvshp']);
   $tag = "";
   
   if(array_key_exists($cm4_key, $cm4_ext_ary)){
      if(array_key_exists($cm4_key, $cm4_ext_area_ary)){
         $cm4_ary = csm_mv_cm4_area_ary($cm4_shp_id, $cm4_key);
      } else {
         $cm4_ary = csm_mv_cm4_genre_ary($cm4_shp_id, $cm4_key);
      }
   } else {
      $cm4_ary = csm_sys_upfile_new_4cm_ary(array('cm4_key' => $cm4_key, 'cm4_dmn_id' => $cm4_dmn_id));
   }
   
   //$tag .= "ok";
   $tag .= "<div class=\"mrt_4cm_div\">\n";
   $tag .= "<div class=\"mrt_4cm_title_div\">";
   //$tag .= "<img src=\"./css_js/xxx_image/cm4_title02.png\" class=\"mrt_4cm_title_img\"/>";
   $tag .= $mrt_cm4_title_ary[$cm4_key];
   $tag .= "</div>\n";
   //$tag .= "<div class=\"mrt_4cm_box_div\">\n";
   $tag .= "<ul class=\"mrt_4cm_ul\">\n";
   foreach($cm4_ary as $cm4_id => $c4_ary){
      $jpg_url = "https://dogo459.com".$c4_ary['top_path']."/".$c4_ary['image'];
      $mv_jikoku = mrt_fnc_byou_jikoku($c4_ary['file_time']);
      $an_id = mrt_fnc_angouka($cm4_id);
      $an_id = mrt_fnc_angouka($an_id);
      $tag .= "<li>\n";
      $tag .=    "<table class=\"mrt_4cm_tbl\">\n";
      $tag .=       "<tr>\n";
      $tag .=          "<td class=\"mrt_4cm_img_td\">";
      //$tag .=                "<a href=\"http://459x.com/_mrt_/_csm_movie_/?mvid=".$an_id."&ag=sm\">";
      $tag .=             "<div title=\"".$c4_ary['file_come']."\" class=\"mrt_4cm_img_div\" mvurl=\"".$c4_ary['url']."\" mvlink=\"https://459x.com/_mrt_/_csm_movie_/?mvid=".$an_id."&ag=&ap=on\" ";
      $tag .= " style=\"background-position: center; background-image: url('".$jpg_url."'); background-repeat: no-repeat; background-size: contain;\" ";
      $tag .= ">";

      $tag .=                "<img src=\"./css_js/xxx_image/cm4_play02.png\" class=\"mrt_4cm_play_img\"/>";
      //$tag .=                "<img src=\"".$jpg_url."\" class=\"mrt_4cm_img\"/>";

      $tag .=             "</div>";
      //$tag .=                "</a>";
      //$tag .=             "<div class=\"mrt_4cm_play_img_div\" mvurl=\"".$c4_ary['url']."\">";
      //$tag .=             "</div>";
      $tag .=          "</td>\n";
      $tag .=       "</tr>\n";
      $tag .=       "<tr>\n";
      $tag .=          "<td class=\"mrt_4cm_jikoku_td\">";
      
      $tag .= "<span class=\"mrt_4cm_jikoku_span\">".$mv_jikoku."</span>";
      
      $tag .=          "</td>\n";
      $tag .=       "</tr>\n";
      $tag .=       "<tr>\n";
      $tag .=          "<td class=\"mrt_4cm_title_td\">";
      
      $tag .= "<span class=\"mrt_4cm_title_span\">".$c4_ary['file_title']."</span>";
      
      $tag .=          "</td>\n";
      $tag .=       "</tr>\n";
      $tag .=    "</table>\n";
      $tag .= "</li>\n";
   }
   $tag .= "</ul>\n";
   //$tag .= "</div>\n";
   $tag .= "</div>\n";
   
   unset($cm4_ary); unset($mv_jikoku); unset($mrt_cm_title_ary); unset($cm4_key); unset($cm4_ext_ary);
   unset($cm4_ext_area_ary); unset($cm4_ext_genre_ary);
   print $tag;
   unset($tag);
}




/*
<!DOCTYPE html>
<html lang="ja">
<head>
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8" />
<META NAME="robots" content="noindex, nofollow, noarchive" />
<title>動作チェック</title>
</head>
<body>
   print_r (mrt_xhr_post_4cm_select()); //csm_sys_cnf_area_ary
   //print_r(csm_sys_cnf_area_ary());
</body>
</html>
*/
mrt_xhr_post_4cm_select();
?>
