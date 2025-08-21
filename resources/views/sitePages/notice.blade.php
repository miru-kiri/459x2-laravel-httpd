@extends('layouts.site')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネットお知らせ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！')
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
  <style>
.content-area img,
.content-area video {
    max-width: 100% !important;
    height: auto !important;
}
  </style>
@endsection

@section('content')
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
      <div class="card my-3">
        <h5 class="card-header">{{ $news->title }}</h5>
        <div class="card-body">
        <p class="mb-2 text-muted text-end">{{ date('Y年m月d日',strtotime($news->display_date)) }}</p>
        <div class="content-area">
          {!! $news->content !!}
        </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('script')
<script>
  
</script>
@endsection