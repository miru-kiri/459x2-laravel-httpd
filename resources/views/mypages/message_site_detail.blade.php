@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ店舗とのメッセージページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ店舗とのメッセージページです！')
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
      <a href="{{ $url ? url($url) : route('mypage.message.site',['site_id' => $fetchMessages->site_id]) }}" class="btn back-btn">戻る</a>
      <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <p>{{ $fetchMessages->site_name }}</p>
                    <!-- <h3 class="box-title"><span id="recipient-name">{{ $fetchMessages->site_name }}</span></h3> -->
                </div>
                <div class="box-body">
                    <div class="direct-chat-messages" id="chat-messages" style="min-height:500px">
                        @if($fetchMessages->author_flag == 0)
                        <div class="direct-chat-msg">
                            <!-- メッセージ表示部分 -->
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left">ID: {{ $fetchMessages->user_id }} {{ $fetchMessages->user_name }}</span>
                                <br><span>[タイトル] {!! nl2br(e($fetchMessages->title)) !!}</span>
                                <span class="direct-chat-timestamp float-right">{{ $fetchMessages->created_at }}</span>
                            </div>
                            <img class="direct-chat-img" src="{{ $fetchMessages->user_avatar ? asset('/storage/'.$fetchMessages->user_avatar) : asset('/storage/user-no-image.jpg') }}"  alt="message user image">
                            <div class="direct-chat-text">
                              {!! nl2br(e($fetchMessages->content)) !!}
                            </div>
                        </div>
                        @endif
                        <!-- 管理者部分 -->
                        @if($fetchMessages->author_flag == 1)
                        <div class="direct-chat-msg right">
                            <!-- メッセージ表示部分 -->
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-timestamp float-left">{{ $fetchMessages->created_at }}</span>
                                <br><span>[タイトル] {!! nl2br(e($fetchMessages->title)) !!}</span>
                            </div>
                            
                            <img class="direct-chat-img" src="{{ asset('/storage/img_avatar.png') }}" alt="message user image">
                            <!-- <i class="fa fa-edit mr-1"></i><i class="fa fa-trash"></i> -->
                            <div class="direct-chat-text">
                              {!! nl2br(e($fetchMessages->content)) !!}
                            </div>
                        </div>
                        @endif

                        @php $lastFlg = $fetchMessages->author_flag; @endphp
                        @foreach($fetchMessageReplis as $fetchMessageRepli)
                            <!-- ユーザー部分 -->
                            @if($fetchMessageRepli->author_flag == 0)
                            <div class="direct-chat-msg">
                                <!-- メッセージ表示部分 -->
                                <div class="direct-chat-infos clearfix">
                                    @if($lastFlg != $fetchMessageRepli->author_flag)
                                    <span class="direct-chat-name float-left">{{ $fetchMessageRepli->user_name }}</span>
                                    @endif
                                    <span class="direct-chat-timestamp float-right">{{ $fetchMessageRepli->created_at }}</span>
                                </div>
                                @if($lastFlg != $fetchMessageRepli->author_flag)
                                <img class="direct-chat-img" src="{{ $fetchMessageRepli->user_avatar ? asset('/storage/'.$fetchMessageRepli->user_avatar) : asset('/storage/user-no-image.jpg') }}"  alt="message user image">
                                @endif
                                <div class="direct-chat-text">
                                  {!! nl2br(e($fetchMessageRepli->content)) !!}
                                </div>
                            </div>
                            @endif
                            <!-- 管理者部分 -->
                            @if($fetchMessageRepli->author_flag == 1)
                            <div class="direct-chat-msg right">
                                <!-- メッセージ表示部分 -->
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-timestamp float-left">{{ $fetchMessageRepli->created_at }}</span>
                                </div>
                                @if($lastFlg != $fetchMessageRepli->author_flag)
                                <img class="direct-chat-img" src="{{ asset('/storage/img_avatar.png') }}" alt="message user image">
                                @endif
                                <!-- <i class="fa fa-edit mr-1"></i><i class="fa fa-trash"></i> -->
                                <div class="direct-chat-text">
                                    {!! nl2br(e($fetchMessageRepli->title)) !!}
                                    {!! nl2br(e($fetchMessageRepli->content)) !!}
                                </div>
                            </div>
                            @endif
                            @php $lastFlg = $fetchMessageRepli->author_flag; @endphp
                        @endforeach
                    </div>
                </div>
                <div class="box-footer">
                  <form action="{{ route('mypage.message.site.replies') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <textarea name="content" class="form-control" id="content" rows="5" required></textarea>
                    </div>
                    <small class="error_msg" style="color: tomato"></small>
                    <input type="hidden" name="message_id" value="{{ $messageId }}">
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">送信</button>
                    </div>
                  </form>
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
    
      if(error) {
        toastr.error(error)
      }
      if(success) {
        toastr.success(success)
      }
  })
</script>
@endsection