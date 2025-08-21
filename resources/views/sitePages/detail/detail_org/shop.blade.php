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
.tab_active {
  color: white  !important;
  background-color: <?php echo $mainColor; ?> !important;
}
</style>

@section('content')
  <section>
    <h1 class="text-white p-3 text-center fw-bold" style="font-size: 1.2rem;background: {{ $mainColor }}">{{ $sites->name }}</h1>
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
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店舗情報</h2>
      @if($shops)
      <table class="table table-bordered mt-3">
        <tr>
          <td class="text-center table-active" width="50%">
          店舗名
          </td>
          <td class="text-center">
            {{ $shops->name }}
          </td>
        </tr>
        <tr>
          <td class="text-center table-active">
          業種
          </td>
          <td class="text-center">
            {{$genre['text']}}
          </td>
        </tr>
        <tr>
          <td class="text-center table-active">
          営業時間
          </td>
          <td class="text-center">
          {{ $shops->workday_start_time }} ~ {{ $shops->workday_end_time }}
          </td>
        </tr>
        <tr>
          <td class="text-center table-active">
          定休日
          </td>
          <td class="text-center">
            年中無休
          </td>
        </tr>
        <tr>
          <td class="text-center table-active">
          住所
          </td>
          <td class="text-center">
          {{ $shops->address1 }}{{ $shops->address2 }}{{ $shops->address3 }}
          </td>
        </tr>
        <tr>
          <td class="text-center table-active">
          電話番号
          </td>
          <td class="text-center">
          {{ $shops->tel }}
          </td>
        </tr>
        <!-- <tr>
          <td class="text-center table-active">
          駐車場
          </td>
          <td class="text-center">
            あり
          </td>
        </tr> -->
      </table>
      @endif
    </div>
  </section>
  <section>
    <div class="container mb-3">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">アクセスマップ</h2>
      <div class="col-12">
        <div id="map" style="height: 500px; width: 100%;"></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container mb-3">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">ギャラリー</h2>
    </div>
  </section>
  
@endsection

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXJUmRe3I3h31r94Z1xe9Y4QXX8YlDm6g&callback=initMap" async defer></script>
<script>
  function initMap() {
    var geocoder = new google.maps.Geocoder();
    var address = "{{ $shops->address1 }}{{$shops->address2}}{{ $shops->address3 }}"; // ここに表示したい住所を入力

    geocoder.geocode({'address': address}, function(results, status) {
      if (status === 'OK') {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: results[0].geometry.location
        });
        var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location,
          title: "{{ $shops->address1 }}{{$shops->address2}}{{ $shops->address3 }}"
        });
      } else {
        alert('地図を表示できませんでした。エラーコード: ' + status);
      }
    });
  }
</script>
@endsection