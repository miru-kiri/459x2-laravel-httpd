<?php
   /***********************
   * メンズエステサイトの表示利用を定義
   * 任意のジャンルIDを配列に固定
   * etcはメンズエステのグループに要れないサイトだけど表示したいというわがままに応える
   * エステポータルで表示したい場合や非表示にしたい場合はこの配列のIDを書き換えてください
   *   mrt::lastupdate:2025/7/11
   ***********************/
return [
   //メンエスサイトで表示させる業種ID
   'gen' => 
   [
      '32' => 'メンズエステ'
     ,'11' => 'アロマエステ'
     ,'10' => '洗体エステ'
   ],
   'etc' => 
   [
      '34' => 'もみほぐし'
   ],
   'all' => 
   [
      '32' => '32'
     ,'11' => '11'
     ,'10' => '10'
     //,'34' => '34'
   ],
   'area' => 
   [ //カンマ区切りでエリアIDを入れてください
      'matsuyama' => ['14']
     ,'nihama' => ['5']
     ,'shikokuchuo' => ['6'] 
   ], //areaにキーを追加したら必ずarea_nameも追加してください
   'area_name' =>
   [
      'matsuyama' => '松山'
     ,'nihama' => '新居浜'
     ,'shikokuchuo' => '四国中央'
   
   ],
   'tab' =>
   [ //ショップコンテンツページを追加したい場合はmaster_idとWEBキーをセットで入れてください
      '10' => 'top'
     ,'11' => 'scd'
     ,'12' => 'cast'
     ,'13' => 'system'
     ,'14' => 'info'
     ,'15' => 'event'
     ,'16' => 'cast_blog'
     ,'17' => 'tencho_blog'
     ,'18' => 'cast_recruit'
     ,'50' => 'site_news'
   ],
   'memo' =>
   [
      'site_url_prefix' => 'site_'
      //画像を保存してるドメイン
     ,'get_img_domain' => '459x.dogo459.com' 
      //scdページの日数
     ,'scd_days' => '7'
     //各コンテンツの表示件数
     ,'per' => [
          'scd' => '10'
         ,'cast' => '10'
         ,'cast_blog' => '10'
         ,'tencho_blog' => '10'
         ,'site_news' => '10'
     ]
   ],
   'site_info' => 
   [
       '12' => ['name' => '', 'comment' => '領収書発行可']
      ,'14' => ['name' => '', 'comment' => '']
      ,'33' => ['name' => '', 'comment' => '']
      ,'37' => ['name' => '', 'comment' => '']
      ,'38' => ['name' => '', 'comment' => '']
      ,'40' => ['name' => '', 'comment' => '']
      ,'41' => ['name' => '', 'comment' => '']
      ,'42' => ['name' => '', 'comment' => '']
      ,'43' => ['name' => '', 'comment' => '']
      //,'' => ['name' => '', 'comment' => '']
      //,'' => ['name' => '', 'comment' => '']
      //,'' => ['name' => '', 'comment' => '']
      //,'' => ['name' => '', 'comment' => '']

   ],
   'pgcon' => [
      10 =>    [
                  'name' => [
                                          'title' => 'トップ'
                  ],
                  'top' => [
                                          'title' => 'トップ'
                                         ,'event' => 'top'
                  ],
                  'top_today_work' => [
                                          'title' => '本日の出勤'
                                         ,'event' => 'top_today_work'
                  ],
                  'top_shop_news' => [
                                          'title' => 'ショップニュース'
                                         ,'event' => 'top_shop_news'
                  ],
                  'top_manager_news' => [
                                          'title' => '店長BLOG'
                                         ,'event' => 'top_manager_news'
                  ],
                  'top_cast_news' => [
                                          'title' => '写メ日記'
                                         ,'event' => 'top_cast_news'
                  ],
                  'top_ranking' => [
                                          'title' => '指名数ランキング'
                                         ,'event' => 'top_ranking'
                  ],
                  'top_recommend' => [
                                          'title' => '店長おすすめガール'
                                         ,'event' => 'top_recommend'
                  ],
                  'top_movie' => [
                                          'title' => '天国ネット動画'
                                         ,'event' => 'top_movie'
                  ]
               ], 
       
      11 =>    [
                  'name' => [
                                          'title' => '出勤情報'
                  ],
                  'scd' => [
                                          'title' => '本日の出勤'
                                         ,'event' => 'scd'
                  ],
                  'today_work' => [
                                          'title' => '出勤情報'
                                         ,'event' => 'today_work'
                  ]
               ],
      12 =>    [
                  'name' => [
                                          'title' => 'トップ'
                  ],
                  'cast' => [
                                          'title' => '在籍一覧'
                                         ,'event' => 'cast'
                  ]
               ],
      13 =>    [
                  'name' => [
                                          'title' => '料金システム'
                  ],
                  'system' => [
                                          'title' => '料金システム'
                                         ,'event' => 'system'
                  ],
                  'base_price' => [
                                          'title' => '料金システム'
                                         ,'event' => 'base_price'
                  ],
                  'extension_price' => [
                                          'title' => '延長システム'
                                         ,'event' => 'extension_price'
                  ],
                  'other_price' => [
                                          'title' => 'その他'
                                         ,'event' => 'other_price'
                  ]
               ],
      14 =>    [
                  'name' => [
                                          'title' => '店舗情報'
                  ],
                  'info' => [
                                          'title' => '店舗情報'
                                         ,'event' => 'info'
                  ],
                  'shop_info' => [
                                          'title' => '店舗情報'
                                         ,'event' => 'shop_info'
                  ],
                  'shop_access' => [
                                          'title' => 'アクセスマップ'
                                         ,'event' => 'shop_access'
                  ],
                  'shop_gallery' => [
                                          'title' => 'ギャラリー'
                                         ,'event' => 'shop_gallery'
                  ]
               ],
      15 =>    [
                  'name' => [
                                          'title' => 'イベント'
                  ],
                  'event' => [
                                          'title' => 'イベント'
                                         ,'event' => 'event'
                  ],
               ],
      16 =>    [
                  'name' => [
                                          'title' => '写メ日記'
                  ],
                  'cast_blog' => [
                                          'title' => '写メ日記'
                                         ,'event' => 'cast_blog'
                  ],
                  'cast_news' => [
                                          'title' => '写メ日記'
                                         ,'event' => 'cast_news'
                  ]
               ],
      17 =>    [
                  'name' => [
                                          'title' => '店長ブログ'
                  ],
                  'tencho_blog' => [
                                          'title' => '店長ブログ'
                                         ,'event' => 'tencho_blog'
                  ],
                  'manager_news' => [
                                          'title' => '店長ブログ'
                                         ,'event' => 'manager_news'
                  ]
               ],
      18 =>    [
                  'name' => [
                                          'title' => 'スタッフ求人'
                  ],
                  'cast_recruit' => [
                                          'title' => 'スタッフ求人'
                                         ,'event' => 'cast_recruit'
                  ],
                  'recruit' => [
                                          'title' => 'スタッフ求人'
                                         ,'event' => 'recruit'
                  ]
               ],
      50 =>    [
                  'name' => [
                                          'title' => 'ショップニュース'
                  ],
                  'site_news' => [
                                          'title' => 'ショップニュース'
                                         ,'event' => 'site_news'
                  ]
               ]

   ]
   
];