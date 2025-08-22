<!doctype html>
<html lang="ja">
   <head>
{!! $tag_meta !!}
   <title>メンズエリアエステスト</title>
{!! $css_tag !!}
   </head>
   <body>
      <header>
         <section>
            <div class="head">
               <div class="head_img">
                  <img src="{{ asset('img/mens_est_logo.png') }}" alt="メンズエステランドのロゴ" class="logo_img">
               </div>
               <div class="head_pc">
                  <button class="btn menu-button rounded-0 sign_btn" style="background: #EE5318">
                     <i class="fas fa-home" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン 天国ネットからそのままもってきた　以下同文 -->
                     <p class="text-white menu-text">会員登録</p>
                  </button>
                  <button class="btn menu-button rounded-0 login_btn" style="background: #EC1070">
                     <i class="fas fa-sign-in-alt" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
                     <p class="text-white menu-text">ログイン</p>
                  </button>
                  <button class="btn menu-button rounded-0 contact_btn" style="background: #BF0D28">
                     <i class="fas fa-envelope" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
                     <p class="text-white menu-text">お問い合わせ</p>
                  </button>
               </div>
               <div class="head_sp">
                  <div class="hamburger" id="hamburger">
                     <div class="bar"></div>
                     <div class="bar"></div>
                     <div class="bar"></div>
                  </div>
                  <nav class="menu" id="menu">
                      <span class="close-btn" id="closebtn">&times;</span>
                      <a href="#">トップ</a>
                      <a href="#">松山</a>
                      <a href="#">新居浜</a>
                      <a href="#">四国中央</a>
                      <a href="#">会員登録</a>
                      <a href="#">ログイン</a>
                      <a href="#">お問い合わせ</a>
                  </nav>
               </div>
            </div>
         </section>
      </header>
{{ $path_area }}
   <main>
      <section>
         <div class="area_serect">
         @foreach(config('EstheSite.area_name') as $akey => $av)
            @if($akey == $path_area)
            <a href="/{{ $akey }}" class="atr">
            @else
            <a href="/{{ $akey }}">
            @endif
               <p>{{ $av }}</p>
            </a>
         @endforeach
         </div>
      </section>
      <section>
         <div class="est_area">
         @foreach(config('EstheSite.area_name') as $akey => $av)
            @if($akey == $path_area)
            <h2>{{$av}}のメンズエステ一覧</h2>
            @endif
         @endforeach
            @foreach($sites as $sid => $sary)
            <div class="est_tenp">
               <div class="est_tenp_info">
                  <p>{{ $sary['content'] }}</p>
               </div>
               <div class="est_tenp_name">
                  <div class="est_tenp_name_img">
                     <a href="./{{ $path_area }}/{{$def_mens_ary['memo']['site_url_prefix']}}{{$sid}}">
                        <img src="{{ asset('img/area/shop_img_'. $sary['id'] . '_.jpg') }}">
                     </a>
                  </div>
                  <div class="est_tenp_name_text">
                     <a href="./{{ $path_area }}/{{$def_mens_ary['memo']['site_url_prefix']}}{{$sid}}">
                        <h3>{{ $sary['name'] }}</h3>
                        <p>
                           @foreach($SidGidAry[$sid] as $gno => $gid)
                              {{ $genres[$gid]['name'] }} &nbsp;
                           @endforeach
                        </p>
                        <p>{{ $shops[ $sary['shop_id'] ]['address1'] }}</p>
                        <p>{{ $shops[ $sary['shop_id'] ]['opening_text'] }}</p>
                     </a>
                  </div>
               </div>
               <!--表示試験で入力していたもの。　不要のため近々削除予定
               <div class="est_tenp_cast">
                  <div class="est_tenp_cast_today">
                     <img src="{{ asset('img/test_cast.jpg') }}">
                     <div>
                        <p>キャスト名</p>
                        <p>14:00～21:00</p>
                     </div>
                  </div>
                  <div class="est_tenp_cast_today">
                     <img src="{{ asset('img/test_cast.jpg') }}">
                     <div>
                        <p>キャスト名</p>
                        <p>14:00～21:00</p>
                     </div>
                  </div>
                  <div class="est_tenp_cast_today">
                     <img src="{{ asset('img/test_cast.jpg') }}">
                     <div>
                        <p>キャスト名</p>
                        <p>14:00～21:00</p>
                     </div>
                  </div>
                  <div class="est_tenp_cast_today">
                     <img src="{{ asset('img/test_cast.jpg') }}">
                     <div>
                        <p>キャスト名</p>
                        <p>14:00～21:00</p>
                     </div>
                  </div>
                  <div class="est_tenp_cast_today">
                     <img src="{{ asset('img/test_cast.jpg') }}">
                     <div>
                        <p>キャスト名</p>
                        <p>14:00～21:00</p>
                     </div>
                  </div>
                  <div class="est_tenp_cast_today">
                     <img src="{{ asset('img/test_cast.jpg') }}">
                     <div>
                        <p>キャスト名</p>
                        <p>14:00～21:00</p>
                     </div>
                  </div>
               </div>
               -->
               <div class="est_tenp_cast" memo="テストの入れ物">
               @if(isset($scary[$sid]['scd']['no>cid']))
                  @foreach($casts as $cid=> $cary)
                     @if(in_array($cid, $scary[$sid]['scd']['no>cid']))
                        <div class="est_tenp_cast_today">
                           <a href="./{{ $path_area }}/{{$def_mens_ary['memo']['site_url_prefix']}}{{$sid}}/cast/{{ $cid }}">
                        @if(isset($cimgs[$sid][$cid][0]['path']))
                           <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage{{ $cimgs[$sid][$cid][0]['directory'] }}LM_{{ $cimgs[$sid][$cid][0]['path'] }}" width="120px"/>
                        @else
                           <img src="https://{{ config('EstheSite.memo.get_img_domain') }}/storage/no-image.jpg" />
                        @endif
                           <div>
                              <p>{{ $casts[$cid]['source_name'] }}</p>
                              <p>{{ $scary[$sid]['scd']['d>cid'][ $scary[$sid]['day_change']['now_date'] ][$cid]['start_time'] ?? 0 }}～
                              {{ $scary[$sid]['scd']['d>cid'][ $scary[$sid]['day_change']['now_date'] ][$cid]['end_time'] ?? 0 }}</p>
                           </div>
                           </a>
                        </div>
                     @endif
                  @endforeach
               @endif


               </div>
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
     
   </main>
{!! $js_tag !!}


<?php
print '<br /><br /><br /><br /><br /><br />'
?>
@foreach($areap as $ky => $ary)
   <div>{{ $ary['name'] }}</div>
   <div>{{ $ary['content'] }}</div>
   <hr />
@endforeach


<br />
<br />
@foreach($sites as $sid => $sary)
   <div>ID:{{ $sid }}</div>
   <div>店名:{{ $sary['name'] }}</div>
   <div> 業種：
   @foreach($SidGidAry[$sid] as $gno => $gid)
      {{ $genres[$gid]['name'] }} &nbsp;
   @endforeach
   </div>
   <div>開店時間：{{ $shops[ $sary['shop_id'] ]['opening_text'] }}</div>
   <div>休業日：{{ $shops[ $sary['shop_id'] ]['holiday_text'] }}</div>
   <div>お問合せ：{{ $shops[ $sary['shop_id'] ]['tel'] }}</div>
   <div>場所：{{ $shops[ $sary['shop_id'] ]['address1'] }}</div>
   <hr />
@endforeach

@php

dd($url_area);
//$def_mens_ary['memo']['site_url_prefix'].$site_id

@endphp
<br />
<br />
<br />
<br />
{!! $js_tag !!}
   </body>
</html>