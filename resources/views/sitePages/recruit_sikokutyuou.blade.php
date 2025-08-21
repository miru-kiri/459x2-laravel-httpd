@extends('layouts.site')

@section('title','四国中央のメンズエステ・キャバクラ求人｜女性向け高収入バイトならコスモ天国ネット')
@section('description','四国中央市の風俗求人です。お金が稼げて人気のメンズエステ・キャバクラ求人が掲載されている【コスモ天国ネット】なら安心・安全な高収入求人情報がきっと見つかります！四国中央エリアの高収入アルバイトを検索するなら風俗求人サイト【コスモ天国ネット】にお任せください！コスモ天国ネットは女の子の味方の求人情報サイトです。')
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
        <h2 class="bg_col_3">四国中央エリア</h2>
        <img src="./img/shikokuchuo_area_top_1000_400.jpg">
     </div>
      <!--四国中央-->
      <div class="kjn_box ">
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=6&site_id=147&tab_id=17">
                  <img src="{{ asset('img/aroma_syokora.jpg') }}" alt="四国中央エリアのメンズエステ店・SPAアロマショコラの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=6&site_id=147&tab_id=17">
                  <h3>SPAアロマショコラ</h3>
               </a>
               <div>メンズエステ</div>
            </div>
            <p>四国最大級グループ店。快適な環境で働き方は自由自在。集客力にも期待できます。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0896-23-3663">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/neeJlWmRl5" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:s08028589654@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=3&area_id=6&site_id=98&tab_id=27">
                  <img src="{{ asset('img/nanohana.jpg') }}" alt="四国中央エリアのキャバクラ店・Club菜の花の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=3&area_id=6&site_id=98&tab_id=27">
                  <h3>Club 菜の花</h3>
               </a>
                <div>キャバクラ</div>
            </div>
            <p>地元で親しまれるガールズバー。アットホームな雰囲気で安心して働けます。</p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0896-23-1108">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/Ne36CzVR3D" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:job.menu@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="kjn_text">
            <h2>四国中央市でお仕事をお探しの女性へ</h2>
            <p>
              四国中央市では<strong>メンズエステやキャバクラのお仕事を探すことができます</strong>。  
              求人数は多くありませんが、<strong>落ち着いた環境でじっくり働きたい方におすすめ</strong>です。  
              <strong>送迎・寮完備</strong>など女性に嬉しいサポートも充実しており、<strong>未経験の方も安心してスタート可能</strong>です。  
              <strong>日払い対応</strong>や<strong>プライバシー対策も万全</strong>なので、副業や初めての方もぜひ求人情報をチェックしてみてください。
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