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
      <div class="row">
        @foreach($cards as $card)
        <div class="col-6 col-md-4 mt-3">
          <a href="{{ $card['url'] }}" style="text-decoration: none">
            <div class="card rounded-0 py-2 mb-0">
              <div class="text-center text-body">
                <h2 style="font-size: 1rem">{{ $card['text'] }}</h2>
              </div>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  <section>
    <div class="container mt-3">
      <div class="mb-3" id="tengokunet">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid {{ $mainColor }};">天国ネットとは？</h2>
        <span>天国ネットとは、四国地方中心に、風俗・メンズエステ・キャバクラ・飲食店などをご紹介するポータルサイトです。<br></span>
      </div>
      <div class="mb-3" id="member">
        <p class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid {{ $mainColor }};">お得な会員システムについて</p>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
      </div>
      <div class="mb-3" id="reserve">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid {{ $mainColor }};">ご予約について </h2>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
      </div>
      <div class="mb-3" id="price">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid {{ $mainColor }};">料金について</h2>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
      </div>
      <div class="mb-3" id="cast">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid {{ $mainColor }};">女の子について</h2>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
      </div>
      <div class="mb-3" id="other">
        <h2 class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid {{ $mainColor }};">その他</h2>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
        <span>Q: ********************<br></span>
        <span>A: ********************<br></span>
      </div>
    </div>
  </section>
  <!-- バナー -->
  <section class="my-5">
    <div class="container text-center">
      <span>バナーをいただき次第設置予定。</span>
    </div>
  </section>
@endsection