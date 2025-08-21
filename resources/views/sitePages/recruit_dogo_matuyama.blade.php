@extends('layouts.site')

@section('title','松山・道後エリアの女性向け高収入求人｜風俗・メンズエステの求人ならコスモ天国ネット')
@section('description','松山・道後の風俗求人です。厳選された風俗・メンズエステ求人が掲載されている【コスモ天国ネット】なら安心・安全な高収入求人情報がきっと見つかります！松山の高収入アルバイトを検索するなら風俗求人サイト【コスモ天国ネット】にお任せください！コスモ天国ネットは女の子の味方の求人情報サイトです。')
@section('keywords', '女性求人,風俗,ソープ,ヘルス,メンズエステ,松山,道後')
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
<link rel="stylesheet" href="{{ asset('css/site/miru_top.css?202507051918') }}">
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
       @if('dogo' == $krn_gtu)
     <div class="kjn_cnt ttl_kre atv" id="ttl1">
        <h2 class="bg_col_1 scroll_target"><span>高収入　女性求人</span><br />道後エリア</h2>
        <img src="{{ asset('img/dogo_area_top_1000_400.jpg') }}">
     </div>
     @else
     <!--松山タブ-->
     <div class="kjn_cnt ttl_kre" id="ttl2">
        <h2 class="bg_col_1 scroll_target">高収入　松山エリア</h2>
        <img src="{{ asset('img/matsuyama_area_top_1000_400.jpg') }}">
     </div>
     @endif
      <div id="recruit_view"></div>
      <div id="recruit_view_2"></div>
      <div class="tabs">
         <a href="./recruit_dogo_matuyama?mord=dogo#recruit_view">
            <div class="tab active shake-button">道 後</div>
         </a>
         <a href="./recruit_dogo_matuyama?mord=matsuyama#recruit_view_2">
            <div class="tab shake-button">松 山</div>
         </a>
      </div>
      <!--道後エリア-->
      @if('dogo' == $krn_gtu)
      <div class="tab-content active" id="mord1">
         <div class="tnp">          
            <div class="tnp_img">
               <a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=110&tab_id=9#fst_view">
                  <img src="{{ asset('img/aidoru.jpg') }}" alt="道後エリアのヘルス店・愛ドル学園の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=110&tab_id=9">
                  <h3>愛ドル学園</h3>
               </a>
               <div>
                  <a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=110&tab_id=9#fst_view">
                     <img src="{{ asset('img/health_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               未経験でも安心♪高収入＆身バレ対策も万全！送迎・寮完備で働きやすさ◎
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-142-311">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/vG362lU_ij" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:t0120142311@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=118&tab_id=8">
                  <img src="{{ asset('img/sirayuki.jpg') }}" alt="道後エリアのソープランド店・道後しらゆきひめの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=118&tab_id=8">
                  <h3>道後しらゆきひめ</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/soap_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               愛媛県内送迎無料◎未経験者も安心して働ける環境です。待遇も充実しています！すぐに面接可能で高収入！
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-312-101">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/2KnhLM9Ewo" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:dogo-shirayuki@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=2&tab_id=9">
                  <img src="{{ asset('img/torebvi_mty.jpg') }}" alt="道後エリアのヘルス店・道後トレビの泉の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=2&tab_id=9">
                  <h3>道後トレビの泉</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/health_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               清掃徹底の清潔空間♪託児所・寮完備で子育て中も安心◎アリバイ対策ありで秘密厳守！
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-847-340">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/gFItc5HWDF" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:izumi7340@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=10&tab_id=8">
                  <img src="{{ asset('img/banira.jpg') }}" alt="道後エリアのホテヘル・デリヘル店・バニラリップの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=10&tab_id=8">
                  <h3>バニラリップ</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/delivery_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
              未経験・出稼ぎ歓迎◎主要ホテル近くの好立地で集客安定！託児所・性病対策・秘密対策も万全です♪
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-707-499">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/PzK3MtVArZ" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:lesson-one@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=123&tab_id=9">
                  <img src="{{ asset('img/ameijin.jpg') }}" alt="道後エリアのヘルス店・アメイジングビルの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=123&tab_id=9">
                  <h3>アメイジングビル</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/health_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               講習体制が整っていて未経験でも安心♪送迎・生理休暇・キレイな空間など嬉しい条件が満載です！
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-490-719">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/75LHkodEAy" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:amazing@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=130&tab_id=9">
                  <img src="{{ asset('img/hitoduma.jpg') }}" alt="道後エリアのヘルス店・優しい人妻の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=130&tab_id=9">
                  <h3>優しい人妻</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/health_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               高収入狙える高バック率！自由出勤＆送迎完備◎託児所提携もあり子育て中の方にも優しいお店です♪
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-490-820">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/ty_5eaetu8" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:08029719438@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=24&tab_id=8">
                  <img src="{{ asset('img/akakuro.jpg') }}" alt="道後エリアのヘルス店・赤と黒の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=24&tab_id=8">
                  <h3>赤と黒</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/health_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               豪華な店内は必見♪衛生管理◎＆モニター確認で安心！身バレが心配な方にもおすすめの人気店☆
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-490-720">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/VnHSAo0TuQ" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:r-b-p@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=137&tab_id=8">
                  <img src="{{ asset('img/eikoku.jpg') }}" alt="道後エリアのソープランド店・英乃國屋の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=137&tab_id=8">
                  <h3>英乃国屋</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/soap_640_190px.png')}}" alt='ソープテスト画像'>
                  </a>
               </div>
            </div>
            <p>
               未経験でも安心！マニュアル＆稼げるノウハウあり◎送迎＆即入寮OKのマンション完備で快適勤務♪
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-145-586">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/eQ63kiD9sr" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:eikoku8751@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=133&tab_id=18">
                  <img src="{{ asset('img/hatimitu.jpg') }}" alt="道後エリアの風俗エステ店・ごほうびスパはちみつの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=133&tab_id=18">
                  <h3>ごほうびスパ　はちみつ</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/fuzoku_esthe_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               身バレ対策ばっちり！モニター確認OK◎寮・送迎・託児所付きで未経験でも安心の職場環境です☆
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-932-6840">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/pqNqOB-v09" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:honey6840@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=136&tab_id=9">
                  <img src="{{ asset('img/esute_tamasi.jpg') }}" alt="道後エリアのヘルス店・回春エステ魂の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=136&tab_id=9">
                  <h3>回春エステ魂</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/health_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               初心者OK！ソフトサービス中心で安心♪研修あり◎送迎・アリバイ・生理休暇などサポート万全！
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-707-499">電話</a>
                <a class="tnp_ren_line" href="#" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:lesson-one@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="kjn_text">
           <h2>道後エリアで高収入を目指す女性へ</h2>
           <p>
             道後エリアは観光地としても知られており、常に多くのお客様が訪れるため、安定した集客が期待できます。
             <strong>未経験でも安心して働ける</strong>環境や、<strong>送迎・寮完備、託児所提携</strong>といった女性に嬉しいサポートが充実しています。
             プライバシー対策も万全なので、副業や身バレが心配な方にもおすすめです。
             高収入・日払いOKの求人情報をぜひチェックして、自分に合ったお仕事を見つけてください。
           </p>
         </div>
      </div>
      <!--松山エリア-->
      @else
      <div class="tab-content" id="mord2">
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=26&tab_id=18">
                  <img src="{{ asset('img/erizabes.jpg') }}" alt="松山エリアのメンズエステ店・エリザベスの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=26&tab_id=18">
                  <h3>エリザベス</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/mens_esthe_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               幅広い年齢層が未経験から安心して働ける環境。お昼も高収入が狙えます。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-947-1301">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/abwmQbftG1" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:spa.eli3@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=28&tab_id=18">
                  <img src="{{ asset('img/hiza.jpg') }}" alt="松山エリアのメンズエステ店・膝麻久庵の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=28&tab_id=18">
                  <h3>膝麻久庵</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/mens_esthe_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               18歳〜40歳の女性募集。未経験者歓迎の安心店舗型で、身バレ対策も万全です。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-933-8171">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/neLqe8x7Fm" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:hizamakuan1@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=31&tab_id=18">
                  <img src="{{ asset('img/reityeru.jpg') }}" alt="松山エリアのメンズエステ店・レイチェルの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=31&tab_id=18">
                  <h3>レイチェル</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/mens_esthe_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               未経験歓迎で研修充実。待機時間も給与支給あり、高収入を目指せるお店です。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-915-5680">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/xcZsVPQza6" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:reicheru-aroma1@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=153&tab_id=18">
                  <img src="{{ asset('img/roiyaru.jpg') }}" alt="松山エリアのメンズエステ店・セレブメンエスロイヤルの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=2&area_id=14&site_id=153&tab_id=18">
                  <h3>セレブメンエス　ロイヤル</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/mens_esthe_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               未経験歓迎で託児所提携など女性に優しい待遇が充実。体験入店や遠方からの出稼ぎも歓迎です。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-986-6680">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/cscc3iVzLo" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:celeb_royal@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=4&area_id=14&site_id=122&tab_id=36">
                  <img src="{{ asset('img/usa.jpg') }}" alt="松山エリアのセクキャバ店・USAの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=4&area_id=14&site_id=122&tab_id=36">
                  <h3>U-S-A</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/sexy_cabalet_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               可愛い衣装貸与で楽しく働ける環境。頑張りが給与に反映され、高収入も目指せます。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:0120-440-606">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/MufgSydfeU" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:campus_usa@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=6&area_id=13&site_id=33&tab_id=48">
                  <img src="{{ asset('img/enkaiya.jpg') }}" alt="松山エリアのコンパニオン店・道後宴会屋の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=6&area_id=13&site_id=33&tab_id=48">
                  <h3>道後宴会屋</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/enkai_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               明るく楽しい宴会をサポート。未経験歓迎で丁寧な研修あり、安心して働けます。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-933-6767">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/WRpyZ20Bum" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:enkaiya5151@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="tnp">          
            <div class="tnp_img">
               <a class="k_clr1" href="">
                  <img src="{{ asset('img/hitoduma.jpg') }}" alt="松山エリアの飲食店・しゃぶしゃぶ田中の求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="">
                  <h3>しゃぶしゃぶ田中</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/eat_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
              未経験歓迎！明るい接客でお客様をおもてなし。丁寧な研修で安心スタートできます。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-933-6767">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/WRpyZ20Bum" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:">メール</a>
            </div>
         </div>
         <div class="tnp">
            <div class="tnp_img">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=5&area_id=14&site_id=114&tab_id=42">
                  <img src="{{ asset('img/biosu.jpg') }}" alt="松山エリアの整体・マッサージ店・もみほぐしビオスの求人バナー">
               </a>
            </div>
            <div class="tnp_ttl">
               <a class="k_clr1" href="https://459x.com/detail/top?genre_id=5&area_id=14&site_id=114&tab_id=42">
                  <h3>もみほぐしビオス</h3>
               </a>
               <div>
                  <a>
                     <img src="{{ asset('img/mens_esthe_640_190px.png')}}" alt='テスト画像'>
                  </a>
               </div>
            </div>
            <p>
               駅チカ新店で未経験歓迎。施術着貸与や各種保険完備で安心して働けます。
            </p>
            <div class="tnp_ren">
                <a class="tnp_ren_tel" href="tel:089-932-7332">電話</a>
                <a class="tnp_ren_line" href="https://line.me/ti/p/loUAeNnNjk" target="_blank" rel="noopener noreferrer">LINE</a>
                <a class="tnp_ren_mail" href="mailto:bios-matsuyama88@docomo.ne.jp">メール</a>
            </div>
         </div>
         <div class="kjn_text">
            <h2>松山エリアで安心して働ける高収入バイトを探す女性へ</h2>
            <p>
              松山エリアでは、<strong>メンズエステ</strong>や<strong>セクキャバ、飲食店</strong>など、風俗以外で<strong>高収入を目指せる女性向け求人</strong>が豊富です。
              <strong>未経験歓迎・日払いOK・自由出勤</strong>など、ライフスタイルに合わせて働きやすい条件が整っており、<strong>学生・主婦・Wワーク希望の方</strong>にも人気があります。
              また、<strong>託児所提携・送迎サービス・体験入店</strong>などのサポートも充実しているため、安心してお仕事を始められます。
              松山で自分らしく働けるバイトを見つけて、新しい一歩を踏み出してみませんか？
            </p>
         </div>
      </div>
      @endif
      <div id="ala_line"></div>
      <div class="ala">
         <h2>高収入求人エリア一覧</h2>
         <div class="ala_lst">
            <div class="ala_lst_item bg_col_1 shine_button" style="position: relative;">
               @if('dogo' == $krn_gtu)
               <a href="{{ $krn_url }}/recruit_dogo_matuyama/?mord=matsuyama#recruit_view_2" title="松山">松山</a>
               @else
               <a href="{{ $krn_url }}/recruit_dogo_matuyama/?mord=dogo#recruit_view" title="道後">道後</a>
               @endif
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
      <!--
      <div class="tabs">
         @if('dogo' == $krn_gtu)
        <div class="tab shake-button" ><a href="#ttl1">道 後</a></div>
         @else
        <div class="tab shake-button" ><a href="#ttl2">松 山</a></div>
         @endif
      </div>
-->
      <div class="mdr">
         <a href="{{ $krn_url }}/recruit_top">
            <p>求人トップへ戻る</p>
         </a>
      </div>
   </section>
   <section class="kjn_sec">
      <div class="kjn_contact">
         <a href="https://cosmo-group.co.jp/recruit.php" title="高収入男性求人がいっぱい｜愛媛エリア・香川エリア｜正社員・アルバイト情報｜コスモグループ" target="_blank">
            <h2>PR|<span>男性</span>向け<span>高収入</span>求人<br/>正社員・アルバイト<span>情報</span>はこちら</h2>
            <p>求人に関するご質問や、応募方法についての詳細は、お気軽にお問い合わせください。</p>
         </a>
         <a href="https://cosmo-group.co.jp/recruit.php" title="高収入男性求人がいっぱい｜正社員・アルバイト情報｜コスモグループ" target="_blank">
            <img src="{{ asset('img/mens_1800_360px_1.jpg') }}" alt="高収入男性求人をすぐご案内できます｜正社員・アルバイト情報｜コスモグループ" target="_blank">
         </a>
      </div>
   </section>

@endsection
@section('script')
<script src="{{ $krn_url }}/js/miru_ev_no.js"></script>
@endsection