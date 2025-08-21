@extends('layouts.mypage')

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
  <div class="container mt-3"  style="width: 360px;">
    <div class="text-center my-3">
      <p class="fw-bold mb-4 fs-5" style="color: #016DB7;">電話番号変更手続き</p>
      <p class="fw-bold mb-1"><i class="fas fa-key mr-1"  style="color: #016DB7;"></i>認証コード入力</p>
      <small>送信された認証コードを入力してください。</small>
    </div>
    <form action="{{ route('mypage.phone.confirm') }}" method="POST">
      @csrf
      <div class="form-group text-center mb-5">
        <label>認証コード</label><br>
        <span class="error_message" role="alert">
            <strong>{{ session('status') }}</strong>
        </span>
        <input type="text" class="form-control" id="code" name="code" />
        <div class="text-center pt-4">
          <button class="btn tel-btn btn-block" style="color: white;background: #016DB7;">変更する</button>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('script')
@endsection