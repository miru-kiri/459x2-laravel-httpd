<!doctype html>
<html lang="ja">
   <head>
@php /* メタ情報 */ @endphp
{!! $meta_tag !!}
@php /* title どうしよ */ @endphp
<title>サイトメンズエステスト</title>
@php /* css まとめておく */ @endphp
{!! $css_tag !!}
   </head>
   <body>
      <div><span><a href="{{ config('app.url') }}">他の地域</a></span><span><a href="{{ config('app.url') }}/{{ $url_area }}">近隣店舗</a></span></div>
      <header></header>
      <main>
         <section>
            <div class="shop_img">
               <img src="{{asset('img/site/site_top_site_id_'.$site_id.'.png')}}">
            </div>
            <div class="shop_tag">
            @foreach($m_tab as $tno => $tary)
               <a href="{{ config('app.url') }}/{{ $url_area }}/{{ config('EstheSite.memo.site_url_prefix') }}{{ $site_id }}/{{ config('EstheSite.tab.'.$tary['master_id'].'') }}">
                  <span>{{ $tary['name'] }}</span>
               </a>
            @endforeach
            </div>
            <div class="shop_detail">
               <h2>{{$sites[$site_id]['name']}}</h2>
               <p>{{$shops[$sites[$site_id]['shop_id']]['short_name']}}</p>
               <div class="flex">
                  <i class="bi bi-clock-fill"></i>
                  <p>{{$shops[$sites[$site_id]['shop_id']]['opening_text']}}</p>
               </div>
               <div class="flex">
                  <i class="bi bi-shop"></i>
                  <p>{{$shops[$sites[$site_id]['shop_id']]['address1']}}</p>
               </div>
               <div class="flex">
                  <i class="bi bi-telephone-outbound-fill"></i>
                  <p>{{$shops[$sites[$site_id]['shop_id']]['tel']}}</p>
               </div>
               <div class="shop_detail_map  btn btn-primary">
                  <a href="https://maps.app.goo.gl/j34cf6wfeDzG9jMa6">
                     <i class="bi bi-shop"></i>
                     <p>マップ</p>
                  </a>
               </div>
               

            </div>

         </section>
@foreach($d_tab as $no => $dtary)
   <section class="shop_sec">
   <div class="border border-2">
   @if(isset($dtary['title']))
      <div class="border">
      <h3 class="shop_cnt_ttl">{{ $dtary['title'] }}</h3>
      </div>
      @if(isset($dtary['sub_title']))
         <div class="shop_cnt_sbttl">
         {!! $dtary['sub_title'] !!}
         </div>
      @endif
      @php /* 手動コンテンツ */ @endphp
      @if(isset($dtary['content']))
         <div>
         {!! $dtary['content'] !!}
         </div>
      @endif
      @php /* 紐づけコンテンツ　出勤・動画・ブログ類　elseifで繋げる */ @endphp
      @if('top_movie' == $dtary['event'])
      @php /* 動画 */ @endphp
                  <div class="d-flex" style="height: 300px;">
                     @if(isset($mvs['siteid>no'][$site_id]))
                        @foreach($mvs['siteid>no'][$site_id] as $no => $mary)
                           <div style="width: 130px; height: 100px;">
                              <div>{{ $mary['title'] }}</div>
                              <video class="cast-video w-100" src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $mary['file_path'] }}/{{ $mary['file_name'] }}.mp4" controls=""></video>
                              @php /* キャストの名前が存在するならという書き方 フリーは存在しない　<div>ごとにした方がいいね */ @endphp
                              @if(isset($casts[$mary['cast_id']]['source_name']))
                                 <div>{{ $casts[$mary['cast_id']]['source_name'] }}</div>
                              @endif
                              <div>{{ $mary['content'] }}</div>
                           </div>
                        @endforeach
                     @endif
                  </div>
      @elseif('top_today_work' == $dtary['event'])
                  @if(isset($scd['d>cid'][$ymd_date]))
                     <div class="border d-flex shop_cast_box">
                     @foreach($casts as $cid => $cary)
                        @if(in_array($cid, $scd['no>cid']))
                           <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/cast/{{ $cid }}">
                           <div class="border">
                              @if(isset($sid_cid_imgs[$site_id][$cid]))
                                 <div>
                                    <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $sid_cid_imgs[$site_id][$cid][0]['directory'] }}LM_{{ $sid_cid_imgs[$site_id][$cid][0]['path'] }}" width="200"/>
                                 </div>
                              @else
                                 <div>
                                    <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage/no-image.jpg" />
                                 </div>
                              @endif
                              <div>{{ $casts[$cid]['source_name'] }}</div>
                              <div>{{ $scd['d>cid'][$ymd_date][$cid]['start_time'] }}</div>
                              <div>{{ $scd['d>cid'][$ymd_date][$cid]['end_time'] }}</div>
                           </div>
                           </a>
                        @endif
                     @endforeach
                     </div>
                  @endif
      @elseif('top_shop_news' == $dtary['event'])
                  @if(isset($sp_news['data']))
                     <div class="shop_news">
                     @foreach($sp_news['data'] as $no => $ary)
                        <div class="border">
                           <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/site_news/{{ $ary['id'] }}">
                           <div class="shop_news_ttl">{{ $ary['title'] }}</div>
                           <div class="shop_news_text">{{ $ary['published_at'] }}</div>
                           </a>
                        </div>
                     @endforeach
                     </div>
                  @endif
      @elseif('top_recommend' == $dtary['event'])
      @php /* 店長おすすめガール */ @endphp
                  @if(isset($reco_casts[$site_id]))
                     <div>
                     @foreach($reco_casts[$site_id] as $cid => $cary)
                        <div>
                           <div>{{ $cary['source_name'] }}</div>
                           @if(isset($sid_cid_imgs[$site_id][$cid]))
                              <div>
                                 <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $sid_cid_imgs[$site_id][$cid][0]['directory'] }}LM_{{ $sid_cid_imgs[$site_id][$cid][0]['path'] }}" width="200"/>
                              </div>
                           @else
                              <div>
                                 <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage/no-image.jpg" />
                              </div>
                           @endif
                        </div>
                     @endforeach
                     </div>
                  @endif
      @elseif('top_manager_news' == $dtary['event'])
      @php /* 店長ブログ */ @endphp
                  @if(isset($mng_blog))
                     <div>
                        @foreach($mng_blog as $no => $ary)
                           <div class="border">
                              <div>{{ $ary['title'] ?? '' }}</div>
                              <div>{{ $ary['published_at'] ?? '' }}</div>
                           </div>
                        @endforeach
                     </div>
                  
                  @endif
      @elseif('top_cast_news' == $dtary['event'])
      @php /* ブログ　*/ @endphp
         @if(isset($cblog['cb']))
            <div class="shop_cast_blog">
            @foreach($cblog['cb'] as $bid => $ary)
               <div class="border">
               <a href="{{ config('app.url') }}/{{ $url_area }}/{{ $url_siteid }}/cast_blog/{{ $ary['cast_id'] }}?id={{ $bid }}">
               @if(isset($cbimg[$bid]))
                  @foreach($cbimg[$bid] as $no => $biary)
                     <div class="shop_cast_blog_img">
                     <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $biary['image_url'] }}" width="200"/>
                     </div>
                  @endforeach
               @endif
               <div>{{ $ary['title'] }}</div>
               <div>{{ $ary['published_at'] }}</div>
               @if(isset($casts[$ary['cast_id']]['source_name']))
                  <div>{{ $casts[$ary['cast_id']]['source_name'] }}</div>
               @endif
               </div>
               </a>
            @endforeach
            </div>
         @endif
      @endif

   @endif
            </div>
         </section>
@endforeach
         </section>
      </main>
</body>
</html>