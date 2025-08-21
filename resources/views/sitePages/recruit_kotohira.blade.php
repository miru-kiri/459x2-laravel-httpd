@extends('layouts.site')

@section('title','琴平の風俗求人・女性向け高収入バイト情報｜コスモ天国ネット')
@section('description','琴平エリアの風俗求人です。稼げる風俗・ソープ求人が掲載されている【コスモ天国ネット】なら安心・安全な高収入求人情報がきっと見つかります！琴平エリアの高収入アルバイトを検索するなら風俗求人サイト【コスモ天国ネット】にお任せください！コスモ天国ネットは女の子の味方の求人情報サイトです。')
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
  background-color:#fff;
  padding:0 5px;
}
</style>

@endsection

@section('content')
   <section class="kjn_sec">
     <div class="kjn_cnt">
        <h2 class="bg_col_4">琴平エリア</h2>
        <img src="./img/kotohira_area_top_1000_400.jpg">
     </div>
      <!--琴平-->
      <div class="kjn_box ">
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=10&site_id=128&tab_id=9">
                  <img src="{{ asset('img/oreno_sdr.jpg') }}" alt="琴平エリアのソープランド店・俺のシンデレラの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=10&site_id=128&tab_id=9">
                  <h3>俺のシンデレラ</h3>
               </a>
               <div>ソープランド</div>
            </div>
            <p>観光地近くで集客力◎競合少なめで安定収入。高待遇・高バック率でしっかり稼げます。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-732-253">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/BfOZKYtJj_" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:08039258236@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=10&site_id=55&tab_id=9">
                  <img src="{{ asset('img/jakku.jpg') }}" alt="琴平エリアのソープランド店・ジャックと豆の木の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=10&site_id=55&tab_id=9">
                  <h3>ジャックと豆の木</h3>
               </a>
               <div>ソープランド</div>
            </div>
            <p>競合が少ない好立地。身バレ対策も万全で安心。高収入を目指せる環境が整っています。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-732-253">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/BfOZKYtJj_" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:08039258236@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="kjn_text">
            <h2>琴平エリアで高収入を目指す女性へ</h2>
            <p>
              琴平エリアはソープランドを中心とした風俗店が多く、<strong>高収入を目指す女性にぴったりの地域</strong>です。  
              <strong>未経験でも安心の研修制度</strong>や、<strong>日払いOK・完全個室待機</strong>など、働きやすさを重視した環境が整っています。  
              また、<strong>送迎・寮完備・託児所提携</strong>といった女性に嬉しいサポートも充実しており、プライバシー対策も万全です。  
              琴平で高収入バイトを探すなら、ぜひ求人情報をチェックして自分に合った働き方を見つけてください。
            </p>
         </div>
      </div>
      <div id="ala_line"></div>
      <div class="ala">
         <h2>高収入求人エリア一覧</h2>
         <div class="ala_lst">
            <div class="ala_lst_item bg_col_1">
               <a href="{{ $krn_url }}/recruit_dogo_matuyama?mord=dogo#recruit_view" title="琴平">道後</a>
            </div>
            <div class="ala_lst_item bg_col_6">
               <a href="{{ $krn_url }}/recruit_dogo_matuyama?mord=matsuyama#recruit_view" title="松山">松山</a>
            </div>
            <div class="ala_lst_item bg_col_2">
               <a href="{{ $krn_url }}/recruit_nihama/#recruit_view" title="新居浜">新居浜</a>
            </div>
            <div class="ala_lst_item lst_sto bg_col_3">
               <a href="{{ $krn_url }}/recruit_sikokutyuou/#recruit_view" title="四国中央">四国<br />中央</a>
            </div>
            <div class="ala_lst_item bg_col_5">
               <a href="{{ $krn_url }}/recruit_takamatu/#recruit_view" title="高松">高松</a>
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