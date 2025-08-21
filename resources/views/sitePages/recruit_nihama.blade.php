@extends('layouts.site')

@section('title','新居浜のメンズエステ求人｜女性向け高収入バイトはコスモ天国ネット')
@section('description','新居浜市の風俗求人です。お金を稼げて人気のメンズエステ求人が掲載されている【コスモ天国ネット】なら安心・安全な高収入求人情報がきっと見つかります！新居浜エリアの高収入アルバイトを検索するなら風俗求人サイト【コスモ天国ネット】にお任せください！コスモ天国ネットは女の子の味方の求人情報サイトです。')
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
        <h2 class="bg_col_2">新居浜エリア</h2>
        <img src="./img/niihama_area_top_1000_400.jpg">
     </div>
      <!--新居浜-->
      <div class="kjn_box ">
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=5&site_id=115&tab_id=17">
                  <img src="{{ asset('img/reityer_nhm.jpg') }}" alt="新居浜エリアのメンズエステ店・レイチェル新居浜店の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=5&site_id=115&tab_id=17">
                  <h3>レイチェル新居浜店</h3>
               </a>
                <div>メンズエステ</div>
            </div>
            <p>身バレ対策が整った安心の環境。自由な働き方でしっかり稼ぎたい方におすすめです。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:090-1003-8287">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/VeRXZkvdiw" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:spa-niihama@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="kjn_text">
            <h2>新居浜エリアで高収入を目指す女性へ</h2>
            <p>
              新居浜エリアはメンズエステのお仕事が中心で、地域に根ざした働きやすい環境が整っています。  
              未経験の方でも安心して始められる<strong>研修制度やサポート体制</strong>が充実しており、女性が無理なく高収入を目指せます。  
              <strong>日払い対応や送迎サービス</strong>もあり、プライバシー対策も万全なので、副業や身バレが気になる方にもおすすめです。  
              新居浜でメンズエステのお仕事をお探しの方は、ぜひ求人情報をチェックして、自分に合ったお仕事を見つけてください。
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
            <div class="ala_lst_item lst_sto bg_col_3">
               <a href="{{ $krn_url }}/recruit_sikokutyuou/#recruit_view" title="四国中央">四国<br />中央</a>
            </div>
            <div class="ala_lst_item bg_col_4">
               <a href="{{ $krn_url }}/recruit_kotohira/#recruit_view" title="琴平">琴平</a>
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