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
.cast_link_btn a{
  color: white  !important;
  background-color: <?php echo $mainColor; ?> !important;
}
.list_title {
  color:inherit;
  text-decoration: none;
}
.content-area img,
.content-area video {
    max-width: 100% !important;
    height: auto !important;
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
</style>
@endsection

@section('content')
  <section>
    <h1 class="text-white p-3 text-center fw-bold" style="font-size:1.2rem;background: {{ $mainColor }}">{{ $sites->name }}</h1>
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
                  <img src="{{ asset('storage' . $image->image )}}" loading="lazy" style="width:100%; height: 300px;object-fit:contain"></img>
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
  <section>
    <div class="container">
    <div class="text-white p-2 text-center" style="background: whitesmoke">
      @foreach($tabs as $tab)
      <a href="{{ $tab['url'] }}" class="btn {{ $tab['active'] ? 'tab_active' : '' }}">{{ $tab['name'] }}</a>
      @endforeach
    </div>
    </div>
  </section>
  <section>
    @php
       $castDetailUrl = route('site.detail.cast.detail', [
          'genre_id' => $genreId,
          'area_id' => $areaId,
          'site_id' => $siteId,
          'cast_id' => $castId
       ]);
    @endphp
    @if($blog)
    <div class="container">
      <!-- <div class="">
        {{ $blog->title }}
      </div>
      <div class="">
        {{ date('Y年m月d日',strtotime($blog->published_at)) }}
      </div> -->
      <div class="card my-3">
        <h5 class="card-header">{{ $blog->title }}</h5>
        @if($categoryId == 3)
        <div class="cast_link_btn" style="text-align:right;padding:10px 15px 0 0;"><a href="{{ $castDetailUrl }}" style="max-width:70%;display: inline-block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;padding:8px 20px;border-radius:10px;line-height:1.2;text-decoration:none;font-size:14px;">{{ $stname }}</a></div>
        <!--({{ $cast->age }})身長：{{ $cast->height }}B：{{ $cast->bust }}W：{{ $cast->waist }}H：{{ $cast->hip }}-->
        @endif
        <div class="card-body">
          <p class="mb-2 text-muted text-end">{{ date('Y年m月d日 H:i',strtotime($blog->published_at)) }}</p>
          @if($blogImage)
          <div class="">
            <img class="my-2" src="{{ asset('/storage' . $blogImage) }}" loading="lazy" style="width: 100%; max-width:550px; object-fit:contain" alt="{{ $blog->title }}"></img>
          </div>
          @endif
          <div class="content-area">
            {!! $blog->content !!}
          </div>
        </div>
      </div>
      {!! $html !!}
    </div>
    @endif
  </section>
  @if($newBlogDatas)
  <section class="py-5">
    <div class="container">
      @if($categoryId == 1)
        <p class="text-center text-white py-2 mb-4" style="background-color: {{ $mainColor }};font-size:1.1rem;">新着ショップニュース</h2>
      @endif
      @if($categoryId == 2)
        <p class="text-center text-white py-2 mb-4" style="background-color: {{ $mainColor }};font-size:1.1rem;">新着店長ブログニュース</h2>
      @endif
      @foreach($newBlogDatas as $newBlogData)
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <p class="card-subtitle mb-2 text-muted">
              {{ date('Y年m月d日 H:i', strtotime($newBlogData->published_at)) }}
            </p>
            <a href="{{ route('site.detail.blog.detail', ['genre_id' => $genreId, 'area_id' => $areaId, 'site_id' => $newBlogData->site_id, 'category_id' => $categoryId, 'id' => $newBlogData->id]) }}"
                class="list_title fw-bold">
              {{ $newBlogData->title }}
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </section>
  @endif
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
//ログ
    const parameter = {
      blog_id: "{{ $blogId }}",
      category_id: "{{ $categoryId }}",
      site_id: "{{ $siteId }}",
      cast_id: "{{ $castId }}",
    }
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      data: parameter, // JSONデータ本体
      // contentType: "application/json",
      url: "{{ route('site.log.blog') }}",
      dataType:"json",
    }).done(function(data,status,jqXHR) {

    }).fail(function(jqXHR, textStatus, errorThrown) {
        
    }).always(function (data) {
      //
    })

    $(document).ready(function() {
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
        if("{{ $categoryId }}" == 3) {
          // 特定のURLにリダイレクトする先
		const castDetailUrl = "{!! route('site.detail.cast.detail',['genre_id' => '__genre_id__','area_id' => '__area_id__','site_id' => '__site_id__','cast_id' => '__cast_id__']) !!}".replace('__genre_id__', "{{ $genreId }}").replace('__area_id__', "{{ $areaId }}").replace('__site_id__', "{{ $siteId }}").replace('__cast_id__', "{{ $castId }}");
          // すべてのaタグをチェック
          $('a').each(function() {
              const href = $(this).attr('href');
              // hrefがnullでないかつ、特定の文字列が含まれているかチェック
              if (href && href.includes("http://365diary.net/")) {
                $(this).attr('href', castDetailUrl);
              }
          });
        }
    });
</script>
@endsection
