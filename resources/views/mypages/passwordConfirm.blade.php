@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページパスワード再発行ページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページパスワード再発行です！')
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
    <div class="text-center my-4">
      <p class="fw-bold mb-4 fs-5" style="color: #016DB7;">パスワード変更手続き</p>
      <p class="fw-bold mb-1"><i class="fas fa-key mr-1"  style="color: #016DB7;"></i>認証コード入力</p>
      <small>新しいパスワードを設定してください。</small><br>
      <small>パスワードは6文字以上で設定してください。</small>
    </div>
    <form action="{{ route('mypage.password.confirm') }}" method="POST">
      @csrf
      <div class="form-group text-center mb-5">
        @if ($errors->any())
        <div class="mb-3">
          @foreach ($errors->all() as $error)
              <small style="color:tomato">{{ $error }}</small><br>
          @endforeach
        </div>
        @endif
        <label><i class="fas fa-lock mr-1"  style="color: #016DB7;"></i>新しいパスワード</label><br>
        <input type="password" class="form-control" id="password" name="password" /><br>
        <label><i class="fas fa-lock mr-1"  style="color: #016DB7;"></i>パスワード確認</label><br>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" /><br>
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