@extends('layouts.mypage')

@section('title','コスモ天国ネット')

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
<link rel="stylesheet" href="{{ asset('css/site/mypage.css') }}">
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css"> -->
<style>
  .error_message {
      width: 100%;
      margin-top: 0.25rem;
      font-size: 80%;
      color: #dc3545;
  }
</style>
@endsection

@section('content')
<section>
  <div class="text-white p-3 text-center fw-bold" style="background: #016DB7;">マイページ</div>
</section>
<section>
  <div class="mypage_pc mypage_menu_content justify-content-center py-1">
    @foreach($tabs as $tab)
      <a href="{{ $tab['url'] }}" class="btn rounded-0 {{ $loop->last ? 'mypage_menu_bar_end' : 'mypage_menu_bar' }} {{ $tab['is_active'] ? 'tab_acive' : '' }}">{{ $tab['name'] }}</a>
    @endforeach
  </div>
</section>
<section>
  <div class="container mt-3" style="width: 360px;">
    @if($type == 1)
    <div class="text-center my-3">
      <p class="fw-bold mb-4 fs-5" style="color: #016DB7;">電話番号変更手続き</p>
      <p class="fw-bold mb-1"><i class="fas fa-key mr-1"  style="color: #016DB7;"></i>電話番号変更</p>
      <small>入力されたメールアドレスに</small><br>
      <small>認証SMSを送信致します。</small>
    </div>
    <div class="form-group text-center mb-5">
      <label>登録中の電話番号</label>
      <p>{{ str_replace('+81',0,$data->phone) }}</p>
      <label>新しい電話番号</label><br>
      <small class="fw-bold error_message" id="tel_error_message"></small>
      <input type="text" class="form-control" id="phone" />
      <div class="text-center pt-4">
        <button class="btn tel-btn btn-block" style="color: white;background: #016DB7;">送信</button>
      </div>
    </div>
    @endif
    @if($type == 2)
    <p class="text-center fw-bold mb-4" style="color: #016DB7;">メールアドレス変更手続き</p>
    <div class="text-center my-3">
      <p class="fw-bold mb-1"><i class="fas fa-key mr-1"  style="color: #016DB7;"></i>メールアドレス変更</p>
      <small>入力されたメールアドレスに</small><br>
      <small>認証メールを送信致します。</small>
    </div>
    <div class="mb-5">
      @csrf
      <div class="form-group text-center">
        @if($data->email)
          <label>登録中のメールアドレス</label>
          <p>{{ $data->email }}</p>
        @endif
        <label>メールアドレス</label><br>
        <small class="fw-bold error_message" id="email_error_message"></small>
        <input type="text" class="form-control" id="email" /><br>
      </div>
      <div class="text-center">
        <button class="btn email-btn btn-block" style="color: white;background: #016DB7;">送信</button>
      </div>
    </div>
    @endif
    @if($type == 3)
    <p class="text-center fw-bold mb-4" style="color: #016DB7;">パスワード変更手続き</p>
    <div class="text-center">
      <p class="fw-bold mb-1"><i class="fas fa-key mr-1"  style="color: #016DB7;"></i>パスワード再設定</p>
      @if($data->email)
        <small style="color:tomato">ご登録されたメールアドレス</small><small>に</small><br>
        <small>パスワード再発行用URLを送信致します。</small>
      @endif
    </div>
    @if($data->email)
    <div class="mb-5">
      @csrf
      <div class="form-group text-center mt-3">
        <label>メールアドレス</label><br>
        <p>{{ $data->email }}</p>
      </div>
      <div class="text-center">
        <button class="btn password-btn btn-block" style="color: white;background: #016DB7;">送信</button>
      </div>
    </div>
    @else
    <div class="text-center mb-5">
      <p class="text-center mt-3" style="color:tomato">メールアドレスが登録されていません。</p>
      <span>下記URLからご登録ください。</span><br>
      <a href="{{ route('mypage.setting.edit.page',['type' => 2]) }}">{{ route('mypage.setting.edit.page',['type' => 2]) }}</a>
    </div>
    @endif
    <!-- <p class="text-center fw-bold mb-4" style="color: #016DB7;">パスワード再発行手続き</p>
    <div class="text-center">
      <p class="fw-bold mb-1"><i class="fas fa-key mr-1"  style="color: #016DB7;"></i>パスワード再設定</p>
      <small>新しいパスワードを設定してください。</small><br>
      <small>パスワードは6文字以上で設定してください。</small>
    </div>
    <form class="my-5">
      @csrf
      <div class="form-group">
        <label><i class="fas fa-lock mr-1"  style="color: #016DB7;"></i>新しいパスワード</label><br>
        <input type="text" class="form-control" id="password" /><br>
      </div>
      <div class="form-group">
        <label><i class="fas fa-lock mr-1"  style="color: #016DB7;"></i>パスワード確認</label><br>
        <input type="text" class="form-control" id="password" /><br>
      </div>
      <div class="text-center">
        <button class="btn tel-btn btn-block" style="color: white;background: #016DB7;">送信</button>
      </div>
    </form> -->
    @endif
  </div>
</section>
@endsection

@section('script')
@if($type == 1)
  <script src="{{ asset('vendor/jquery/jquery.numeric.min.js') }}"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script> -->
@endif
<script>
  const type = "{{ $type }}"
  if(type == 1) {
    $("#phone").numeric({
      decimal: false,
      negative: false
    });
    // const input = document.querySelector("#phone");
    // window.intlTelInput(input, {
    //   separateDialCode: true,
    //   placeholderNumberType: '',
    //   onlyCountries: ["jp"],
    //   utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
    // });
  }

  $('.tel-btn').on('click', function() {
    const phone = $('#phone').val()
    $('#tel_error_message').text('');
    if(!phone) {
      $('#tel_error_message').text('電話番号を入力してください');
      return;
    }
    const regexp = /^0\d{9,10}$/;
    if(!regexp.test(phone)) {
      $('#tel_error_message').text('電話番号の形式が違います。');
      return;
    }
    const parameter = {
      phone: phone,
      code_area: "+81",
    }
    $("#overlay").fadeIn(300);
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: JSON.stringify(parameter),
      type: "POST",
      contentType: "application/json",
      url: "{{ route('mypage.phone.auth') }}",
      dataType:"json",
    }).done(function(data, status, jqXHR) {
      if(data.result == 0) {
        // 成功時リロード
        location.href = "{{ route('mypage.phone.auth.page') }}"
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
  $('.email-btn').on('click', function() {
    const email = $('#email').val()
    $('#email_error_message').text('');
    if(!email) {
      $('#email_error_message').text('メールアドレスを入力してください');
      return;
    }
    const regexp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if(!regexp.test(email)) {
      $('#email_error_message').text('メールアドレスの形式が違います。');
      return;
    }
    const parameter = {
      email: email,
    }
    $("#overlay").fadeIn(300);
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: JSON.stringify(parameter),
      type: "POST",
      contentType: "application/json",
      url: "{{ route('mypage.email.auth') }}",
      dataType:"json",
    }).done(function(data, status, jqXHR) {
      if(data.result == 0) {
        // 成功時リロード
        location.href = "{{ route('mypage.email.auth.page') }}"
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
  $('.password-btn').on('click', function() {
    const parameter = {
      email: "{{ $data->email }}"
    }
    $("#overlay").fadeIn(300);
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: JSON.stringify(parameter),
      type: "POST",
      contentType: "application/json",
      url: "{{ route('mypage.password.auth') }}",
      dataType:"json",
    }).done(function(data, status, jqXHR) {
      if(data.result == 0) {
        // 成功時リロード
        location.href = "{{ route('mypage.password.end') }}"
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
@endsection