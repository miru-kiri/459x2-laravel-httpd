<?php
/*


*/

require_once("../rd_post.php");

function mrt_xhr_post_mv_count(){
   $tag = "ok";
   //$_POST['mvid'] = "TWc9PQ==";
   $mvid_get = htmlspecialchars($_POST['mvid']);
   $mvid = mrt_fnc_hukugouka($mvid_get);
   $mvid = mrt_fnc_hukugouka($mvid);
   if($mvid != "" && mrt_fnc_suzinomi ($mvid)){
      //$db_ary = array('db_key' => "csm_sys_file", 'tbl_key' => "tbl_upfile");
      $sc_fld_ary = array('id' => $mvid);
      //$sc_cnt_fld = "fld15";
      //$mv_ct_ary = csm_sys_counter_ary($db_ary, $sc_fld_ary, $sc_cnt_fld);
      $mv_ct_ary = csm_sys_upfile_counter_ary($sc_fld_ary);
   }
   //print_r($mv_ct_ary);
   unset($mvid_get); unset($mvid); unset($mv_ct_ary);
   unset($db_ary); unset($sc_fld_ary); unset($sc_cnt_fld);
   print $tag;
   unset($tag);
}

mrt_xhr_post_mv_count();

?>