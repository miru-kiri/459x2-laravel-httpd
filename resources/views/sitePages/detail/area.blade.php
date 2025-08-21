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
<link rel="stylesheet" href="{{ asset('css/site/miru_top.css?202507031132') }}">
<style>
main{
  background-image:url("{{ asset('img/bg_umi.jpg') }}");
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center;
  padding-bottom:80px;
}
header{
  /*
  background-image: url("{{ asset('img/header_gw.png') }}");
  background-position: center center;
  background-size: contain;
  background-repeat: repeat-x;  
  */
  background: linear-gradient(to top, #1ab5e7 0%, #ffffff 60%);
}
header div.sp {
    margin-bottom: 0 !important;
}
.logo{
  /*
  background-color:#fff;
  */
  padding:0 5px;
}
.nav-pills .nav-link {
  border: 1px solid <?php echo $mainColor; ?> !important;
  color: <?php echo $mainColor; ?> !important;
}
.nav-pills .nav-link.active {
  color: #fff !important;
  background: <?php echo $mainColor; ?> !important;
}
.shop_btn {
  color: <?php echo $mainColor; ?> !important;
  border: 2px solid <?php echo $mainColor; ?> !important;
}
.shop_btn:hover {
  color: white !important;
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
.list_title:hover {
  color:inherit;
}
.cast_list_card {
  color:inherit;
  text-decoration: none;
}
.tooltip {
  color: white !important;
}
.tooltip-inner {
  background-color: <?php echo $mainColor; ?> !important;
}
.bs-tooltip-auto[data-popper-placement^=bottom] .tooltip-arrow::before, .bs-tooltip-bottom .tooltip-arrow::before {
  border-bottom-color: <?php echo $mainColor; ?> !important;
}
video {
  width: 100%;
  height: 300px;
}
.genre-badge {
  background-color: <?php echo $mainColor; ?> !important;
  color: white;
}
img[src*="loading_s.gif"]{
  max-width:100%!important;
  height: auto!important;
  width: auto !important;
}
</style>
@endsection

@section('content')
<section>
    <div class="text-white p-3 mb-3 text-center fw-bold" style="background: {{ $areaColor }}">
      <h1 class="fw-bold" style="font-size:1rem">{{ $areaText }}</h1>
      @php
      $messe = [
        ['genreId' => 1, 'areaId' => 13, 'messe' =>'ソープ・ヘルスで旅の疲れを癒し道後の情緒を味わえる店を紹介' ],
        ['genreId' => 1, 'areaId' => 11, 'messe' =>'ソープ・ヘルスで味わう甘美な快楽と濃密な癒しが溶け合う特別な店を紹介' ],
        ['genreId' => 1, 'areaId' => 10, 'messe' =>'ソープ・ヘルスで極上の癒しと快感を味わえる、非日常的なリラックス空間の店を紹介' ],
        ['genreId' => 4, 'areaId' => 14, 'messe' =>'セクキャバで感じる距離感ゼロの極上快楽、大人の社交場を提供する松山のお店を紹介' ],
        ['genreId' => 3, 'areaId' => 6, 'messe' =>'キャバクラで四国の風情を感じる洗練されたサービスと贅沢なひとときを楽しめる店を紹介' ],
        ['genreId' => 2, 'areaId' => 14, 'messe' =>'メンズエステで心身リフレッシュ、極上の癒しと快適なひとときを提供する店を紹介' ],
        ['genreId' => 2, 'areaId' => 5, 'messe' =>'メンズエステで心身を解きほぐし、リラックスできる贅沢な癒し空間を提供する店を紹介' ],
        ['genreId' => 2, 'areaId' => 6, 'messe' =>'メンズエステで完全プライベートな癒し空間、贅沢なリラックスひとときを楽しめる店を紹介' ],
        ['genreId' => 6, 'areaId' => 13, 'messe' =>'コンパニオンと共に道後温泉近くで、贅沢なひとときと癒しの時間を楽しめるお店を紹介' ],
        ['genreId' => 6, 'areaId' => 14, 'messe' =>'コンパニオンと過ごす、魅惑的なひとときと心地よい癒しを提供するお店を紹介' ],
        ['genreId' => 5, 'areaId' => 14, 'messe' =>'地元食材の美味しさが光る料理と、贅沢で忘れられないひとときを堪能できるお店を紹介' ],
        ['genreId' => 7, 'areaId' => 14, 'messe' =>'マッサージで深い癒しを提供し、体の疲れをリセットできるお店を紹介' ],
      ];
    @endphp

    @foreach($messe as $mse)
        @if($mse['genreId'] == $genreId && $mse['areaId'] ==$areaId )
          <h2 class="text-white text-center" style="font-weight: 100; font-size: 16px;">
            {{ $mse['messe']}}
          </h2>
        @endif
    @endforeach
    </div>
  </section>
  <section>
    <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mt-1" style="background-color: unset;">
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
    <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店舗を選ぶ</h2>
      <span class="headline_text fw-bold">
        @if('gnr' == $kio_gen)
           <span>今日はどこをご利用ですか？オススメ店舗から選択できます。</span>
        @else
           <span>今日はどこで遊ぶ？遊びたい店舗から選択できます。</span>
        @endif 
    </span>
      @foreach($sites as $site)
      <div class="col-12">
          <div class="card my-2">
            <div class="text-white p-2 fs-5 fw-bold" style="background: {{ $mainColor }}">
              <a href="{{ route('site.detail.top',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $site->id ]) }}" class="list_title">
                <h3 class="pt-2 fw-bold" style="font-size:1.2rem;">{{ $site->name }}</h3>
              </a>
            </div>
            <div class="container">
              <div class="row py-2"> 
                <div class="my-3 shop_info_pc">
                @isset($formatSiteGenre[$site->id])
                    @php
                       $rvs = [];
                       $rvs = array_reverse($formatSiteGenre[$site->id], true);
                    @endphp
                    @foreach($rvs as $siteGenre)
                      <span class="badge genre-badge">{{ $siteGenre->name }}</span>
                    @endforeach
                  @endisset
                  @if($site->open_time && $site->close_time)
                  <br>
                  <span class="mr-1 fw-light"><i class="far fa-clock mr-1"></i>{{ $site->opening_text }}</span>
                  @endif
                  @if($site->course)
                  <span class="mr-1 fw-light"><i class="fas fa-yen-sign mr-1"></i>{{ $site->course->time }}分 {{ $site->course->fee }}円 ~</span>
                  @endif
                  <span class="fw-light"><i class="fas fa-map-marker-alt mr-1"></i>{{ $areaText }}エリア</span>
                </div>
                <div class="col-6 col-md-4 text-center">
                  <a href="{{ route('site.detail.top',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $site->id ]) }}">
                    <img class="my-2" src="https://459x.com/storage/top/loading_s.gif" data-layzr="{{ asset('/storage' . $site->thumbnail) }}" style="height: 200px; width: 150px;object-fit:contain" alt="{{ $site->name }}"></img>
                  </a>
                </div>
                <div class="col-6 shop_info_sp">
                  @isset($formatSiteGenre[$site->id])
                    @php
                       $rvs = [];
                       $rvs = array_reverse($formatSiteGenre[$site->id], true);
                    @endphp
                    @foreach($rvs as $siteGenre)
                      <span class="badge genre-badge">{{ $siteGenre->name }}</span>
                    @endforeach
                  @endisset
                  @if($site->open_time && $site->close_time)
                  <br>
                  <span class="mr-1 fw-light"><i class="far fa-clock mr-1"></i>{{ $site->opening_text }}</span><br>
                  @endif
                  @if($site->course)
                  <span class="mr-1 fw-light"><i class="fas fa-yen-sign mr-1"></i>{{ $site->course->time }}分 {{ $site->course->fee }}円 ~</span><br>
                  @endif
                  <span class="fw-light"><i class="fas fa-map-marker-alt mr-1"></i>{{ $areaText }}エリア</span>
                </div>
                <div class="col-12 col-md-8">
                  <p class="fw-bold text-muted">{!! nl2br(e($site->content)) !!}</p>
                  @if($genreId < 5)
                  <p class="fw-bold" style="color: {{ $mainColor }}">本日出勤の女の子</p>
                  <div class="container-fluid">
                    <div class="row flex-nowrap" style="overflow-x: auto;">
                    @isset($formatWorkCasts[$site->id])
                      @foreach($formatWorkCasts[$site->id] as $castId => $data)
                      <div class="card px-0 mr-2" style="width: 7rem;" data-toggle="tooltip" data-bs-placement="bottom" title="{!! nl2br(e($data->sokuhime_comment)) !!}">
                        <a href="{{ route('site.detail.cast.detail',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $site->id,'cast_id' => $castId]) }}" class="cast_list_card">
                          @if($data->exclusive_status_image)
                          <div style="position: relative; display: inline-block;">
                            <img src="https://459x.com/storage/top/loading_s.gif" data-layzr="{{ asset('img/'. $data->exclusive_status_image ) }}" class="stay-status-img"></img>
                            <img src="https://459x.com/storage/top/loading_s.gif" data-layzr="{{ asset('storage/'.$data->image) }}" class="card-img-top" alt="{{ $site->name }} {{ $data->source_name }}" style="max-height: 150px !important;">
                          </div>
                          @else
                            <img src="https://459x.com/storage/top/loading_s.gif" data-layzr="{{ asset('storage/'.$data->image) }}" class="card-img-top" alt="{{ $site->name }} {{ $data->source_name }}" style="max-height: 150px !important;">
                          @endif
                          <p class="card-text text-white text-center" style="background: grey">{{ $data->start_time }} ~ {{ $data->end_time }}</p>
                        </a>
                      </div>
                      @endforeach
                    @endisset
                    </div>
                  </div>
                  @endif
                </div>
              </div>
              <a href="{{ route('site.detail.top',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $site->id ]) }}" class="btn btn-block my-3 fw-bold rounded-pill shop_btn">お店の詳細はこちら</a>
            </div>
          </div>
      </div>
      @endforeach
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">ショップニュース</h2>
        <span class="headline_text fw-bold">
        @if('gnr' == $kio_gen)
           <span>お店からのお得な情報をピックアップ</span>
        @else
           <span>お店からのお得な情報をピックアップ</span>
        @endif
      </span>
        <div class="mt-3">
        @foreach($shopBlogs as $shopBlog)
        <ul>
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日 H:i',strtotime($shopBlog->published_at)) }}</p>
              <!-- <div class="label">{{ $shopBlog->site_name }}</div> -->
            <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $shopBlog->site_id,'category_id' => 1,'id' => $shopBlog->id]) }}" class="list_title fw-bold">{{ $shopBlog->title }}</a>
          </li>
        </ul>
        
        <!-- <div class="list-group mb-2">
          <a href="#" class="list-group-item" style="text-decoration: none; color: inherit;">
            <div class="d-flex w-100 justify-content-end">
              <small class="text-muted">{{ date('Y年m月d日',strtotime($shopBlog->published_at)) }}</small>
            </div>
            <p class="mb-2 fw-bold">{{ $shopBlog->site_name }}</p>
            <p class="mb-1">{{ $shopBlog->title }}</p>
          </a>
        </div> -->
        @endforeach
        </div>
      </div>
    </div>
  </section>
  @if($genreId < 5)
  <section>
    <div class="container">
      <div class="headline mb-3">
        <p class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">写メ日記</p>
        <span class="headline_text fw-bold">
        @if('gnr' == $kio_gen)
           <span>{{ $gnr_name }}に在籍のスタッフのブログをリアルタイムで紹介</span>
        @else
           <span>{{ $gnr_name }}に在籍の女の子の日記をリアルタイムで紹介</span>
        @endif
      </span>
        <div class="row my-3">
        @foreach($castBlogs as $castBlog)
          <!-- <div class="col-6 col-md-4">
            <div class="card text-center my-2">
              <span class="pt-2">{{ $castBlog->site_name }}</span>
              <a href="{{ route('site.detail.blog.detail',['site_id' => $castBlog->site_id,'category_id' => 3,'id' => $castBlog->id]) }}">
              <img class="py-3" src="{{ asset('/storage' . $castBlog->image) }}" style="height: 200px; object-fit: contain;" alt="{{ $castBlog->site_name }} {{ $castBlog->source_name }}">
              </a>
              <p class="">{{ $castBlog->source_name }}</p>
              <p>{{ $castBlog->title }}</p>
              <p><small class="text-muted">{{ date('Y年m月d日 H:i',strtotime($castBlog->published_at)) }}</small></p>
            </div>
          </div> -->
          <div class="col-4 col-md-2 mb-3">
            <div class="image-container">
              <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $castBlog->site_id,'category_id' => 3,'id' => $castBlog->id]) }}">
                <img src="https://459x.com/storage/top/loading_s.gif" data-layzr="{{ asset('/storage' . $castBlog->image) }}" alt="{{ $castBlog->site_name  }} {{ $castBlog->source_name }}">              
                <div class="text-overlay">
                  <p class="cast-blog-site">{{ $castBlog->site_name }}</p>
                  <p class="cast-blog-title">{{ $castBlog->title }}</p>
                  <p class="cast-blog-name">{{ $castBlog->source_name }}</p>
                  <p class="cast-blog-date">{{ date('Y年m月d日 H:i',strtotime($castBlog->published_at)) }}</p>
                </div>
              </a>
            </div>
          </div>
        @endforeach
        </div>
      </div>
    </div>
  </section>
  <!-- <section>
     <div class="container">
      <div class="headline mb-3">
        <h2 class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">ランキング</h2>
        <ul class="nav nav-pills my-3" id="pills-tab" role="tablist">
          <li class="nav-item col-4 d-flex justify-content-center" role="presentation">
            <button
              class="nav-link active btn-block"
              id="pills-access-tab"
              data-bs-toggle="pill"
              data-bs-target="#pills-access"
              type="button"
              role="tab"
              aria-controls="pills-access"
              aria-selected="true"
            >
              <small class="fw-bold">アクセス</small>
            </button>
          </li>
          <li class="nav-item col-4 d-flex justify-content-center" role="presentation">
            <button
              class="nav-link btn-block"
              id="pills-favarite-tab"
              data-bs-toggle="pill"
              data-bs-target="#pills-favarite"
              type="button"
              role="tab"
              aria-controls="pills-favarite"
              aria-selected="false"
            >
              <small class="fw-bold">お気に入り</small>
            </button>
          </li>
          <li class="nav-item col-4 d-flex justify-content-center" role="presentation">
            <button
              class="nav-link btn-block"
              id="pills-diary-tab"
              data-bs-toggle="pill"
              data-bs-target="#pills-diary"
              type="button"
              role="tab"
              aria-controls="pills-diary"
              aria-selected="false"
            >
              <small class="fw-bold">写メ日記</small>
            </button>
          </li>
        </ul>
        <div class="container">
          
          <div class="tab-content" id="pills-tabContent">
            <div
              class="tab-pane fade show active"
              id="pills-access"
              role="tabpanel"
              aria-labelledby="pills-access-tab"
            >
            <div class="row my-3">
              @foreach($castAccessRanks as $castAccessRank)
                <div class="col-{{ $loop->iteration == 1 ? 12 : 6 }} col-md-4">
                  <div class="card text-center my-2">
                  <a href="{{ route('site.detail.cast.detail',['site_id' => $castAccessRank->site_id,'cast_id' => $castAccessRank->id]) }}" class="list_title">
                    <div class="triangle-number-{{$loop->iteration}}"></div>
                    <div class="triangle-number-text">{{ $loop->iteration }}<span class="triangle-number-small-text">位</span></div>
                    <img class="py-3" src="{{ asset('/storage' . $castAccessRank->image) }}" style="height: 200px; object-fit: contain;" alt="{{ $castAccessRank->source_name }}">
                    <p class="mb-0">{{ $castAccessRank->source_name }}({{ $castAccessRank->age }})</p>
                    <small class="text-muted mb-3">B{{ $castAccessRank->bust }}({{ $castAccessRank->cup  }})/W{{ $castAccessRank->waist }}/H{{ $castAccessRank->hip }}</small>
                  </a>
                  </div>
                </div>
              @endforeach
              @if(!$castAccessRanks)
                <p class="text-center">データがありません。</p>
              @endif
              </div>
            </div>
            
            <div
              class="tab-pane fade"
              id="pills-favarite"
              role="tabpanel"
              aria-labelledby="pills-favarite-tab"
            >
              <div class="row my-3">
              @foreach($castFavoriteRanks as $castFavoriteRank)
                <div class="col-{{ $loop->iteration == 1 ? 12 : 6 }} col-md-4">
                  <div class="card text-center my-2">
                  <a href="{{ route('site.detail.cast.detail',['site_id' => $castFavoriteRank->site_id,'cast_id' => $castFavoriteRank->id]) }}" class="list_title">
                    <div class="triangle-number-{{$loop->iteration}}"></div>
                    <div class="triangle-number-text">{{ $loop->iteration }}<span class="triangle-number-small-text">位</span></div>
                    <img class="py-3" src="{{ asset('/storage' . $castFavoriteRank->image) }}" style="height: 200px; object-fit: contain;" alt="{{ $castFavoriteRank->source_name }}">
                    <p class="mb-0">{{ $castFavoriteRank->source_name }}({{ $castFavoriteRank->age }})</p>
                    <small class="text-muted mb-3">B{{ $castFavoriteRank->bust }}({{ $castFavoriteRank->cup  }})/W{{ $castFavoriteRank->waist }}/H{{ $castFavoriteRank->hip }}</small>
                  </a>
                  </div>
                </div>
              @endforeach
              @if(!$castFavoriteRanks)
                <p class="text-center">データがありません。</p>
              @endif
              </div>
            </div>
            
            <div
              class="tab-pane fade"
              id="pills-diary"
              role="tabpanel"
              aria-labelledby="pills-diary-tab"
            >
              <div class="row my-3">
              @foreach($castDiaryRanks as $castDiaryRank)
                <div class="col-{{ $loop->iteration == 1 ? 12 : 6 }} col-md-4">
                  <div class="card text-center my-2">
                  <a href="{{ route('site.detail.cast.detail',['site_id' => $castDiaryRank->site_id,'cast_id' => $castDiaryRank->id]) }}" class="list_title">
                    <div class="triangle-number-{{$loop->iteration}}"></div>
                    <div class="triangle-number-text">{{ $loop->iteration }}<span class="triangle-number-small-text">位</span></div>
                    <img class="py-3" src="{{ asset('/storage' . $castDiaryRank->image) }}" style="height: 200px; object-fit: contain;" alt="{{ $castDiaryRank->source_name }}">
                    <p class="mb-0">{{ $castDiaryRank->source_name }}({{ $castDiaryRank->age }})</p>
                    <small class="text-muted mb-3">B{{ $castDiaryRank->bust }}({{ $castDiaryRank->cup  }})/W{{ $castDiaryRank->waist }}/H{{ $castDiaryRank->hip }}</small>
                  </a>
                  </div>
                </div>
              @endforeach
              @if(!$castDiaryRanks)
                <p class="text-center">データがありません。</p>
              @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> --> 
  @endif
  <section>
    <div class="container">
      <div class="headline mb-3">
        <h2 class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">天国ネット動画</h2>
        <span class="headline_text fw-bold">
        @if('gnr' == $kio_gen)
           <span>{{ $gnr_name }}に在籍のスタッフの動画をリアルタイムで紹介</span>
        @else
           <span>{{ $gnr_name }}に在籍の女の子の動画をリアルタイムで紹介</span>
        @endif
      </span>
        <div class="row my-3">
          @foreach($movieData as $data)
			@php
            if(empty($data->cast_id)) {
                $url = route('site.detail.top', ['site_id' => $data->site_id]);
            } else {
                $url = route('site.detail.cast.detail', ['site_id' => $data->site_id, 'cast_id' => $data->cast_id]);
            }
          @endphp
          <div class="col-6 col-md-4 mb-4">
            <div class="video-card mr-2">
              <video class="cast-video w-100" src="{{ asset('storage' . $data->file_path .'/' . $data->file_name . '.mp4') }}" controlsList="nodownload" oncontextmenu="return false;" preload="none" controls muted playsinline loading="lazy"></video>
              <div class="video-content p-3">
                <h6 class="video-title fw-bold mb-2" style="color: {{ $mainColor }}">{{ $data->title }}</h6>
                <a href="{{ $url }}" class="cast-name text-decoration-none">
                  <span class="d-block mb-2 text-primary">{{ $data->source_name ?? $data->site_name }}</span>
                </a>
                <p class="video-description text-muted mb-0 small">{{ $data->content }}</p>
              </div>
            </div>
		   </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
      <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店長BLOG</h2>
        <span class="headline_text fw-bold mb-3">
        @if('gnr' == $kio_gen)
           <span>店長しか知らないお得な情報を配信</span>
        @else
           <span>店長しか知らない㊙︎な情報を配信</span>
        @endif
      </span>
        <div class="mt-3">
        @foreach($managerBlogs as $managerBlog)
        <!-- <div class="list-group mb-2">
          <a href="#" class="list-group-item" style="text-decoration: none; color: inherit;">
            <div class="d-flex w-100 justify-content-end">
              <small class="text-muted">{{ date('Y年m月d日 H:i',strtotime($managerBlog->published_at)) }}</small>
            </div>
            <p class="mb-2 fw-bold">{{ $managerBlog->site_name }}</p>
            <p class="mb-1">{{ $managerBlog->title }}</p>
          </a>
        </div> -->
        <ul>
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日 H:i',strtotime($managerBlog->published_at)) }}</p>
            <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $managerBlog->site_id,'category_id' => 2,'id' => $managerBlog->id]) }}" class="list_title fw-bold">{{ $managerBlog->title }}</a>
          </li>
        </ul>
        @endforeach
        </div>
      </div>
    </div>
    <div class="col-12 text-center  mb-5">
       <a href="https://459x.dogo459.com/recruit_top" target="_blank"><img class="top_banner" src="{{ asset('img/recruit_cast_banner.jpg') }}" alt="女の子求人バナー"/></a>
    </div>
    <div class="col-12 text-center  mb-5">
       <a href="https://cosmo-group.co.jp/recruit.php" target="_blank"><img class="top_banner" src="{{ asset('img/recruit_banner.jpg') }}" alt="コスモグループ 採用情報"/></a>
    </div>
  </section>
  @if ($genreId == 1 && $areaId == 13)
    <div class="container inner kabuki kabuki_bg">
      <p style="color:#222;">
        <span style="color:#e20f0f; font-weight: bold;">道後歌舞伎通り</span>とは、愛媛県松山市の道後温泉近くにある坂道で、かつての歓楽街の面影を残す観光地です。<br><br>
        <a href="https://459x.com/dogo_kabuki" style="color:#0d6bd8; text-decoration: underline;">
        道後歌舞伎通りの詳細ページへ
        </a>
      </p>
    </div>
  @endif
  <!--krn_health_2025_5_22-->
    @if ($genreId == 1 && $areaId == 13)
    <div class="container inner health health_bg">
      <p style="color:#222;">
        <span style="color:#e20f0f; font-weight: bold;">道後ヘルスビル</span>とは、多彩な風俗店が集結する大人たちの聖地！それが「道後ヘルスビル」<br />
        道後エリアで最も有名な風俗スポット『道後ヘルスビル』の魅力を徹底解説します。<br /><br />
        <a href="https://develop.459x.com/health_bill" style="color:#0d6bd8; text-decoration: underline;">
        道後ヘルスビルの詳細ページへ
        </a>
      </p>
    </div>
  @endif


<!--krnテスト エリア内でだけ表示-->
  <section class="py-5">
      <div class="container bg-white p-4 rounded shadow-sm" style="font-size: 12px;">
          <h3 class="mb-4 fw-bold" style="font-size: 16px;">{{ $testAra }}</h3>
          {!! $krnAra !!}
      </div>
  </section>
    <!-- 求人画像ポップアップ -->
  <div id="recruitPopup" class="recruit-popup">
    <a href="https://459x.dogo459.com/recruit_top">
      <img src="{{ asset('img/cast_recruit_maru.png') }}" alt="高収入｜コスモ天国ネット求人情報">
    </a>
  </div>

  
@endsection
@section('script')
<script src="https://459x.com/js/layzr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://develop.459x.com/js/miru_ev_no.js"></script>
<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({html:true});
});
</script>
<script>
   //動画の遅延
  document.addEventListener("DOMContentLoaded", function() {
    const videos = document.querySelectorAll('.cast-video');
    // IntersectionObserverの設定
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {  // 動画が画面に表示された場合
          const video = entry.target;
          video.src = video.getAttribute('src'); 
          video.load(); // 動画の読み込みを開始
          video.play(); // 動画を再生
          observer.unobserve(video); // 一度読み込みが完了したら監視を停止
        }
      });
    }, { threshold: 0.5 }); // 50%表示されたら読み込み開始
    // 各動画を監視対象に追加
    videos.forEach(video => {
      observer.observe(video);
    });
  });
</script>
<script>var layzr = new Layzr();</script>
@endsection