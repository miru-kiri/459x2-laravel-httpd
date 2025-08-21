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
      @foreach($shopManagerPaginated as $managerBlog)
      <!-- <div class="col-4 col-md-2">
        <div class="card px-0"> -->
        <ul>
          <li class="list_content">
            <p class="mb-2 text-muted">{{ date('Y年m月d日',strtotime($managerBlog['published_at'])) }}</p>
            <a href="{{ route('site.detail.blog.detail',['site_id' => $managerBlog['site_id'],'category_id' => 2,'id' => $managerBlog['id']]) }}" class="list_title fw-bold">{{ $managerBlog['title'] }}</a>
          </li>
        </ul>
        <!-- </div>
        </div> -->
        @endforeach
      </div>
    </div>
    {{ $shopManagerPaginated->links() }}
  </section>
@endsection