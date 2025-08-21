<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット</title>
    <meta name="description" content="有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！">
    <meta name="keywords" content="四国,天国ネット,風俗,メンズエステ,キャバクラ,セクキャバ,飲食店,宴会コンパニオン" />

    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:site_name" content="コスモ天国ネット">
    <meta property="og:title" content="四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット">
    <meta property="og:description" content="有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！">
    <meta property="og:image" content="{{ asset('img/twitter_card_common.jpg') }}">
    <meta name="twitter:card" content="summary_large_image" />

    <link rel="canonical" href="{{ request()->fullUrl() }}" />

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <!-- font-awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/site/layout.css') }}">
</head>
<body>
  <header>
    <div class="pc mx-3">
        <a href="{{ route('site') }}"><img class="logo" src="{{ asset('img/logo.png') }}" alt="ロゴ" style="margin-left: 20px;" /></a>
    </div>
    <div class="sp">
      <a href="{{ route('site') }}"><img class="logo mt-3" src="{{ asset('img/logo.png') }}" alt="ロゴ" style="margin-left: 20px;"></a>
      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <nav class="globalMenuSp">
        <div id="sp-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
          <ul>
              <li><a href="{{ route('site') }}">TOP</a></li> 
          </ul>
        </div>
      </nav>
    </div>
    <!-- <div class="hamburger">☰</div> -->
  </header>
  <main>
      <div class="container d-flex align-items-center justify-content-center">
        <p class="my-5">未成年の方は利用できません。</p>
      </div>
  </main>
  <footer>
    <p class="footer-copyright">
      Copyright(C)2023 コスモ天国ネット All Rights Reserved
    </p>
  </footer>
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
  <script>
      $(function() {
          $('.hamburger').click(function() {
              $(this).toggleClass('active');
              // console.log($(this).hasClass('active'))
              if ($(this).hasClass('active')) {
                  // $('.globalMenuSp').addClass('active');
                  $(".globalMenuSp").addClass('panelactive');//ナビゲーションにpanelactiveクラスを付与
              } else {
                  // $('.globalMenuSp').removeClass('active');
                  $(".globalMenuSp").removeClass('panelactive');//ナビゲーションのpanelactiveクラスも除去
              } 
          
          });
      });
      $("#sp-nav-list a").click(function () {//ナビゲーションのリンクがクリックされたら
          $(".hamburger").removeClass('active');//ボタンの activeクラスを除去し
          $(".globalMenuSp").removeClass('panelactive');//ナビゲーションのpanelactiveクラスも除去
      });
  </script>
  @yield('script')
</body>
</html>
