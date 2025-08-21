<?php

return [
    "admin" => [
        "create" => [
            "id",
            "name",
            "login_id",
            "password",
            "mail",
            "role",
            "is_public"
        ],
    ],
    "corp" => [
        "create" => [
            'id',
            'name',
            'short_name',
            'type',
            'responsible_name',
            'postal_code',
            'address1',
            'address2',
            'address3',
            'tel',
            'fax',
            'is_cosmo',
            'sort',
            'remarks',
            'is_public'
        ],
    ],
    "shop" => [
        "create" => [
            'id',
            'corporate_id',
            'name',
            'kana',
            'short_name',
            'short_kana',
            'style',
            'genre_id',
            'responsible_name',
            'postal_code',
            'address1',
            'address2',
            'address3',
            'mail',
            'tel',
            'fax',
            'is_cosmo',
            'applying',
            // 'recruit_tel',
            // 'recruit_mail',
            'opening_text',
            'holiday_text',
            'sort',
            'remarks',
            'is_notification',
            'is_public',
        ],
    ],
    "site" => [
        "create" => [
            'id',
            'shop_id',
            'url',
            'name',
            'style',
            // 'top_url',
            // 'recruit_key',
            'template',
            'is_cosmo',
            'sort',
            'remarks',
            'content',
            // 'switching_time',
            // 'blog_owner_host',
            // 'blog_owner_user',
            // 'blog_owner_pass',
            // 'blog_staff_host',
            // 'blog_staff_user',
            // 'blog_staff_pass',
            // 'mail_magazine_url',
            // 'mail_magazine_key',
            // 'mail_magazine_create_mail',
            // 'mail_magazine_delete_mail',
            // 'recruit_line_url',
            // 'recruit_line_id',
            // 'analytics_code',
            // 'analytics_api',
            // 'is_https',
            // 'portal_template_url',
            // 'portal_tab',
            // 'staff_sort',
            // 'open_time',
            // 'close_time',
            // 'is_externally_server',
            'is_public',
        ],
    ],
    "area" => [
        "create" => [
            'id',
            'group_id',
            'name',
            'content',
            'sort',
            'remarks',
            'color',
            'is_public'
        ],
    ],
    "genre" => [
        "create" => [
            'id',
            'group_id',
            'name',
            'content',
            'sort',
            'remarks',
            'is_public'
        ],
    ],
    "option" => [
        "create" => [
            "id",
            "site_id",
            "name"
        ],
    ],
    "cast" => [
        "create" => [
            "base" => [
                'source_name',
                'catch_copy',
                'site_id',
                'age',
                'blood_type',
                'constellation',
                'height',
                'bust',
                'cup',
                'waist',
                'hip',
                'figure',
                'figure_comment',
                'self_pr',
                'shop_manager_pr',
                'is_public',
            ],
        ],
    ],
    "user" => [
        "edit" => [
            'id',
            'name',
            // 'name_furigana',
            // 'name_show',
            // 'nickname',
            'phone',
            'email',
            'address',
            'birth_day',
            'rank',
            'block',
            'memo',
        ]
    ],
    "blog" => [
        "shop" => [
            "create" => [
                'id',
                'site_id',
                'title',
                'content',
                'published_at'
            ],
            "manager" => [
                "create" => [
                    'id',
                    'site_id',
                    'title',
                    'content',
                    'published_at'
                ],
            ],
        ],
        "cast" => [
            "create" => [
                'id',
                'cast_id',
                'title',
                'content',
                'published_at'
            ],
        ],
    ],
    "system" => [
        "nomination_fee" => [
            "create" => [
                'id',
                'site_id',
                'nomination_fee',
                'extension_fee',
                'extension_time_unit',
                'fee',
            ],
        ],
    ],
];
