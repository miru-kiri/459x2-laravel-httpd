<!doctype html>
<html lang="ja">
   <head>
{!! $meta_tag !!}

      <title>メンズエステスト</title>
{!! $css_tag !!}

   </head>
   <body>
      <header>
         <div>
            <div>
               <img scr="{{ asset('img/mens_est_logo.png') }}" alt="メンズエステランドのロゴ" class="logo_img">
            </div>
         </div>
      </header>
      <div class="kirino">テスト！</div>
      <section>
         <div class="est_top">
            <img src="{{ asset('img/esthe_top_image.jpg') }}" alt="メンズエステランドトップ画像" class="top-img">
         </div>
         <h1 class="est_top_ttl">デザインにｈ１入れるとこないから要相談</h1>
      </section>
      <section>
         <div class="est_search">
            <div class="est_search_img">
               <img src="{{ asset('img/glasses.png')}}">
            </div>
            <div class="est_search_ttl">エリアからお店を探す</div>
         </div>
         <div class="est_area_sec">
         @foreach(config('EstheSite.area_name') as $akey => $av)
            <a href="/{{ $akey }}">
               <p>{{ $av }}</p>
            </a>
         @endforeach
         </div>
      </section>
      <section>
         <h3 class="est_news_ttl">ショップニュース</h3><!--ここはデータを取得してforeachで回す予定-->
      </section>
      <section>
         <h3>今すぐ受付可能なお店</h3>
         <div class="est_shop_list">
            @foreach($genre_sitesAry as $sno => $agary)
            <div class="est_shop_item">
               <a href="{{ $agary['remarks'].'/site_'.$agary['id'] }}">
                  <img src="{{ asset('img/index/index_shop_id_'.$agary['id'].'_.jpg') }}" alt="メンズエステのショップ画像">
                  <div class="est_shop_text">
                     <h4>{{$agary['name']}}</h4>
                     <p>{{$shops[  $sites[$agary['id']]['shop_id']  ]['address1'] }}</p>
                     <p>{{$shops[  $sites[$agary['id']]['shop_id']  ]['opening_text']}}</p>
                     <p>{{ config('EstheSite.site_info.'.$sno.'.comment') }}</p>
                  </div>
               </a>
            </div>
            @endforeach
         </div>
      </section>
      <section>
         <div class="est_copyright">
            <h2>メンズエステランド</h2>
            <p>愛媛の人気メンズエステ情報サイト</p>
         </div>
      </section>






@php
//echo ($path_area);
print "<div>日本語</div>"
//print_r($areas);

//print '<br /><br /><br /><br /><br />';

//print_r($mens_areas);
//print '<br /><br /><br /><br /><br />';
//dd($d_tab);
//print_r($areas);
/******
*  
*   画像ファイル名
*   qjin_index_360_500_|siteid|.png
*    <img src="~~laravel_関数~~/qijin_idx_360_500_{{ $agary['id'] }}.png" />
*
* compact('areas', 'sites', 'genre_sites', 'genre_site_id_in'
*                                , 'area_sites', 'genres', 'mens_id_Ary', 'genre_sitesAry'
*                                , 'site_genidAry', 'site_areaAry', 'shops'
*/


@endphp
<br /><br /><br /><br />
<!--サイト情報一覧-->
<div>「メンズエステサイトに表示可能な全サイト」</div>
@foreach($genre_sitesAry as $sno => $agary)
<div>
site_id:[{{ $agary['id'] }}]<br />
sites.name:{{ $agary['name'] }}<br />
shops.opening_text:{{ $shops[  $sites[$agary['id']]['shop_id']  ]['opening_text'] }}<br />
shops.holiday_text:{{ $shops[  $sites[$agary['id']]['shop_id']  ]['holiday_text'] }}<br />
   genres.name:
   @foreach($site_genidAry[$agary['id']] as $ano => $av)
      {{ $genres[$ano]['name'] }}
   @endforeach
<hr />
</div>
@endforeach
<br /><br /><br /><br />
<!--紐づけされているジャンルのみ-->
<div>「サイト設定で登録されてるジャンルのみ」</div>
@foreach($mens_genres as $gky => $gid)
   {{ $genres[$gid]['name'] }}
<hr />
@endforeach
<br /><br /><br /><br />
<!--紐づけされているエリアのみ-->
<div>「サイト設定で登録されてるエリアのみ」</div>
@foreach($mens_areas as $ma => $maid)
{{ $areas[$maid]['name'] ?? $maid.':非表示になってます' }}
<hr />
@endforeach
<br /><br /><br /><br />
{!! $js_tag !!}
<br /><br />
@php

dd($genre_sitesAry);


@endphp
</body>
</html>