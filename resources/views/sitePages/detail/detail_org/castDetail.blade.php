@extends('layouts.site')

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

<link rel="stylesheet" href="{{ asset('vendor/ekko-lightbox/ekko-lightbox.css') }}">
<link rel="stylesheet" href="{{ asset('css/site/top.css') }}">
<style>
.nav-pills .nav-link {
  border: 1px solid <?php echo $mainColor; ?> !important;
  color: <?php echo $mainColor; ?> !important;
}
.nav-pills .nav-link.active {
  color: #fff !important;
  background: <?php echo $mainColor; ?> !important;
}
.cast-schedule-btn {
  color: <?php echo $mainColor; ?> !important;
  border: 2px solid <?php echo $mainColor; ?> !important;
}
.cast-schedule-btn:hover {
  color: white !important;
  background-color: <?php echo $mainColor; ?> !important;
}
.tab_active {
  color: white  !important;
  background-color: <?php echo $mainColor; ?> !important;
}
.question_badge {
  background-color: #B8182E;
}
.answer_badge {
  background-color: #0A6EB1;
}
.blog_more_btn {
  color: <?php echo $mainColor; ?> !important;
  border: 1px solid <?php echo $mainColor; ?> !important;
}
.cast_introduce_content {
  border-radius: 15px !important;
}
.intro_list {
  font-size: 1rem;
  border-bottom: 1px <?php echo $mainColor; ?> dotted;
}
</style>

@endsection

@section('content')
  <section>
    <h1 class="text-white p-3 text-center fw-bold" style="font-size: 1.2rem; background: {{ $mainColor }}">{{ $sites->name }}</h1>
  </section>
  <section class="shop_info_pc">
    <!-- <div class="container"> -->
    <div class="text-white p-2 text-center" style="background: whitesmoke">
      @foreach($tabs as $tab)
      <a href="{{ $tab['url'] }}" class="btn {{ $tab['active'] ? 'tab_active' : '' }}">{{ $tab['name'] }}</a>
      @endforeach
    </div>
    <!-- </div> -->
  </section>
  
  <section>
    <div class="container">
      <!-- <h5 class="mt-3" style="color: {{ $mainColor }}">{{ $casts->source_name }}({{ $casts->age }})</h5> -->
      <div class="row my-3 cast_pc">
        <div class="col-4">
          <div class="card rounded-0" style="background: {{ $mainColor }}">
            <div class="card m-3 p-3 cast_introduce_content">
                  <h2 class="mb-3" style="color: {{ $mainColor }}">{{ $casts->source_name }}({{ $casts->age }})</h2>
                  <p class="mb-0">身長　　{{ $casts->height }}cm</p>
                  <hr>
                  <p class="mb-0">B/W/H　　B{{ $casts->bust }}({{ $casts->cup }})/W{{ $casts->waist }}/H{{ $casts->hip }} </p>
                  <hr>
                  <p class="mb-0">キャッチコピー {{ $casts->catch_copy }}</p>
                  <hr>
                  <p class="mb-0">体型　　{{ $casts->figure_text }}</p>
                  <hr>
                  <p class="mb-0">コメント　　{{ $casts->figure_comment ? $casts->figure_comment : '-' }}</p>
                <hr>
                <div class="card">
                  <div class="card-header text-center" style="color: white;background: {{ $mainColor }} !important;">
                    <small>メッセージ</small>
                  </div>
                  <div class="card-body" style=" background: #FDDEE3;">
                    <small class="card-text" style="color: {{ $mainColor }};">{!! nl2br(e($casts->self_pr)) !!}</small>
                  </div>
                </div>
                @if(session()->has('user_id'))
                <div class="text-center">
                  <button class="btn favorite_btn rounded-pill" data-id="{{ $casts->id }}" style="color: white; background: {{ $mainColor }};"><i class="fas fa-heart mr-1"></i>お気に入り登録する</button>
                </div>
                @endif
            </div>
          </div>
        </div>
        <div class="col-4 text-center">
          @foreach($casts->image as $image)
            @if($loop->iteration == 2)
              @break;
            @endif
            <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery" data-type="image">
              <img src="{{ asset('/storage' . $image->directory . 'MS_' . $image->path) }}" style="height: 33rem;" class="img-fluid" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
            </a>
          @endforeach
        </div>
          <div class="col-4">
            @foreach($casts->image as $image)
              @if($loop->first)
                @continue;
              @endif
              <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery" data-type="image">
                <img src="{{ asset('/storage' . $image->directory . 'SM_' . $image->path) }}" class="img-fluid mb-2" style="height: 10.7rem;" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
              </a>
            @endforeach
          </div>
      </div>
      <div class="cast_sp my-3">
        <h5 class="mb-3" style="color: {{ $mainColor }}">{{ $casts->source_name }}({{ $casts->age }})</h5>
        <div class="col-12 text-center">
          @foreach($casts->image as $image)
            @if($loop->iteration == 2)
              @break;
            @endif
            <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery"> 
              <!-- data-title="sample 1 - white" -->
              <img src="{{ asset('/storage' . $image->directory . 'MS_' . $image->path) }}" style="height: 20rem;" class="img-fluid" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
            </a>
          @endforeach
        </div>
        <div class="container-fluid my-3">
          <div class="row flex-nowrap" style="overflow-x: auto;">
            @foreach($casts->image as $image)
              @if($loop->first)
                @continue;
              @endif
              <div style="width: 10rem;">
                <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery">
                  <img src="{{ asset('/storage' . $image->directory . 'SM_' . $image->path) }}" class="img-fluid mb-2" style="height: 10rem;" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
                </a>
              </div>
            @endforeach
          </div>
        </div>
        <div class="">
          <div class="card text-center">
            <span class="py-2 intro_list">身長　　{{ $casts->height }}cm</span>
            <span class="py-2 intro_list">B/W/H　　B{{ $casts->bust }}({{ $casts->cup }})/W{{ $casts->waist }}/H{{ $casts->hip }} </span>
            <span class="py-2 intro_list">キャッチコピー {{ $casts->catch_copy }}</span>
            <span class="py-2 intro_list">体型　　{{ $casts->figure_text }}</span>
            <span class="py-2 intro_list">コメント　　{{ $casts->figure_comment ? $casts->figure_comment : '-' }}</span>
          </div>
          <div class="card">
            <div class="card-header text-center" style="color: white;background: {{ $mainColor }} !important;">
              <small>メッセージ</small>
            </div>
            <div class="card-body" style=" background: #FDDEE3;">
              <small class="card-text" style="color: {{ $mainColor }};">{!! nl2br(e($casts->self_pr)) !!}</small>
            </div>
          </div>
          <div class="text-center">
            <button class="btn favorite_btn rounded-pill" data-id="{{ $casts->id }}" style="color: white; background: {{ $mainColor }};"><i class="fas fa-heart"></i>お気に入り登録する</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <p class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">出勤スケジュール</p>
      </div>
    <table class="table table-bordered cast_schedule_pc">
      <tr>
      @foreach($dateArray as $dateAry)
        <td class="text-center {{ $dateAry['active'] ? 'tab_active' : '' }}">{{ $dateAry['date'] }}</td>
        <!-- <td class="text-center">{{ $dateAry['schedule'] ? $dateAry['schedule']->start_time . ' ~ ' . $dateAry['schedule']->end_time : '-' }}</td>
        <td class="text-center"><a class="btn" style="background: {{$mainColor}}; color:">web予約</a></td> -->
        @endforeach
      </tr>
      <tr>
      @foreach($dateArray as $dateAry)
        <!-- <td class="text-center {{ $dateAry['active'] ? 'tab_active' : '' }}">{{ $dateAry['date'] }}</td> -->
        <td class="text-center">{{ $dateAry['schedule'] ? $dateAry['schedule']->start_time . ' ~ ' . $dateAry['schedule']->end_time : '-' }}</td>
        <!-- <td class="text-center"><a class="btn" style="background: {{$mainColor}}; color:">web予約</a></td> -->
      @endforeach
      </tr>
      <tr>
      @foreach($dateArray as $dateAry)
        <!-- <td class="text-center {{ $dateAry['active'] ? 'tab_active' : '' }}">{{ $dateAry['date'] }}</td> -->
        <!-- <td class="text-center">{{ $dateAry['schedule'] ? $dateAry['schedule']->start_time . ' ~ ' . $dateAry['schedule']->end_time : '-' }}</td> -->
        <td class="text-center"><a href="{{ route('mypage.reserve.course',['cast_id' => $casts->id]) }}" class="btn" style="background: {{$mainColor}}; color:white;"><small>web予約</small></a></td>
        @endforeach
      </tr>
    </table>
    <div class="cast_schedule_sp">
      <table class="table table-bordered">
      @foreach($dateArray as $dateAry)
      <tr>
        <td class="text-center {{ $dateAry['active'] ? 'tab_active' : '' }}"><p class="pt-2 mb-0">{{ $dateAry['date'] }}</p></td>
        <td class="text-center"><p class="pt-2 mb-0">{{ $dateAry['schedule'] ? $dateAry['schedule']->start_time . ' ~ ' . $dateAry['schedule']->end_time : '-' }}</p></td>
        <td class="text-center"><a href="{{ route('mypage.reserve.course',['cast_id' => $casts->id]) }}" class="btn" style="background: {{$mainColor}}; color:white;">web予約</a></td>
      </tr>
      @endforeach
      <tr>
      </table>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <p class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">写メ日記一覧</p>
        <div class="row my-3">
          @foreach ($blogs as $blog)
          <div class="col-12 col-md-4">
            <a href="{{ route('site.detail.blog.detail',['site_id' => $blog->site_id,'category_id' => 3,'id' => $blog->id]) }}" class="blog_card">
              <div class="card px-0">
                <img class="pt-3 text-center" src="{{ $blog->image ?  asset('/storage' . $blog->image->image_url) : asset('storage/no-image.jpg') }}" class="card-img-top" style="height: 220px; width: auto; object-fit:contain" alt="{{ $sites->name }} {{ $casts->source_name }} {{ $blog->title }}">
                <div class="text-center" style="height: 5rem">
                  <p class="pt-3">{{ $blog->title }}</p>
                  <p><small class="text-muted">{{ date('Y年m月d日',strtotime($blog->published_at)) }}</small></p>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        @if($blogs->isNotEmpty())
        <div class="d-flex justify-content-center">
          <a href="{{ route('site.detail.diary',['site_id' => $siteId,'cast_id' => $casts->id]) }}" class="btn blog_more_btn px-5">もっと見る</a>
        </div>
        @else
          <p>投稿がありません。</p>
        @endif
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <p class="fw-bold fs-5" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店長からのコメント</p>
        <p>{!! nl2br(e($casts->shop_manager_pr)) !!}</p>
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <p class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">アンケート</p>
        @foreach($formatQuestionData as $questionData)
          @if($questionData['answer'])
            <div class="row my-3">
              <div class="col-12">
                  <p><span class="badge question_badge mr-3">Q</span>{{ $questionData['question'] }}</p>
                  <p><span class="badge answer_badge mr-3">A</span>{{ $questionData['answer'] ? $questionData['answer']  : ' - ' }}</p>
                  <hr>
              </div>
            </div>
            @endif
          @endforeach
      </div>
    </div>
  </section>
@endsection

@section('script')

  <!-- Bootstrap -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Ekko Lightbox -->
  <script src="{{ asset('vendor/ekko-lightbox/ekko-lightbox.min.js') }}"></script>

  <script>
  $(function () {
    //ログ
    const parameter = {
      cast_id: "{{ $casts->id }}"
    }
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      data: parameter, // JSONデータ本体
      // contentType: "application/json",
      url: "{{ route('site.log.cast') }}",
      dataType:"json",
    }).done(function(data,status,jqXHR) {

    }).fail(function(jqXHR, textStatus, errorThrown) {
        
    }).always(function (data) {
      // 常に実行する処理
    });
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        // alwaysShowClose: true
        // maxImageWidth: 500, //拡大画像の最大横幅： 高さのみでサイズ制限する時は 0を設定
        // maxImageHeight: 500, //拡大画像の最大高さ： 幅のみでサイズ制限する時は 0を設定
      });
    });
  })
  $('.favorite_btn').on('click', function() {
    //お気に入り登録する
    const parameter = {
      cast_id: "{{ $casts->id }}"
    }
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      data: parameter, // JSONデータ本体
      // contentType: "application/json",
      url: "{{ route('site.like.cast') }}",
      dataType:"json",
    }).done(function(data,status,jqXHR) {
      if(data.result == 0) {
        toastr.success(data.message)
      }
      if(data.result == 1) {
        toastr.error(data.message)
      }
      if(data.result == 2) {
        toastr.warning(data.message)
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        toastr.error('処理に失敗しました。')
    }).always(function (data) {
      // 常に実行する処理
    });
  });
  
  </script>
@endsection