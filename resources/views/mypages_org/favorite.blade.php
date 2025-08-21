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
        <li class="nav-item"><a class="nav-link {{ $type != 1 ? 'active' : ''  }}" href="#cast" data-toggle="tab">お気に入りの女の子</a></li>
        <li class="nav-item"><a class="nav-link {{ $type == 1 ? 'active' : ''  }}" href="#shop" data-toggle="tab">お気に入りの店舗</a></li>
      </ul>
    </div>
    <!-- <p class="text-center fw-bold mb-4" style="color: #016DB7;">お気に入りの女の子</p> -->
    <div class="card-body">
      <div class="tab-content">
        <div class="{{ $type != 1 ? 'active' : ''  }} tab-pane" id="cast">
          <div class="row">
            @if($favoriteCast->isNotEmpty())
              @foreach($favoriteCast as $cast)
                <div class="col-6 col-md-3 text-center my-3">
                  <img src="{{ asset('storage'. $cast->image) }}" class="favorite_img"/>
                  <p class="my-1" style="height: 2.5rem">{{ $cast->source_name }}
                    @if($cast->age)
                    ({{ $cast->age }})
                    @endif
                  </p>
                  @if($cast->height || $cast->bust || $cast->cup || $cast->waist ||$cast->hip)
                  <small>
                    @if($cast->height)
                      {{ $cast->height }}cm
                    @endif
                    @if($cast->bust || $cast->cup)
                      @if($cast->bust)
                        B{{ $cast->bust }}
                      @endif
                      @if($cast->cup)
                        ({{$cast->cup}})
                      @endif
                      /
                    @endif
                    @if($cast->waist)
                      W{{ $cast->waist }}
                    @endif
                    @if($cast->hip)
                      /H{{ $cast->hip }}
                    @endif
                  </small><br>
                  @endif
                  @if($cast->genre_category)
                    <p class="mb-0" style="color: white;background: {{ $cast->genre_category['color'] }}">{{ $cast->genre_category['text'] }}</p>
                  @endif
                  <div style="height: 3rem">
                    <span class="fuzoku_text">{{ $cast->site_name }}</span><br>
                  </div>
                  @if($cast->approval_status != 3)
                    <div style="position: relative; display: inline-block;">
                        @if(isset($formatNewData[$cast->cast_id]))
                          <span class="badge badge-primary badge-pill" style="position: absolute; top: -14px; left: -5px;"><small>new</small></span>
                        @endif
                        <a href="{{ route('mypage.message.cast',['cast_id' => $cast->cast_id]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <small>メッセージ</small>
                        </a>
                    </div>
                    <!-- <a href="{{ route('mypage.message.cast',['cast_id' => $cast->cast_id]) }}" class="btn btn-sm btn-outline-primary rounded-pill"><small>メッセージ</small></a> -->
                  @endif
                  <button class="btn btn-sm btn-outline-secondary delete_btn rounded-pill" data-id="{{ $cast->id }}"><small>削除</small></button>
                </div>
              @endforeach
            @else
              <p class="text-center">本日のお気に入り登録はありません</p>
            @endif
          </div>
        </div>
        <div class="{{ $type == 1 ? 'active' : ''  }} tab-pane" id="shop">
          @if($favoriteShop->isNotEmpty())
          <div class="row">
            @foreach($favoriteShop as $shop)
              <div class="col-6 col-md-3 text-center my-3">
                @isset($shopImages[$shop->site_id]->image)
                  <img src="{{ asset('storage'. $shopImages[$shop->site_id]->image) }}" class="favorite_img"/>
                @else
                  <img src="{{ asset('storage/no-image.jpg') }}" class="favorite_img"/>
                @endisset
                <div class="my-3">
                  <a href="{{ route('site.detail.top',['site_id' => $shop->site_id]) }}">{{ $shop->site_name }}</a><br>
                </div>
                <div style="position: relative; display: inline-block;">
                @isset($formatNewShopData[$shop->site_id])
                <span class="badge badge-primary badge-pill" style="position: absolute; top: -14px; left: -5px;"><small>new</small></span>
                @endisset
                <a href="{{ route('mypage.message.site',['site_id' => $shop->site_id]) }}" class="btn btn-sm btn-outline-primary rounded-pill"><small>メッセージ</small></a>
                </div>
                <button class="btn btn-sm btn-outline-secondary delete_btn_shop rounded-pill" data-id="{{ $shop->id }}"><small>削除</small></button>
              </div>
            @endforeach
          </div>
          @else
            <p class="text-center">お気に入りの店舗はありません</p>
          @endif
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
  const warning = "{{ session('warning') }}"
  const error = "{{ session('error') }}"
  $(document).ready(function() {
    if(warning) {
        toastr.warning(warning)
    }
    if(error) {
        toastr.error(error)
    }
  })
  $('.delete_btn').on('click', function() {
    // 削除時のイベントを発火
    if(!confirm('選択した女の子をお気に入りから削除しますか？')) {
      return;
    }
    const id = $(this).data('id')
    const url = "{{ route('mypage.favorite.delete', ['id' => '__id__']) }}".replace('__id__', id); // id パラメータを正しく渡す
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        url: url,
    }).done(function(data, status, jqXHR) {
      if(data.result == 0) {
        // 成功時リロード
        toastr.success(data.message)
        location.href = "{{ route('mypage.favorite') }}"
      } else {
        toastr.error(data.message)
        // $(".image-btn-submit").prop('disabled', false);
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {
      toastr.error('処理に失敗しました。')
      // $(".image-btn-submit").prop('disabled', false);
    });
  })
  $('.delete_btn_shop').on('click', function() {
    // 削除時のイベントを発火
    if(!confirm('選択した店舗をお気に入りから削除しますか？')) {
      return;
    }
    const id = $(this).data('id')
    const url = "{{ route('mypage.favorite.shop.delete', ['id' => '__id__']) }}".replace('__id__', id); // id パラメータを正しく渡す
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        url: url,
    }).done(function(data, status, jqXHR) {
      if(data.result == 0) {
        // 成功時リロード
        toastr.success(data.message)
        location.href = "{{ route('mypage.favorite') }}"
      } else {
        toastr.error(data.message)
        // $(".image-btn-submit").prop('disabled', false);
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {
      toastr.error('処理に失敗しました。')
      // $(".image-btn-submit").prop('disabled', false);
    });
  })
</script>
@endsection