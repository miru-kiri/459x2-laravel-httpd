@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ利用履歴ページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ利用履歴ページです！')
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
    <a href="{{ $tab['url'] }}" class="btn rounded-0 {{ $loop->last ? 'mypage_menu_bar_end' : 'mypage_menu_bar' }} {{ $tab['is_active'] ? 'tab_acive' : '' }} ">{{ $tab['name'] }}</a>
    @endforeach
  </div>
</section>
<section>
  <div class="container mt-3">
    <p class="text-center fw-bold mb-4" style="color: #016DB7;">利用履歴</p>
    <div class="row">
      @if($historyData->isNotEmpty())
        @foreach($historyData as $history)
          <div class="col-12 col-md-6 my-3">
            <p class="fw-bold mb-1">{{ $history->site_name }}</p>
            <span>{{ $history->format_date }}~</span><br>
            <span>{{ $history->course_name }}</span>
          </div>
          <div class="col-12 col-md-6 my-3">
            <div class="row">
              <div class="col-6">
                <img src="{{ asset('storage/' . $history->image) }}" style="height: 200px; object-fit: contain; max-width: 100%;"/>
              </div>
              <div class="col-6">
                @if(!empty($history->cast_id))
                <p>{{ $history->source_name }}
                  @if($history->age)
                  ({{ $history->age }})
                  @endif
                </p>
                @if($history->height || $history->bust || $history->cup || $history->waist ||$history->hip)
                <p>
                    @if($history->height)
                      {{ $history->height }}cm
                    @endif
                    @if($history->bust || $history->cup)
                      @if($history->bust)
                        B{{ $history->bust }}
                      @endif
                      @if($history->cup)
                        ({{$history->cup}})
                      @endif
                      /
                    @endif
                    @if($history->waist)
                      W{{ $history->waist }}
                    @endif
                    @if($history->hip)
                      /H{{ $history->hip }}
                    @endif
                </p>
                @endif
                @else
                <p>フリー予約</p><br>
                @endif
                
                @if($history->is_review  == 0)
                  <button type="button" style="color:white;background: #016DB7" class="btn btn-default review_btn" data-toggle="modal" data-target="#modal-default" data-id="{{ $history->id }}" data-site_id="{{ $history->site_id_reserve }}" data-cast_id="{{ $history->cast_id }}" data-time_play="{{ date('Y-m-d',strtotime($history->start_time)) }}">
                  口コミを投稿する
                  </button>

                  <div class="modal fade" id="review-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">口コミ投稿</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                        <div class="modal-body">
                        <form action="{{ route('mypage.review') }}" method="POST">
                          @csrf
                          <div class="form-group">
                            <label>タイトル</label>
                            <input class="form-control" type="text" name="title" required/>
                          </div>
                          <div class="form-group">
                            <label>内容</label>
                            <textarea class="form-control" name="content" rows="5" required></textarea>
                          </div>
                          @foreach($reviewCriterials as $key => $name)
                          <div class="form-group">
                            <label>{{ $name }}</label>
                            <select class="form-control" name="criterial-{{$key}}">
                            @for($i=5; $i>=1; $i -= 0.5)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                            </select>
                          </div>
                          @endforeach
                          <input type="hidden" id="id" name="id" value="0" />
                          <input type="hidden" id="site_id" name="site_id" value="0" />
                          <input type="hidden" id="cast_id" name="cast_id" value="0" />
                          <input type="hidden" id="time_play" name="time_play" value="0" />
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">戻る</button>
                            <button class="btn btn-submit" style="color:white;background: #016DB7">投稿する</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
          <!-- <hr> -->
        @endforeach
      @else
        <p class="text-center">利用履歴はありません</p>
      @endif
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
$('.review_btn').on('click', function() {
  let id = $(this).data('id');
  $('#id').val(id);
  let castId = $(this).data('cast_id');
  $('#cast_id').val(castId);
  let siteId = $(this).data('site_id');
  $('#site_id').val(siteId);
  let timePlay = $(this).data('time_play');
  $('#time_play').val(timePlay);
  $('#review-modal').modal('show');
})
</script>
@endsection