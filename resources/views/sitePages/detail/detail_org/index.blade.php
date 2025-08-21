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

@section('style')

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
</style>

@endsection

@section('content')
  <section>
    <h1 class="text-white p-3 text-center fw-bold" style="font-size:1.2rem; background: {{ $mainColor }}">{{ $sites->name }}</h1>
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
  @if($sites->template < 5)
  <section>
    <div class="container">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">本日の出勤</h2>
      <div class="row mt-3">
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
  </section>
  @endif
  <section>
    <div class="container">
      <div class="mb-3">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">ショップニュース</h2>
        <span class="headline_text fw-bold">お店からのお得な情報をピックアップ</span>
        <div class="mt-3">
        @foreach($shopBlogs as $shopBlog)
        <ul>
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日',strtotime($shopBlog->published_at)) }}</p>
            <a href="{{ route('site.detail.blog.detail',['site_id' => $shopBlog->site_id,'category_id' => 1,'id' => $shopBlog->id]) }}" class="list_title fw-bold">{{ $shopBlog->title }}</a>
          </li>
        </ul>
        @endforeach
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
      <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店長BLOG</h2>
        <span class="headline_text fw-bold mb-3">店長しか知らない㊙︎な情報を配信</span>
        <div class="mt-3">
        @foreach($managerBlogs as $managerBlog)
        <ul>
        
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日',strtotime($managerBlog->published_at)) }}</p>
            <a href="{{ route('site.detail.blog.detail',['site_id' => $managerBlog->site_id,'category_id' => 2,'id' => $managerBlog->id ]) }}" class="list_title fw-bold">{{ $managerBlog->title }}</a>
          </li>
        </ul>
        <!-- <div class="list-group mb-2">
          <a href="#" class="list-group-item" style="text-decoration: none; color: inherit;">
            <div class="d-flex w-100 justify-content-end">
              <small class="text-muted">{{ date('Y年m月d日',strtotime($managerBlog->published_at)) }}</small>
            </div>
            <p class="mb-2 fw-bold">{{ $managerBlog->site_name }}</p>
            <p class="mb-1">{{ $managerBlog->title }}</p>
          </a>
        </div> -->
        @endforeach
        </div>
      </div>
    </div>
  </section>
  @if($sites->template < 5)
  <section>
    <div class="container">
      <div class="headline mb-3">
        <h2 class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">写メ日記</h2>
        <span class="headline_text fw-bold">在籍の女の子の日記をリアルタイムで紹介</span>
        <div class="row my-3">
        @foreach($castBlogs as $castBlog)
          <div class="col-12 col-md-4">
            <!-- <a href="{{ route('site.detail.blog.detail',['category_id' => 3,'id' => $castBlog->id ]) }}" class="blog_card"> -->
              <div class="card text-center my-2">
                <span class="pt-2">{{ $castBlog->site_name }}</span>
                <img class="py-3" src="{{ asset('/storage' . $castBlog->image) }}" style="height: 200px; object-fit: contain;" alt="{{ $sites->name }} {{ $castBlog->source_name }} {{ $castBlog->title }}">
                <p class="">{{ $castBlog->source_name }}</p>
                <a href="{{ route('site.detail.blog.detail',['site_id' => $castBlog->site_id,'category_id' => 3,'id' => $castBlog->id]) }}">{{ $castBlog->title }}</a>
                <p><small class="text-muted">{{ date('Y年m月d日',strtotime($castBlog->published_at)) }}</small></p>
              </div>
            <!-- </a> -->
          </div>
        @endforeach
        </div>
      </div>
    </div>
  </section>
  
  <section>
    <div class="container">
      <div class="headline mb-3">
        <p class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">指名数ランキング</p>
      </div>
        <div class="container">
          <!-- <small style="color: {{ $mainColor }}">直近3日以内のアクセス総順にランキング</small> -->
          <!-- <div class="row my-3"> -->
            @foreach($castReserveRanks as $castReserveRank)
              <!-- <div class="col-6 col-md-2">
                <div class="card text-center my-2">
                <a href="{{ route('site.detail.cast.detail',['site_id' => $castReserveRank->site_id,'cast_id' => $castReserveRank->id]) }}" class="cast_list_card">
                  <div class="triangle-number-{{$loop->iteration}}"></div>
                  <div class="triangle-number-text">{{ $loop->iteration }}<span class="triangle-number-small-text">位</span></div>
                  <img class="py-3" src="{{ asset('/storage' . $castReserveRank->image) }}" style="max-width: 100%;height: 200px; object-fit: contain;" alt="{{ $castReserveRank->source_name }}">
                  <p class="mb-0">{{ $castReserveRank->source_name }}({{ $castReserveRank->age }})</p>
                  <small class="text-muted mb-3">B{{ $castReserveRank->bust }}({{ $castReserveRank->cup  }})/W{{ $castReserveRank->waist }}/H{{ $castReserveRank->hip }}</small>
                </a>
                </div>
              </div> -->
              <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-md-2 text-center">
                  <div class="triangle-number-{{$loop->iteration}}"></div>
                  <div class="triangle-number-text">{{ $loop->iteration }}<span class="triangle-number-small-text">位</span></div>
                    <img src="{{ asset('/storage' . $castReserveRank->image) }}" style="max-width: 100%;height: 200px; object-fit: contain;" alt="{{ $castReserveRank->source_name }}">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <p class="mb-0">{{ $castReserveRank->source_name }}({{ $castReserveRank->age }})</p>
                      <small class="text-muted mb-3">B{{ $castReserveRank->bust }}({{ $castReserveRank->cup  }})/W{{ $castReserveRank->waist }}/H{{ $castReserveRank->hip }}</small>
                      <p class="my-3">{!! nl2br(e($castReserveRank->shop_manager_pr)) !!}</p>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            @if(!$castReserveRanks)
              <p class="text-center">データがありません。</p>
            @endif
            <!-- </div> -->
          </div>
      </div>
    </div>
  </section>
  <section>
    @if($recommendCasts->isNotEmpty())
    <div class="container">
      <h2 class="fw-bold fs-5 mb-0 mt-3" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店長おすすめガール</h2>
      <div class="row mt-3">
        @foreach($recommendCasts as $cast)
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
    @endif
  </section>
  @endif
  @if($movieData->isNotEmpty())
  <section>
    <div class="container">
      <div class="headline mb-3">
        <p class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">天国ネット動画</p>
        <span class="headline_text fw-bold">今日はどこで遊ぶ？遊びたい地域から選択できます。</span>
      </div>
        <div class="row my-3">
          @foreach($movieData as $data)
          <div class="col-12 col-md-6">
            <video class="video" src="{{ asset('storage' . $data->file_path .'/' . $data->file_name . '.mp4') }}" width="100%" controls></video>
          </div>
          @endforeach
        </div>
      </div>
  </section>
  @endif
  
@endsection

@section('script')
<script>
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

</script>
@endsection