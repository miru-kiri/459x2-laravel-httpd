@extends('layouts.mypage')

@section('title','四国最大級のおすすめ風俗優良店まとめサイト|コスモ天国ネット マイページ設定ページ')
@section('description','有名な道後歌舞伎通りのヘルス・高松城東のソープ風俗情報満載の天国ネット！他にも松山・新居浜・今治のセクキャバ・メンズエステ・アロマエステ・琴平のソープなど様々な風俗や有名な観光地情報を紹介！マイページ設定ページです！')
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
<link href="{{ asset('vendor/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
<style>
.error_message {
  color: tomato;
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
    <p class="text-center fw-bold mb-4" style="color: #016DB7;">設定</p>
    <p class="fw-bold fs-5 pb-2" style="border-bottom: 2px solid #d3d3d3;"><i class="fas fa-user mr-1"  style="color: #016DB7;"></i>アカウント情報</p>
      <!-- <form action="{{ route('mypage.setting.edit') }}" method="POST">
        @csrf -->
        <div class="form-group row pb-2" style="border-bottom: 2px solid #d3d3d3;">
          <label for="name" class="col-sm-4 col-form-label"><i class="fas fa-user mr-1"  style="color: #016DB7;"></i>名前</label>
          <div class="col-sm-6">
            <p class="mb-0 fw-bold">{{ $data->name }}</p>
            <!-- <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}">
            <small class="error_message">{{$errors->first('name')}}</small> -->
          </div>
        </div>
      <!-- </form> -->
      <form action="{{ route('mypage.setting.edit.birthday') }}" method="POST">
        @csrf
        <div class="form-group row  pb-3"  style="border-bottom: 2px solid #d3d3d3;">
          <label for="birth_day" class="col-sm-4 col-form-label"><i class="fas fa-gift mr-1"  style="color: #016DB7;"></i>生年月日</label>
          <div class="col-sm-6">
            <div class="input-group">
              <input type="text" class="form-control" id="birth_day" name="birth_day" value="{{ $data->birth_day ? date('Y/m/d',strtotime($data->birth_day)) : '' }}" @if($data->birth_day) disabled @endif>
              @if($data->birth_day)
                <span class="input-group-text">{{  calculateAge($data->birth_day) }}歳</span>
              @endif
            </div>
            <small class="error_message">{{$errors->first('birth_day')}}</small>
          </div>
          @if(!$data->birth_day)
          <div class="col-sm-2">
            <button class="btn btn-block" style="color: white;background: #016DB7">保存</button>
          </div>
          @endif
        </div>
      </form>
      <div class="form-group row pb-3" style="border-bottom: 2px solid #d3d3d3;">
        <label for="phone" class="col-sm-4 col-form-label"><i class="fas fa-phone mr-1"  style="color: #016DB7;"></i>電話番号</label>
        <div class="col-sm-6">
          <p class="mt-2 mb-0">{{ str_replace('+81',0,$data->phone) }}</p>
          <!-- <input type="text" class="form-control" id="phone" value="{{ $data->phone }}"  disabled> -->
        </div>
        <div class="col-sm-2">
          <a href="{{ route('mypage.setting.edit.page',['type' => 1]) }}" class="btn btn-block tel-form" style="color: white;background: #016DB7">変更依頼</a>
        </div>
      </div>
      <div class="form-group row  pb-3"  style="border-bottom: 2px solid #d3d3d3;">
        <label for="email" class="col-sm-4 col-form-label"><i class="fas fa-envelope mr-1"  style="color: #016DB7;"></i>メールアドレス</label>
        <div class="col-sm-6">
          <p class="mt-2 mb-0">{{ $data->email }}</p>
          <!-- <input type="text" class="form-control" id="email" value="{{ $data->mail }}"  disabled> -->
        </div>
        <div class="col-sm-2">
          <a href="{{ route('mypage.setting.edit.page',['type' => 2]) }}" class="btn btn-block email-form" style="color: white;background: #016DB7">変更依頼</a>
        </div>
      </div>
      <div class="form-group row pb-3"  style="border-bottom: 2px solid #d3d3d3;">
        <label for="password" class="col-sm-4 col-form-label"><i class="fas fa-lock mr-1"  style="color: #016DB7;"></i>パスワード</label>
        <div class="col-sm-6">
          <p class="mt-2 mb-0">**********</p>
        </div>
        <div class="col-sm-2">
          <a href="{{ route('mypage.setting.edit.page',['type' => 3]) }}" class="btn btn-block password-form" style="color: white;background: #016DB7">変更依頼</a>
        </div>
      </div>
      <div class="form-group row pb-3"  style="border-bottom: 2px solid #d3d3d3;">
        <label for="withdrawal" class="col-sm-4 col-form-label"><i class="fas fa-user-minus mr-1"  style="color: #016DB7;"></i>退会する</label>
        <div class="col-sm-6">
          <p class="mt-2 mb-0"></p>
        </div>
        <div class="col-sm-2">
          <a href="{{ route('mypage.withdrawal') }}" class="btn btn-block withdrawal-form" style="color: white;background: #016DB7">退会</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('script')
<script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui/datepicker-ja.js') }}"></script>
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
    $('#birth_day').datepicker({    
        changeYear: true,  // 年選択をプルダウン化
        changeMonth: true  // 月選択をプルダウン化});
    });
  })
</script>
@endsection