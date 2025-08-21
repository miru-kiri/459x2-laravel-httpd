<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
    </title>
	<meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')" />

    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:site_name" content="コスモ天国ネット">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="{{ asset('img/twitter_card_common.jpg') }}">
    <meta name="twitter:card" content="summary_large_image" />

    <link rel="canonical" href="{{ request()->fullUrl() }}" />

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <!-- font-awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}"></link>

    <link rel="stylesheet" href="{{ asset('css/site/layout.css') }}">
    <link rel="icon" href="{{ asset('tengoku_woman.png') }}">
    @yield('style')
    <style>
      body {
        margin-bottom: 80px;
		font-family: "Helvetica Neue",Arial,"Hiragino Kaku Gothic ProN","Hiragino Sans",'Meiryo',sans-serif;
      }
    </style>
</head>
<body>
  <header>
    <div class="pc mx-3">
        <a href="{{ route('site') }}"><img class="logo" src="{{ asset('img/logo.png') }}" alt="ロゴ" style="margin-left: 20px;" /></a>
        <div>
        <!-- <button type="button" class="btn btn-secondary rounded-pill serch-button"><small>条件で検索</small> <i class="fas fa-search" style="color: #ffffff"></i></button> -->
        @if(session()->has('user_id'))
          <button class="btn menu-button rounded-0 mypage_btn" style="background: #016DB7">
            <i class="fas fa-user" style="color: #ffffff"></i>
            <p class="text-white menu-text">会員ページ</p>
          </button>
          <button class="btn menu-button rounded-0 logout_btn" style="background: #EC1070">
            <i class="fas fa-sign-out-alt" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
            <p class="text-white menu-text">ログアウト</p>
          </button>
          <button class="btn menu-button rounded-0 qr_btn" style="background: #EA5514">
            <i class="fas fa-qrcode" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
            <p class="text-white menu-text">QR</p>
          </button>
        @else
          <button class="btn menu-button rounded-0 sign_btn" style="background: #EE5318">
            <i class="fas fa-home" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
            <p class="text-white menu-text">会員登録</p>
          </button>
          <button class="btn menu-button rounded-0 logout_btn" style="background: #EC1070">
            <i class="fas fa-sign-out-alt" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
            <p class="text-white menu-text">ログアウト</p>
          </button>
        @endif
          <button class="btn menu-button rounded-0 contact_btn" style="background: #BF0D28">
            <i class="fas fa-envelope" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
            <p class="text-white menu-text">お問い合わせ</p>
          </button>
        </div>
        <!-- <nav>
          <ul>
            <li><a href="#">メニュー2</a></li>
            <li><a href="#">メニュー3</a></li>
          </ul>
        </nav> -->
    </div>
    <div class="sp mb-3">
      <a href="{{ route('site') }}"><img class="logo mt-3" src="{{ asset('img/logo.png') }}" alt="ロゴ" style="margin-left: 20px;"></a>
      <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <nav class="globalMenuSp">
        @yield('sp-nav')
      </nav>
    </div>
    <!-- <div class="hamburger">☰</div> -->
  </header>
  <ul class="side-menu">
    <a href="{{ route('site.search') }}" style="text-decoration: none"><li class="side-menu-text" style="background: #05184D"><i class="fas fa-search-location"></i> 現在地から検索</li></a>
  </ul>
  <main>
      <div id="overlay">
          <div class="cv-spinner">
              <span class="spinner"></span>
          </div>
      </div>
      @yield('content')
  </main>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">会員QR</h5>
        </div>
        <div class="modal-body text-center">
          {!! QrCode::size(300)->generate(session('user_id')) !!}
        </div>
      </div>
    </div>
  </div>
  <footer>
    <div class="sp footer-btn">
      <div class="d-flex bg-light mb-3">
      @if(session()->has('user_id'))
        <a href="{{ route('site') }}" class="btn btn-app m-0 flex-fill pt-2">
          <i class="fas fa-home"></i><small>ホーム</small>
        </a>
        <!-- <a href="{{ route('mypage.top') }}" class="btn btn-app m-0 flex-fill pt-2">
          <i class="fas fa-user"></i><small>会員画面</small>
        </a> -->
        <a href="{{ route('mypage.logout') }}" class="btn btn-app m-0 flex-fill pt-2 login_btn">
          <i class="fas fa-sign-out-alt"></i><small class="text-nowrap">ログアウト</small>
        </a>
        <a class="btn btn-app m-0 flex-fill pt-2 qr_btn">
          <i class="fas fa-qrcode"></i><small class="text-nowrap">QR</small>
        </a>
      @else
        <a href="{{ route('site') }}" class="btn btn-app m-0 flex-fill pt-2">
          <i class="fas fa-home"></i><small>ホーム</small>
        </a>
        <a href="{{ route('mypage.signup') }}" class="btn btn-app m-0 flex-fill pt-2 sign_btn">
          <i class="fas fa-user"></i><small>会員登録</small>
        </a>
        <a href="{{ route('mypage.login') }}" class="btn btn-app m-0 flex-fill pt-2 login_btn">
          <i class="fas fa-sign-in-alt"></i><small class="text-nowrap">ログイン</small>
        </a>
      @endif
        <a href="{{ route('mypage.contact') }}" class="btn btn-app m-0 flex-fill pt-2 contact_btn">
          <i class="fas fa-envelope"></i><small class="text-nowrap">お問い合わせ</small>
        </a>
      </div> 
    </div>
    <p class="footer-copyright">
      Copyright(C)2023 コスモ天国ネット All Rights Reserved
    </p>
  </footer>
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script> --> 
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-85C5C68BYB"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-85C5C68BYB');
</script>
  <script>
      $(function() {
          $("img").on('contextmenu', function(e) {
              return false;
          });
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
      $('.mypage_btn').on('click',function(){
        //ログインチェック
        location.href = "{{ route('mypage.top') }}"
      });
      $('.sign_btn').on('click',function(){
        //ログインチェック
        location.href = "{{ route('mypage.signup') }}"
      });
      $('.logout_btn').on('click',function(){
        //ログインチェック
        location.href = "{{ route('mypage.logout') }}"
      });
      $('.contact_btn').on('click',function(){
        location.href = "{{ route('mypage.contact') }}"
      });
      $('.qr_btn').on('click',function(){
        $('#exampleModal').modal('toggle');
      });
  </script>
  @yield('script')
</body>
</html>
