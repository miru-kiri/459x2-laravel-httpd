@extends('layouts.site')

@section('title', $pgttl)
@section('description', $pgdesc)
@section('keywords',  $keywords )

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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<style>
  main {
    padding-bottom: 50px;
  }
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
/* .read-more-001 {
    position: relative;
} */

.read-more-001 p {
    position: relative;
    max-height: 100px; /* 開く前に見せたい高さを指定 */
    margin-bottom: 0;
    overflow: hidden;
    transition: max-height 1s;
}

.read-more-001:has(:checked) p {
    max-height: 100vh;
}

.read-more-001 p::after {
    display: block;
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 60px;
    background: linear-gradient(180deg, hsla(0, 0%, 100%, 0) 0, hsla(0, 0%, 100%, .9) 50%, hsla(0, 0%, 100%, .9) 0, #fff);
    content: '';
}

.read-more-001:has(:checked) p::after {
    content: none;
}

.read-more-001 label {
    display: flex;
    align-items: center;
    gap: 0 4px;
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    color: <?php echo $mainColor; ?> ;
    font-size: .8em;
}

.read-more-001 label:hover {
    color: #c7511f;
    text-decoration: underline;
    cursor: pointer;
}

.read-more-001:has(:checked) label {
    display: none;
}

.read-more-001 label::after {
    display: inline-block;
    width: 10px;
    height: 5px;
    background-color: #b6bdc3;
    clip-path: polygon(0 0, 100% 0, 50% 100%);
    content: '';
}

.read-more-001 input {
    display: none;
}
.swiper--wrapper {
  /* wrapperのサイズを調整 */
  width: 100%;
  height: 300px;
}

.swiper-slide {
  /* スライドのサイズを調整、中身のテキスト配置調整、背景色 */
  color: #ffffff;
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 300px;
  text-align: center;
}
.swiper-button-next,
.swiper-button-prev {
    --swiper-navigation-color: <?php echo $mainColor; ?>;
}
.swiper-pagination-bullet,
.swiper-pagination-bullet-active{
    background-color: <?php echo $mainColor; ?>;
}
video {
  width: 100%;
  height: 300px;
}
/* スマートフォンサイズ時のメディアクエリ */
@media (max-width: 768px) {
  video {
    width: 100%;
    height: 150px;
  }
}
</style>

@endsection

@section('content')
  <section>
    <h1 class="text-white p-3 text-center fw-bold" style="font-size: 1.2rem; background: {{ $mainColor }}">{{ $sites->name }}</h1>
  </section>
  <section>
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-1" style="background-color: white;">
          @foreach($breadCrumbs as $breadCrumb)
          @if ($loop->count == 1)
            <li class="breadcrumb-item active" aria-current="page"><small>{{ $breadCrumb['label'] }}</small></li>
          @else
            @if ($loop->last)
              <li class="breadcrumb-item active" aria-current="page"><small>{{ $breadCrumb['label'] }}</small></li>
            @else
              <li class="breadcrumb-item"><a href="{{ $breadCrumb['url'] }}"><small>{{ $breadCrumb['label'] }}</small></a></li>
            @endif
          @endif
          @endforeach
        </ol>
      </nav>
    </div>
  </section>
  @if($mainImage->isNotEmpty())
  <section>
    <div class="container text-center">
    <div class="swiper">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            @foreach($mainImage as $image)
              <div class="swiper-slide">
                @if($image->url)
                <a href="{{ $image->url }}">
                @endif
                  <img src="{{ asset('storage' . $image->image )}}" loading="lazy" style="width:100%; height: 300px; object-fit:contain"></img>
                @if($image->url)
                </a>
                @endif
              </div>
            @endforeach
          </div>
          <!-- 必要に応じてページネーション -->
          <div class="swiper-pagination"></div>
          <!-- 必要に応じてナビボタン -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
      </div>
    </div>
  </section>
  @endif
  <br/>
  <br/>
  <br/>
  <br/>
  <br/>
  <br/>
  <br/>
  <br/>
  <section>
  <!-- <section class="shop_info_pc"> -->
    <div class="container">
    <div class="text-white p-2 text-center" style="background: whitesmoke">
      @foreach($tabs as $tab)
      <a href="{{ $tab['url'] }}" class="btn {{ $tab['active'] ? 'tab_active' : '' }}">{{ $tab['name'] }}</a>
      @endforeach
    </div>
    </div>
  </section>
  
  <section>
    <div class="container">
      <!-- <h5 class="mt-3" style="color: {{ $mainColor }}">{{ $casts->source_name }}({{ $casts->age }})</h5> -->
      <div class="row my-3 cast_pc">
        <div class="col-4">
          <div class="card rounded-0" style="background: {{ $mainColor }}">
            <div class="card m-3 p-3 cast_introduce_content">
                  <h2 class="mb-3" style="color: {{ $mainColor }}">
                    {{ $casts->source_name }}
                    @if($casts->age)
                    ({{ $casts->age }})
                    @endif
                  </h2>
                  @if($casts->height)
                  <p class="mb-0">身長　　{{ $casts->height }}cm</p>
                  <hr>
                  @endif
                  @if($casts->bust || $casts->cup || $casts->waist || $casts->hip)
                  <p class="mb-0">B/W/H　　B{{ $casts->bust }}({{ $casts->cup }})/W{{ $casts->waist }}/H{{ $casts->hip }} </p>
                  <hr>
                  @endif
                  @if($casts->catch_copy)
                  <p class="mb-0">{{ $casts->catch_copy }}</p>
                  <hr>
                  @endif
                  @if($casts->figure_text)
                  <p class="mb-0">体型　　{{ $casts->figure_text }}</p>
                  <hr>
                  @endif
                  @if($casts->option_text)
                  <p class="mb-0">オプション　　{{ $casts->option_text }}</p>
                  <hr>
                  @endif
                  @if($casts->figure_comment)
                  <p class="mb-0">コメント　　{{ $casts->figure_comment }}</p>
                  <hr>
                  @endif
                @if($casts->self_pr)
                <div class="card">
                  <div class="card-header text-center" style="color: white;background: {{ $mainColor }} !important;">
                    <small>メッセージ</small>
                  </div>
                  <div class="card-body" style=" background: #FDDEE3;">
                    <small class="card-text" style="color: {{ $mainColor }};">{!! nl2br(e($casts->self_pr)) !!}</small>
                  </div>
                </div>
                @endif
                <div class="text-center">
                  <button class="btn favorite_btn rounded-pill" data-id="{{ $casts->id }}" style="color: white; background: {{ $mainColor }};"><i class="fas fa-heart mr-1"></i>お気に入り登録する</button>
                </div>
            </div>
          </div>
        </div>
        <div class="col-4 text-center">
          @foreach($casts->image as $image)
            @if($loop->iteration == 2)
              @break;
            @endif
            <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery" data-type="image">
              <img src="{{ asset('/storage' . $image->directory . 'MS_' . $image->path) }}" loading="lazy" style="height: 33rem; object-fit:contain" class="img-fluid" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
            </a>
          @endforeach
        </div>
          <div class="col-4">
            @foreach($casts->image as $image)
              @if($loop->first)
                @continue;
              @endif
              <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery" data-type="image">
                <img src="{{ asset('/storage' . $image->directory . 'SM_' . $image->path) }}" class="img-fluid mb-2" loading="lazy" style="height: 10.7rem; object-fit:contain" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
              </a>
            @endforeach
          </div>
      </div>
      <div class="cast_sp my-3">
        <h5 class="mb-3" style="color: {{ $mainColor }}">
          {{ $casts->source_name }}
          @if($casts->age)
            ({{ $casts->age }})
          @endif
        </h5>
        <div class="col-12 text-center">
          @foreach($casts->image as $image)
            @if($loop->iteration == 2)
              @break;
            @endif
            <a href="{{ asset('/storage' . $image->directory . 'ML_' . $image->path) }}" data-toggle="lightbox" data-gallery="gallery"> 
              <!-- data-title="sample 1 - white" -->
              <img src="{{ asset('/storage' . $image->directory . 'MS_' . $image->path) }}" loading="lazy" style="height: 30rem;  object-fit:contain" class="img-fluid" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
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
                  <img src="{{ asset('/storage' . $image->directory . 'SM_' . $image->path) }}" class="img-fluid mb-2" loading="lazy" style="height: 10rem;  object-fit:contain" alt="{{ $sites->name }} {{ $casts->source_name }}"></img>
                </a>
              </div>
            @endforeach
          </div>
        </div>
        <div class="">
        @if($casts->height || $casts->bust || $casts->cup || $casts->waist || $casts->hip || $casts->catch_copy || $casts->figure_text || $casts->figure_comment)
          <div class="card text-center">
            @if($casts->height)
            <span class="py-2 intro_list">身長　　{{ $casts->height }}cm</span>
            @endif
            @if($casts->bust || $casts->cup || $casts->waist || $casts->hip)
            <span class="py-2 intro_list">B/W/H　　B{{ $casts->bust }}({{ $casts->cup }})/W{{ $casts->waist }}/H{{ $casts->hip }} </span>
            @endif
            @if($casts->catch_copy)
            <span class="py-2 intro_list">キャッチコピー {{ $casts->catch_copy }}</span>
            @endif
            @if($casts->figure_text)
            <span class="py-2 intro_list">体型　　{{ $casts->figure_text }}</span>
            @endif
            @if($casts->option_text)
            <span class="py-2 intro_list">オプション　　{{ $casts->option_text }}</span>
            @endif
            @if($casts->figure_comment)
            <span class="py-2 intro_list">コメント　　{{ $casts->figure_comment ? $casts->figure_comment : '-' }}</span>
            @endif
          </div>
          @endif
          @if($casts->self_pr)
          <div class="card">
            <div class="card-header text-center" style="color: white;background: {{ $mainColor }} !important;">
              <small>メッセージ</small>
            </div>
            <div class="card-body" style=" background: #FDDEE3;">
              <small class="card-text" style="color: {{ $mainColor }};">{!! nl2br(e($casts->self_pr)) !!}</small>
            </div>
          </div>
          @endif
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
        <td class="text-center {{ $dateAry['active'] ? 'tab_active' : '' }}">{{ $dateAry['date'] }}({{ $dateAry['week'] }})</td>
        @endforeach
      </tr>
      <tr>
      @foreach($dateArray as $dateAry)
        <td class="text-center">{{ $dateAry['schedule'] ? $dateAry['schedule']->start_time . ' ~ ' . $dateAry['schedule']->end_time : '-' }}</td>
      @endforeach
      </tr>
      <tr>
      @foreach($dateArray as $dateAry)
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
        <div class="row my-2">
          @foreach ($blogs as $blog)
          <!-- <div class="col-12 col-md-4">
            <div class="card px-0 text-center">
              <a href="{{ route('site.detail.blog.detail',['site_id' => $blog->site_id,'category_id' => 3,'id' => $blog->id]) }}">
              </a>
              <div class="text-center" style="height: 5rem">
                <p class="pt-3">{{ $blog->title }}</p>
                <p><small class="text-muted">{{ date('Y年m月d日',strtotime($blog->published_at)) }}</small></p>
              </div>
            </div>
          </div> -->
          <div class="col-4 col-md-2 my-3">
            <div class="image-container">
              <a href="{{ route('site.detail.blog.detail',['genre_id' => $genreId,'area_id' => $areaId,'site_id' => $blog->site_id,'category_id' => 3,'id' => $blog->id]) }}">
                <img src="{{ asset('/storage' . $blog->image) }}" class="card-img-top" loading="lazy" alt="{{ $sites->name }} {{ $casts->source_name }} {{ $blog->title }}">
                <div class="text-overlay">
                  <p class="cast-blog-title">{{ $blog->title }}</p>
                  <p class="cast-blog-name">{{ $blog->source_name }}</p>
                  <p class="cast-blog-date">{{ date('Y年m月d日 H:i',strtotime($blog->published_at)) }}</p>
                </div>
              </a>
            </div>
          </div>
          @endforeach
        </div>
        @if($blogs->isNotEmpty())
        <!-- <div class="d-flex justify-content-center">
          <a href="{{ route('site.detail.top',['site_id' => $siteId,'cast_id' => $casts->id]) }}" class="btn blog_more_btn px-5">もっと見る</a>
        </div> -->
        @else
          <p>投稿がありません。</p>
        @endif
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <p class="fw-bold fs-5" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">口コミ</p>
        @if($reviewData->isNotEmpty())
        <div class="row">
          @foreach($reviewData as $review)
          <div class="col-12 col-md-4">
            <div class="card px-0">
              <div class="card-header" style="background: {{ $mainColor }};color: white;">
                {{ $review->title }}
              </div>
              <div class="card-body read-more-001">
                <p>
                  投稿日時: {{ $review->created_at }}<br/>
                  訪問日: {{ $review->time_play }}<br/>
                  {!! nl2br(e($review->content)) !!}<br/>
                </p>
                <label>
                    <input type="checkbox"/>
                    ...続きを読む
                </label>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
          <p>データがありません。</p>
        @endif
        <button type="button" style="color:white;background: #016DB7" class="btn btn-default" data-toggle="modal" data-target="#reviewModal">
          口コミを投稿する
        </button>
      </div>
    </div>

    <!-- Modal nhập review -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">口コミ投稿</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store.review') }}" id="reviewForm" method="POST">
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
                        <div class="form-group">
                            <label>訪問日</label>
                            <input class="form-control" type="date" name="time_play" required>
                        </div>
                        <input type="hidden" id="site_id" name="site_id" value="{{$siteId}}" />
                        <input type="hidden" id="cast_id" name="cast_id" value="{{$casts->id}}" />
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">戻る</button>
                    <button class="btn btn-submit" id="submitFormReview" style="color:white;background: #016DB7">投稿する</button>
                </div>
            </div>
        </div>
    </div>

  </section>
  <section>
    <div class="container">
        <div class="mb-3">
          <p class="fw-bold fs-5" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">天国ネット動画</p>
          @if($movieData->isNotEmpty())
            <div class="row">
            @foreach($movieData as $data)
            <!-- <div class="col-12 col-md-6"> -->
            @php
            if(empty($data->cast_id)) {
                $url = route('site.detail.top', ['site_id' => $data->site_id]);
            } else {
                $url = route('site.detail.cast.detail', ['site_id' => $data->site_id, 'cast_id' => $data->cast_id]);
            }
            @endphp
            <div class="col-6">
              <div class="video-card">
                <video class="cast-video w-100" src="{{ asset('storage' . $data->file_path .'/' . $data->file_name . '.mp4') }}" controlsList="nodownload" oncontextmenu="return false;" preload="none" muted playsinline controls loading="lazy"></video>
                <div class="video-content p-3">
                  <h6 class="video-title fw-bold mb-2" style="color: {{ $mainColor }}">{{ $data->title }}</h6>
                  <a href="{{ $url }}" class="cast-name text-decoration-none">
                    <span class="d-block mb-2 text-primary">{{ $data->source_name ?? $data->site_name }}</span>
                  </a>
                  <p class="video-description text-muted mb-0 small">{{ $data->content }}</p>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @else
            <p>データがありません。</p>
          @endif
        </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
        <p class="fw-bold fs-5" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">店長からのコメント</p>
        @if($casts->shop_manager_pr)
          <p>{!! nl2br(e($casts->shop_manager_pr)) !!}</p>
        @else
          <p>データがありません。</p>
        @endif
      </div>
    </div>
  </section>
  <section>
    <div class="container">
      <div class="mb-3">
      @if($formatQuestionData)
        <p class="fw-bold fs-5 mb-0" style="color: {{ $mainColor }}; border-bottom: 2px solid #d3d3d3;">アンケート</p>
        @foreach($formatQuestionData as $questionData)
            @if(!isset($questionData['answer']))
              @continue;
            @endif
            @if($questionData['is_public'] != 1)
              @continue;
            @endif
            @if(!$questionData['answer'])
              @continue;
            @endif
            <div class="row my-3">
              <div class="col-12">
                  <p><span class="badge question_badge mr-3">Q</span>{{ $questionData['question'] }}</p>
                  <p><span class="badge answer_badge mr-3">A</span>{{ $questionData['answer'] ? $questionData['answer']  : ' - ' }}</p>
              </div>
            </div>
          @endforeach
      @endif
      </div>
    </div>
  </section>
@endsection

@section('script')

  <!-- Bootstrap -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Ekko Lightbox -->
  <script src="{{ asset('vendor/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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
    const swiper = new Swiper(".swiper", {
        // ページネーションが必要なら追加
        pagination: {
            el: ".swiper-pagination"
        },
        // ナビボタンが必要なら追加
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        }
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
        location.href = "{{ route('mypage.login') }}"
        // toastr.error(data.message)
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
  <script>
      document.addEventListener("DOMContentLoaded", function() {
        const videos = document.querySelectorAll('.cast-video');
        
        // IntersectionObserverの設定
        const observer = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {  // 動画が画面に表示された場合
              const video = entry.target;
              video.src = video.getAttribute('src'); 
              video.load(); // 動画の読み込みを開始
              video.play(); // 動画を再生
              observer.unobserve(video); // 一度読み込みが完了したら監視を停止
            }
          });
        }, { threshold: 0.5 }); // 50%表示されたら読み込み開始
        
        // 各動画を監視対象に追加
        videos.forEach(video => {
          observer.observe(video);
        });
      });

      // DOMツリーが完全に読み込まれた後に実行される
    document.addEventListener('DOMContentLoaded', function() {
      // 'lazy-load' クラスを持つすべての画像を取得
      const images = document.querySelectorAll('img.lazy-load');
    });
  </script>
  <script>
    $(document).ready(function() {
        $("#submitFormReview").on("click", function() {
            console.log("Submit button clicked");
            let title = $("input[name='title']").val().trim();
            let content = $("textarea[name='content']").val().trim();
            let timePlay = $("input[name='time_play']").val();

            // Simple validation
            if (title === "") {
                alert("タイトルを入力してください。");
                return;
            }

            if (content === "") {
                alert("内容を入力してください。");
                return;
            }

            if (timePlay === "") {
                alert("再生日付を選択してください。");
                return;
            }

            let form = $("#reviewForm")[0];
            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('store.review') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert("レビューが送信されました！");
                    $("#reviewForm")[0].reset();
                    $("#reviewModal").modal("hide");
                },
                error: function(xhr) {
                    alert("エラーが発生しました。もう一度試してください。");
                    console.error(xhr.responseText);
                }
            });
        });
    });

  </script>

@endsection
