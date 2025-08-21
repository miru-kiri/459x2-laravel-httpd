@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ口コミページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ口コミページです！')
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
    <div class="my-3">
      <p class="fw-bold mb-4 fs-5 text-center" style="color: #016DB7;">口コミ投稿</p>
      <div class="row">
      @if($reviewData->isNotEmpty())
        @foreach($reviewData as $review)
          <div class="d-flex justify-content-between align-items-start mb-3">
            <p class="fw-bold mb-1">{{ $review->site_name }}</p>
            <div class="text-end">
              <div class="text-muted" style="font-size:12px;">投稿日時: {{ $review->created_at ? $review->created_at : $review->updated_at }}</div>
              <div class="text-muted" style="font-size:12px;">訪問日: {{ $review->time_play }}</div>
            </div>
          </div>
          <div class="col-12 col-md-4 my-3 text-center ">
            <img src="{{ asset('storage/' . $review->image) }}" style="height: 200px;object-fit: contain; max-width: 100%;"/><br>
            <span>{{ $review->source_name }}
              @if($review->age)
                ({{ $review->age }})
              @endif
            </span><br>
            @if($review->bust || $review->cup || $review->waist ||$review->hip)
            <span>
              @if($review->bust || $review->cup)
                @if($review->bust)
                  B{{ $review->bust }}
                @endif
                @if($review->cup)
                  ({{$review->cup}})
                @endif
                /
              @endif
              @if($review->waist)
                W{{ $review->waist }}
              @endif
              @if($review->hip)
                /H{{ $review->hip }}
              @endif
            </span>
            @endif
          </div>
          <div class="col-12 col-md-8 my-3">
            <div class=" mb-3">
              <div class="star-rating" id="star-rating">
                  @isset($averageReviews[$review->id])
                  @php $isPersentage = false; @endphp
                  @for($i = 1; $i<=5; $i++)
                    @if($i > $averageReviews[$review->id])
                      @if($isPersentage)
                        @php  $percentage = 0; @endphp
                      @else
                          @php
                            $isPersentage = true;
                            $integerPart = floor($averageReviews[$review->id]);
                            $decimalPart = $averageReviews[$review->id] - $integerPart;
                            $percentage = $decimalPart * 100;
                          @endphp
                      @endif
                        <span class="star width-{{ $percentage }}">★</span>
                    @else
                      <span class="star">★</span>
                    @endif
                  @endfor
                  <span class="pl-1 star-rating-text">{{ $averageReviews[$review->id] }}</span>
                  @endisset
              </div>
              @foreach($formatReviewCriterialData[$review->id] as $reviewCriterialData)
                <span class="fw-bold" style="font-size:14px;">{{ $reviewCriterialData['name'] }}: {{ $reviewCriterialData['evaluate'] }}</span><br>
              @endforeach
            </div>
            <p class="mb-0 fw-bold">{{ $review->title }}</p>
            <p class="mt-1" style="font-size: 14px">{{ $review->content }}</p>
            @if($review->admin_feedback)
            <p class="mb-0 fw-bold">お店からの返事コメント</p>
              <div class="mb-0 d-flex justify-content-end">
                <p class="text-muted mb-1" style="font-size:12px;">掲載日: {{ date('Y年m月d日',strtotime($review->admin_feedback_time)) }}</p>
              </div>
            <p style="font-size:14px;">{{ $review->admin_feedback }}</p>
            @endif
          </div>
          <hr>
        @endforeach
      @else
        <p class="text-center">現在、登録されている口コミはありません。口コミの投稿は各お店の詳細ページまたは女の子の詳細ページからできます。お遊びされた方は、是非ともその体験をご紹介ください。</p>
      @endif
      </div>
    </div>
  </div>
</section>
@endsection

@section('script')
@endsection