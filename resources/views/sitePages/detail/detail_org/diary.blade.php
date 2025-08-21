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
      <div class="row my-3">
        @foreach ($castBlogsPaginated as $castBlog)
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
          <a href="{{ route('site.detail.blog.detail',['site_id' => $castBlog['site_id'],'category_id' => 3,'id' => $castBlog['id']]) }}" class="blog_card">
              <div class="card px-0">
              <img class="pt-3 text-center" src="{{ isset($formatBlogImages[$castBlog['id']]) ?  asset('/storage' . $formatBlogImages[$castBlog['id']]) : asset('storage/no-image.jpg') }}" class="card-img-top" style="height: 220px; width: auto; object-fit:contain" alt="{{ $sites->name }} {{ $castBlog['source_name'] }} {{ $castBlog['title'] }}">
                <p class="card-text text-white text-center mb-1" style="background: {{ $mainColor }}">{{ $castBlog['source_name'] }}({{ $castBlog['age'] }})</p>
                <div class="text-center" style="height: 5rem">
                  <p class="pt-3">{{ $castBlog['title'] }}</p>
                  <!-- <p class="mb-1">{{ $castBlog['source_name'] }}({{ $castBlog['age'] }})</p> -->
                  <!-- <small class="text-muted">B{{ $castBlog['bust'] ? $castBlog['bust'] : '-' }}({{ $castBlog['cup'] ? $castBlog['cup'] : '-' }})/W{{ $castBlog['waist'] ? $castBlog['waist'] : '-' }}/H{{ $castBlog['hip'] ? $castBlog['hip'] : '-' }}</small> -->
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
      <!-- ページネーションリンクを表示 -->
    </div>
    {{ $castBlogsPaginated->links() }}
  </section>
  
@endsection