@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ女の子とのメッセージページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ女の子とのメッセージページです！')
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
      <a href="{{ $url ? url($url) : route('mypage.favorite') }}" class="btn back-btn">戻る</a>
      <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <p class="box-title fs-bold">{{ $castData->source_name }}さんとのメッセージ<span id="recipient-name"></span></p>
                </div>
                <div class="box-body">
                    <div class="direct-chat-messages" id="chat-messages" style="min-height:400px">
                        @php $lastFlg = -1; @endphp
                        @foreach($fetchMessageReplis as $fetchMessage)
                            <!-- ユーザー部分 -->
                            @if($fetchMessage->author_flag == 0)
                            <div class="direct-chat-msg">
                                <!-- メッセージ表示部分 -->
                                <div class="direct-chat-infos clearfix">
                                    @if($lastFlg != $fetchMessage->author_flag)
                                    <span class="direct-chat-name float-left">{{ $fetchMessage->user_name }}</span>
                                    @endif
                                    <span class="direct-chat-timestamp float-right">{{ $fetchMessage->created_at }}</span>
                                </div>
                                @if($lastFlg != $fetchMessage->author_flag)
                                <img class="direct-chat-img" src="{{ $fetchMessage->user_avatar ? asset('/storage/'.$fetchMessage->user_avatar) : asset('/storage/user-no-image.jpg') }}"  alt="message user image">
                                @endif
                                <div class="direct-chat-text">{!! nl2br(e($fetchMessage->content)) !!}</div>
                            </div>
                            @endif
                            <!-- キャスト部分 -->
                            @if($fetchMessage->author_flag == 1)
                            @if($fetchMessage->status == 'reject')
                              @continue;
                            @endif
                            <div class="direct-chat-msg right">
                                <!-- メッセージ表示部分 -->
                                <div class="direct-chat-infos clearfix">
                                    @if($lastFlg != $fetchMessage->author_flag)
                                    <span class="direct-chat-name float-right">{{ $fetchMessage->source_name }}</span>
                                    @endif
                                    <span class="direct-chat-timestamp float-left">{{ $fetchMessage->created_at }}</span>
                                </div>
                                @if($lastFlg != $fetchMessage->author_flag)
                                <img class="direct-chat-img" src="{{ $fetchMessage->cast_avatar ? asset('/storage/'.$fetchMessage->cast_avatar) : asset('/storage/default_cast.png') }}" alt="message user image">
                                @endif
                                <!-- <i class="fa fa-edit mr-1"></i><i class="fa fa-trash"></i> -->
                                <div class="direct-chat-text">{!! nl2br(e($fetchMessage->content)) !!}</div>   
                            </div>
                            @endif
                            @php $lastFlg = $fetchMessage->author_flag; @endphp
                        @endforeach
                    </div>
                </div>
                <div class="box-footer">
                    <form action="{{ route('mypage.message.cast') }}" method="POST">
                      @csrf
                      <div class="input-group">
                          <textarea name="content" class="form-control" id="content" rows="5" required></textarea>
                      </div>
                      <small class="error_msg" style="color: tomato"></small>
                      <input type="hidden" name="message_id" value="{{ $messageId }}">
                      <input type="hidden" name="cast_id" value="{{ $castId }}">
                      <div class="mt-3 d-flex justify-content-end">
                          <button type="submit" class="btn btn-primary">送信</button>
                      </div>
                    </form>
                </div>
            </div>
            </div>
            </div>
            </div>
    </div>
  </div>
</section>
@endsection

@section('script')
<script>
$(document).ready(function() {
    const success = "{{ session('success') }}"
    const error = "{{ session('error') }}"
    const warning = "{{ session('warning') }}"
    
      if(error) {
        toastr.error(error)
      }
      if(success) {
        toastr.success(success)
      }
      if(warning) {
        toastr.warning(warning)
      }
  })
</script>
@endsection