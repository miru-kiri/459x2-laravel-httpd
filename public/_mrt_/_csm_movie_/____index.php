<?php

//このファイルの存在場所定義
   $dir_name = dirname(__FILE__)."/";
   //db tbl list read
   require_once($dir_name."conf/db_tbl_list.php");
   //
   require_once($dir_name."conf/mrt_db_func.php");
   require_once($dir_name."conf/mrt_fnc.mrt");
   require_once($dir_name."conf/mrt_mv_func.php");
   
   $mvid = htmlspecialchars($_GET['mvid']);
   if($mvid != "" && mrt_fnc_suzinomi ($mvid)){
      $mv_res = csm_sys_upfile_movie_play(array('id' => $mvid));
   }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8" />
<META NAME="robots" content="noindex, nofollow, noarchive" />
<title>動作チェック</title>
</head>
<body>
<div>
<?php

print_r(csm_sys_cnf_area_ary());

?>
</div>
<div>
<iframe id="mrtplayer" type="text/html" width="640" height="390"
  src="<?php print "http://459x.com/_mrt_/_movie_/ifr.php?mvid=".$mv_res['file_id']; ?>"
  frameborder="0"/></iframe>
</div>
<textarea name="hhhh" rows="10" cols="50">&lt;iframe id="mrtplayer" type="text/html" width="640" height="390"
  src="<?php print "http://459x.com/_mrt_/_movie_/ifr.php?mvid=".$mv_res['file_id']; ?>"
  frameborder="0"&gt;
  &lt;/iframe&gt;</textarea>
</body>
<?php unset($mv_res); ?>
</html>
