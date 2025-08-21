@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページTOP')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページTOP画面です！')
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
    <p class="text-center">いらっしゃいませ</p>
    <p class="text-center">{{$data->name}}様</p>
  </div>
</section>
<section>
  <div class="container mt-3">
    <div class="row">
      <div class="col-12 col-md-6">
        <div class="card text-center card_content">
          <div class="card-header point_card">
            ポイント履歴
          </div>
          <div class="card-body">
            <div style="height: 237px; overflow: hidden;">
              <img src="{{ asset('img/point.png') }}" height="150px;"/>
              <p href="{{ route('mypage.point') }}" class="fs-5 fw-bold mt-3">{{ $totalPoint }}PT</p>
            </div>
            <a href="{{ route('mypage.point') }}" class="btn point_btn rounded-0"><small>もっと見る</small></a>
          </div>    
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card text-center card_content">
          <div class="card-header favorite_shop_card">
            お気に入りの店舗
          </div>    
          <div class="card-body">
          <div style="height: 237px; overflow: hidden;">
            <div class="row">
          @if($favoriteShop->isNotEmpty())
            @foreach($favoriteShop as $shop)
              <div class="col-12">
                <p class="fw-bold">{{ $shop->site_name }}</p>
              </div>
            @endforeach
          @else
            <p class="text-center">お気に入りの店舗はありません</p>
          @endif
            </div>
            </div>
            <a href="{{ route('mypage.favorite',['type' => 1]) }}" class="btn favorite_shop_btn rounded-0"><small>もっと見る</small></a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card text-center card_content">
          <div class="card-header favorite_card">
            お気に入りの女の子
          </div>    
          <div class="card-body">
          <div style="height: 237px">
            <div class="row">
          @if($favoriteCast->isNotEmpty())
            @foreach($favoriteCast as $cast)
              <div class="col-12">
              <!-- 'genre_id' => $genreId,'area_id' => $areaId, -->
              <img src="{{ asset('storage'. $cast->image) }}" class="favorite_img"/>
                <a href="{{ route('site.detail.cast.detail',['site_id' => $cast->site_id,'cast_id' => $cast->cast_id]) }}">
                  <p class="favorite_text text-center mt-1">{{ $cast->source_name }}
                    @if($cast->age)
                    ({{ $cast->age }})
                    @endif
                  </p>
                </a>
              </div>
            @endforeach
          @else
            <p class="text-center">お気に入り登録はありません</p>
          @endif
            </div>
            </div>
            <a href="{{ route('mypage.favorite') }}" class="btn favorite_btn rounded-0"><small>もっと見る</small></a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="card card_content">
          <div class="card-header history_card  text-center">
              利用履歴
          </div>    
          <div class="card-body">
          <div style="height: 237px; overflow: hidden;">
          @if($reserveData->isNotEmpty())
            @foreach($reserveData as $reserve)
              <p class="mb-2 text-muted">{{ date('Y年m月d日',strtotime($reserve->start_time)) }}</p>
              <p class="list_title fw-bold">{{ $reserve->site_name }}</p>
            @endforeach
          @else
            <p class="text-center">利用履歴はありません</p>
          @endif
          </div>
            <div class="text-center">
              <a href="{{ route('mypage.history') }}" class="btn history_btn rounded-0"><small>もっと見る</small></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section>
  <div class="container">
    <div class="my-3">
      <p class="fw-bold fs-5 mb-0" style="color: #016DB7; border-bottom: 2px solid #016DB7;">ご予約状況</p>
      <div class="row">
        @if($reserveNowData->isNotEmpty())
          @foreach($reserveNowData as $reserveNow)
          <div class="col-12 col-md-6 my-3">
            <p class="fw-bold mb-1">{{ $reserveNow->site_name }}</p>
            <span>{{ $reserveNow->format_date }}~</span><br>
            <span>{{ $reserveNow->course_name }}</span>
          </div>
          <div class="col-12 col-md-6 my-3">
            <div class="row">
              <div class="col-6">
                <img src="{{ asset('storage/' . $reserveNow->image) }}" style="height: 200px"/>
              </div>
              <div class="col-6">
                @if(!empty($reserveNow->cast_id))
                <span>{{ $reserveNow->source_name }}
                  @if($reserveNow->age)
                    ({{ $reserveNow->age }})
                  @endif
                </span><br>
                @if($reserveNow->height || $reserveNow->bust || $reserveNow->cup || $reserveNow->waist ||$reserveNow->hip)
                  <span>
                    @if($reserveNow->height)
                      {{ $reserveNow->height }}cm
                    @endif
                    @if($reserveNow->bust || $reserveNow->cup)
                      @if($reserveNow->bust)
                        B{{ $reserveNow->bust }}
                      @endif
                      @if($reserveNow->cup)
                        ({{$reserveNow->cup}})
                      @endif
                      /
                    @endif
                    @if($reserveNow->waist)
                      W{{ $reserveNow->waist }}
                    @endif
                    @if($reserveNow->hip)
                      /H{{ $reserveNow->hip }}
                    @endif
                  </span>
                @endif
                @else
                <span>フリー予約</span>
                @endif
              </div>
            </div>
          </div>
          <!-- <hr> -->
          @endforeach
        @else
          <p class="text-center">予約中のデータはありません</p>
        @endif
      </div>
    </div>
  </div>
</section>

@endsection

@section('script')
<script>
  $(document).ready(function() {
    const status = "{{ session('status') }}"
    const error = "{{ session('error') }}"
    if(status) {
      if(error) {
        toastr.error(status)
      } else {
        toastr.success(status)
      }
    }
  })
</script>
@endsection