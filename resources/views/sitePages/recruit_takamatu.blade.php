@extends('layouts.site')

@section('title','高松の風俗求人・女性向け高収入バイトならコスモ天国ネット｜未経験歓迎・日払いOK')
@section('description','高松・城東町の風俗求人です。お金が稼げる人気の風俗・ソープ・ヘルス求人が掲載されている【コスモ天国ネット】なら安心・安全な高収入求人情報がきっと見つかります！香川エリアの高収入アルバイトを検索するなら風俗求人サイト【コスモ天国ネット】にお任せください！コスモ天国ネットは女の子の味方の求人情報サイトです。')
@section('sp-nav')
<div id="sp-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
  <ul>
    @foreach($tabs as $tab)
      <li><a href="{{ $tab['url'] }}">{{ $tab['name'] }}</a></li> 
    @endforeach
  </ul>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/site/top.css') }}">
<link rel="stylesheet" href="{{ asset('css/site/miru_top.css') }}">
<style>
main{
  background-image:url("{{ asset('img/bg_img_apr.jpg') }}");
  background-size:cover;
  padding-bottom: 70px;
}
header{
  background-image: url("{{ asset('img/header_apr.png') }}");
  background-position: center center;
  background-size: cover;
}
.logo{
  padding:0 5px;
}
</style>
@endsection
@section('content')
   <section class="kjn_sec">
     <div class="kjn_cnt">
        <h2 class="bg_col_5">高松エリア</h2>
        <img src="./img/takamatsu_area_top_1000_400.jpg">
     </div>
      <!--高松-->
      <div class="kjn_box ">
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=154&tab_id=9">
                  <img src="{{ asset('img/kyunkyun.jpg') }}" alt="高松エリアのヘルス店・きゅんきゅんの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=154&tab_id=9">
                  <h3>エステ＆ヘルス<br />きゅんきゅん</h3>
               </a>
                <div>店舗型ヘルス</div>
            </div>
            <p>身バレ対策完備で安心。出稼ぎや通勤も歓迎、女性が働きやすい環境です。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:087-822-5528">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/_PkyMuLFEN" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=111&tab_id=9">
                   <img src="{{ asset('img/torebi_tkm.jpg') }}" alt="高松エリアのソープランド店・高松トレビの泉の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=111&tab_id=9">
                  <h3>高松トレビの泉</h3>
               </a>
               <div>ソープランド</div>
            </div>
            <p>高級店ならではの待遇と集客力。未経験も安心の環境でしっかり稼げます。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-797-260">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/wA4ODERsZx" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:08019908087@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=104&tab_id=9">
                  <img src="{{ asset('img/kinpei.jpg') }}" alt="高松エリアのソープランド店・金瓶梅の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=104&tab_id=9">
                  <h3>金瓶梅</h3>
               </a>
               <div>ソープランド</div>
            </div>
            <p>集客力抜群で安定勤務可能。初心者も安心、働いてから判断できます。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-204-641">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/5878hXdQ74" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:jobkpp@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=101&tab_id=9">
                  <img src="{{ asset('img/sirayuki_tkm.jpg') }}" alt="高松エリアのソープランド店・高松しらゆき姫の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=11&site_id=101&tab_id=9">
                  <h3>高松しらゆき姫</h3>
               </a>
               <div>ソープランド</div>
            </div>
            <p>老舗で集客力抜群。即入居寮完備で出稼ぎ歓迎。身バレ対策も万全です。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-245-546">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/tqQZoLlSLG" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:shirayukihime@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="kjn_text">
            <h2>高松エリアで高収入を目指す女性へ</h2>
            <p>
              高松エリアはヘルスとソープが中心の風俗店が多く、<strong>高収入を実現したい女性におすすめの地域</strong>です。  
              <strong>未経験歓迎のサポート体制</strong>や、<strong>日払い対応・完全個室待機</strong>など、働きやすい環境が整っています。  
              さらに、<strong>送迎・寮完備・託児所提携</strong>など女性に嬉しい各種サポートも充実し、安心して働ける環境が整っています。  
              高松で風俗のお仕事をお探しの方は、ぜひ求人情報をチェックして、自分に合った働き方を見つけてください。
            </p>
         </div>
      </div>      
      <div id="ala_line"></div>
      <div class="ala">
         <h2>高収入求人エリア一覧</h2>
         <div class="ala_lst">
            <div class="ala_lst_item bg_col_1">
               <a href="{{ $krn_url }}/recruit_dogo_matuyama?mord=dogo#recruit_view" title="道後">道後</a>
            </div>
            <div class="ala_lst_item bg_col_6">
               <a href="{{ $krn_url }}/recruit_dogo_matuyama?mord=matsuyama/#recruit_view" title="松山">松山</a>
            </div>
            <div class="ala_lst_item bg_col_2">
               <a href="{{ $krn_url }}/recruit_nihama/#recruit_view" title="新居浜">新居浜</a>
            </div>
            <div class="ala_lst_item lst_sto bg_col_3">
               <a href="{{ $krn_url }}/recruit_sikokutyuou/#recruit_view" title="四国中央">四国<br />中央</a>
            </div>
            <div class="ala_lst_item bg_col_4">
               <a href="{{ $krn_url }}/recruit_kotohira/#recruit_view" title="琴平">琴平</a>
            </div>
         </div>
      </div>
      <div class="mdr">
         <a href="{{ $krn_url }}/recruit_top">
            <p>求人トップへ戻る</p>
         </a>
      </div>
   </section>
@endsection
@section('script')
<script src="{{ $krn_url }}/js/miru_ev_no.js"></script>
@endsection