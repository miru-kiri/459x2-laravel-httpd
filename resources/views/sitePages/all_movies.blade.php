@extends('layouts.site')

@section('title','【天国ネット動画一覧】リアルタイム更新中｜キャスト・店舗の動画が見放題')
@section('description','天国ネットに在籍するキャスト・スタッフのリアルタイム動画を一覧表示。店舗ごとの動画や新着ムービーをいつでもチェック可能。気になる子の雰囲気や接客の様子も動画で事前に確認できます。')
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
<link rel="stylesheet" href="{{ asset('css/site/top.css') }}" media="print" onload="this.media='all'">
<link rel="stylesheet" href="{{ asset('css/site/miru_top.css') }}" media="print" onload="this.media='all'">
<style>
main{
  background-image:url("{{ asset('img/bg_img_apr.jpg') }}");
  background-size:cover;
  padding-bottom: 70px;
}
a{
  text-decoration: none;
}
h1, h2 {
  color: #d25a5a;
}
ul {
  padding-left: 1.5em;
}
iframe {
  width: 100%;
  height: 300px;
  border: none;
}
.box {
  background-color: #fffaf6;
  padding: 2.5em 2em;
  border: 2px dashed #ff9999;
  /*
  border-radius: 10px;
  */
}
img {
  width: 100%;
  max-width: 700px;
  border-radius: 8px;
  margin: 1em 0;
}
.image-icon {
  width: 80px;
  float: right;
  margin-left: 1em;
}
section:nth-child(odd) {
  background: #f7ebf0; 
}

section:nth-child(even) {
  background: #fff; 
}

section {
  padding: 2.5em 2em;
  /*
  border-radius: 10px;
  */
  width: 100%;
  max-width: 1200px;
  margin: 0 auto 0 auto;
} 
header{
  background-image: url("{{ asset('img/header_apr.png') }}");
  background-position: center center;
  background-size: cover;
}
.logo{
  background-color:#fff;
  padding:0 5px;
}
.pa_w10{
  padding: 10px 0;
}
.pa_w20{
  padding: 20px 0 ;
}
.pa_top10{
  padding-top: 10px;
}
</style>

@endsection

@section('content')
  <section>
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mt-1" style="background-color: unset;">
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
    <section class="kabuki">
      <div class="kabuki_link">
        <h3><a href="https://459x.com/" title="大人の夜を熱くするコスモ天国ネット">コスモ天国ネット</a>が紹介する<br>おすすめ<a href="https://459x.com/detail/area?genre_id=1&area_id=13" title="コスモ天国ネットが紹介する道後歌舞伎通り付近のソープ・ファッションヘルス店">道後歌舞伎通りの<br />ソープ・ファッションヘルス店</a></h3>
        <div class="kabuki_link_info">
          「男の聖地」道後ヘルスビルを知られた方は、以下の店舗情報もおすすめです。<br>
          観光後のひとときに、癒やしと楽しみをどうぞ。
        </div>
        <ul class="kabuki_link_content">
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=2" title="コスモ天国ネット｜道後歌舞伎通りにあるファッションヘルス「道後トレビの泉」">道後トレビの泉</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=10" title="コスモ天国ネット｜道後歌舞伎通りにあるデリバリー・ホテルヘルス「バニラリップ」">バニラリップ</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=24" title="コスモ天国ネット｜道後歌舞伎通りにあるファッションヘルス「赤と黒」">赤と黒</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=110" title="コスモ天国ネット｜道後歌舞伎通りにあるファッションヘルス「愛ドル学園～制服美少女集めました～」">愛ドル学園～制服美少女集めました～</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=118" title="コスモ天国ネット｜道後歌舞伎通りにあるソープランド「道後しらゆきひめ」">道後しらゆきひめ</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=123" title="コスモ天国ネット｜道後歌舞伎通りにあるファッションヘルス「アメイジングビル」">アメイジングビル</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=130" title="コスモ天国ネット｜道後歌舞伎通りにあるファッションヘルス「優しいひとづま」">優しいひとづま</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=133" title="コスモ天国ネット｜道後歌舞伎通りにある風俗エステ「ごほうびスパ はちみつ」">ごほうびスパ はちみつ</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=136" title="コスモ天国ネット｜道後歌舞伎通りにあるデリバリーヘルス「回春エステ魂」">回春エステ魂</a></li>
          <li><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=137" title="コスモ天国ネット｜道後歌舞伎通りにあるソープランド「英乃國屋」">英乃國屋</a></li>
        </ul>
      </div>
    </section>      
  
  
@endsection
