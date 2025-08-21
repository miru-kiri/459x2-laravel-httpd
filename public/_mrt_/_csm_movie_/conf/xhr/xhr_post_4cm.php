<?php
/*


*/

require_once("../rd_post.php");

function mrt_xhr_post_4cm(){
   global $mrt_cm_title_ary;
   $cm4_key = htmlspecialchars($_POST['mvcm']);
   $tag = "";
   $cm4_ary = csm_sys_upfile_new_4cm_ary(array('cm4_key' => $cm4_key));
   //$tag .= "ok";
   $tag .= "<div class=\"mrt_4cm_div\">\n";
   $tag .= "<div class=\"mrt_4cm_title_div\"><img src=\"./css_js/xxx_image/cm4_title02.png\" class=\"mrt_4cm_title_img\">";
   $tag .= $mrt_cm_title_ary[$cm4_key];
   $tag .= "</div>\n";
   //$tag .= "<div class=\"mrt_4cm_box_div\">\n";
   $tag .= "<ul class=\"mrt_4cm_ul\">\n";
   foreach($cm4_ary as $cm4_id => $c4_ary){
      $jpg_url = "https://dogo459.com".$c4_ary['top_path']."/".$c4_ary['image']."?rc=".time();
      $mv_jikoku = mrt_fnc_byou_jikoku($c4_ary['file_time']);
      $an_id = mrt_fnc_angouka($cm4_id);
      $an_id = mrt_fnc_angouka($an_id);
      $tag .= "<li>\n";
      $tag .=    "<table class=\"mrt_4cm_tbl\">\n";
      $tag .=       "<tr>\n";
      $tag .=          "<td class=\"mrt_4cm_img_td\">";
      //$tag .=                "<a href=\"https://459x.com/_mrt_/_csm_movie_/?mvid=".$an_id."&ag=sm\">";
      $tag .=             "<div class=\"mrt_4cm_img_div\" mvurl=\"".$c4_ary['url']."\" mvlink=\"https://459x.com/_mrt_/_csm_movie_/?mvid=".$an_id."&ag=sm\"";
      $tag .= " style=\"background-position: center; background-image: url(\"".$jpg_url."\"); background-repeat: no-repeat; background-size: contain;\" ";
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
   
   unset($cm4_ary); unset($mv_jikoku); unset($mrt_cm_title_ary); unset($cm4_key);
   print $tag;
   unset($tag);
}

mrt_xhr_post_4cm();

?>