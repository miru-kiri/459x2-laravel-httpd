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
.cast_list_card {
  color:inherit;
  text-decoration: none;
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
    <div class="container mt-3">
      <div class="row">
      @foreach($dateArray as $dateAry)
          <div class="col-{{ $loop->iteration == 1 ? 12 : 4 }} col-md-{{ $loop->iteration == 1 ? 12 : 2 }}  text-center mb-3">
            <a href="{{ route('site.detail.work',['site_id' => $siteId, 'date' => $dateAry['Ymd'] ]) }}" class="btn btn-block date_btn {{ $dateAry['active'] ? 'date_btn_active' : '' }}" style="white-space:nowrap;">{{ date('n月j日') == $dateAry['date'] ? '本日' : '' }} {{ $dateAry['date'] }}({{ $dateAry['week'] }})</a>
          </div>
      @endforeach
      </div>
      
      <div class="row">
        @foreach($casts as $cast)
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <a href="{{ route('site.detail.cast.detail',['site_id' => $cast->site_id,'cast_id' => $cast->cast_id]) }}" class="cast_list_card">
              <div class="card px-0">
                <img class="pt-3 text-center" src="{{ asset('/storage' . $cast->image) }}" class="card-img-top" style="height: 220px; width: auto; object-fit:contain" alt="{{ $sites->name }} {{ $cast->source_name }}">
                <p class="card-text text-white text-center mb-1" style="background: {{ $mainColor }}">{{ $cast->sokuhime_status }}</p>
                <div class="text-center" style="height: 5rem">
                  <p class="mb-1">{{ $cast->source_name }}({{ $cast->age }})</p>
                  <small class="text-muted">B{{ $cast->bust ? $cast->bust : '-' }}({{ $cast->cup ? $cast->cup : '-' }})/W{{ $cast->waist ? $cast->waist : '-' }}/H{{ $cast->hip ? $cast->hip : '-' }}</small>
                </div>
                <button class="btn btn-block cast-schedule-btn">{{ $cast->start_time }} ~ {{ $cast->end_time }}</button>
              </div>
          </a>
          </div>
        @endforeach
      </div>
      </div>
    </div>
  </section>
  
@endsection