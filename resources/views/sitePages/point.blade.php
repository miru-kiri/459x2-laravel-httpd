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
    <div class="container">
       ページテスト



    
    </div>
  </section>
@endsection