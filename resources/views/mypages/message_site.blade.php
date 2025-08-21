@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ店舗とのメッセージページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ店舗とのメッセージページです！')
@section('keywords',  '四国,天国ネット,コスモ天国ネット,風俗,メンズエステ,キャバクラ,セクキャバ,飲食店,宴会コンパニオン')

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
  .back-btn {
    background-color: #f8f9fa;
    border-color: #ddd;
    color: #444;
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
      <p class="fw-bold mb-4 fs-5 text-center" style="color: #016DB7;">メッセージ</p>
      <div class="my-3">
        <div class="d-flex justify-content-between">
          <a href="{{ route('mypage.favorite',['type' => 1]) }}" class="btn back-btn">戻る</a>
          <button class="btn create-btn" style="background: #016DB7;color:white" data-toggle="modal" data-target="#modal-default">新規登録</button>
        </div>
      </div>
      <div class="modal fade" id="create-modal">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">新規投稿</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                  <span aria-hidden="true">×</span>
                </button>
              </div>
            <div class="modal-body">
            <form action="{{ route('mypage.message.site') }}" method="POST">
              @csrf
              <div class="form-group">
                <label>タイトル</label>
                <input class="form-control" type="text" name="title" required/>
              </div>
              <div class="form-group">
                <label>内容</label>
                <textarea class="form-control" name="content" rows="5" required></textarea>
              </div>
              <input type="hidden" id="site_id" name="site_id" value="{{ $siteId }}" />
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">戻る</button>
                <button class="btn btn-submit" style="color:white;background: #016DB7">投稿する</button>
            </div>
            </form>
          </div>
        </div>
        </div>
      <div class="row">
        @foreach($messageData as $message)
        <div class="col-12">
          <div class="card">
          <div class="card-body">
          <div class="d-flex justify-content-end">
            <small class="text-muted">{{ $message->created_at }}</small>
          </div>
          <p>{!! nl2br(e($message->title)) !!}</p>
          <small class="text-muted">{!! nl2br(e($message->content)) !!}</small>
          <div class="d-flex justify-content-end">
              <a href="{{ route('mypage.message.site.detail',['id' => $message->id]) }}" class="btn" style="color:white;background: #016DB7;">返信する</a>
          </div>
          </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection

@section('script')
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
$(document).ready(function() {
    const success = "{{ session('success') }}"
    const error = "{{ session('error') }}"
    
      if(error) {
        toastr.error(error)
      }
      if(success) {
        toastr.success(success)
      }
  })

$('.create-btn').on('click', function() {
  $('#create-modal').modal('show');
})
</script>
@endsection