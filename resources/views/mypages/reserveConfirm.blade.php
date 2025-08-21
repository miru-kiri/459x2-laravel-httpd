@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページweb予約確認ページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページweb予約確認ページです！')
@section('keywords',  '四国,天国ネット,コスモ天国ネット,風俗,メンズエステ,キャバクラ,セクキャバ,飲食店,宴会コンパニオン' )

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
  .btn-submit {
    background: #016DB7;
    color: white;
  }
  .btn-submit:hover {
    background: #013e66;
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
      <p class="fw-bold mb-4 text-center" style="color: #016DB7;">③仮予約確認</p>
      <div class="container">
      <form action="{{ route('mypage.reserve.confirm') }}" method="POST">
        @csrf
        <div class="my-3">
          <p>下記の内容で仮予約してもよろしいでしょうか？</p>
          <p class="fw-bold" style="border-bottom: 1px solid #d3d3d3;">ご予約内容</p>
          <p>キャスト名: {{ $castData->source_name ?? 'フリー予約' }}</p>
          <input type="hidden" name="cast_id" value="{{ $castData->id ?? 0 }}" />
          <input type="hidden" name="type" value="{{ $castData ? 0 : 1 }}" />
          <input type="hidden" name="type_reserve" value="1" />
          @if($indicateData)
            @if($feeType == 1)
              <p>指名料: {{ number_format($indicateData->fee) }}円</p>
              <input type="hidden" name="indicate_fee1" value="{{ $indicateData->fee }}" />
            @endif
            @if($feeType == 2)
              <p>指名料: {{ number_format($indicateData->nomination_fee) }}円</p>
              <input type="hidden" name="indicate_fee2" value="{{ $indicateData->nomination_fee }}" />
            @endif
          @endif
          <p>コース名: {{ $courseData->name }}</p>
          <input type="hidden" name="course_id" value="{{ $courseData->id }}" />
          <input type="hidden" name="course_name" value="{{ $courseData->name }}" />
          <input type="hidden" name="course_time" value="{{ $courseData->time }}" />
          <input type="hidden" name="site_id_reserve" value="{{ $siteId }}" />
          <p>コース金額: {{ number_format($courseData->fee) }}円</p>
          <input type="hidden" name="course_money" value="{{ $courseData->fee }}" />
          <p>開始時間: {{ $startTime }}</p>
          <input type="hidden" name="start_time" value="{{ $startTime }}" />
          <p>終了時間: {{ $endTime }}</p>
          <input type="hidden" name="end_time" value="{{ $endTime }}" />
          <p>金額: {{ number_format($amount) }}円</p>
          <input type="hidden" name="amount" value="{{ $amount }}" />
        </div>
        <div class="my-3">
          <p class="fw-bold" style="border-bottom: 1px solid #d3d3d3;">ご予約のお客様情報</p>
          <label>住所</label><br>
          <input class="form-control" type="text" name="address" /><br>
          <label>ご要望</label><br>
          <textarea class="form-control" name="memo" rows="3" ></textarea><br>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-light btn-back">キャンセル</button>
          <button class="btn btn-submit">予約する</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('script')
<script>
  const warning = "{{ session('warning') }}"
  const error = "{{ session('error') }}"
  $(document).ready(function() {
    if(warning) {
        toastr.warning(warning)
    }
    if(error) {
        toastr.error(error)
    }
  })
  // フィルターフォームの送信時にテーブルを再描画
  $('.btn-back').on('click', function(e) {
    const date = "{{ date('Ymd') }}"
    const redirectUrl = "{!! route('mypage.reserve.calender', ['cast_id' => $castId,'site_id' => $siteId,'course_id' => $courseId,'fee_type' => $feeType,'first_date' => '__first_date__']) !!}".replace('__first_date__', date);
    location.href = redirectUrl;
  });
  $('.btn-submit').on('click', function(e) {
    $("#overlay").fadeIn(300);
    $("#overlay").fadeOut(300);
  });
</script>
@endsection