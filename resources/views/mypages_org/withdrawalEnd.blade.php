<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex" />

    <title>
        @yield('title')
    </title>

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
    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}"></link>

    <link rel="stylesheet" href="{{ asset('css/site/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site/mypage.css') }}">
    <link rel="icon" href="{{ asset('tengoku_woman.png') }}">
    @yield('style')
    <style>
      body {
        margin-bottom: 80px;
      }
      .error_message {
          width: 100%;
          margin-top: 0.25rem;
          font-size: 80%;
          color: #dc3545;
      }
    </style>
</head>
<body>
<header>
    <div class="sp mb-3">
      <a href="{{ route('site') }}"><img class="logo mt-3" src="{{ asset('img/logo.png') }}" alt="ロゴ" style="margin-left: 20px;"></a>
      
    </div>
  </header>
  <main>
      <div id="overlay">
          <div class="cv-spinner">
              <span class="spinner"></span>
          </div>
      </div>
      <section>
        <div class="text-white p-3 text-center fw-bold" style="background: #016DB7;"><h1 style="font-size:1rem;">マイページ</h1></div>
      </section>
      <section>
        <div class="container mt-3">
          <div class="text-center my-3">
            <h2 class="fw-bold mb-4 fs-5" style="color: #016DB7;">退会完了画面</h2>
          </div>
            <div class="text-center my-5">
              <p class="fw-bold h5">退会の処理が正常に完了しました。</p>
              <p class="fw-bold h5">ご利用ありがとうございました。</p>
              <a href="{{ route('site') }}" class="btn btn-block mt-3" style="color: white;background: #016DB7;">TOPへ戻る</a>
            </div>
        </div>
      </section>
      </main>
      <footer>
        <p class="footer-copyright">
          Copyright(C)2023 コスモ天国ネット All Rights Reserved
        </p>
      </footer>
      <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
      <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <!-- <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script> --> 
    </body>
</html>