@extends('layouts.site')

@section('title', $pgttl)
@section('description', $pgdesc)
@section('keywords',  $keywords )

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<style>
  main {
    padding-bottom: 50px;
  }
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
.list_content {  
  /* display: flex; */
  list-style: none;
  padding: 10px 0px;
  border-bottom:1px solid #d3d3d3 !important;;
}
.list_title {
  color:inherit;
  text-decoration: none;
}
.cast_list_card {
  color:inherit;
  text-decoration: none;
}
.date_btn {
  color: <?php echo $mainColor; ?> !important;
  border: 1px solid <?php echo $mainColor; ?> !important;
}
.date_btn:hover {
  color: white  !important;
  background-color: <?php echo $mainColor; ?> !important;
}
.date_btn_active {
  color: white  !important;
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
img {
    max-width: 100%;
    height: auto;
}
video {
    max-width: 100%;
    height: auto;
}
.tooltip {
  color: white !important;
}
.tooltip-inner {
  background-color: <?php echo $mainColor; ?> !important;
}
.bs-tooltip-auto[x-placement^=bottom] .arrow::before, .bs-tooltip-bottom .arrow::before {
  border-bottom-color: <?php echo $mainColor; ?> !important;
}
.swiper--wrapper {
  /* wrapperのサイズを調整 */
  width: 100%;
  height: 300px;
}

.swiper-slide {
  /* スライドのサイズを調整、中身のテキスト配置調整、背景色 */
  color: #ffffff;
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 300px;
  text-align: center;
}
.swiper-button-next,
.swiper-button-prev {
    --swiper-navigation-color: <?php echo $mainColor; ?>;
}
.swiper-pagination-bullet,
.swiper-pagination-bullet-active{
    background-color: <?php echo $mainColor; ?>;
}
video {
  width: 100%;
  height: 300px;
}
/* スマートフォンサイズ時のメディアクエリ */
@media (max-width: 768px) {
  video {
    width: 100%;
    height: 150px;
  }
}

</style>

@endsection

@section('content')
  <section>
    <h1 class="text-white mb-0 p-3 text-center fw-bold" style="font-size:1.2rem; background: {{ $mainColor }}">{{ $sites->name }}</h1>
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
    </div>
  </section>
  @if($mainImage->isNotEmpty())
  <section>
    <div class="container text-center">
    <div class="swiper">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            @foreach($mainImage as $image)
              <div class="swiper-slide">
                @if($image->url)
                <a href="{{ $image->url }}">
                @endif
                  <img src="{{ asset('storage' . $image->image )}}" loading="lazy" style="width:100%; height: 300px; object-fit:contain"></img>
                @if($image->url)
                </a>
                @endif
              </div>
            @endforeach
          </div>
          <!-- 必要に応じてページネーション -->
          <div class="swiper-pagination"></div>
          <!-- 必要に応じてナビボタン -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
      </div>
    </div>
  </section>
  @endif
  <section class="container">
    <!-- <div class="container"> -->
    <div class="text-white p-2 text-center" style="background: whitesmoke">
      @foreach($tabs as $tab)
          <a href="{{ $tab['url'] }}#fst_view" class="btn {{ $tab['active'] ? 'tab_active' : '' }}">{{ $tab['name'] }}</a>
      @endforeach
      <a href="https://cosmo-group.co.jp/recruit.php" class="btn" style="color:#fff; background-color:#036eb8">男女スタッフ求人</a>
    </div>
    <!-- </div> -->
  </section>
  <section class="my-3">
    <div id="fst_view"></div>
  @foreach($contents as $id => $cont) 
      {!!  $cont !!}
  @endforeach
  </section>
  <section class="container">
    <div class="text-white p-2 text-center" style="background: whitesmoke">
      @foreach($tabs as $tab)
          <a href="{{ $tab['url'] }}#fst_view" class="btn {{ $tab['active'] ? 'tab_active' : '' }}">{{ $tab['name'] }}</a>
      @endforeach
      <a href="https://cosmo-group.co.jp/recruit.php" class="btn" style="color:#fff; background-color:#036eb8">男女スタッフ求人</a>
    </div>
  </section>

@endsection

@section('script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7mRMvEmcoJx88KwfShBfzX1samxbAc5w&callback=initMap" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip({html:true,placement: 'bottom'});
      const swiper = new Swiper(".swiper", {
          // ページネーションが必要なら追加
          pagination: {
              el: ".swiper-pagination"
          },
          // ナビボタンが必要なら追加
          navigation: {
              nextEl: ".swiper-button-next",
              prevEl: ".swiper-button-prev"
          }
      });
    });
    //ログ
    const parameter = {
      site_id: "{{ $siteId }}",
    }
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      data: parameter, // JSONデータ本体
      // contentType: "application/json",
      url: "{{ route('site.log.site') }}",
      dataType:"json",
    }).done(function(data,status,jqXHR) {

    }).fail(function(jqXHR, textStatus, errorThrown) {
        
    }).always(function (data) {
      //
    })
    const isMap = "{{$isMap}}"
    function initMap() {
    var geocoder = new google.maps.Geocoder();
    var address = "{{ $shops->address1 }}{{$shops->address2}}{{ $shops->address3 }}"; // ここに表示したい住所を入力
    if(isMap) {
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
          // alert('地図を表示できませんでした。エラーコード: ' + status);
        }
      });
    }
  }

</script>
@endsection
