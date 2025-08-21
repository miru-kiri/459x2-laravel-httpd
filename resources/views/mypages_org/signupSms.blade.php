<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        コスモ天国ネット | 認証画面
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
      .error_message {
        color: tomato;
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
    <div class="ml-4">
    <div class="text-center">
        <img class="my-5 " src="{{ asset('img/logo.png') }}" style="width: 270px"/>
      </div>
      <p class="text-center fs-5 fw-bold mb-4 pb-2" style="border-bottom:2px solid #d3d3d3">認証コードを入力</p>
      <form action="{{ route('mypage.signup.confirm') }}" method="POST">
        @csrf
      <div class="row">
          <div class="form-group">
            <label for="name">認証コード</label><br>
            <input type="text" class="form-control" name="code" id="code" placeholder="" value="{{ old('code') }}" style="width: 300px"/><br>
            <small class="error_message">{{$errors->first('code')}}</small>
          </div>
          <input type="hidden" name="user_id" value="{{ $userId }}"/>
          <div class="form-bottom">
            <button class="btn btn-block" id="submit_btn" style="background: #EF747D;color: white">登録する</button>            
          </div>
        </div>
      </form>
      <div class="text-center mt-3">
        <a href="#" id="again">再送する</a>
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
    // ブラウザバック対策
    window.onpageshow = function(event) {
      if (event.persisted) {
        window.location.reload();
      }
    };
    $(document).ready(function() {
      const status = "{{ session('status') }}"
      const error = "{{ session('error') }}"
      if(status) {
        if(error) {
          toastr.error(status)
        } else {
          toastr.success(status)
        }
      }
    })
    $('#again').on('click', function() {
      $("#overlay").fadeIn(300);
      const parameter = {
        user_id: "{{ $userId }}",
      }
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: JSON.stringify(parameter),
        type: "POST",
        contentType: "application/json",
        url: "{{ route('mypage.signup.sms.again') }}",
        dataType:"json",
      }).done(function(data, status, jqXHR) {
        if(data.result == 0) {
          // 成功時リロード
          toastr.success(data.message)
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
