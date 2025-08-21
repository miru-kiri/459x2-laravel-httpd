<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        コスモ天国ネット | 新規登録
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
    <link href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
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
    <div class="container">
      <div class="text-center">
        <img class="my-5 " src="{{ asset('img/logo.png') }}" style="width: 300px"/>
      </div>
      <p class="text-center fs-5 fw-bold mb-4 pb-2" style="border-bottom:2px solid #d3d3d3">新規登録フォーム</p>
      <form action="{{ route('mypage.signup.sms') }}" method="POST">
        @csrf
      <div class="row">
          <div class="form-group col-12 col-md-6">
            <label for="name">名前</label><br>
            <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{ old('name') }}" /><br>
            <small class="error_message">{{$errors->first('name')}}</small>
          </div>   
          <div class="form-group col-12 col-md-6">
            <label for="name_furigana">名前(かな)</label><br>
            <input type="text" class="form-control" name="name_furigana" id="name_furigana" value="{{ old('name_furigana') }}" placeholder="" /><br>
            <small class="error_message">{{$errors->first('name_furigana') }}</small>
          </div>   
          <div class="form-group col-12 col-md-6">
            <label for="name_show">ニックネーム</label><br>
            <input type="text" class="form-control" name="name_show" id="name_show" value="{{ old('name_show') }}" placeholder="" /><br>
            <small class="error_message">{{$errors->first('name_show')}}</small>
          </div>   

          <div class="form-group col-12 col-md-6">
            <label for="email">メールアドレス</label><br>
            <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="" /><br>
            <small class="error_message">{{$errors->first('email')}}</small>
          </div>

          <div class="form-group col-12 col-md-6">
            <label for="site_id">よく利用する店舗</label><br>
            <select class="form-control" name="site_id" id="site_id">
              @foreach($siteData as $site)
                <option value="{{ $site->id }}" {{ old('site_id') === $site->id ? 'selected' : ''}}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="form-group col-12 col-md-6">
            <label for="birth_day">生年月日</label><br>
            <input type="text" class="form-control" name="birth_day" id="birth_day" value="{{ old('birth_day') }}" placeholder="" id="datepicker" /><br>
            <small class="error_message">{{$errors->first('birth_day')}}</small>
          </div>   

          <div class="form-group col-12 col-md-6">
            <label for="phone">電話番号</label><br>
            <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" placeholder="" /><br>
            @if($errors->first('phone'))
              <small class="error_message">{{$errors->first('phone') }} </small>
            @endif
          </div>   

          <div class="form-group col-12 col-md-6">
            <label for="address">住所</label><br>
            <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}" placeholder="" /><br>
            <small class="error_message">{{$errors->first('address')}}</small>
          </div>   
          <!-- 不使用 -->
          <!-- <div class="form-group col-12 col-md-6">
            <label for="nickname">ログインID</label>
            <input type="text" class="form-control" name="nickname" id="nickname" value="{{ old('nickname') }}" placeholder=""/>
            <small class="error_message">{{$errors->first('nickname')}}</small>
          </div>   -->

          <div class="form-group col-12 col-md-6">
            <label for="password">パスワード</label>
            <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" placeholder=""/>
            <small class="error_message">{{$errors->first('password')}}</small>
          </div>  
          
          <p class="text-center" style="color: tomato;">入力された電話番号宛にSMS認証メッセージを送信します。</p>
          <div class="form-bottom">
            <button class="btn btn-block" id="submit_btn" style="background: #EF747D;color: white">送信する</button>            
          </div>
        </div>
      </form>
  </main>
  <!-- <footer>
    <p class="footer-copyright">
      Copyright(C)2023 コスモ天国ネット All Rights Reserved
    </p>
  </footer> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery-ui/datepicker-ja.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery.numeric.min.js') }}"></script>
  <!-- <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
  <!-- <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script> --> 

  <!-- <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script> -->
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
      // const input = document.querySelector("#phone");
      // window.intlTelInput(input, {
      //   separateDialCode: true,
      //   placeholderNumberType: '',
      //   onlyCountries: ["jp"],
      //   utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
      // });
    
      $("#phone").numeric({
        decimal: false,
        negative: false
      });
      $('#birth_day').datepicker({    
        changeYear: true,  // 年選択をプルダウン化
        changeMonth: true,  // 月選択をプルダウン化});
        yearRange: "-100:+0" // 表示する年の範囲を設定
      });
    });
    
    $('#submit_btn').on('click', function() {
      $("#overlay").fadeIn(300);
    });
    //   const parameter = {
    //     name: $('#name').val(),
    //     name_furigana: $('#name_furigana').val(),
    //     name_show: $('#name_show').val(),
    //     email: $('#email').val(),
    //     birth_day: $('#birth_day').val(),
    //     phone: $('#phone').val(),
    //     site_id: $('#site_id').val(),
    //     address: $('#address').val(),
    //     nickname: $('#nickname').val(),
    //     password: $('#password').val(),
    //   }
    //   $("#overlay").fadeIn(300);
    //   $.ajax({
    //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     data: JSON.stringify(parameter),
    //     type: "POST",
    //     contentType: "application/json",
    //     url: "{{ route('mypage.signup.sms') }}",
    //     dataType:"json",
    //   }).done(function(data, status, jqXHR) {
    //     if(data.result == 0) {
    //       // 成功時リロード
    //       // localStorage.setItem('user_id',"{{ session('user_id') }}")
    //       location.href = "{{ route('mypage.signup.sms.page') }}"
    //     } else {
    //       toastr.error(data.message)
    //       // $(".image-btn-submit").prop('disabled', false);
    //     }
    //   }).fail(function(jqXHR, textStatus, errorThrown) {
    //     toastr.error('処理に失敗しました。')
    //     console.log(jqXHR)
    //     // $(".image-btn-submit").prop('disabled', false);
    //   }).always(function (data) {
    //     // 常に実行する処理
    //         $("#overlay").fadeOut(300);
    //         // setTimeout(function(){
    //         //     $("#overlay").fadeOut(300);
    //         // },500);
    //   });        
    // });
  </script>
</body>
</html>
