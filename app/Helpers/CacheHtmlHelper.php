<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;
use App\Models\M_Shop;
use App\Models\M_Site;

class CacheHtmlHelper
{
    /**
     * Xử lý cached HTML và thay thế header, csrf nếu user đã login.
     */
    public static function renderCachedHtml($cachedHtml, $request)
    {
        if (!$cachedHtml) {
            return null;
        }

        if (session('user_id')) {
            $customHeader = View::make('layouts.partials.header')->render();
            $customQr = View::make('layouts.partials.qr')->render();

            $kio_genmst = [
                'fzk' => [ //風俗 
                '1' => '風俗'
                ]
            , 'gnr' => [ //一般
                '2' => 'メンズエステ'
                , '3' => 'キャバクラ'
                , '4' => 'セクキャバ'
                , '5' => '飲食'
                , '6' => '宴会'
                , '7' => 'もみほぐし'
            ] 
            ];
            //page のgenre_idを取得
            $kio_gen = ''; 
            $kio_gid = "";
            if($request->has('genre_id')) {
                $kio_gid = $request->genre_id;
                foreach($kio_genmst as $gky => $gary){
                    if(array_key_exists($kio_gid, $gary)){
                        $kio_gen = $gky;
                        $gnr_name = $gary[$kio_gid];
                    }
                }
                if($kio_gen == "") {
                    $kio_gen = 'fzk';
                }
            } else {
                $kio_gid = "";
                $kio_gen = 'fzk';
            }

            if ($request->has('site_id')){
                $siteId = $request->site_id ?? 0;
                $sites = M_Site::findOrFail($siteId);
                $shops = M_Shop::findOrFail($sites->shop_id);
                $customFooter = View::make('layouts.partials.footer', ['kio_gen' => $kio_gen, 'shops' => $shops])->render();
            } else {
                $customFooter = View::make('layouts.partials.footer', ['kio_gen' => $kio_gen])->render();
            }
            $csrfMetaTag = View::make('layouts.partials.csrf_meta')->render();

            // Thay thế HEADER
            $cachedHtml = preg_replace(
                '/<!-- HEADER_START -->(.*?)<!-- HEADER_END -->/is',
                "<!-- HEADER_START -->\n{$customHeader}\n<!-- HEADER_END -->",
                $cachedHtml
            );

            // Thay thế QR
            $cachedHtml = preg_replace(
                '/<!-- QR_START -->(.*?)<!-- QR_END -->/is',
                "<!-- QR_START -->\n{$customQr}\n<!-- QR_END -->",
                $cachedHtml
            );

            // Thay thế FOOTER
            $cachedHtml = preg_replace(
                '/<!-- FOOTER_START -->(.*?)<!-- FOOTER_END -->/is',
                "<!-- FOOTER_START -->\n{$customFooter}\n<!-- FOOTER_END -->",
                $cachedHtml
            );

            // Thay thế CSRF meta
            $cachedHtml = preg_replace(
                '/<meta name="csrf-token" content=".*?">/i',
                $csrfMetaTag,
                $cachedHtml
            );
        }

        return response($cachedHtml);
    }
}
