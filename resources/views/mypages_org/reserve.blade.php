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
<style>
  .error_message {
      width: 100%;
      margin-top: 0.25rem;
      font-size: 80%;
      color: #dc3545;
  }
  .btn-possible {
    background: #28b463;
    color: white;
  }
  .btn-possible:hover {
    background: #1f8a4c;
    color: white;
  }
  .btn-week {
    background: #016DB7;
    color: white;
  }
  .btn-week:hover {
    background: #013e66;
    color: white;
  }
  .tel-btn {
    background: #eb5757;
    color: white;
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
  <div class="container mt-3">
    <div class="my-3">
      <p class="fw-bold mb-4 fs-5 text-center" style="color: #016DB7;">web予約</p>
      <p class="fw-bold mb-4 text-center" style="color: #016DB7;">②日付選択</p>
      <!-- キャスト画像等の情報 -->
      @if($castData)
        <div class="card mb-3">
          <div class="row g-0">
            <div class="col-4">
              <img src="{{ asset('storage/'.$castData->image) }}" height="200px">
            </div>
            <div class="col-8">
              <div class="card-body">
                <p class="card-text">{{ $castData->site_name }}</p>
                <h5 class="card-title">{{ $castData->source_name }}</h5>
                @if($castData->bust || $castData->cup || $castData->waist ||$castData->hip)
                <p class="card-text">
                  <small class="text-muted">
                    @if($castData->bust || $castData->cup)
                      @if($castData->bust)
                        B{{ $castData->bust }}
                      @endif
                      @if($castData->cup)
                        ({{$castData->cup}})
                      @endif
                      @endif
                    @if($castData->waist)
                      / W{{ $castData->waist }}
                    @endif
                    @if($castData->hip)
                      /H{{ $castData->hip }}
                    @endif
                  </small>
                </p>
                @endif
              </div>
            </div>
          </div>
        </div>      
      @else
      <p class="fw-bold">フリー予約</p>
      @endif
      <p class="fw-bold" style="color: #016DB7;">選択コース: {{ $courseData->name }} {{ number_format($courseData->fee) }}円/{{ $courseData->time }}分</p>
      @if($formatTime)
      <div class="text-center">
        <p class="fw-bold">{{ date('m月d日',strtotime($firstDate)) }} ~ {{ date('m月d日',strtotime($endDate)) }}</p>
      </div>
      <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-week next-week" data-date="{{ date('Ymd', strtotime($firstDate . '-8 day')) }}" {{ $firstDate == date('Ymd') ?  'disabled' : '' }}>前の週</button>
        <button class="btn btn-week next-week" data-date="{{ date('Ymd', strtotime($endDate . '+1 day')) }}">次の週</button>
      </div>

      <div class="table-responsive">
        <table class="table table table-bordered">
          <tr>
            <td class="text-center">日時</td>
            @foreach($formatDate as $day => $date)
              <td class="text-center">{{ $date }}</td>
            @endforeach
          </tr>
          @foreach($formatTime as $time => $dayAry)
          <tr>
              <td class="text-center">{{ $time }}</td>
              @foreach($dayAry as $day => $result)
                @if($result['result'] == 1)
                  <!-- btn-possible -->
                  <td class="text-center"><button class="btn btn-block btn-possible" data-date_time="{{ $result['dateTime'] }}"><i class="far fa-circle"></i></button></td>
                @elseif($result['result'] == 2)
                <!-- TEL -->
                  <td class="text-center"><button class="btn btn-block tel-btn">TEL</button></td>
                @else
                  <td class="text-center">-</td>
                @endif
              @endforeach
          <tr>
          @endforeach
          </table>
        </div>
      @else
        <p class="text-center">現在予約を受けておりません。</p>
      @endif
      <small>※18歳未満(高校生を含む)の方の利用はお断りいたします。</small><br>
      <small>※当店には18歳未満のコンパニオンは在籍しておりません。</small>
    </div>
  </div>
</section>
@endsection

@section('script')
<!-- <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
<script>
  // フィルターフォームの送信時にテーブルを再描画
  $('.next-week').on('click', function(e) {
        $("#overlay").fadeIn(300);
        const date =  $(this).data('date');
        const redirectUrl = "{!! route('mypage.reserve.calender', ['cast_id' => $castId,'site_id' => $siteId,'course_id' => $courseId,'fee_type' => $feeType,'first_date' => '__first_date__']) !!}".replace('__first_date__', date);
        location.href = redirectUrl;
        $("#overlay").fadeOut(300);
  });
  // 予約画面へ遷移
  $('.btn-possible').on('click', function(e) {
        $("#overlay").fadeIn(300);
        // const date =  String($(this).data('date'));
        // const time =  String($(this).data('time'));
        const dateTime = $(this).data('date_time')
        const redirectUrl = "{!! route('mypage.reserve.confirm', ['course_id' => $courseId,'fee_type' => $feeType,'cast_id' => $castId,'date_time' => '__date_time__','site_id' => $siteId]) !!}".replace('__date_time__', dateTime);
        location.href = redirectUrl;
        $("#overlay").fadeOut(300);
  });
</script>
@endsection