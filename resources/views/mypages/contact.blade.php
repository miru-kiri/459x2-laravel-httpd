<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
      四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット お問い合わせフォーム
    </title>

    <meta name="description" content="有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！お問い合わせページ">
    <meta name="keywords" content="四国,天国ネット,コスモ天国ネット,風俗,メンズエステ,キャバクラ,セクキャバ,飲食店,宴会コンパニオン,お問わせフォーム" />
    
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:site_name" content="コスモ天国ネット">
    <meta property="og:title" content="四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット お問い合わせフォーム">
    <meta property="og:description" content="有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！お問い合わせページ">
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
    <div class="ml-4">
      <div class="text-center">
        <img class="my-5 " src="{{ asset('img/logo.png') }}" style="width: 300px"/>
      </div>
      <form action="{{ route('mypage.contact.registration') }}" method="POST">
        @csrf
        <p class="text-center fs-5 fw-bold mb-4 pb-2" style="border-bottom:2px solid #d3d3d3">お問い合わせフォーム</p>
        <div class="row">
        @foreach($formColumns as $column)
        <div class="form-group col-12 col-md-{{ $column['col'] }}">
          <label for="{{ $column['name'] }}">{{ $column['label'] }}</label>
            @if($column['type'] == 'text')
              <input type="text" class="form-control" name="{{ $column['name'] }}" id="{{ $column['name'] }}"  value="{{ old($column['name']) }}"  placeholder=""/>
              <small class="error_message">{{$errors->first($column['name'])}}</small>
            @endif
            @if($column['type'] == 'date')
            <input type="date" class="form-control" name="{{ $column['name'] }}" id="{{ $column['name'] }}" value="{{ old($column['name']) ??  date('Y-m-d') }}" placeholder=""/>
            <small class="error_message">{{$errors->first($column['name'])}}</small>
            @endif
            @if($column['type'] == 'textarea')
              <textarea class="form-control" name="{{ $column['name'] }}" id="{{ $column['name'] }}" rows="5" placeholder="">{{ old($column['name']) ?? '' }}</textarea>
              <small class="error_message">{{$errors->first($column['name'])}}</small>
            @endif
            @if($column['type'] == 'select')
              @if($column['name'] == 'site_id')
              <select class="form-control" id="{{ $column['name'] }}"  name="{{ $column['name'] }}">
                  @foreach($siteData as $site)
                    <option value="{{ $site->id }}" {{ old($column['name']) == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                  @endforeach
              </select>
              @endif
              @if($column['name'] == 'cast_id')
              <select class="form-control" id="{{ $column['name'] }}" name="{{ $column['name'] }}">
                <option value="0">--選択してください--</option>
                @isset($formatCastData[$firstSiteId])
                  @foreach($formatCastData[$firstSiteId] as $cast)
                      @if($cast->site_id == $firstSiteId)
                        <option value="{{ $cast->id }}" {{ old($column['name']) == $cast->id ? 'selected' : '' }}>{{ $cast->source_name }}</option>
                      @endif
                  @endforeach
                @endisset
              </select>
              @endif
              @if($column['name'] == 'time')
              <select class="form-control"  name="{{ $column['name'] }}">
              @foreach($timeAry as $value => $label)
                <option value="{{ $value }}" {{ old($column['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
              </select>
              @endif
            @endif
          </div>  
        @endforeach
        </div>
        <input type="hidden" name="user_id" value="{{ $userId }}" />
        <div class="form-bottom">
          <div class="row">
            <div class="col-6">
              <a class="btn btn-light btn-block" href="{{ route('site') }}"><small>トップへ戻る</small></a>            
            </div>
            <div class="col-6">
              <button class="btn btn-block" id="submit_btn" style="background: #EF747D;color: white"><small>送信</small></button>            
            </div>
          </div>
        </div>
      </form>
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
    const castData = @json($formatCastData);
    const status = "{{ session('status') }}"
    const error = "{{ session('error') }}"

    if(status) {
      if(error) {
        toastr.success(status)
      } else {
        toastr.error(status)
      }
    }
    
    $('#site_id').on('change',function() {
        const siteId = $('#site_id').val();
        //まず部署セレクトボックスをすべてクリアする
        $('#cast_id').children().remove();
        //変更後の支店に属する部署を追加する
        $('#cast_id').append($('<option>').html('--選択してください--').val(0));
        //選択された店舗下
        $.each(castData[siteId], function(d_no, d_val) {
            $('#cast_id').append($('<option>').html(d_val.source_name).val(d_no));
        });
    });

    // function fetchCast(siteId) {
    //   const parameter = {
    //     site_id: siteId
    //   };
    //   $.ajax({
    //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     data: JSON.stringify(parameter),
    //     type: "POST",
    //     contentType: "application/json",
    //     url: "{{ route('mypage.filtering.cast') }}",
    //     dataType:"json",
    //   }).done(function(data, status, jqXHR) {
    //     if(data.result == 0) {
    //       // 成功時selectボックス作成
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
    //   });
    // }
  </script>
</body>
</html>
