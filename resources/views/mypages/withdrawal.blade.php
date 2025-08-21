@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ退会手続きページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ退会手続きページです！')
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
  <div class="text-white p-3 text-center fw-bold" style="background: #016DB7;"><h1 style="font-size:1rem;">マイページ</h1></div>
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
    <div class="text-center my-3">
      <h2 class="fw-bold mb-4 fs-5" style="color: #016DB7;">退会手続き</h2>
    </div>
    <!-- <form action="{{ route('mypage.phone.confirm') }}" method="POST">
      @csrf -->
      <div class="form-group text-center mb-5">
        <p class="fw-bold">退会すると当サービスが一切利用できなくなります。</p>
        <p class="fw-bold">ポイント、お気に入り、各種設定などのすべてのデータが削除されます。</p>
        <p class="fw-bold h5" style="color: #cc0000">一度削除されたデータは復元できません。</p>
        <p class="fw-bold">それでもよろしければ下記より退会を行なってください。</p>
        <div class="text-center pt-4">
          <a href="{{ route('mypage.withdrawal.confirm') }}" class="btn withdrawal-btn btn-block" style="color: white;background: #cc0000;">退会する</a>
        </div>
      </div>
    <!-- </form> -->
  </div>
</section>
@endsection

@section('script')
<script>
  // $('.withdrawal-btn').on('click', function() {
    
  // });
</script>
@endsection