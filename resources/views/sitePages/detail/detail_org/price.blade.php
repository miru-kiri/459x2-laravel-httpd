@extends('layouts.site')

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

<link rel="stylesheet" href="{{ asset('css/site/top.css') }}">
<style>
.nav-pills .nav-link {
  border: 1px solid <?php echo $mainColor; ?> !important;
  color: <?php echo $mainColor; ?> !important;
}
.nav-pills .nav-link.active {
  color: #fff !important;
  background: <?php echo $mainColor; ?> !important;
}
.cast-schedule-btn {
  color: <?php echo $mainColor; ?> !important;
  border: 2px solid <?php echo $mainColor; ?> !important;
}
.cast-schedule-btn:hover {
  color: white !important;
  background-color: <?php echo $mainColor; ?> !important;
}
.price_sub_text {
  color: <?php echo $mainColor; ?> !important;
  border: 2px solid <?php echo $mainColor; ?> !important;
}
.tab_active {
  color: white  !important;
  background-color: <?php echo $mainColor; ?> !important;
}
</style>

@section('content')
  <section>
    <h1 class="text-white p-3 text-center fw-bold" style="font-size:1.2rem;background: {{ $mainColor }}">{{ $sites->name }}</h1>
  </section>
  <section class="shop_info_pc">
    <!-- <div class="container"> -->
    <div class="text-white p-2 text-center" style="background: whitesmoke">
      @foreach($tabs as $tab)
      <a href="{{ $tab['url'] }}" class="btn {{ $tab['active'] ? 'tab_active' : '' }}">{{ $tab['name'] }}</a>
      @endforeach
    </div>
    <!-- </div> -->
  </section>
  <section>
    <div class="container">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">料金システム</h2>
      @if($courses->isNotEmpty())
      <table class="table table-bordered mt-3">
        @foreach($courses as $course)
        <tr>
          <td class="text-center table-active">
            {{ $course->time }}分
          </td>
          <td class="text-center">
            {{ number_format($course->fee) }}円
          </td>
        </tr>
        @endforeach
        @if($nominationFees)
          @if($nominationFees->nomination_fee > 0)
          <tr>
            <td class="text-center table-active">
              指名料
            </td>
            <td class="text-center">
              {{ number_format($nominationFees->nomination_fee) }}円
            </td>
          </tr>
          @endif
        </table>
        @endif
        <div class="text-center price_sub_text">
          <p class="mt-3 mb-1">松山市内無料送迎あり</p>
          <p class="mb-3">(時間帯によってはお断りさせて頂く場合がございますので、ご了承ください。)</p>
        </div>
        @endif
    </div>
  </section>
  <section>
    <div class="container">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">延長システム</h2>
      @if($nominationFees)
      <table class="table table-bordered mt-3">
        @if($nominationFees->extension_time_unit > 0&& $nominationFees->extension_fee > 0)
        <tr>
          <td class="text-center table-active">
          {{ number_format($nominationFees->extension_time_unit) }}分
          </td>
          <td class="text-center">
            {{ number_format($nominationFees->extension_fee) }}円
          </td>
        </tr>
        @endif
      </table>
      @endif
    </div>
  </section>
  <section>
    <div class="container">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">その他</h2>
      <div class="text-center my-3">
        <p class="fw-bold">[ご予約について]</p>
        <p>♦︎電話予約</p>
        <p>前日予約 AM9:00 ~ 受付</p>
        <p>当日予約 AM8:30 ~ 受付</p>
        <p class="fw-bold">[確認電話]</p>
        <p>予約時間の30分前に来店確認のお電話をお客様からお願い致します。</p>
        <p>予約助教により、ご来店時間が多少前後する場合がございます。</p>
        <p>予め、ご了承ください。</p>
        <p>♦︎ネット予約</p>
        <p>前日 ~ 1週間から受付</p>
        <p>※キャストによりネット予約の事前予約日時は変わりますので、ご了承ください。</p>
        <p class="fw-bold">[無料駐車場完備]</p>
        <p>近隣に無料駐車場あり。</p>
        <p>詳しくはスタッフまでお尋ねください。</p>
        <p class="fw-bold">[無料歓迎]</p>
        <p>ご予約の際にお気軽にご利用くださいませ。</p>
      </div>
    </div>
  </section>
  
@endsection