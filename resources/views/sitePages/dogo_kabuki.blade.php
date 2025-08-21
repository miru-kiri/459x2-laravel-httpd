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
<link rel="stylesheet" href="{{ asset('css/site/top.css') }}">
<link rel="stylesheet" href="{{ asset('css/site/miru_top.css') }}">
<style>
main{
  background-image:url("{{ asset('img/bg_img_apr.jpg') }}");
  background-size:cover;
  padding-bottom: 70px;
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
          <h1>道後歌舞伎通り完全ナビ</h1>
          <div class="page-intro">
            <h2 class="page-title">歴史と文化が交差する坂道「道後歌舞伎通り」とは</h2>
            <div class="image-block">
               <img src="{{ asset('img/kabuki_top.jpg') }}" alt="道後歌舞伎通りの風景">
            </div>
            <nav class="toc">
              <h2>目次</h2>
              <ul>
                <li><a href="#section1">1. 歴史と名前の由来</a></li>
                <li><a href="#section2">2. 芸者と三味線の時代</a></li>
                <li><a href="#section3">3. 遊郭の文化と広がり</a></li>
                <li><a href="#section4">4. 昭和期のネオン街</a></li>
                <li><a href="#section5">5. 現在の風情</a></li>
                <li><a href="#section6">6. 地元の記憶と記録</a></li>
              </ul>
            </nav>
          </div>
          <h2  id="section1">1. 歴史と名前の由来</h2>
          <div class="image-block">
            <img src="{{ asset('img/kabuki_1.jpg') }}" alt="道後歌舞伎通りの歴史的風景">
          </div>
          <p>道後温泉の奥にひっそりと佇む坂道には、どこか懐かしさを感じさせる通りがあります。<br />
              かつてここには、「<span>道後歌舞伎通り</span>」という名前がつけられ、多くの人々に親しまれていました。<br /><br />
          </p>
        </section>
      
        <section class="kabuki">
          <h2  id="section2">2. 芸者と三味線の時代</h2>
          <div class="image-block">
            <img src="{{ asset('img/kabuki_7.jpg') }}" alt="道後歌舞伎通り｜芸者と三味線の演舞">
          </div>
          <p>
              坂沿いに並ぶ茶屋では、湯上がりの客たちが集い、三味線の音色に耳を傾けながら、賑やかなひとときを楽しんでいました。
              茶屋の女たちは、都で流行していた「遊女歌舞伎」の一部を取り入れ、客たちにその舞を披露していたと言われています。
            </p>
        </section>
        <section class="kabuki">
          <h2  id="section3">3. 遊郭の文化と広がり</h2>
          <div class="image-block">
            <img src="{{ asset('img/kabuki_3.jpg') }}" alt="道後歌舞伎通り｜賑やかだった時代の街並み">
          </div>
          <p>
              その舞には華やかな所作や扇を使った巧みな演技があり、客たちはその芸に魅了され、いつしかこの坂を道後の歌舞伎通りと呼ぶようになったのでしょう。
          </p>
        </section>
        <section class="kabuki">
          <h2  id="section4">4. 昭和期のネオン街</h2>
          <div class="image-block">
            <img src="{{ asset('img/kabuki_9.jpg') }}" alt="道後歌舞伎通り｜昭和のネオン街の様子">
          </div>
          <p>
              時代が移り変わり、昭和初期にはこの坂が「ネオン坂」と呼ばれることもありました。
              街は煌びやかな明かりに包まれ、賑やかな夜の町を象徴する場所となったのです。<br />
              けれどもその名は次第に使われなくなり、今では「<span>道後歌舞伎通り</span>」という名前だけが、静かに残っています。<br /><br />
          </p>
        </section>
        <section class="kabuki">
          <h2  id="section5">5. 現在の風情</h2>
          <div class="image-block">
            <img src="{{ asset('img/kabuki_5.jpg') }}" alt="現在の道後歌舞伎通り｜坂道の様子">
          </div>
          <p>
              行き交う人影の向こうに、かつての賑わいが重なって見える。<br />
              そんな、時間の層を感じさせる通りです。<br />
          </p>
        </section>
        <section class="kabuki">
          <h2  id="section6">6. 地元の記憶と記録</h2>
          <div class="image-block">
            <img src="{{ asset('img/kabuki_6.jpg') }}" alt="道後の記憶を語る写真">
          </div>
          <p>
            かつて遊郭として賑わったこの通りには、今も人々の記憶の中に、当時の面影が残されています。<br />
            華やかさの裏にあった営みや人のつながりが、語りや記録を通して静かに受け継がれています。
          </p>
          <div class="kabuki_wiki">※参考：<a href="https://ja.wikipedia.org/wiki/道後歌舞伎通り" target="_blank" rel="noopener noreferrer">ウィキペディア（道後歌舞伎通り）</a></div>
        </section>
        
        <section class="kabuki">
          <div class="kabuki_link">
            <h3><a href="https://459x.com/" title="大人の夜を熱くするコスモ天国ネット">コスモ天国ネット</a>が紹介する<br>おすすめ<a href="https://459x.com/detail/area?genre_id=1&area_id=13" title="コスモ天国ネットが紹介する道後歌舞伎通り付近のソープ・ファッションヘルス店"><span style="color: red; font-size:1.2rem; font-weight: bold;">道後歌舞伎通り</span>の<br />ソープ・ファッションヘルス店</a></h3>
            <div class="kabuki_link_info">
              道後歌舞伎通りの由来や歴史をご覧になった方には、以下の店舗情報もおすすめです。<br>
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