@extends('layouts.site')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット現在地から検索')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！現在地から店舗を検索することができます！')
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
      <h2 class="fw-bold" style="color: #05184D;font-size:1.2rem">お客様の位置情報からお近くの店舗を検索することができます。</h2>
      <p style="font-size:0.8rem">お客様のご希望条件をチェックして検索ボタンをクリックしてください。</p>
      <h3 class="fw-bold" style="font-size: 1rem; border-bottom: 1px solid #d3d3d3">ジャンル</h3>
      <!-- <div class="mb-3"> -->
        <!-- <small>※検索したいものにチェックを入れてください。</small> -->
      <!-- </div> -->
      <form action="{{ route('site.search.detail') }}" method="POST">
      @csrf
      <div class="row mb-3">
        @foreach($genre as $g)
        <div class="col-6 col-md-2">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="{{ $g['name'] }}">
            <label class="form-check-label">{{ $g['label'] }}</label>
          </div>
        </div>
        @endforeach
      </div>

      <h3 class="fw-bold mb-0" style="font-size: 1rem; border-bottom: 1px solid #d3d3d3">風俗詳細検索</h3>
      @isset($formatDetail[1])
      <div class="row my-3">
        @foreach($formatDetail[1] as $detail)
        <div class="col-6 col-md-2">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="detail-{{ $detail->id }}">
            <label class="form-check-label">{{ $detail->name }}</label>
          </div>
        </div>
        @endforeach
      </div>
      @endisset
      <h3 class="fw-bold mb-0" style="font-size: 1rem; border-bottom: 1px solid #d3d3d3">メンズエステ詳細検索</h3>
      @isset($formatDetail[2])
      <div class="row my-3">
        @foreach($formatDetail[2] as $detail)
        <div class="col-6 col-md-2">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="detail-{{ $detail->id }}">
            <label class="form-check-label">{{ $detail->name }}</label>
          </div>
        </div>
        @endforeach
      </div>
      @endisset
      <h3 class="fw-bold mb-0" style="font-size: 1rem;border-bottom: 1px solid #d3d3d3">セクキャバ詳細検索</h3>
      @isset($formatDetail[3])
      <div class="row my-3">
        @foreach($formatDetail[3] as $detail)
        <div class="col-6 col-md-2">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="detail-{{ $detail->id }}">
            <label class="form-check-label">{{ $detail->name }}</label>
          </div>
        </div>
        @endforeach
      </div>
      @endisset
      <div class="d-flex justify-content-center my-5">
        <button class="btn btn-outline-secondary"  id="search_btn" style="width: 50%">検索</button>
      </div>
      </form>
    </div>
  </section>
@endsection

@section('script')
<script>
  $('#searchForm').submit(function(event) {
    // チェックされているチェックボックスを確認
    var isChecked = $('.form-check-input:checked').length > 0;
    if(!isChecked) {
      alert('一つ以上選択してください。')
      return false;
    }
  })
</script>
@endsection