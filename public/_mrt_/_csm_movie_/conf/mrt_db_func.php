<?php
/*

   

*/
//DB接続
function csm_db_con ($db_key){
   global $db_list;
   $sqlt_db_dir = "";
   //pdo conect
   if($db_list[$db_key]['info'] == 'sqlite'){
      $pdo_res = "sqlite:".$sqlt_db_dir.$db_list[$db_key]['name'];
      $db = new PDO($pdo_res);
      //chmod($db_name,0666);
      //bufferを利用可能にする。php5.0-5.1
      //$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
   } else if($db_list[$db_key]['info'] == 'mysql'){
      $pdo_res = 'mysql:host='.$db_list[$db_key]['url'].';dbname='.$db_list[$db_key]['name'];
      $db = new PDO($pdo_res, $db_list[$db_key]['user'], $db_list[$db_key]['pass'] );
      $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); 
   }
   unset($db_list); unset($sqlt_db_dir);
   return $db;
}

function csm_db_tbl_list($db_key,$tbl_key){
   global $tbl_list;
   $mrt_ary = array();
   foreach($tbl_list[$db_key][$tbl_key] as $tn_key => $tn_val){
      $mrt_ary[$tn_key] = $tn_val;
   }
   unset($tbl_list);
   return $mrt_ary;
}

function csm_sys_upfile_movie_play($any_ary = array()){
   $mrt_ary = array();
   $db_key = 'x459x_movie'; $tbl_key = 'tbl_upfile';
   $db = csm_db_con($db_key);
   $sql  = "SELECT * FROM `".$tbl_key."` ";
   $sql .= " WHERE `id` = '".$any_ary['id']."' AND `fld29` = 'none'";
   $sql .= ";";
   $res = $db -> prepare($sql);
   $res -> execute();
   while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
      $mrt_ary['file_id']         = $rc['id'];
      $mrt_ary['shop_id']         = $rc['fld1'];
      $mrt_ary['domain_id']       = $rc['fld2'];
      $mrt_ary['domain_name']     = $rc['fld3'];
      $mrt_ary['staff_id']        = $rc['fld4'];
      $mrt_ary['genre']           = $rc['fld5'];
      $mrt_ary['file_name']       = $rc['fld6'];
      $mrt_ary['exten']           = $rc['fld7'];
      $mrt_ary['image']           = $rc['fld8'];
      $mrt_ary['top_path']        = $rc['fld9'];
      $mrt_ary['url']             = $rc['fld10'];
      $mrt_ary['staff_name']      = $rc['fld11'];
      $mrt_ary['file_title']      = $rc['fld12'];
      $mrt_ary['file_come']       = $rc['fld13'];
      $mrt_ary['file_time']       = $rc['fld14'];
      $mrt_ary['file_count']      = $rc['fld15'];
      $mrt_ary['file_nm_count']   = $rc['fld16'];
      $mrt_ary['file_nw_count']   = $rc['fld17'];
      $mrt_ary['file_om_count']   = $rc['fld18'];
      $mrt_ary['file_ow_count']   = $rc['fld19'];
      $mrt_ary['cm_select']       = $rc['fld20'];
      $mrt_ary['src_tag']         = $rc['fld21'];
      $mrt_ary['swing_select']    = $rc['fld22'];
      $mrt_ary['cm_hid']          = $rc['fld28'];
      $mrt_ary['hid']             = $rc['fld29'];
      $mrt_ary['time']            = $rc['fld30'];
   }
   unset($db); unset($sql); unset($res); unset($db_key); unset($tbl_key); unset($mn_ary);
   unset($any_ary);
   return $mrt_ary;
}

//指定したテーブルの複数フィールドを検索して複数のフィールドを書き換え
function csm_sys_fld_src_ary_rw_ary ($db_key, $tbl_key, $src_fld_ary = array(), $rw_fld_ary = array()){
   $mrt_ary = array(); $mrt_ary['val'] = ""; $mrt_ary['ary'] = array(); $mrt_ary['torf'] = false; $mrt_ary['sql'] = "";
   $sql = "UPDATE `".$tbl_key."` SET ";
   
   $rw_c = count($rw_fld_ary);
   $w = '1';
   foreach($rw_fld_ary as $rw_fl_nm => $rw_fl_vl){
      $sql .= " `".$rw_fl_nm."` = '".$rw_fl_vl."'";
      if($w < $rw_c){$sql .= ",";}
      $w++;
   }
   
   $sql .= " WHERE ";
   $sc_c = count($src_fld_ary);
   $d = '1';
   foreach($src_fld_ary as $fl_nm => $fl_vl){
      $sql .= " `".$fl_nm."` = '".$fl_vl."'";
      if($d < $sc_c){$sql .= " AND ";}
      $d++;
   }
   $sql .= ";";
   $mrt_ary['sql'] = $sql;
   $db = csm_db_con($db_key);
   $res = $db -> prepare($sql);
   $res -> execute();
   if($res){$mrt_ary['torf'] = true;}
   unset($sql); unset($db); unset($res); unset($src_fld_ary); unset($sc_c); unset($rw_fld_ary); unset($rw_c); unset($w);
   return $mrt_ary;
}
function csm_sys_counter_ary($db_ary = array(), $sc_fld_ary = array(), $sc_cnt_fld = ""){
   $mrt_ary = array(); $mrt_ary['val'] = "";
   $db = csm_db_con($db_ary['db_key']);
   $sql = "";
   $sql .= "UPDATE ".$db_ary['tbl_key']." SET `".$sc_cnt_fld."` = ".$sc_cnt_fld." + 1 WHERE ";
   $dl_c = count($sc_fld_ary);
   $d = '1';
   foreach($sc_fld_ary as $fl_nm => $fl_vl){
      $sql .= " `".$fl_nm."` = '".$fl_vl."'";
      if($d < $dl_c){$sql .= " AND ";}
      $d++;
   }
   $sql .= ";";
   $mrt_ary['sql'] = $sql;
   $res = $db -> prepare($sql);
   $res -> execute();
   if($res){$mrt_ary['torf'] = true;}
   unset($db); unset($db_ary); unset($sc_fld_ary); unset($sql); unset($dl_c); unset($res);
   return $mrt_ary;
}

function csm_sys_upfile_counter_ary($sc_fld_ary = array()){
   $mrt_ary = array(); $mrt_ary['val'] = "";
   $db = csm_db_con("x459x_movie");
   $sql = "";
   $sql .= "UPDATE tbl_upfile SET `fld15` = fld15 + 1, `fld16` = fld16 + 1, `fld17` = fld17 + 1 WHERE ";
   $dl_c = count($sc_fld_ary);
   $d = '1';
   foreach($sc_fld_ary as $fl_nm => $fl_vl){
      $sql .= " `".$fl_nm."` = '".$fl_vl."'";
      if($d < $dl_c){$sql .= " AND ";}
      $d++;
   }
   $sql .= ";";
   $mrt_ary['sql'] = $sql;
   $res = $db -> prepare($sql);
   $res -> execute();
   if($res){$mrt_ary['torf'] = true;}
   unset($db); unset($db_ary); unset($sc_fld_ary); unset($sql); unset($dl_c); unset($res);
   return $mrt_ary;
}

//4件の次の動画取得(最新順)
function csm_sys_upfile_new_4cm_ary($any_ary = array()){
   $mrt_ary = array();
   $db_key = 'x459x_movie'; $tbl_key = 'tbl_upfile';
   $db = csm_db_con($db_key);
   $sql  = "SELECT * FROM `".$tbl_key."` ";
   if(!is_array($any_ary)){$any_ary = array();}
   if($any_ary['cm4_key'] == 'random'){
      $sql .= " WHERE `fld28` = 'none' AND `fld29` = 'none' ORDER BY RAND() LIMIT 4";
   } else 
   if($any_ary['cm4_key'] == 'shop_girls_new'){ 
      $sql .= " WHERE `fld2` = '".$any_ary['cm4_dmn_id']."' AND `fld4` <> 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4";
   } else 
   if($any_ary['cm4_key'] == 'shop_free_new'){
      $sql .= " WHERE `fld2` = '".$any_ary['cm4_dmn_id']."' AND `fld4` = 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4";
   } else 
   if($any_ary['cm4_key'] == 'shop_all_new'){ 
      $sql .= " WHERE `fld2` = '".$any_ary['cm4_dmn_id']."' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4";
   } else 
   if($any_ary['cm4_key'] == 'shop_girls_rnd'){ 
      $sql .= " WHERE `fld2` = '".$any_ary['cm4_dmn_id']."' AND `fld4` <> 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY RAND() LIMIT 4";
   } else 
   if($any_ary['cm4_key'] == 'shop_free_rnd'){
      $sql .= " WHERE `fld2` = '".$any_ary['cm4_dmn_id']."' AND `fld4` = 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY RAND() LIMIT 4";
   } else 
   if($any_ary['cm4_key'] == 'shop_all_rnd'){
      $sql .= " WHERE `fld2` = '".$any_ary['cm4_dmn_id']."' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY RAND() LIMIT 4";
   } else {
      $sql .= " WHERE `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4";
   }
   $sql .= ";";
   $res = $db -> prepare($sql);
   $res -> execute();
   while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
      $mrt_ary[$rc['id']]['file_id']         = $rc['id'];
      $mrt_ary[$rc['id']]['shop_id']         = $rc['fld1'];
      $mrt_ary[$rc['id']]['domain_id']       = $rc['fld2'];
      $mrt_ary[$rc['id']]['domain_name']     = $rc['fld3'];
      $mrt_ary[$rc['id']]['staff_id']        = $rc['fld4'];
      $mrt_ary[$rc['id']]['genre']           = $rc['fld5'];
      $mrt_ary[$rc['id']]['file_name']       = $rc['fld6'];
      $mrt_ary[$rc['id']]['exten']           = $rc['fld7'];
      $mrt_ary[$rc['id']]['image']           = $rc['fld8'];
      $mrt_ary[$rc['id']]['top_path']        = $rc['fld9'];
      $mrt_ary[$rc['id']]['url']             = $rc['fld10'];
      $mrt_ary[$rc['id']]['staff_name']      = $rc['fld11'];
      $mrt_ary[$rc['id']]['file_title']      = $rc['fld12'];
      $mrt_ary[$rc['id']]['file_come']       = $rc['fld13'];
      $mrt_ary[$rc['id']]['file_time']       = $rc['fld14'];
      $mrt_ary[$rc['id']]['file_count']      = $rc['fld15'];
      $mrt_ary[$rc['id']]['file_nm_count']   = $rc['fld16'];
      $mrt_ary[$rc['id']]['file_nw_count']   = $rc['fld17'];
      $mrt_ary[$rc['id']]['file_om_count']   = $rc['fld18'];
      $mrt_ary[$rc['id']]['file_ow_count']   = $rc['fld19'];
      $mrt_ary[$rc['id']]['cm_select']       = $rc['fld20'];
      $mrt_ary[$rc['id']]['src_tag']         = $rc['fld21'];
      $mrt_ary[$rc['id']]['swing_select']    = $rc['fld22'];
      $mrt_ary[$rc['id']]['cm_hid']          = $rc['fld28'];
      $mrt_ary[$rc['id']]['hid']             = $rc['fld29'];
      $mrt_ary[$rc['id']]['time']            = $rc['fld30'];
   }
   unset($db); unset($sql); unset($res); unset($db_key); unset($tbl_key); unset($mn_ary);
   unset($any_ary);
   return $mrt_ary;
}


//コンフィグ呼び出し配列化
function csm_sys_config_ary ($db_key, $tbl_key, $sc_fld_ary){
   /*
      fld2=(key1=val,key2=val,...)
      fld3(key1=subkey+subkey+subkey,key2=subkey+subkey,....
      $sc_fld_ary=array(sc_fld => フィールド名, sc_val => 検索語)
   */
   $mrt_ary = array(); $mrt_ary['val'] = ""; $mrt_ary['mes'] = ""; $mrt_ary['ary'] = array();
   
   $db = csm_db_con($db_key);
   $sql  = "SELECT * FROM `".$tbl_key."` ";
   $sql .= " WHERE `".$sc_fld_ary['sc_fld']."` = '".$sc_fld_ary['sc_val']."' ";
   $sql .= ";";
   $res = $db -> prepare($sql);
   $res -> execute();
   $txt1_val = "";      $txt2_val = "";      $txt3_val = "";
   $txt1_ary = array(); $txt2_ary = array(); $txt3_ary = array();
   $t2_b_ary = array();
   while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
      //キャリッジターン
      $txt3_val = mrt_fnc_enter_null($rc['fld3']);
      $txt2_val = mrt_fnc_enter_null($rc['fld2']);
      //空白
      $txt3_val = mrt_fnc_space_null($txt3_val);
      $txt2_val = mrt_fnc_space_null($txt2_val);
      // ',' ary 0=no 1=val_ary
      $txt3_ary = explode(",", $txt3_val);
      $txt2_ary = explode(",", $txt2_val);
      foreach($txt2_ary as $t2_no => $t2_vl){
         $t2_a_ary = explode("=", $t2_vl);
         $t2_b_ary[$t2_a_ary[0]] = $t2_a_ary[1];
      }
      foreach($txt3_ary as $t3_no => $t3_vl){
         //"=" ary 0=no 1=val_ary
         $t3_a_ary = explode("=", $t3_vl);
         $t3_b_ary = explode("+", $t3_a_ary[1]);
         foreach($t3_b_ary as $t3_b_no => $t3_b_vl){
            $mrt_ary['ary'][$t3_a_ary[0]][$t3_b_vl] = $t2_b_ary[$t3_b_vl];
         }
      }
      $mrt_ary['ary']['ttl'] = $t2_b_ary;
   }
   unset($txt1_val); unset($txt2_val); unset($txt3_val);
   unset($txt1_ary); unset($txt2_ary); unset($txt3_ary);
   unset($t2_a_ary); unset($t2_b_ary); unset($t3_a_ary); unset($t3_b_ary);
   unset($db); unset($res);
   return $mrt_ary;
}
function csm_sys_config_area_ary(){
   $mrt_ary = array(); $mrt_ary['ary'] = array();
   $mrt_ary['ary'] = array(   
                                            'kagawa' => array('kagawa_tosan' => "東讃地方", 'kagawa_seisan' => "西讃地方")
                                          , 'ehime'  => array('ehime_chuyo' => "中予地方", 'ehime_toyo' => "東予地方", 'ehime_nanyo' => "南予地方")
                                          , 'ttl'  => array(  'kagawa' => "香川県"
                                                              , 'kagawa_tosan' => "香川県東讃地方", 'kagawa_seisan' => "香川県西讃地方"
                                                              , 'ehime' => "愛媛県"
                                                              , 'ehime_chuyo' => "愛媛県中予地方", 'ehime_toyo' => "愛媛県東予地方"
                                                              , 'ehime_nanyo' => "愛媛県南予地方"
                                                           )
                                          //, 'tokushima' => array('tokushima_hokutobu' => "北東部", 'tokushima_seibu' => "西部", 'tokushima_nanbu' => "南部")
                                          //, 'kochi' => array('kochi_aki' => "安芸地域", 'kochi_chuo' => "高知中央地域")
   );
   return $mrt_ary;
}
/* -------------------------------------------------------------
      area　エリアを配列化
---------------------------------------------------------------*/
function csm_sys_cnf_area_ary(){
   
   $mrt_ary = array(); $mrt_ary['val'] = ""; $mrt_ary['mes'] = ""; $mrt_ary['ary'] = array(); $mrt_ary['slct_ary'] = array();
   //$sc_fld_ary = array('sc_fld' => 'fld1', 'sc_val' => 'cosmo_cnf_area_plf_select');
   //$area_ary = csm_sys_config_ary ('csm_sys_cnf', 'tbl_val', $sc_fld_ary);
   $area_ary = csm_sys_config_area_ary();
   $db = csm_db_con('x459x_conf');
   $sql = "SELECT * FROM `area` WHERE `fd12` = 'start' AND `fd13` = 'none' ;";
   $res = $db -> prepare($sql);
   $res -> execute();
   $gnr_ttl_ary = array();
   while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
      foreach($area_ary['ary'] as $plf_key => $tiki_ary){
         if($plf_key != "ttl"){
            $gnr_ttl_ary[$plf_key] = $area_ary['ary']['ttl'][$plf_key];
            foreach($tiki_ary as $tiki_nm => $city_ary){
               if($tiki_nm == $rc['fd3']){
                  $mrt_ary['ary'][$plf_key][$rc['fd3']][$rc['fd1']]['ttl'] = $rc['fd2'];
                  $mrt_ary['ary'][$plf_key][$rc['fd3']][$rc['fd1']]['inf'] = $rc['fd4'];
                  $mrt_ary['slct_ary'][$plf_key][$rc['fd3']][$rc['fd1']]  = $rc['fd2'];
                  foreach($rc as $fld_key => $fld_val){
                     if($fld_key == "fd1"){
                        $gnr_ttl_ary[$fld_val] = $rc['fd2'];
                     } else 
                     if($fld_key == "fd3"){
                        $gnr_ttl_ary[$fld_val] = $area_ary['ary']['ttl'][$fld_val];
                     }
                  }
               }
            }
         }
      }
   }
   
   $mrt_ary['slct_ary']['ttl'] = $gnr_ttl_ary;
   
   unset($dt_ary); unset($db); unset($sc_fld_ary); unset($genre_ary); unset($tiki_ary); unset($city_ary);
   unset($gnr_ttl_ary); unset($area_ary);
   return $mrt_ary;
}

//shop cid より該当するエリアキーを配列で取得
function csm_sys_mv_shopid_areakey_ary($ary = array()){
   $mrt_ary = array();
   //$db_key = 'csm_sys_crp'; $tbl_key = 'tbl_shop_area_val';
   $db_key = 'x459x_corp'; $tbl_key = 'site';
   $db = csm_db_con($db_key);
   $sql  = "SELECT * FROM `".$tbl_key."` ";
   $sql .= " WHERE `fd1` = '".$ary['sc_fld']['cid']."' AND `fd4` = 'official01_shop' AND `fd11` = 'cosmogroup' AND `fd77` = 'start' AND `fd78` = 'none' ";
   $sql .= " LIMIT 1 ;";
   $res = $db -> prepare($sql);
   $res -> execute();
   while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
      $mrt_ary = explode(',', $rc['fd76']);
      
   }
   unset($db); unset($sql); unset($res); unset($db_key); unset($tbl_key); unset($mn_ary);
   unset($any_ary); unset($ary);
   return $mrt_ary;

}
//shop cid より該当するジャンルキーを配列で取得
function csm_sys_mv_shopid_genrekey_ary($ary = array()){
   $mrt_ary = array();
   //$db_key = 'csm_sys_crp'; $tbl_key = 'tbl_shop_genre_val';
   $db_key = 'x459x_corp'; $tbl_key = 'site';
   $db = csm_db_con($db_key);
   $sql  = "SELECT * FROM `".$tbl_key."` ";
   $sql .= " WHERE `fd1` = '".$ary['sc_fld']['cid']."' AND `fd4` = 'official01_shop' AND `fd11` = 'cosmogroup' AND `fd77` = 'start' AND `fd78` = 'none' ";
   $sql .= " LIMIT 1 ;";
   $res = $db -> prepare($sql);
   $res -> execute();
   while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
      $mrt_ary = explode(',', $rc['fd75']);
      
   }
   unset($db); unset($sql); unset($res); unset($db_key); unset($tbl_key); unset($mn_ary);
   unset($any_ary); unset($ary);
   return $mrt_ary;

}


//shop カンマ区切りのジャンルから特定ジャンルに一致するものだけのshop id摘出
function csm_sys_mv_shop_genre_seek($wd_genre = ""){
   $mrt_ary = array();
   if($wd_genre != ""){
      //$db_key = "csm_sys_crp"; $tbl_key = "tbl_shop_genre_val";
      $db_key = "x459x_corp"; $tbl_key = "site";
      $db = csm_db_con($db_key);
      $sql  = "SELECT * FROM `".$tbl_key."` ";
      $sql .= " WHERE `fd4` = 'official01_shop' AND `fd11` = 'cosmogroup' AND FIND_IN_SET('".$wd_genre."', fd75) AND `fd77` = 'start' AND `fd78` = 'none' ";
      $sql .= ";";
      $res = $db -> prepare($sql);
      $res -> execute();
      while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
         $mrt_ary[$rc['fd1']] = $rc['fd1'];
      }
      
   }
   
   unset($db); unset($sql); unset($res); unset($rc);
   unset($db_key); unset($tbl_key); unset($wd_genre);
   return $mrt_ary;
}
//shop カンマ区切りのエリアから特定エリアに一致するものだけのshop id摘出
function csm_sys_mv_shop_area_seek($wd_area = ""){
   $mrt_ary = array();
   if($wd_area != ""){
      //$db_key = "csm_sys_crp"; $tbl_key = "tbl_shop_area_val";
      $db_key = "x459x_corp"; $tbl_key = "site";
      $db = csm_db_con($db_key);
      $sql  = "SELECT * FROM `".$tbl_key."` ";
      $sql .= " WHERE `fd4` = 'official01_shop' AND `fd11` = 'cosmogroup' AND FIND_IN_SET('".$wd_area."', fd76) AND `fd77` = 'start' AND `fd78` = 'none' ";
      $sql .= ";";
      $res = $db -> prepare($sql);
      $res -> execute();
      while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
         $mrt_ary[$rc['fd1']] = $rc['fd1'];
      }
      
   }
   
   unset($db); unset($sql); unset($res); unset($rc);
   unset($db_key); unset($tbl_key); unset($wd_area);
   return $mrt_ary;
}

//与えられたショップIDをdomainIDキーにして配列化して摘出officialのみ
function csm_sys_mv_shop_dmn_ary($shop_id_ary = array()){
   $mrt_ary = array();
   if(is_array($shop_id_ary)){
      //$db_key = "csm_sys_crp"; $tbl_key = "tbl_shop_url_val";
      $db_key = "x459x_corp"; $tbl_key = "site";
      $db = csm_db_con($db_key);
      $sql  = "SELECT * FROM `".$tbl_key."` ";
      $sql .= "WHERE `fd1` ";
      //個数確認
      $sia_kosu = count($shop_id_ary);
      $i = '1';
      //in生成
      $sql_in = " IN( ";
      foreach($shop_id_ary as $sia_no => $sia_val){
         $sql_in .= $sia_val;
         if($sia_kosu > $i){
            $sql_in .= ",";
         }
         $i++;
      }
      $sql_in .= " ) AND `fd4` = 'official01_shop' AND `fd11` = 'cosmogroup' AND `fd77` = 'start' AND `fd78` = 'none' ;";
      $sql .= $sql_in;
      $res = $db -> prepare($sql);
      $res -> execute();
      while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
         $mrt_ary[$rc['siteid']] = $rc['siteid'];
      }
   }
   unset($db); unset($sql); unset($shop_id_ary); unset($sql_in); unset($res); unset($rc);
   return $mrt_ary;
}
//cm4
function csm_sys_mv_dmn_cm4_ary($dmn_id_ary = array(), $cm4_key = ""){
   $mrt_ary = array();
   if(is_array($dmn_id_ary)){
      //$db_key = "csm_sys_file"; $tbl_key = "tbl_upfile";
      $db_key = "x459x_movie"; $tbl_key = "tbl_upfile";
      $db = csm_db_con($db_key);
      $sql  = "SELECT * FROM `".$tbl_key."` ";
      $sql .= "WHERE `fld2` ";
      //個数確認
      $sia_kosu = count($dmn_id_ary);
      $i = '1';
      //in生成
      $sql_in = " IN( ";
      foreach($dmn_id_ary as $sia_no => $sia_val){
         $sql_in .= $sia_val;
         if($sia_kosu > $i){
            $sql_in .= ",";
         }
         $i++;
      }
      if($cm4_key == 'shop_area_rnd' || $cm4_key == 'shop_genre_rnd'){ 
         $sql_in .= " ) AND `fld28` = 'none' AND `fld29` = 'none'  ORDER BY RAND() LIMIT 4;";
      } else 
      if($cm4_key == 'shop_area_free_new' || $cm4_key == 'shop_genre_free_new'){ //
         $sql_in .= " ) AND `fld4` = 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4;";
      } else 
      if($cm4_key == 'shop_area_girls_new' || $cm4_key == 'shop_genre_girls_new'){ //
         $sql_in .= " ) AND `fld4` <> 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4;";
      } else 
      if($cm4_key == 'shop_area_girls_rnd' || $cm4_key == 'shop_genre_girls_rnd'){ //
         $sql_in .= " ) AND `fld4` <> 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY RAND() LIMIT 4;";
      } else 
      if($cm4_key == 'shop_area_free_rnd' || $cm4_key == 'shop_genre_free_rnd'){ //
         $sql_in .= " ) AND `fld4` = 'free' AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4;";
      } else {
         $sql_in .= " ) AND `fld28` = 'none' AND `fld29` = 'none' ORDER BY fld30 + 0 DESC LIMIT 4;";
      }
      $sql .= $sql_in;
      $res = $db -> prepare($sql);
      $res -> execute();
      while($rc = $res -> fetch(PDO::FETCH_ASSOC)){
         $mrt_ary[$rc['id']]['file_id']         = $rc['id'];
         $mrt_ary[$rc['id']]['shop_id']         = $rc['fld1'];
         $mrt_ary[$rc['id']]['domain_id']       = $rc['fld2'];
         $mrt_ary[$rc['id']]['domain_name']     = $rc['fld3'];
         $mrt_ary[$rc['id']]['staff_id']        = $rc['fld4'];
         $mrt_ary[$rc['id']]['genre']           = $rc['fld5'];
         $mrt_ary[$rc['id']]['file_name']       = $rc['fld6'];
         $mrt_ary[$rc['id']]['exten']           = $rc['fld7'];
         $mrt_ary[$rc['id']]['image']           = $rc['fld8'];
         $mrt_ary[$rc['id']]['top_path']        = $rc['fld9'];
         $mrt_ary[$rc['id']]['url']             = $rc['fld10'];
         $mrt_ary[$rc['id']]['staff_name']      = $rc['fld11'];
         $mrt_ary[$rc['id']]['file_title']      = $rc['fld12'];
         $mrt_ary[$rc['id']]['file_come']       = $rc['fld13'];
         $mrt_ary[$rc['id']]['file_time']       = $rc['fld14'];
         $mrt_ary[$rc['id']]['file_count']      = $rc['fld15'];
         $mrt_ary[$rc['id']]['file_nm_count']   = $rc['fld16'];
         $mrt_ary[$rc['id']]['file_nw_count']   = $rc['fld17'];
         $mrt_ary[$rc['id']]['file_om_count']   = $rc['fld18'];
         $mrt_ary[$rc['id']]['file_ow_count']   = $rc['fld19'];
         $mrt_ary[$rc['id']]['cm_select']       = $rc['fld20'];
         $mrt_ary[$rc['id']]['src_tag']         = $rc['fld21'];
         $mrt_ary[$rc['id']]['swing_select']    = $rc['fld22'];
         $mrt_ary[$rc['id']]['hid']             = $rc['fld29'];
         $mrt_ary[$rc['id']]['time']            = $rc['fld30'];
      }
   }
   unset($db); unset($sql); unset($dmn_id_ary); unset($sql_in); unset($res); unset($rc);
   unset($cm4_key);
   return $mrt_ary;
}

?>