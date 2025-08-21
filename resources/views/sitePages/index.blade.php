@extends('layouts.site')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！')
@section('keywords',  $keywords )

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
  <link rel="stylesheet" href="{{ asset('css/site/miru_top.css?202507031056') }}">
    <style>
    main{
        background-image:url("{{ asset('img/bg_umi.jpg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        padding-bottom:80px;    
    }
    header{
      /*
        background-image: url("{{ asset('img/header_apr.png') }}");
        background-position: center center;
        background-size: cover;
      */
      background: linear-gradient(to top, #1ab5e7 0%, #ffffff 60%);
      }
    .logo{
      /*
        background-color:#fff;
      */
        padding:0 5px;
    }
    </style>
@endsection

@section('content')
  <div id="full-overlay">
    <div class="inner">
    <h1 class="mb-0 underage_title">コスモ天国ネット</h1>
    <h1 class="my-1 underage_title">オフィシャルサイト</h1>
    <h1 class="underage_title">コスモグループ</h1><br>
    <img class="my-3" src="{{ asset('img/logo.png') }}" alt="コスモ天国ネット 愛媛と香川の風俗情報サイト"  style="width: 300px; object-fit: contain;max-width:100%"/><br>
    <span>当サイトは18歳未満または高校生の利用を</span><br>
    <span>お断りしております。</span><br>
    <span>あなたは18歳以上ですか？</span><br>
    <div class="row mt-4">
      <div class="col-6">
        <small class="ml-5">ハイ</small>
      </div>
      <div class="col-6">
        <small class="mr-5">イイエ</small>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <button class="btn underage_yes_btn close-overlay ml-5"><span>ENTER</span></button>
      </div>
      <div class="col-6">
        <a href="./site/underage" class="btn btn-light underage_no_btn mr-5"><span>LEAVE</span></a>
      </div>
    </div>
    </div>
  </div>
  <section>
    <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mt-1" style="background-color: unset;">
        @foreach($breadCrumbs as $breadCrumb)
        @if ($loop->count == 1)
          <li class="breadcrumb-item active" aria-current="page"><small>{{ $breadCrumb['label'] }}</small></li>
        @else
          @if ($loop->last)
            <li class="breadcrumb-item active" aria-current="page"><small>{{ $breadCrumb['label'] }}</small></li>
          @else
            <li class="breadcrumb-item"><a href="{{ $breadCrumb['url'] }}"><small>{{ $breadCrumb['label'] }}</small></a></li>
          @endif
        @endif
        @endforeach
      </ol>
    </nav>
      <div class="row">
        @foreach($cards as $card)
        <div class="col-6 col-md-4">
          <!-- <a href="{{ $card['url'] }}" style="text-decoration: none !important">
            <div class="card my-3" style="background-color: {{ $card['color'] }};  position: relative;">
              <div class="text-center genre-card-title">
                <div class="genre-card-triangle"></div>
                  <h2 class="fw-bold" style="font-size: 1rem">{{ $card['text'] }}</h2>
              </div>
            </div>
          </a> -->
          <a href="{{ $card['url'] }}" class="none" style="text-decoration: none !important">
            <div class="card" style="background-color: {{ $card['color'] }};">
              <img src="{{ $card['image'] }}" class="card-img-top top_img" alt="{{ $card['text'] }}">
              <div class="card-body top_img_text_area">
                <h2 class="fw-bold text-center top_img_text">{{ $card['text'] }}</h2>
              </div>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  <section>
     {!! $qrCode !!}
  </section>
  <section>
<!--ニュース記事削除
    <div class="container">
      <div class="news text-center">
        <h3 class="news-title">NEWS</h3>
-->
        <!-- <span>新着情報</span> -->
<!--
        @if($news->isNotEmpty())
        <div class="news-scroll">
          <table class="news-table">
            @foreach($news as $n)
            <tr class="news-tr">              
                <td class="news-td text-nowrap pr-4">[{{ date('Y年m月d日',strtotime($n->display_date)) }}]</td>
                <td><a href="{{ route('site.notice',['id' => $n->id]) }}">{{ $n->title }}</a></td>
            </tr>
            @endforeach
          </table>
        </div>
        @else
          <p>お知らせはありません。</p>
        @endif
      </div>
    </div>
-->
    <div class="col-12 text-center  mb-5">
       <a href="https://459x.dogo459.com/recruit_top" target="_blank"><img class="top_banner" src="{{ asset('img/recruit_cast_banner.jpg') }}" alt="女の子求人バナー"/></a>
    </div>
    <div class="col-12 text-center  mb-5">
       <a href="https://cosmo-group.co.jp/recruit.php" target="_blank"><img class="top_banner" src="{{ asset('img/recruit_banner.jpg') }}" alt="コスモグループ 採用情報"/></a>
    </div>
  </section>
  <section class="container">
    <div class="test_text">
      <h3 class="test_text_title text-center">
        COSMO GROUP<br />
        <span>安心＆安全に遊べるコスモグループ</span>        
      </h3>
      <div class="test_text_official">
        <p class="text_red">
          愛媛県・香川県を中心に展開する<br />
          四国最大手の「コスモグループ」の<br />
          公式ポータルサイトです。
        </p>
        <p>四国でナイトアミューズメント店３０店舗展開！</p>
      </div>
      <div class="test_text_content">
        <p>
          コスモグループは、愛媛と香川でナイトレジャーやメンズエステなどの店舗を展開している四国で最大のグループです。<br>
          ただグループと言っても、持ち株会社(ホールディング)の下で、各店舗が子会社として管理されているわけではありません。<br>
          それぞれの店舗には全て社長が在籍していて、単独の法人として独立しています。<br>
          コスモグループという名称の中まではありますが、全ての店舗は別会社で独立採算制を採用していて、経営の責任も各店の社長が負う形を取っています。<br>
          これによって、同じグループで合っても、場合によってはライバルになり、各店舗が切磋琢磨しながら、常にお客様に満足していただけるサービスを生み出し提供することを心掛けています。<br>
          ナイトレジャーは、目に止まりにくい産業ではありますが、大勢のファンの皆様に支えられている社会に必要な娯楽です。<br>
          コスモグループはこれからも社会の一員として、各店舗が知識を出し合い、社会規則を厳守しながら、より楽しい時間と空間を創造してまいります!</p>          
        </p>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="row">
        <div class="col-12 text-center  mb-3 bgc_1 pd_bottom">
          <a href="https://cosmo-group.co.jp/" target="_blank"><img class="top_banner" src="{{ asset('img/official_banner.jpg') }}" alt="コスモグループで四国最大級のエンターテインメントを！"/></a>
        </div>
      </div>
    </div>
  </section>
  <section class="container">
    <div class="portal text-center">
      <p>
        総合ポータルサイト<br />
        COSMO GROUP PORTAL SITE
      </p>
    </div>
  </section>
  <!-- 求人画像ポップアップ -->
  <div id="recruitPopup" class="recruit-popup">
    <a href="{{ $krn_url }}/recruit_top">
      <img src="{{ asset('img/cast_recruit_maru.png') }}" alt="求人情報">
    </a>
  </div>
@endsection

@section('script')
<script>
  $(function() {
    // 「みる？」ボタンクリック
    const is_underage = localStorage.getItem('is_underage')
    if(!is_underage) {      
      $('#full-overlay').css('display','initial');
    }

    $(".close-overlay").on('click', function() {
      //localstorageに保存しておく
      localStorage.setItem('is_underage',true)
      $("#full-overlay").fadeOut();
    });
    
    //ログ
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      // contentType: "application/json",
      url: "{{ route('site.log.top') }}",
      dataType:"json",
    }).done(function(data,status,jqXHR) {

    }).fail(function(jqXHR, textStatus, errorThrown) {
        
    }).always(function (data) {
      // 常に実行する処理
    });
  });
</script>
<script src="{{ $krn_url }}/js/miru_ev_no.js"></script>
@endsection
