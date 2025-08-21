@extends('layouts.site')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|天国ネット現在地から検索詳細')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！現在地から店舗を紹介しています!')
@section('keywords',  $keywords)

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
  <link rel="stylesheet" href="{{ asset('css/site/top.css') }}">
@endsection

@section('content')
  <section>
    <div class="text-white p-3 mb-3 text-center fw-bold" style="background: #05184D;"><h1 style="font-size:1.2rem">現在地からお店を検索</h1></div>
  </section>
  <section>
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-1" style="background-color: white;">
          @foreach($breadCrumbs as $breadCrumb)
          @if ($loop->count == 1)
            <li class="breadcrumb-item active" aria-current="page"><small>{{ $breadCrumb['label'] }}</small></li>
          @else
            @if ($loop->last)
              <li class="breadcrumb-item active" aria-current="page"><small>{{ $breadCrumb['label'] }}</small></li>
            @else
              <li class="breadcrumb-item"><a href="{{ $breadCrumb['url'] }}"><small>{{ $breadCrumb['label'] }}</small></a></li>
            @endif
          @endif
          @endforeach
        </ol>
      </nav>
      <div class="col-12">
        <div id="map" style="height: 500px; width: 100%;"></div>
      </div>
    </div>
  </section>
@endsection

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7mRMvEmcoJx88KwfShBfzX1samxbAc5w&callback=initMap" async defer></script>
<script>
  const shopData = @json($shopData);
  function initMap() {
    $("#overlay").fadeIn(300);
    function success(pos) {
      // console.log(pos)
      var lat = pos.coords.latitude;
      var lng = pos.coords.longitude;
      var latlng = new google.maps.LatLng(lat, lng); //中心の緯度, 経度
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: latlng
      });
      var local_marker = new google.maps.Marker({
        position: latlng, //マーカーの位置（必須）
        map: map, //マーカーを表示する地図
        title: '現在地'
      });
      var infowindow = new google.maps.InfoWindow({
        content: '現在地'
      });
      // マーカーをクリックしたときに情報ウィンドウを表示する
      local_marker.addListener('click', function() {
        infowindow.open(map, local_marker);
      });

      var geocoder = new google.maps.Geocoder();
      var addressCounts = {}; // 住所ごとのカウントを保存

      shopData.forEach(function(shop) {
        let address = shop.address1 + shop.address2
        const url = "{!! route('site.detail.top', ['site_id' => '__site_id__']) !!}".replace('__site_id__', shop.site_id);
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            var baseLocation = results[0].geometry.location;
             // 同じ住所があればカウントを増やす
            if (addressCounts[baseLocation]) {
              addressCounts[baseLocation]++;
            } else {
              addressCounts[baseLocation] = 1;
            }
            // マーカーをずらす量
            var newLat = baseLocation.lat();
            var newLng = baseLocation.lng();
            if(addressCounts[baseLocation] > 1) {
              var offset = 0.00001;
              newLat = baseLocation.lat() + (offset * addressCounts[baseLocation]);
              newLng = baseLocation.lng() + (offset * addressCounts[baseLocation]);
            }
            var markerPosition = new google.maps.LatLng(newLat, newLng);
            // マーカー2
            var marker = new google.maps.Marker({
              position: markerPosition,
              map: map,
              title: shop.site_name
            });

            var infowindow2 = new google.maps.InfoWindow({
              content: 
              `<div>
                <a href="${url}" target="_blank">${shop.site_name}</a><br>
                ${shop.address1 + shop.address2 + shop.address3}<br>
                <p>${shop.content}</p>
              </div>`
            });
    
            // マーカーをクリックしたときに情報ウィンドウを表示する
            marker.addListener('click', function() {
              infowindow2.open(map, marker);
            });
          } else {
            // console.log(status)
          }
        });
      });
      
      
      $("#overlay").fadeOut(300);
    }
    function fail(error) {
      // console.log(error)
    }
    
    navigator.geolocation.getCurrentPosition(success, fail);
  }
</script>
@endsection