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
      <h2 class="fw-bold mb-4 fs-5" style="color: #016DB7;">退会最終確認</h2>
    </div>
    <!-- <form action="{{ route('mypage.phone.confirm') }}" method="POST">
      @csrf -->
      <div class="form-group text-center mb-5">
        <p class="fw-bold h5" style="color: #cc0000">退会処理を行いアカウント情報をすべて削除します。</p>
        <p class="fw-bold h5" style="color: #cc0000">削除された情報は復旧できませんがよろしいですか？</p>
        <p class="fw-bold h5" style="color: #cc0000">この操作は取り消せません。</p>
        <div class="text-center pt-4">
          <a href="{{ route('mypage.withdrawal') }}" class="btn btn-block" style="color: white;background: #016DB7;">戻る</a>
          <button class="btn withdrawal-btn btn-block" style="color: white;background: #cc0000;">退会する</button>
        </div>
      </div>
    <!-- </form> -->
  </div>
</section>
@endsection

@section('script')
<script>
  $('.withdrawal-btn').on('click', function() {
    $("#overlay").fadeIn(300);
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        contentType: "application/json",
        url: "{{ route('mypage.withdrawal') }}",
      }).done(function(data, status, jqXHR) {
        if(data.result == 0) {
          // 成功時リロード
          location.href = "{{ route('mypage.withdrawal.end') }}"
        } else {
          toastr.error(data.message)
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
        toastr.error('処理に失敗しました。')
        console.log(jqXHR)
      }).always(function (data) {
        // 常に実行する処理
        $("#overlay").fadeOut(300);
      });        
  });
</script>
@endsection