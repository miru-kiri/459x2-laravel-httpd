@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページweb予約ページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページweb予約ページです！')
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
  .btn-possible {
    background: #28b463;
    color: white;
  }
  .btn-possible:hover {
    background: #1f8a4c;
    color: white;
  }
  .btn-week {
    background: #016DB7;
    color: white;
  }
  .btn-week:hover {
    background: #013e66;
    color: white;
  }
  .course_active {
    background: #68a9cf;
    /* color: white; */
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
      <p class="fw-bold mb-4 fs-5 text-center" style="color: #016DB7;">web予約</p>
      <p class="fw-bold mb-4 text-center" style="color: #016DB7;">①コース選択</p>
      <!-- キャスト画像等の情報 -->
      @if($castData)
      <div class="card mb-3">
        <div class="row g-0">
          <div class="col-4">
            <img src="{{ asset('storage/'.$castData->image) }}" height="150px">
          </div>
          <div class="col-8">
            <div class="card-body">
              <small class="card-text">{{ $castData->site_name }}</small><br>
              <small>{{ $castData->source_name }}</small>
              @if($castData->bust || $castData->cup || $castData->waist ||$castData->hip)
                <p class="card-text">
                  <small class="text-muted">
                    @if($castData->bust || $castData->cup)
                      @if($castData->bust)
                        B{{ $castData->bust }}
                      @endif
                      @if($castData->cup)
                        ({{$castData->cup}})
                      @endif
                      /
                    @endif
                    @if($castData->waist)
                      W{{ $castData->waist }}
                    @endif
                    @if($castData->hip)
                      /H{{ $castData->hip }}
                    @endif
                  </small>
                </p>
                @endif
            </div>
          </div>
        </div>
      </div>      
      @endif
    </div>
      @if($courseData->isNotEmpty())
        <div class="row">
        <p style="border-bottom: 1px solid #d3d3d3;">コースを選択してください</p>
        <small class="error_message" id="course_error"></small>
        @foreach($courseData as $course)
        <div class="col-12 col-md-6">
          <div class="info-box course-{{ $course->id }}">
            <div class="info-box-content">
                  <span class="info-box-text">{{ $course->name }}</span>
                  <span class="info-box-number">{{ number_format($course->fee) }}円/{{ $course->time }}分</span>
            </div>
            <div class="d-flex align-items-end">
              <!-- <a href="{{ route('mypage.reserve.calender',['cast_id' => $castId,'course_id' => $course->id]) }}" class="btn" style="background: #016DB7;color: white">選択する</a> -->
              <button class="btn course_btn course-btn-{{ $course->id }}" style="background: #016DB7;color: white" data-course="{{ $course->id }}">選択する</button>
            </div>
          </div>
        </div>
        @endforeach
        </div>
        @if($indicateData)
        <p style="border-bottom: 1px solid #d3d3d3;">指名形態を選択してください</p>
        <small class="error_message" id="indicate_error"></small>
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="info-box indication_fee">
              <div class="info-box-content">
                    <span class="info-box-text">指名料</span>
                    <span class="info-box-number">{{ number_format($indicateData->fee) }}円</span>
              </div>
              <div class="d-flex align-items-end">
                <button class="btn indicate_btn" style="background: #016DB7;color: white">選択する</button>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="info-box nomination_fee">
              <div class="info-box-content">
                    <span class="info-box-text">本指名料(2回目)</span>
                    <span class="info-box-number">{{ number_format($indicateData->nomination_fee) }}円</span>
              </div>
              <div class="d-flex align-items-end">
                <button class="btn nomination_btn" style="background: #016DB7;color: white">選択する</button>
              </div>
            </div>
          </div>
        </div>
        @endif
        <input type="hidden" name="fee_type" class="fee_type" value="-1" />
        <div class="text-center my-3">
          <button class="btn next_btn" style="background: #016DB7;color: white">次へ</button>
        </div>
      @else
        <p class="my-3">web予約可能なコースはありません。店舗へ直接ご連絡ください。</p>
      @endif
  </div>
</section>
@endsection

@section('script')
<script>
  let activeCourse = 0
  let activeFeeType = 0
  const isFree = "{{ $isFree }}";
  const indicateData = @json($indicateData);
  // フィルターフォームの送信時にテーブルを再描画
  $('.next_btn').on('click', function(e) {
        $('#course_error').text('')
        if(activeCourse == 0) {
          $('#course_error').text('コースを選択してください。')
          return;
        }
        const type = $('.fee_type').val()
        $('#indicate_error').text('')
        if(indicateData.id > 0){
          if(type == '-1') {
            $('#indicate_error').text('指名形態を選択してください。')
            return;
          }
        }
        $("#overlay").fadeIn(300);
        let redirectUrl = "{!! route('mypage.reserve.calender', ['cast_id' => $castId,'course_id' => '__course_id__','fee_type' => '__type__']) !!}".replace('__course_id__', activeCourse).replace('__type__', type);
        if(isFree) {
          redirectUrl = "{!! route('mypage.reserve.calender', ['cast_id' => $castId,'site_id' => $siteId,'course_id' => '__course_id__','fee_type' => '__type__']) !!}".replace('__course_id__', activeCourse).replace('__type__', type);
        }
        location.href = redirectUrl;
        $("#overlay").fadeOut(300);
  });
  //コース
  $('.course_btn').on('click', function(e) {
      const courseId = $(this).data('course');
      //2回目
      if(activeCourse == courseId) {
        $(this).text('選択する');
        activeCourse = 0;
        $(`.course-${courseId}`).removeClass('course_active');
        return;
      }
      if(activeCourse != 0) {
        $(`.course-btn-${activeCourse}`).text('選択する');
        $(`.course-${activeCourse}`).removeClass('course_active');
      }
      $(`.course-${courseId}`).addClass('course_active');
      $(this).text('選択中');
      activeCourse = courseId;
  });
  //指名料
  $('.indicate_btn').on('click', function(e) {
      const type = $('.fee_type').val();

      if(activeFeeType == 2) {
        $(`.nomination_fee`).removeClass('course_active');
        $(`.nomination_btn`).text('選択する');
      }
      if(type == 1) {
        $(this).text('選択する');
        $(`.indication_fee`).removeClass('course_active');
        $('.fee_type').val(-1);
        return;
      } else {
          $(this).text('選択中');
          $(`.indication_fee`).addClass('course_active');
          $('.fee_type').val(1);
      }
      activeFeeType = $('.fee_type').val();
  });
  //本指名
  $('.nomination_btn').on('click', function(e) {
      const type = $('.fee_type').val();

      if(activeFeeType == 1) {
        $(`.indication_fee`).removeClass('course_active');
        $(`.indicate_btn`).text('選択する');
      }
      if(type == 2) {
        $(this).text('選択する');
        $(`.nomination_fee`).removeClass('course_active');
        $('.fee_type').val(-1);
        return;
      } else {
          $(this).text('選択中');
          $(`.nomination_fee`).addClass('course_active');
          $('.fee_type').val(2);
      }
      activeFeeType = $('.fee_type').val();
  });
</script>
@endsection