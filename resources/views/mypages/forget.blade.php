<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
      四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット パスワード再発行ページ
    </title>

    <meta name="description" content="有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！パスワード再発行ページです！">
    <meta name="keywords" content="四国,天国ネット,コスモ天国ネット,風俗,メンズエステ,キャバクラ,セクキャバ,飲食店,宴会コンパニオン" />
    
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:site_name" content="コスモ天国ネット">
    <meta property="og:title" content="四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット パスワード再発行ページ">
    <meta property="og:description" content="有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！パスワード再発行ページです！">
    <meta property="og:image" content="{{ asset('img/twitter_card_common.jpg') }}">
    <meta name="twitter:card" content="summary_large_image" />
    
    <link rel="canonical" href="{{ request()->fullUrl() }}" />


    <!-- Scripts -->
    <!-- <; src="{{ asset('js/app.js') }}" defer></script> -->
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
    <!-- icon -->
    <link rel="icon" href="{{ asset('tengoku_woman.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">

    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}"></link>
</head>
<body>
  <header>
  </header>

  <main>
    <div class="container" style="width: 360px;">
    <div class="ml-4">
      <img class="my-3" src="{{ asset('img/logo.png') }}" style="width: 270px"/>
          <div class="form-group">
            <label for="phone">電話番号</label><br>
            <input type="text" class="form-control" name="login_id" id="phone" placeholder="" /><br>
            <small class="fw-bold" style="color: #EF747D">ハイフンなしで入力してください</small>
          </div>   
          
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="" style="width: 270px" />
          </div>  
  
          <div class="form-bottom">
            <a href="{{ route('mypage.forget') }}" class="original-src mr-3" style="font-size: 0.8rem">パスワードを忘れた方はこちら</a>
            <button class="btn" id="submit_btn" style="background: #EF747D;color: white"><small>ログイン</small></button>            
          </div>
    </div>
    </div>
  </main>
  <!-- <footer>
    <p class="footer-copyright">
      Copyright(C)2023 コスモ天国ネット All Rights Reserved
    </p>
  </footer> -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
  <!-- <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
  <!-- <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script> --> 

  <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
  <script>
    const input = document.querySelector("#phone");
    window.intlTelInput(input, {
      separateDialCode: true,
      placeholderNumberType: '',
      onlyCountries: ["jp"],
      utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
    });

    $('#submit_btn').on('click', function() {
      if($('#phone').val()) {

      }
      const parameter = {
        login_id: $('#phone').val(),
        password: $('#password').val(),
        code_area: "+81",
      }
      console.log(parameter)
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: JSON.stringify(parameter),
        type: "POST",
        contentType: "application/json",
        url: "{{ route('mypage.login.auth') }}",
        dataType:"json",
      }).done(function(data, status, jqXHR) {
        if(data.result == 0) {
          // 成功時リロード
          localStorage.setItem('user_id',"{{ session('user_id') }}")
          location.href = "{{ route('mypage.top') }}"
        } else {
          toastr.error(data.message)
          // $(".image-btn-submit").prop('disabled', false);
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
        toastr.error('処理に失敗しました。')
        console.log(jqXHR)
        // $(".image-btn-submit").prop('disabled', false);
      });
      
    });
  </script>
</body>
</html>
