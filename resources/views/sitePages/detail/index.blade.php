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
<link rel="stylesheet" href="{{ asset('css/site/top.css?202507031115') }}">
<link rel="stylesheet" href="{{ asset('css/site/miru_top.css?202507031115') }}">
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
  background-image: url("{{ asset('img/header_apr.png') }}");
  background-position: center center;
  background-size: cover;
  */
  background: linear-gradient(to top, #1ab5e7 0%, #ffffff 60%);
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
</style>
@endsection

@section('content')
  <section>
    <div class="text-white p-3 mb-3 text-center fw-bold" style="background: <?php echo $mainColor; ?>">
      <h1 class="fw-bold" style="font-size:1rem;">{{ $mainText }}</h1>
        @php
      $genreTitles = [
        ['genreId' => 1, 'title' =>'ソープ・ヘルスは、極上の癒しと快感が満ちる、まさに天国のような至福のひとときを提供します。'],
        ['genreId' => 2, 'title' =>'メンズエステで体感するのは、快感と癒しが交差する極上のリラクゼーション──あなた専用の天国が、ここに。'],
        ['genreId' => 3, 'title' =>'キャバクラは、洗練された空間で心弾むひとときを楽しめる、大人だけの贅沢な夜の社交場。'],
        ['genreId' => 4, 'title' =>'セクキャバは、距離感ゼロの甘い誘惑が交わる、大人のための艶やかな社交場。'],
        ['genreId' => 5, 'title' =>'愛媛県産の新鮮な食材を使った料理を、心地よい空間でお楽しみいただける場所です。'],
        ['genreId' => 6, 'title' =>'宴会コンパニオンが彩るのは、華やかな女性たちの笑顔と魅力的な会話で、甘く誘惑的な飲み会のひとときを演出します。'],
        ['genreId' => 7, 'title' =>'マッサージともみほぐしで、日々の疲れを取り除き、心地よい空間の中でリフレッシュできる贅沢な時間を提供します。'],
      ];
    @endphp
    
    @foreach($genreTitles as $genre)
      @if($genre['genreId'] == $genreId)
        <div style="font-weight: 100;">
          {{ $genre['title'] }}
        </div>
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
      <div class="headline mb-3">
        <h2 class="headline_title fw-bold fs-5 mb-0" style="color: <?php echo $mainColor; ?>; border-bottom: 2px solid #d3d3d3;">地域から選ぶ</h2>
        <span class="headline_text fw-bold">今日はどこで遊ぶ？遊びたい地域から選択できます。</span>
      </div>
      <div class="row">
        @foreach($cards as $card)
        <div class="col-6 col-md-4">
        <a href="{{ $card['url'] }}" class="none" style="text-decoration: none !important">
          <div class="card mb-3" style="background-color: {{ $card['color'] }};  position: relative;">
            <div class="text-center genre-card-title">
              <div class="genre-card-triangle"></div>
                <h2 class="fw-bold" style="font-size: 1rem">{{ $card['text'] }}<h2>
            </div>
          </div>
        </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @if($genreId < 5)
  <section>
    <div class="container">
      <div class="headline mb-3">
        <h2 class="headline_title fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">写メ日記</h2>
        <span class="headline_text fw-bold">在籍の女の子の日記をリアルタイムで紹介</span>
        <div class="row my-3">
        @foreach($castBlogs as $castBlog)
          <!-- <div class="col-12 col-md-4">
            <div class="card text-center my-2">
              <span class="pt-2">{{ $castBlog->site_name }}</span>
              <a href="{{ route('site.detail.blog.detail',['site_id' => $castBlog->site_id,'category_id' => 3,'id' => $castBlog->id]) }}" >
                <img class="py-3" src="{{ asset('/storage' . $castBlog->image) }}" loading="lazy" style="height: 200px; object-fit: contain;" alt="{{ $castBlog->site_name  }} {{ $castBlog->source_name }}">
              </a>
              <p class="">{{ $castBlog->source_name }}</p>
              <p>{{ $castBlog->title }}</p>
              <p><small class="text-muted">{{ date('Y年m月d日',strtotime($castBlog->published_at)) }}</small></p>
            </div>
          </div> -->
          <div class="col-4 col-md-2 mb-3">
            <div class="image-container">
              <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'site_id' => $castBlog->site_id,'category_id' => 3,'id' => $castBlog->id]) }}" >
                <img src="{{ asset('/storage' . $castBlog->image) }}" loading="lazy" alt="{{ $castBlog->site_name  }} {{ $castBlog->source_name }}">              
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
                    <img class="py-3" src="{{ asset('/storage' . $castAccessRank->image) }}" loading="lazy" style="height: 200px; object-fit: contain;" alt="{{ $castAccessRank->source_name }}">
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
                    <img class="py-3" src="{{ asset('/storage' . $castFavoriteRank->image) }}" loading="lazy" style="height: 200px; object-fit: contain;" alt="{{ $castFavoriteRank->source_name }}">
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
                    <img class="py-3" src="{{ asset('/storage' . $castDiaryRank->image) }}" loading="lazy" style="height: 200px; object-fit: contain;" alt="{{ $castDiaryRank->source_name }}">
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
        <span class="headline_text fw-bold">在籍の女の子の動画をリアルタイムで紹介</span>
      </div>
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
            <div class="video-card">
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
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">ショップニュース</h2>
        <span class="headline_text fw-bold">お店からのお得な情報をピックアップ</span>
        <div class="mt-3">
        @foreach($shopBlogs as $shopBlog)
        <ul>
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日 H:i',strtotime($shopBlog->published_at)) }}</p>
              <!-- <div class="label">{{ $shopBlog->site_name }}</div> -->
            <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'site_id' => $shopBlog->site_id,'category_id' => 1,'id' => $shopBlog->id]) }}" class="list_title fw-bold">{{ $shopBlog->title }}</a>
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
  <section>
    <div class="container">
      <div class="mb-3">
      <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店長BLOG</h2>
        <span class="headline_text fw-bold mb-3">店長しか知らない㊙︎な情報を配信</span>
        <div class="mt-3">
        @foreach($managerBlogs as $managerBlog)
        <ul>
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日 H:i',strtotime($managerBlog->published_at)) }}</p>
            <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'site_id' => $managerBlog->site_id,'category_id' => 2,'id' => $managerBlog->id ]) }}" class="list_title fw-bold">{{ $managerBlog->title }}</a>
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
    <div class="col-12 text-center  mb-5">
       <a href="https://459x.dogo459.com/recruit_top" target="_blank"><img class="top_banner" src="{{ asset('img/recruit_cast_banner.jpg') }}" alt="女の子求人バナー"/></a>
    </div>
    <div class="col-12 text-center  mb-5">
       <a href="https://cosmo-group.co.jp/recruit.php" target="_blank"><img class="top_banner" src="{{ asset('img/recruit_banner.jpg') }}" alt="コスモグループ 採用情報"/></a>
    </div>
  </section>
   <!--krnテスト　業種別の説明--> 
  <section class="py-5">
      <div class="container bg-white p-4 rounded shadow-sm" style="font-size: 12px;">
          <h3 class="mb-4 fw-bold" style="font-size: 16px;">{{ $fstItem }}</h3>
          {!! $secItem !!}
      </div>
  </section>
  <!-- 求人画像ポップアップ -->
  <div id="recruitPopup" class="recruit-popup">
    <a href="{{ $krn_url }}/recruit_top">
      <img src="{{ asset('img/cast_recruit_maru.png') }}" alt="高収入｜コスモ天国ネット求人情報">
    </a>
  </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://develop.459x.com/js/miru_ev_no.js"></script>
<script>
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
      // DOMツリーが完全に読み込まれた後に実行される
    document.addEventListener('DOMContentLoaded', function() {
      // 'lazy-load' クラスを持つすべての画像を取得
      const images = document.querySelectorAll('img.lazy-load');
    });
</script>
@endsection
