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
  <div class="card">
    <div class="card-header p-2">
      <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link {{ $type != 1 ? 'active' : ''  }}" href="#cast" data-toggle="tab">女の子</a></li>
        <li class="nav-item"><a class="nav-link {{ $type == 1 ? 'active' : ''  }}" href="#shop" data-toggle="tab">店舗</a></li>
      </ul>
    </div>
    <!-- <p class="text-center fw-bold mb-4" style="color: #016DB7;">お気に入りの女の子</p> -->
    <div class="card-body">
      <div class="tab-content">
        <div class="{{ $type != 1 ? 'active' : ''  }} tab-pane" id="cast">
          <div class="row">
          @if($castMessageDatas->isNotEmpty())
          @foreach($castMessageDatas as $message)
            <div class="col-12">
              <div class="card">
              <div class="card-body">
              <div class="d-flex justify-content-end">
                <small class="text-muted">{{ $message->created_at }}</small>
              </div>
              <p>{!! nl2br(e($message->source_name)) !!}</p>
              <small class="text-muted">{!! nl2br(e($message->content)) !!}</small>
              <div class="d-flex justify-content-end">
                  <div style="position: relative; display: inline-block;">
                  @if(isset($formatNewData[$message->id]))
                    <span class="badge badge-primary badge-pill" style="position: absolute; top: -14px; left: -5px;"><small>new</small></span>
                  @endif
                    <a href="{{ route('mypage.message.cast',['id' => $message->id,'cast_id' => $message->cast_id,'url' => 'sites/mypage/message?type=0']) }}" class="btn btn-outline-primary rounded-pill">返信する</a>
                </div>
              </div>
              </div>
              </div>
            </div>
          @endforeach
          @else
            <p>現在メッセージはありません。</p>
          @endif
          </div>
        </div>
        <div class="{{ $type == 1 ? 'active' : ''  }} tab-pane" id="shop">
          <button class="btn create-btn mb-3" style="background: #016DB7;color:white" data-toggle="modal" data-target="#modal-default">メッセージの作成</button>
          <div class="row">
          @if($siteMessageDatas->isNotEmpty())
          @foreach($siteMessageDatas as $message)
            <div class="col-12">
              <div class="card">
              <div class="card-body">
              <div class="d-flex justify-content-end">
                <small class="text-muted">{{ $message->created_at }}</small>
              </div>
              <p>{{ $message->site_name }}</p>
              <p>{!! nl2br(e($message->title)) !!}</p>
              <small class="text-muted">{!! nl2br(e($message->content)) !!}</small>
              <div class="d-flex justify-content-end">
                  <div style="position: relative; display: inline-block;">
                    @isset($formatNewShopData[$message->id])
                    <span class="badge badge-primary badge-pill" style="position: absolute; top: -14px; left: -5px;"><small>new</small></span>
                    @endisset
                    <a href="{{ route('mypage.message.site.detail',['id' => $message->id,'url' => 'sites/mypage/message?type=1']) }}" class="btn btn-outline-primary rounded-pill">返信する</a>
                  </div>
              </div>
              </div>
              </div>
            </div>
          @endforeach
          @else
            <p>現在メッセージはありません。</p>
          @endif
          </div>
          <div class="modal fade" id="create-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">メッセージの作成</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                <div class="modal-body">
                <form action="{{ route('mypage.message.site') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label>サイト</label>
                    <select class="form-control" name="site_id">
                      @foreach($siteDatas as $siteData)
                      <option value="{{ $siteData->id }}">{{ $siteData->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>タイトル</label>
                    <input class="form-control" type="text" name="title" required/>
                  </div>
                  <div class="form-group">
                    <label>内容</label>
                    <textarea class="form-control" name="content" rows="5" required></textarea>
                  </div>
                  <!-- <input type="hidden" id="site_id" name="site_id" value="{{ $siteId }}" /> -->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">戻る</button>
                    <button class="btn btn-submit" style="color:white;background: #016DB7">投稿する</button>
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
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
  const success = "{{ session('success') }}"
  const warning = "{{ session('warning') }}"
  const error = "{{ session('error') }}"
  $(document).ready(function() {
    if(success) {
        toastr.success(success)
    }
    if(warning) {
        toastr.warning(warning)
    }
    if(error) {
        toastr.error(error)
    }
  })
  $('.create-btn').on('click', function() {
    $('#create-modal').modal('show');
  })
</script>
@endsection