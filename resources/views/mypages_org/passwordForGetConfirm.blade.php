<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        コスモ天国ネット | パスワード再発行フォーム
    </title>

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
    <style>
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
    </style>
</head>
<body>
  <header>
  </header>

  <main>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    <div class="container" style="width: 360px;">
      <img class="my-5" src="{{ asset('img/logo.png') }}" style="width: 270px"/>
          
          <div class="form-group">
            <label for="password">新しいパスワード</label>
            <input type="password" class="form-control" name="password" id="password" placeholder=""/>
          </div>  

          <div class="form-group">
            <label for="password">再度入力</label>
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder=""/>
          </div>  
  
          <div class="form-bottom">
            <button class="btn" id="submit_btn" style="background: #EF747D;color: white"><small>ログイン</small></button>            
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

  <script>
    

    //localstorageに認証データがあれば、自動でログインさせる
    // $(document).ready(function() {
    //   $("#overlay").fadeIn(300);
    //   const parameter = {
    //     user_id: localStorage.getItem('user_id'),
    //     user_token: localStorage.getItem('user_token')
    //   }
    //   $.ajax({
    //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     data: JSON.stringify(parameter),
    //     type: "POST",
    //     contentType: "application/json",
    //     url: "{{ route('mypage.check.token') }}",
    //     dataType:"json",
    //   }).done(function(data, status, jqXHR) {
    //     if(data.result == 0) {
    //       // 成功時リロード
    //       location.href = "{{ route('mypage.top') }}"
    //     }
    //   }).fail(function(jqXHR, textStatus, errorThrown) {
    //     console.log(jqXHR)
    //     // $(".image-btn-submit").prop('disabled', false);
    //   }).always(function (data) {
    //     // 常に実行する処理
    //     $("#overlay").fadeOut(300);
    //   });        
    // });

    $('#submit_btn').on('click', function() {
      if(!$('#password').val()) {
        return false;
      }
      if($('#password').val() != $('#password_confirmation').val()) {
        toastr.error('再度入力欄は同じ値を入力してください。')
        return false;
      }
      const parameter = {
        password: $('#password').val(),
      }
      $("#overlay").fadeIn(300);
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: JSON.stringify(parameter),
        type: "POST",
        contentType: "application/json",
        url: "{{ route('mypage.password.confirm') }}",
        dataType:"json",
      }).done(function(data, status, jqXHR) {
        if(data.result == 0) {
          // 成功時リロード
          // localStorage.setItem("user_id",data.user_id)
          // localStorage.setItem("user_token",data.user_token)
          toastr.success('パスワードを更新しました。ログイン画面へ戻ります。')
          setTimeout(function(){
            location.href = "{{ route('mypage.login') }}"
          },1500)
        } else {
          toastr.error(data.message)
          // $(".image-btn-submit").prop('disabled', false);
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
        toastr.error('処理に失敗しました。')
        console.log(jqXHR)
        // $(".image-btn-submit").prop('disabled', false);
      }).always(function (data) {
        // 常に実行する処理
            $("#overlay").fadeOut(300);
            // setTimeout(function(){
            //     $("#overlay").fadeOut(300);
            // },500);
      });        
    });
  </script>
</body>
</html>
