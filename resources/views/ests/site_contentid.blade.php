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
            <div class="shop_tag">
            @foreach($m_tab as $tno => $tary)
               <a href="{{ config('app.url') }}/{{ $url_area }}/{{ config('EstheSite.memo.site_url_prefix') }}{{ $site_id }}/{{ config('EstheSite.tab.'.$tary['master_id'].'') }}">
                  <span>{{ $tary['name'] }}</span>
               </a>
            @endforeach
            </div>
      <div class="cast">
         <h2>{{ $m_tab[$m_tab_id]['name'] }}</h2>
         
@if('cast' == $url_contents)
@php /* キャストページ */ @endphp
      <div class="border">
         <div>{{ $casts[$url_contentid]['source_name'] }}</div>
         <div>{{ $casts[$url_contentid]['age'] }}歳</div>
         <div>{{ $casts[$url_contentid]['shop_manager_pr'] }}</div>
         @if(isset($sid_cid_imgs[$site_id][$url_contentid][0]))
            <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $sid_cid_imgs[$site_id][$url_contentid][0]['directory'] }}LM_{{ $sid_cid_imgs[$site_id][$url_contentid][0]['path'] }}" width="100"/>
         @else
            <img src="https://{{ $def_mens_ary['memo']['get_img_domain'] }}/storage/no-image.jpg" width="200"/>
         @endif
         <div class="d-flex">
            @if(isset($scd['d>cid']))
               @foreach($scd['d>cid'] as $dt => $dcary)
                  <div>
                     <div>{{ $dt }}</div>
                     @if(isset($dcary[$url_contentid]))
                        <div>{{ $dcary[$url_contentid]['start_time'] }}</div>
                        <div>{{ $dcary[$url_contentid]['end_time'] }}</div>
                     @endif
                  </div>
               @endforeach
            @endif
         </div>
         <div class="border">
            @if(isset($cb_ary['defo']['data'][0]))
               <div>
                  <div>{{ $cb_ary['defo']['data'][0]['title'] }}</div>
                  {!! $cb_ary['defo']['data'][0]['content'] !!}
                  <div>{{ $cb_ary['defo']['data'][0]['published_at'] }}</div>
               </div>
            @endif
            <div class="d-flex">
               @foreach($cb_ary['defo']['data'] as $cno => $cary)
                  <div>
                     <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/cast_blog/{{ $url_contentid }}?id={{ $cary['id'] }}">
                     <div>{{ $cary['title'] }}</div>
                     <div>{{ $cary['published_at'] }}</div>
                     @if(isset($cbim[$cary['id']][0]))
                        @foreach($cbim[$cary['id']] as $no => $iry)
                           <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $iry['image_url'] }}" />
                        @endforeach
                     @endif
                     </a>
                  </div>
               @endforeach
            </div>
            <div class="border">
               @if(isset($mv[$url_contentid][0]))
                  <div>
                     <video class="cast-video w-100" src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $mv[$url_contentid][0]['file_path'] }}/{{ $mv[$url_contentid][0]['file_name'] }}.mp4" controls=""></video>
                     <div>{{ $mv[$url_contentid][0]['title'] }}</div>
                     <div>{{ $mv[$url_contentid][0]['content'] }}</div>
                  </div>
                  <div class="d-flex">
                  @foreach($mv[$url_contentid] as $n => $mary)
                     @if(5 > $n)
                        @if(isset($mary['file_name']))
                           <div style="width: 150px;">
                              <video class="cast-video w-100" src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $mary['file_path'] }}/{{ $mary['file_name'] }}.mp4" controls=""></video>
                              <div>{{ $mary['title'] }}</div>
                           </div>
                        @endif
                     @endif
                  @endforeach
                  </div>
               @endif
            </div>
            <div class="border">
               @if(isset($qu))
                  @foreach($qu as $qid => $qary)
                     
                     @if(isset($an[$url_contentid][$qid]['answer']))
                        <div>Q::{{ $qary['question'] }}</div>
                        @if($an[$url_contentid][$qid]['answer'] == '')
                           <div>{{ $qary['default_answer'] }}</div>
                        @else
                           <div>A::{{ $an[$url_contentid][$qid]['answer'] }}</div>
                        @endif
                     @endif

                  @endforeach
               @endif
            </div>
         </div>
      </div>
      
@elseif('cast_blog' == $url_contents)
@php /* キャストブログページ */ @endphp
   <div>
   @if(isset($cb1_ary['data'][0]))
      <div>
         <div>{{ $casts[ $cb1_ary['data'][0]['cast_id'] ]['source_name'] }}</div>
         <div>{{ $cb1_ary['data'][0]['title'] }}</div>
         <div class="border">{!! $cb1_ary['data'][0]['content'] !!}</div>
         @if(isset($cb1im[ $cb1_ary['data'][0]['id'] ]))
            <div>
            @foreach($cb1im[ $cb1_ary['data'][0]['id'] ] as $no => $iry)
               <div>
                  <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $iry['image_url'] }}" />
               </div>
            @endforeach
            </div>
         @endif
         <div>{{ $cb1_ary['data'][0]['published_at'] }}</div>
         <br />
         <br />
         <div class="d-flex justify-content-between">
            @if(isset($prvcb[0]))
               <span><a href="{{ url()->current() }}?id={{ $prvcb[0]['id'] }}">≪≪前</a></span>
            @else
               <span>≪≪前</span>
            @endif
            @if(isset($nxtcb[0]))
               <span><a href="{{ url()->current() }}?id={{ $nxtcb[0]['id'] }}">次≫≫</a></span>
            @else
               <span>次≫≫</span>
            @endif
         </div>
      </div>
      <br />
      <br />
      <div class="d-flex justify-content-between">
      @foreach($cb_ary['data'] as $no => $ary)
         <div>
            <a href="{{ url()->current() }}?id={{ $ary['id'] }}">
            <div>{{ $ary['title'] }}</div>
         @if(isset($cbim[$ary['id']]))
            <div>
            @foreach($cbim[ $ary['id'] ] as $no => $iry)
               <div>
                  <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $iry['image_url'] }}" style="width:100px;" />
               </div>
            @endforeach
            </div>
         @endif
         <div>{{ $ary['published_at'] }}</div>
         </a>
         </div>
      @endforeach
      </div>
      @if(isset($cb_ary['links']))
         <div>
         @foreach($cb_ary['links'] as $pn => $pary)
            @if($pary['active'])
               <span>{!! $pary['label'] !!}</span>
            @else
               <span><a href="{{ $pary['url'] }}">{!! $pary['label'] !!}</a></span>
            @endif
         @endforeach
         </div>
      @endif
   @endif
   </div>

@elseif('site_news' == $url_contents)
@php /* ショップニュースページ */ @endphp
   @if(isset($spn[0]))
      <div>
         <div>{!! $spn[0]['title'] !!}</div>
         <div>{!! $spn[0]['published_at'] !!}</div>
         <div>{!! $spn[0]['content'] !!}</div>
      </div>
      <div class="d-flex justify-content-between">
         @if(empty($spprev))
            <span>≪≪前へ</span>
         @else
            <span><a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/{{ $url_contents }}/{{ $spprev[0]['id'] }}">≪≪前へ</a></span>
         @endif
         @if(empty($spnext))
            <span>次へ≫≫</span>
         @else
            <span><a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/{{ $url_contents }}/{{ $spnext[0]['id'] }}">次へ≫≫</a></span>
         @endif
      </div>
   @endif

@endif
      </div>
   </section>

<div><br><br><br><br><br><br><br><br><br><br><br><br></div>
<div><br><br><br><br><br><br><br><br><br><br><br><br></div>











@php
/*
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
*/
@endphp
<br /><br />
<br /><br /><br />
<br /><br /><br />
<script src="{{ asset('js/app.js') }}" defer></script>
@php
dd($cb1im);
@endphp
</body>
</html>