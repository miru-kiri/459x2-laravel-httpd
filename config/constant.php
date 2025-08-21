<?php

return [
    "shop" => [
        "style" => [
            ['value'=> 1,'name' => '店舗型'],
            ['value'=> 2,'name' => '無店舗型'],
            ['value'=> 3,'name' => '事務・営業所'],
        ],
        "applying" => [
            ['value' => 'none','name' => "なし"],
            ['value' => 'fuei', 'name' => "風俗営業"], 
            ['value' => 'seifuei', 'name' => "性風俗関連特殊営業"], 
            ['value' => 'insyoku1', 'name' => "接待飲食等営業1号営業"], 
            ['value' => 'insyoku2', 'name' => "接待飲食等営業2号営業"], 
            ['value' => 'insyoku3', 'name' => "接待飲食等営業3号営業"], 
            ['value' => 'insyoku4', 'name' => "接待飲食等営業4号営業"], 
            ['value' => 'insyoku5', 'name' => "接待飲食等営業5号営業"], 
            ['value' => 'insyoku6', 'name' => "接待飲食等営業6号営業"], 
            ['value' => 'yugiei7', 'name'  => "遊技場営業7号営業"],     
            ['value' => 'yugiei8', 'name'  => "遊技場営業8号営業"], 
            ['value' => 'seifuei_tenpo1', 'name'   => "店舗型性風俗特殊営業1号営業"],   
            ['value' => 'seifuei_tenpo2', 'name'   => "店舗型性風俗特殊営業2号営業"], 
            ['value' => 'seifuei_tenpo3', 'name'   => "店舗型性風俗特殊営業3号営業"],   
            ['value' => 'seifuei_tenpo4', 'name'   => "店舗型性風俗特殊営業4号営業"], 
            ['value' => 'seifuei_tenpo5', 'name'   => "店舗型性風俗特殊営業5号営業"],   
            ['value' => 'seifuei_tenpo6', 'name'   => "店舗型性風俗特殊営業6号営業"], 
            ['value' => 'seifuei_mutenpo1', 'name' => "無店舗型性風俗特殊営業1号営業"], 
            ['value' => 'seifuei_mutenpo2', 'name' => "無店舗型性風俗特殊営業2号営業"], 
            ['value' => 'seifuei_eizotoku', 'name' => "映像送信型風俗特殊営業"],
            ['value' => 'seifuei_tenpotel', 'name' => "店舗型電話異性紹介業"], 
            ['value' => 'seifuei_mutenpotel', 'name' => "無店舗型電話異性紹介営業"],    
            ['value' => 'seifuei_sinyasake', 'name' => "深夜酒類提供飲食店営業"]
        ],
    ],
    "site" => [
        "style" => [
            ['value' => 1, 'name' => 'オフィシャル店舗系サイト'],
            ['value' => 2, 'name' => 'オフィシャル企業系サイト'],
            ['value' => 3, 'name' => 'リクルート系サイト'],
            ['value' => 4, 'name' => 'ポータルサイト'],
        ],
        "template" => [
            ['value' => 1, 'name' => '風俗'],
            ['value' => 2, 'name' => 'メンズエステ'],
            ['value' => 3, 'name' => 'キャバクラ'],
            ['value' => 4, 'name' => 'セクキャバ'],
            ['value' => 5, 'name' => '飲食店'],
            ['value' => 6, 'name' => '宴会コンパニオン'],
            ['value' => 7, 'name' => 'もみほぐし'],
            ['value' => -1, 'name' => 'その他'],
        ],
        "category" => [
            ['id' => 1, 'name' => 'サイト別'],
            ['id' => 2, 'name' => 'プロフィール別'],
            ['id' => 3, 'name' => 'ブログ別'],
        ],
    ],
    "is_cosmo" => [
        ['value'=> 1,'name' => 'コスモグループ'],
        ['value'=> 2,'name' => '一般'],
    ],
    "cast" => [
        "blood_type" => [
            ['value' => 0, 'name' => "非公開"],
            ['value' => 'A', 'name' => 'A型'],
            ['value' => 'B', 'name' => 'B型'],
            ['value' => 'O', 'name' => 'O型'],
            ['value' => 'AB', 'name' => 'AB型'],
        ],
        "constellation" => [
            ['value' => 0, 'name' => "非公開"],
            ['value' => '0321-0419', 'name' => '牡羊座(03/21-04/19)'],
            ['value' => '0420-0520', 'name' => '牡牛座(04/20-05/20)'],
            ['value' => '0521-0621', 'name' => '双子座(05/21-06/21)'],
            ['value' => '0622-0722', 'name' => '蟹　座(06/22-07/22)'],
            ['value' => '0723-0822', 'name' => '獅子座(07/23-08/22)'],
            ['value' => '0823-0922', 'name' => '乙女座(08/23-09/22)'],
            ['value' => '0923-1023', 'name' => '天秤座(09/23-10/23)'],
            ['value' => '1024-1121', 'name' => '蠍　座(10/24-11/21)'],
            ['value' => '1122-1221', 'name' => '射手座(11/22-12/21)'],
            ['value' => '1222-0119', 'name' => '山羊座(12/22-01/19)'],
            ['value' => '0120-0218', 'name' => '水瓶座(01/20-02/18)'],
            ['value' => '0219-0320', 'name' => '魚　座(02/19-03/20)']
        ],
        "cup" => [
            ['value' => 0, 'name' => "非公開"],
            ['value' => 'A', 'name' => "Aカップ"],
            ['value' => 'B', 'name' => "Bカップ"],
            ['value' => 'C', 'name' => "Cカップ"],
            ['value' => 'D', 'name' => "Dカップ"],
            ['value' => 'E', 'name' => "Eカップ"],
            ['value' => 'F', 'name' => "Fカップ"], 
            ['value' => 'G', 'name' => "Gカップ"],
            ['value' => 'H', 'name' => "Hカップ"], 
            ['value' => 'I', 'name' => "Iカップ"], 
            ['value' => 'J', 'name' => "Jカップ"],
            ['value' => 'K', 'name' => "Kカップ"]
        ],
        "figure" => [
            ['value' => 0, 'name' => "非公開",'old_value' => 'none'],
            ['value' => 1, 'name' => "身長は小柄で細身",'old_value' => 't_s_bs'],
            ['value' => 2, 'name' => "身長は小柄で普通",'old_value' => 't_s_bm'],
            ['value' => 3, 'name' => "身長は小柄でぽっちゃり",'old_value' => 't_s_bb'], 
            ['value' => 4, 'name' => "身長は標準で細身",'old_value' => 't_m_bs'], 
            ['value' => 5, 'name' => "身長は標準で普通",'old_value' => 't_m_bm'], 
            ['value' => 6, 'name' => "身長は標準でぽっちゃり",'old_value' => 't_m_bb'], 
            ['value' => 7, 'name' => "身長は高めで細身",'old_value' => 't_b_bs'], 
            ['value' => 8, 'name' => "身長は高めで普通",'old_value' => 't_b_bm'], 
            ['value' => 9, 'name' => "身長は高めでぽっちゃり",'old_value' => 't_b_bb']
        ],
        "approval_status" => [
            ['value' => 1, 'name' => '承認あり'],
            ['value' => 2, 'name' => '承認なし'],
            ['value' => 3, 'name' => '受け付けない'],
        ],
        "figure_text" => [
            0 => "",
            1 => "身長は小柄で細身",
            2 => "身長は小柄で普通",
            3 => "身長は小柄でぽっちゃり", 
            4 => "身長は標準で細身", 
            5 => "身長は標準で普通", 
            6 => "身長は標準でぽっちゃり", 
            7 => "身長は高めで細身", 
            8 => "身長は高めで普通", 
            9 => "身長は高めでぽっちゃり"
        ],
        "shedule_week" => [
            '日'=>'Sun',
            '月'=>'Mon',
            '火'=>'Tue',
            '水'=>'Wed',
            '木'=>'Thu',
            '金'=>'Fri',
            '土'=>'Sat'
        ],
        "imadake_status" => [
            'defo' => "予約受付中", 
            'none' => "待ち時間無し", 
            'end' => "受付終了",
            'mini' => "残り枠少ない", 
            'all_end' => "本日完売",
            'cancel_end' => "キャンセル待ち",
            'mini_one' => "あと一枠"
        ],
        "criterials" => [
            1 => "女の子の満足度", 
            2 => "プレイの満足度", 
            3 => "料金納得度",
            4 => "スタッフ対応度", 
            5 => "写真信頼度",
        ],
        "stay_status" => [
            1 => "在籍",
            2 => "他店より応援",
            -1 => "ダミー",
        ],
        "stay_status_old" => [
            1 => "00_member",
            2 => "01_support",
            -1 => "99_dammy",
        ],
        "exclusive_status" => [
            ['value' => 1,'name' => '通常','image' => '','old_value' => '50_none'],
            ['value' => 2,'name' => '新人','image' => 'icon_newface.png','old_value' => '10_new'],
            ['value' => 3,'name' => '体験入店','image' => 'icon_taiken.png','old_value' => '05_experi'],
            ['value' => 4,'name' => '期間限定','image' => 'icon_kikan.png','old_value' => '01_limit'],
            ['value' => 5,'name' => '店長おススメ','image' => 'icon_recommend.png','old_value' => '60_recommend'],
            ['value' => 6,'name' => '当店人気第1位','image' => 'icon_rank1.png','old_value' => '71_rank'],
            ['value' => 7,'name' => '当店人気第2位','image' => 'icon_rank2.png','old_value' => '72_rank'],
            ['value' => 8,'name' => '当店人気第3位','image' => 'icon_rank3.png','old_value' => '73_rank'],
            ['value' => 9,'name' => '長期休暇','image' => '','old_value' => '99_vacation']
        ],
        // '11_normal' => "通常", '22_scout' => "スカウト", '33_dekasegi' => "出稼ぎ"
        // '50_none' => "通常", '10_new' => "新人", '05_experi' => "体験入店", '01_limit' => "期間限定", '60_recommend' => "店長おススメ", '71_rank' => "当店人気第1位", '72_rank' => "当店人気第2位", '73_rank' => "当店人気第3位", '99_vacation' => "長期休暇"
        // '00_member' => "在籍", '01_support' => "他店より応援", '99_dammy' => "ダミー"
        // 'web' => "WEB順", 'scd' => "出勤順", 'name' => "名前順", 'shopno' => "店番順"
    ],
    "user" => [
        "rank" => [
            // ['value' => 'A', 'name' => 'A'],
            // ['value' => 'B', 'name' => 'B'],
            // ['value' => 'C', 'name' => 'C'],
            // ['value' => 'D', 'name' => 'D'],
            // ['value' => 'E', 'name' => 'E'],
            // ['value' => 'F', 'name' => 'F'],
            ['value' => 1, 'name' => 'VIP'],
            ['value' => 2, 'name' => '一般'],
            ['value' => 3, 'name' => '出入禁止'],
            ['value' => 4, 'name' => '要注意'],
            ['value' => 5, 'name' => '業者'],
            ['value' => 6, 'name' => '従業員'],
            ['value' => 7, 'name' => 'その他'],
        ],
        "rank_text" => [
            1  => 'VIP',
            2  => '一般',
            3  => '出入禁止',
            4  => '要注意',
            5  => '業者',
            6  => '従業員',
            7  => 'その他',
        ],
        "block" => [
            ['value' => 0, 'name' => '普通'],
            ['value' => 1, 'name' => 'NG'],
            ['value' => 2, 'name' => '要注意'],
            ['value' => 3, 'name' => '出入り禁止'],
        ],
        "block_text" => [
            0  => '普通',
            1  => 'NG',
            2  => '要注意',
            3  => '出入り禁止',
        ],
        "status_badge" => [
            1 => "<span class='badge bg-danger'>仮予約</span>",
            2 => "<span class='badge bg-danger'>確定</span>",
            3 => "<span class='badge bg-warning'>事前確認</span>",
            4 => "<span class='badge bg-success'>完了</span>",
            5 => "<span class='badge bg-secondary'>キャンセル</span>",
        ],
    ],
    "master" => [
        "system" => [
            "course_type" => [
                ['value' => 0, 'name' => 'ネット'],
                ['value' => 1, 'name' => '普通'],
                ['value' => 2, 'name' => 'フリー'],
            ],
        ],
    ],
    "week" => [
        '日',
        '月',
        '火',
        '水',
        '木',
        '金',
        '土' 
    ], 
    "genre" => [
        1 => [
            'color' => '#EF747D',
            'text' => '風俗',
            
        ],
        2 => [
            'color' => '#6D66AA',
            'text' => 'メンズエステ',
            
        ],
        3 => [
            'color' => '#F684A6',
            'text' => 'キャバクラ',
            
        ],
        4 => [
            'color' => '#B66497',
            'text' => 'セクキャバ',
            
        ],
        5 => [
            'color' => '#F27847',
            'text' => '飲食店',
            
        ],
        6 => [
            'color' => '#C02639',
            'text' => '宴会コンパニオン',
            
        ],
    ],
    "serch" => [
        "genre" => [
            ['value' => 1, 'label' => '風俗', 'name' => 'fuzoku'],
            ['value' => 2, 'label' => 'メンズエステ', 'name' => 'beauty_salon'],
            ['value' => 3, 'label' => 'キャバクラ', 'name' => 'cabaret_club'],
            ['value' => 4, 'label' => 'セクキャバ', 'name' => 'sex_cabaret'],
            ['value' => 5, 'label' => '飲食店', 'name' => 'restaurant'],
            ['value' => 6, 'label' => '宴会コンパニオン', 'name' => 'companion'],
        ],
        // "fuzoku" => [
        //     ['value' => 1, 'label' => '風俗', 'name' => 'fuzoku'],
        //     ['value' => 2, 'label' => 'メンズエステ', 'name' => 'beauty_salon'],
        //     ['value' => 3, 'label' => 'キャバクラ', 'name' => 'cabaret_club'],
        // ],
        // "beauty_salon" => [
        //     ['value' => 1, 'label' => 'セクキャバ', 'name' => ''],
        //     ['value' => 2, 'label' => 'いちゃキャバ', 'name' => 'beauty_salon'],
        //     ['value' => 2, 'label' => 'いちゃキャバ', 'name' => 'beauty_salon'],
        // ],
        // "cabaret_club" => [
        //     ['value' => 1, 'label' => 'セクキャバ', 'name' => ''],
        //     ['value' => 2, 'label' => 'いちゃキャバ', 'name' => 'icha'],
        // ],
    ]
];