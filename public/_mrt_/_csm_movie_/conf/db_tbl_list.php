<?php
/*-----------------------------
    この定義ファイルは他言無用です！
    誰かに話すと情報が漏洩します。閲覧編集させる人の人格を選んでください。
   project:by cosmogroup all web system
#   char:utf-8set
#   mysql,sqlite,posgre...
#
#  推奨：mysql; bufferを有効にしてご利用ください。
#  info:php5.2up,sqlite2up,(gd,pdo,mysql,imagick6up)enable--
#  since:14-02-27  by mrt
-----------------------------*/
   //define db list array
   $db_list = array(
                     'x459x_corp'   => array('name' => 'x459x_corp', 'info' => 'mysql', 'url' => '459x.com', 'user' => 'cosmomrt', 'pass' => '459xmrt2367')
                   , 'x459x_conf'   => array('name' => 'x459x_conf',   'info' => 'mysql', 'url' => '459x.com', 'user' => 'cosmomrt',   'pass' => '459xmrt2367')
                   , 'x459x_staff'  => array('name' => 'x459x_staff',  'info' => 'mysql', 'url' => '459x.com', 'user' => 'cosmomrt',   'pass' => '459xmrt2367')
                   , 'x459x_movie'  => array('name' => 'x459x_movie', 'info' => 'mysql', 'url' => '459x.com', 'user' => 'cosmomrt',   'pass' => '459xmrt2367')
                   );
   //define tbl list array db_key not err
   $tbl_list = array(
                     'x459x_corp'   => array(
                                               'corp'  => array('pfldname' => 'corpid', 'fldname' => 'fd', 'fldkosu' => '60', 'pfldinfo' => 'auto')
                                             , 'shop'  => array('pfldname' => 'shopid', 'fldname' => 'fd', 'fldkosu' => '80', 'pfldinfo' => 'auto')
                                             , 'site'  => array('pfldname' => 'siteid', 'fldname' => 'fd', 'fldkosu' => '80', 'pfldinfo' => 'auto')
                                            ),
                     'x459x_conf'   => array(
                                               'genre'     => array('pfldname' => 'genreid', 'fldname' => 'fd', 'fldkosu' => '15', 'pfldinfo' => 'auto')
                                             , 'area'      => array('pfldname' => 'areaid',  'fldname' => 'fd', 'fldkosu' => '15', 'pfldinfo' => 'auto')
                                             , 'page'      => array('pfldname' => 'pgid',    'fldname' => 'fd', 'fldkosu' => '20', 'pfldinfo' => 'auto')
                                             , 'page_menu' => array('pfldname' => 'pmid',    'fldname' => 'fd', 'fldkosu' => '20', 'pfldinfo' => 'auto')
                                            ),
                     'x459x_staff'  => array(
                                               'staff'      => array('pfldname' => 'staffid', 'fldname' => 'st',  'fldkosu' => '80', 'pfldinfo' => 'auto')
                                             , 'profile_m'  => array('pfldname' => 'profid',  'fldname' => 'pr',  'fldkosu' => '15', 'pfldinfo' => 'auto')
                                             , 'profile'    => array('pfldname' => 'prfid',   'fldname' => 'prf', 'fldkosu' => '20', 'pfldinfo' => 'auto')
                                             , 'scd'        => array('pfldname' => 'scdid',   'fldname' => 'scd', 'fldkosu' => '30', 'pfldinfo' => 'free')
                                            ),
                      'x459x_movie' => array(
                                               'tbl_upfile' => array('pfldname' => 'id',  'fldname'  => 'fld', 'fldkosu'  => '30',   'pfldinfo' => 'auto')
                                             , 'tbl_src_tag' => array('pfldname' => 'id', 'fldname'  => 'fld', 'fldkosu'  => '10',   'pfldinfo' => 'free')
                                          )
                    );






?>
