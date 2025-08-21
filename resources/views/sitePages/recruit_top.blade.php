@extends('layouts.site')

@section('title','松山・高松の女性向け高収入バイト｜風俗・メンズエステ求人ならコスモ天国ネット')
@section('description','愛媛県の松山,道後,新居浜,四国中央と香川県の高松,琴平の高収入女性求人情報が満載です。風俗,ソープ,ヘルス,メンズエステの女性求人を探すならコスモ天国ネットにお任せ！未経験から始められる高収入バイト多数掲載。日払い・個室待機など、希望条件に合った求人が見つかります。')
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
      <div class="kjn_img" >
         <img src="{{ asset('img/kyujin_idx.png') }}">
      </div>
      <div class="kjn_ttl">
         <h1>
            コスモグループの安全安心な<br />
            高収入女子キャスト求人サイトです
         </h1>
      </div>
      <div class="kjn_ela">
         <img src="{{ asset('img/kjn_mmn.png') }}">
         <h2>エリアから探す</h2>
      </div>
      <div class="map">
         <div class="map_item map_mty shine_button">
            <a href="{{ $krn_url }}/recruit_dogo_matuyama" title="松山">松山</a>
         </div>
         <div class="map_item map_nhm shine_button">
            <a href="{{ $krn_url }}/recruit_nihama" title="新居浜">新居浜</a>
         </div>
         <div class="map_item map_sto shine_button">
            <a href="{{ $krn_url }}/recruit_sikokutyuou" title="四国中央">
               四国<br />中央
            </a>
         </div>
         <div class="map_item map_khr shine_button">
            <a href="{{ $krn_url }}/recruit_kotohira" title="琴平">琴平</a>
         </div>
         <div class="map_item map_tmt shine_button">
            <a href="{{ $krn_url }}/recruit_takamatu" title="高松">高松</a>
         </div>
      </div>
      <div class="ala">
         <h2>高収入求人エリア一覧</h2>
         <div class="ala_lst">
            <div class="ala_lst_item bg_col_1 shine_button" style="position: relative;">
               <a href="{{ $krn_url }}/recruit_dogo_matuyama/#recruit_view" title="松山">松山</a>
            </div>
            <div class="ala_lst_item bg_col_2 shine_button" style="position: relative;">
               <a href="{{ $krn_url }}/recruit_nihama/#recruit_view" title="新居浜">新居浜</a>
            </div>
            <div class="ala_lst_item lst_sto bg_col_3 shine_button" style="position: relative;">
               <a href="{{ $krn_url }}/recruit_sikokutyuou/#recruit_view" title="四国中央">四国<br />中央</a>
            </div>
            <div class="ala_lst_item bg_col_4 shine_button" style="position: relative;">
               <a href="{{ $krn_url }}/recruit_kotohira/#recruit_view" title="琴平">琴平</a>
            </div>
            <div class="ala_lst_item bg_col_5 shine_button" style="position: relative;">
               <a href="{{ $krn_url }}/recruit_takamatu/#recruit_view" title="高松">高松</a>
            </div>
         </div>
      </div>
   </section>
   <section class="kjn_sec">
      <div class="kjn_info">
         <h2>取り扱い業種のご紹介</h2>
         <p>コスモ天国ネットでは、以下のような業種で女性求人・高収入バイト情報を掲載しています。それぞれ働き方や条件が異なるため、自分に合ったスタイルで無理なく働けます。</p>
         <ul>
           <li><strong>風俗求人：</strong>ソープ・ヘルス・デリヘルなど。未経験からでも始めやすく、高収入が目指せます。</li>
           <li><strong>メンズエステ：</strong>個室勤務が基本で、リラクゼーションを提供するお仕事。性的サービスなしの求人も多数。</li>
           <li><strong>セクキャバ：</strong>キャバクラよりやや接客が密になる分、高収入が狙えます。明るい雰囲気で働きやすさも◎。</li>
           <li><strong>キャバクラ：</strong>お酒を提供しながら会話を楽しむ接客業。未経験でも研修ありで安心。</li>
           <li><strong>飲食店バイト：</strong>接客や簡単な調理など、ナイトワーク以外の高収入求人も取り扱い中。</li>
           <li><strong>宴会コンパニオン：</strong>旅館や宴会場での接客中心。イベント性が高く、短期で稼ぎやすい。</li>
           <li><strong>整体・リラクゼーション：</strong>整体マッサー師によるボディケア。施術スキルが身につく人気職種。</li>
         </ul>
      </div>
   </section>
   <section class="kjn_sec">
      <div class="kjn_memo">
         <h2>コスモ天国ネットからの一口メモ：安心して働くために</h2>
         <p>
           近年、風俗業界では違法スカウトの摘発や、劣悪な労働環境を強いる悪質な店舗の存在がニュースでも報じられています。<br>
           特に、SNSや街中で声をかけてくるスカウト行為は、法的にも禁止されており、トラブルに巻き込まれるケースもあります。
         </p>
         <p>
           コスモグループが運営する「コスモ天国ネット」では、<strong>直営または信頼できる店舗のみを掲載</strong>しており、<strong>女性スタッフのサポート体制や働く環境の安全性にも配慮</strong>しています。
           大手グループとして、<strong>誠実な社員による丁寧な対応</strong>を徹底し、<strong>安心して働ける職場紹介</strong>をお約束します。
         </p>
         <p>
           「高収入＝危険」ではなく、「高収入でも安心して働ける環境」を、コスモ天国ネットは提供します。
         </p>
      </div>
   </section>


@endsection
