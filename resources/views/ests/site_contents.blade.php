@php
/*****************************************

エリア
[14]愛媛県松山市
[5]新居浜市
[6]四国中央市
サイトコンテンツ
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

*****************************************/
@endphp
<!doctype html>
<html lang="ja">
   <head>
{!! $meta_tag !!}
      <title>サイトコンテンツメンズエステスト</title>
      {!! $css_tag !!}
   </head>
   <body>
      <div><span><a href="{{ config('app.url') }}">他の地域</a></span><span><a href="{{ config('app.url') }}/{{ $url_area }}">近隣店舗</a></span></div>
   <section>
      <div class="shop_img">
         <img src="{{asset('img/site/site_top_site_id_'.$site_id.'.jpg')}}">
      </div>
            <div class="shop_tag">
            @foreach($m_tab as $tno => $tary)
               <a href="{{ config('app.url') }}/{{ $url_area }}/{{ config('EstheSite.memo.site_url_prefix') }}{{ $site_id }}/{{ config('EstheSite.tab.'.$tary['master_id'].'') }}">
                  <span>{{ $tary['name'] }}</span>
               </a>
            @endforeach
            </div>
      <div class="cast">
         <h2>{{ $m_tab[$m_tab_id]['name'] }}</h2>
@if('scd' == $url_contents)
@php 
/* 

出勤ページ 11 today_work

*/ 
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @if('today_work' == $dary['event'])
            <div class="shop_cast_list">
               @foreach($casts as $cid => $cary)
                  <div class="shop_cast_item">
                     <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/cast/{{ $cid }}">
                  @if(isset($sid_cid_imgs[$site_id][$cid]))
                     @foreach($sid_cid_imgs[$site_id][$cid] as $no => $imgary)
                        @if($no == 0)<!--画像を１枚だけ取ってくる-->
                           <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage{{ $imgary['directory'] }}LM_{{ $imgary['path'] }}" />
                        @endif
                     @endforeach
                  @else
                     <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage/no-image.jpg" />
                  @endif
                  <p>{{ $cary['source_name'] }}</p>
                  </a>
               </div>
               @endforeach
            </div>
         @else
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
         @endif
      @endforeach
   @endif

@elseif('cast' == $url_contents)
@php 
/*

キャストページ 12 cast

*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @if('cast' == $dary['event'])
         <div class="shop_cast_list">
            @foreach($casts as $cid => $cary)
            <div class="shop_cast_item">
               <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/{{ $url_contents }}/{{ $cid }}">
                  @if(isset($sid_cid_imgs[$site_id][$cid]))
                  <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage{{ $sid_cid_imgs[$site_id][$cid][0]['directory'] }}LM_{{ $sid_cid_imgs[$site_id][$cid][0]['path'] }}" />
                  @else
                     <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage/no-image.jpg" />
                  @endif
                  <p>{{ $cary['source_name'] }}</p>
               </a>
            </div>
            @endforeach
         </div>
         @else
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
         @endif
      @endforeach
   @endif

@elseif('system' == $url_contents)
@php
/*

料金ページ 13 other_price

*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @if(    'base_price'      == $dary['event'])
            @if(isset($sys[$site_id]))
               @foreach($sys[$site_id] as $yno => $yary)
                  <div><span>{{ $yary['name'] }}</span><span>{{ $yary['time'] }}分</span><span>{{ $yary['fee'] }}円</span></div>
               @endforeach
            @endif
            @if(isset($sysex[$site_id]))
               @foreach($sysex[$site_id] as $sno => $sary)
                  <div>指名{{ $sary['fee'] }}円</div>
                  <div>本指名{{ $sary['nomination_fee'] }}円</div>
               @endforeach
            @endif
         @elseif('extension_price' == $dary['event'])
            @if(isset($sysex[$site_id]))
               @foreach($sysex[$site_id] as $sno => $sary)
                  <div><span>{{ $sary['extension_time_unit'] }}分</span><span>{{ $sary['extension_fee'] }}円</span></div>
               @endforeach
            @endif
         @else
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
         @endif
      @endforeach
   @endif

@elseif('info' == $url_contents)
@php
/*

店舗情報ページ info 14

*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @php /* 追加したコンテンツ  */ @endphp
         <div>{!! $dary['title'] !!}</div>
         <div>{!! $dary['sub_title'] !!}</div>
         <div>{!! $dary['content'] !!}</div>
      @endforeach
   @endif

@elseif('event' == $url_contents)
@php
/*

イベントページ event 15

*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @php /* 追加したコンテンツ  */ @endphp
         <div>{!! $dary['title'] !!}</div>
         <div>{!! $dary['sub_title'] !!}</div>
         <div>{!! $dary['content'] !!}</div>
      @endforeach
   @endif

@elseif('cast_blog' == $url_contents)
@php
/*

キャストブログページ 16 cast_blog == cast_news

*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @if('cast_news' == $dary['event'])

   <div class="shop_cas_blog">
   @if(isset($cb_ary['cb']))
      @foreach($cb_ary['cb'] as $bid => $ary)
      <div class="cas_blog_box">
      <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/{{ $url_contents }}/{{ $ary['cast_id'] }}?id={{ $ary['id'] }}">
         <div class="cas_name">{{ $casts[$ary['cast_id']]['source_name'] }}</div>
         <div class="blog_ttl">{{ $ary['title'] }}</div>
         @if(isset($cbim[$bid]))
            <div>
            @foreach($cbim[$bid] as $no => $iry)
               <div>
                  <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $iry['image_url'] }}" />
               </div>
            @endforeach
            </div>
         @endif
         @php //<div>{!! $ary['content'] !!}</div>
         @endphp 
         <div class="blog_uptime">{{ $ary['published_at'] }}</div>
      </a>
      </div>
      @endforeach
   @endif
   </div>
   <div>
   @foreach($cb_ary['defo']['links'] as $pn => $pary)
      @if($pary['active'])
         <span>{!! $pary['label'] !!}</span>
      @else
         <span><a href="{{ $pary['url'] }}">{!! $pary['label'] !!}</a></span>
      @endif
   @endforeach
   </div>

         @else
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
         @endif
      @endforeach
   @endif

@elseif('tencho_blog' == $url_contents)
@php
/*

店長ブログページ 17 tencho_blog
  もしかしたらメインコンテンツが非表示？そうなら後で修正しようかな
*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
      @endforeach
   @endif

@elseif('cast_recruit' == $url_contents)
@php
/*

キャスト求人ページ 18 
  
*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
      @endforeach
   @endif

@elseif('site_news' == $url_contents)
@php
/*

ショップニュースページ 50 
  
*/
@endphp
   @if(isset($d_tab))
      @foreach($d_tab as $dno => $dary)
         @if('shop_news' == $dary['event'])
            @if(isset($sp_news['data']))
               @foreach($sp_news['data'] as $sno => $sary)
                  <a href="{{ url()->current() }}/{{ $sary['id'] }}">
                  <div>{!! $sary['title'] !!}{{ $sary['published_at'] }}</div>
                  </a>
               @endforeach
               @foreach($sp_news['links'] as $pn => $pary)
                  @if($pary['active'])
                     <span>{!! $pary['label'] !!}</span>
                  @else
                     <span><a href="{{ $pary['url'] }}">{!! $pary['label'] !!}</a></span>
                  @endif
               @endforeach
            @endif
         @else
            @php /* 追加したコンテンツ  */ @endphp
            <div>{!! $dary['title'] !!}</div>
            <div>{!! $dary['sub_title'] !!}</div>
            <div>{!! $dary['content'] !!}</div>
         @endif
      @endforeach
   @endif
   
@endif
      </div>
   </section>

<div><br><br><br><br><br><br><br><br><br><br><br><br></div>
<div><br><br><br><br><br><br><br><br><br><br><br><br></div>

@php 
/* 出勤情報 
$scd
$casts
$dts
m_tab (10)
*/
//print_r($now_date);
@endphp
<div class="border">
   @foreach($dts as $no => $date)
      <div class="border">
      <div>{{ $date }}</div>
      @if(isset($scd['d>cid'][$date]))
         @foreach($scd['d>cid'][$date] as $cid => $cary)
            @if(isset($sid_cid_imgs[$site_id][$cid]))
               <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage{{ $sid_cid_imgs[$site_id][$cid][0]['directory'] }}LM_{{ $sid_cid_imgs[$site_id][$cid][0]['path'] }}" width="200"/>
            @else
               <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage/no-image.jpg" />
            @endif
            <div>{{ $casts[$cid]['source_name'] }}{{ $casts[$cid]['age'] }}</div>
            <div>{{ $cary['start_time'] }}～{{ $cary['end_time'] }}</div>

         @endforeach
      @endif
      </div>
   @endforeach
</div>










<div>
@foreach($casts as $cid => $cary)
   <div>
   cast_id:{{ $cid }}<br />
   名前:{{ $cary['source_name'] }}<br />
   キャッチコピー:{{ $cary['catch_copy'] }}<br />
   年齢:{{ $cary['age'] }}<br />
   サイズ:S:{{ $cary['height'] }}B:{{ $cary['bust'] }}カップ:{{ $cary['cup'] }}W:{{ $cary['waist'] }}H:{{ $cary['hip'] }}bld:{{ $cary['blood_type'] }}<br />
   @if(isset($sid_cid_imgs[$site_id][$cid]))
      @foreach($sid_cid_imgs[$site_id][$cid] as $no => $imgary)
         <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage{{ $imgary['directory'] }}LM_{{ $imgary['path'] }}" />
      @endforeach
   @else
   <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage/no-image.jpg" />
   @endif

   </div>
@endforeach
</div>
<br /><br />
<br /><br /><br />
<br /><br /><br />
<script src="{{ asset('js/app.js') }}" defer></script>
@php
dd($sp_news);
@endphp
</body>
</html>