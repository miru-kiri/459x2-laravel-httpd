<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    @include('layouts.partials.csrf_meta')

    <title>@yield('title')</title>
	  <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')" />

    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:site_name" content="コスモ天国ネット">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="{{ asset('img/twitter_card_common.jpg') }}">
    <meta name="twitter:card" content="summary_large_image" />

    <link rel="canonical" href="{{ canonical_url() }}" />

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    <!-- font-awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" media="print" onload="this.media='all'">
    <!-- <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" media="print" onload="this.media='all'">

    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}"></link>

    <link rel="stylesheet" href="{{ asset('css/site/layout.css?20250411') }}">
    <link rel="icon" href="{{ asset('tengoku_woman.png') }}">
    @yield('style')
    <style>
      body {
		font-family: "Helvetica Neue",Arial,"Hiragino Kaku Gothic ProN","Hiragino Sans",'Meiryo',sans-serif;
      }
      #overlay{ 
        position: fixed;
        top: 0;
        z-index: 100;
        width: 100%;
        height:100%;
        display: none;
        background: rgba(0,0,0,0.6);
      }
      .cv-spinner {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;  
      }
      .spinner {
        width: 40px;
        height: 40px;
        border: 4px #ddd solid;
        border-top: 4px #2e93e6 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
      }
      @keyframes sp-anime {
        100% { 
            transform: rotate(360deg); 
        }
      }
      .is-hide{
        display:none;
      }
      .btn-app {
        min-width: 50px !important;
      }
      .tel-content{
        background: #4F4542;
        opacity: 0.9;
      }
      .tel-btn{
        background: #C30D23;
      }
    </style>
</head>
<body>
  <!-- HEADER_START -->
  @include('layouts.partials.header')
  <!-- HEADER_END -->
  <ul class="side-menu">
        <!-- <a href="{{ route('site.manual') }}" style="text-decoration: none"><li class="side-menu-text" style="color: black;background: #facc03"><i class="fas fa-book"></i> サイトの使い方</li></a> -->
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
  <!-- QR_START -->
  @include('layouts.partials.qr')
  <!-- QR_END -->
  <!-- FOOTER_START -->
  @include('layouts.partials.footer')
  <!-- FOOTER_END -->
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
		  <!-- Google tag (gtag.js) -->
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
        let site_id = "{{ $siteId ?? 0 }}"
        location.href = "{!! route('mypage.signup', ['site_id' => '__site_id__']) !!}".replace('__site_id__', site_id);
      });
      $('.login_btn').on('click',function(){
        //ログインチェック
        location.href = "{{ route('mypage.login') }}"
      });
      $('.logout_btn').on('click',function(){
        //ログインチェック
        location.href = "{{ route('mypage.logout') }}"
      });
      $('.contact_btn').on('click',function(){
        let site_id = "{{ $siteId ?? 0 }}"
        location.href = "{!! route('mypage.contact', ['site_id' => '__site_id__']) !!}".replace('__site_id__', site_id);
      });
      $('.reserve_btn').on('click',function(){
        const site_id =  "{{ request()->query('site_id') }}"
        const redirectUrl = "{!! route('mypage.reserve.course', ['site_id' => '__site_id__']) !!}".replace('__site_id__', site_id);
        location.href = redirectUrl
      });
      $('.tel-btn').on('click',function(){
        alert('「コスモ天国ネットを見た」とお伝えいただけるとスムーズです。')
      });
      $('.like_btn').on('click',function(){
        if(!confirm('閲覧中のサイトをブックマーク登録しますか？')) {
          return false;
        }
        const parameter = {
          site_id: "{{ request()->query('site_id') }}"
        }
        //ログ
        $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              type: "POST",
              data: parameter, // JSONデータ本体
              // contentType: "application/json",
              url: "{{ route('site.like') }}",
              dataType:"json",
            }).done(function(data,status,jqXHR) {
              //成功
              if(data.result == 0) {
                toastr.success(data.message)
              }
              if(data.result == 1) {
                toastr.error(data.message)
              }
              if(data.result == 2) {
                toastr.warning(data.message)
              }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                toastr.error('処理に失敗しました。') 
            }).always(function (data) {
              // 常に実行する処理
            });
        
      });
      $('.qr_btn').on('click',function(){
        $('#exampleModal').modal('toggle');
      });
  </script>
  @yield('script')
</body>
</html>
