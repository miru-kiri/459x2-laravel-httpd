@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページポイント履歴ページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページポイント履歴ページです！')
@section('keywords',  '四国,天国ネット,コスモ天国ネット,風俗,メンズエステ,キャバクラ,セクキャバ,飲食店,宴会コンパニオン' )

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
<link rel="stylesheet" href="{{ asset('css/site/mypage.css') }}">
<style>
  .error_message {
      width: 100%;
      margin-top: 0.25rem;
      font-size: 80%;
      color: #dc3545;
  }
</style>
@endsection

@section('content')
<section>
  <div class="text-white p-3 text-center fw-bold" style="background: #016DB7;">マイページ</div>
</section>
<section>
  <div class="mypage_pc mypage_menu_content justify-content-center py-1">
    @foreach($tabs as $tab)
      <a href="{{ $tab['url'] }}" class="btn rounded-0 {{ $loop->last ? 'mypage_menu_bar_end' : 'mypage_menu_bar' }} {{ $tab['is_active'] ? 'tab_acive' : '' }}">{{ $tab['name'] }}</a>
    @endforeach
  </div>
</section>
<section>
  <div class="container mt-3">
    <div class="my-3">
      <div class="text-center">
        <p class="fw-bold mb-4 fs-5" style="color: #016DB7;">ポイント</p>
        <div class="row mb-3">
          <div class="col-6 text-right">
            <img src="{{ asset('img/point.png') }}" height="120px;"/>
          </div>
          <div class="col-6 text-left">
            <span>所持ポイント</span><br>
            <p class="fw-bold mt-2 mb-4 fs-5">{{ $totalPoint }}pt</p>
          </div>
        </div>
        <div style="border: 1px solid tomato;">
          <small style="padding: 10px;color:tomato;">ポイントは、1ポイント1円としてご利用いただくことができます。</small>
        </div>
      </div>
      <p class="fw-bold mt-3 mb-1 pb-3 fs-5" style="color: #016DB7;border-bottom: 2px solid #d3d3d3;">ポイント取得・利用履歴</p>
      @if($pointHistoryData->isNotEmpty())
        @foreach($pointHistoryData as $history)
          <div class="row py-3" style="border-bottom: 1px solid #d3d3d3;">
            <div class="col-12 col-md-6">
              <p class="fw-bold mb-1">{{ $history->site_name }}</p>
              <span>{{ $history->date_time }}<span><br>
              <button class="btn btn-sm {{  $history->category_color ?? '' }} mt-2 rounded-pill px-3"><small>{{ $history->category_name }}</small></button>
            </div>
            <div class="col-12 col-md-6 text-right">
              <p class="fw-bold mt-3 fs-5">{{ $history->point }}pt</p>
            </div>
          </div>
        @endforeach
      @else
        <p class="text-center">ポイント履歴はありません。</p>
      @endif
    </div>
  </div>
</section>
@endsection

@section('script')
@endsection