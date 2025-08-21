<?php
/*



*/

   $dir_name = dirname(__FILE__)."/";
   //db tbl list read
   require_once($dir_name."db_tbl_list.php");
   //
   require_once($dir_name."mrt_fnc.mrt");
   require_once($dir_name."mrt_db_func.php");
   require_once($dir_name."mrt_mv_func.php");
   //define
   $mrt_cm4_title_ary = array(
                                  'defo'                    => "最新情報",
                                  'random'                  => "特選情報",
                                  
                                  'shop_all_new'            => "当店最新情報",
                                  'shop_all_rnd'            => "当店特選情報",
                                  'shop_free_new'           => "当店こだわり新着",
                                  'shop_girls_new'          => "当店ラインナップ新着",
                                  'shop_free_rnd'           => "当店こだわり特選情報",
                                  'shop_girls_rnd'          => "当店ラインナップ特選",
                                  
                                  
                                  
                                  'shop_genre_new'          => "業種新着情報",
                                  'shop_genre_rnd'          => "業種特選情報",
                                  'shop_genre_free_new'     => "業種こだわり新着情報",
                                  'shop_genre_girls_new'    => "業種ラインナップ新着",
                                  'shop_genre_free_rnd'     => "業種こだわり特選情報",
                                  'shop_genre_girls_rnd'    => "業種ラインナップ特選",
                                  
                                  
                                  
                                  'shop_area_new'           => "最寄新着情報",
                                  'shop_area_rnd'           => "最寄特選情報",
                                  'shop_area_free_new'      => "最寄こだわり情報",
                                  'shop_area_girls_new'     => "最寄ラインナップ新着",
                                  'shop_area_free_rnd'      => "最寄こだわり特選情報",
                                  'shop_area_girls_rnd'     => "最寄ラインナップ特選"
                                  
                                  
                                  

                            );
?>
