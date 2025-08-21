@extends('layouts.site')

@section('title','【男の聖地】道後ヘルスビル徹底ガイド｜愛媛・松山の夜遊びスポット')
@section('description','松山市・道後温泉近くの風俗ビル『道後ヘルスビル』の店舗情報やアクセス、雰囲気を徹底紹介。男の聖地と呼ばれるその実態に迫る。')
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
  <section>
      <h1>道後ヘルスビル徹底ガイド｜松山・道後温泉の夜を彩る風俗街</h1>
      <p>
        愛媛・松山市の観光名所「道後温泉」——そのすぐそばに、<strong>大人たちの隠れた夜の聖地</strong>と呼ばれる場所があります。<br>
        それが「<strong>道後ヘルスビル</strong>」。地元では「男の聖地」とも囁かれるこのビルは、多彩な風俗店が集結し、夜な夜な男たちの心を満たしています。<br>
       「<strong>道後 ヘルス</strong>」というキーワードで検索してたどり着いたあなたへ——<br>
        ここでは、松山・道後エリアで最も有名な風俗スポット<strong>『道後ヘルスビル』</strong>の魅力を徹底解説します。
      </p>
    </section>  
    <section>
      <h2>道後ヘルスビルとは？</h2>
      <img src="{{ asset('img/health_bill.jpg') }}" alt="道後ヘルスビルの夜の外観">
      <!--<img src="./img/3D9A2205.JPG" alt="道後ヘルスビルの夜の外観" />-->
      <p>
        「道後ヘルスビル」は、松山市道後多幸町にある5階建ての建物で、風俗店舗が複数入居する「<strong>道後の夜の中心地</strong>」として知られています。<br>
        1階の無料案内所から始まり、2階〜5階には多彩なジャンルのファッションヘルス店が営業中。<br>
        観光地・道後温泉の裏側に、もう一つの顔があることをご存知でしたか？
      </p>
    </section>  
    <section>
      <h2>入居店舗一覧（2025年現在）</h2>
      <ul>
        <li><strong><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=24" style = "color: #333;">赤と黒～女と男の秘密倶楽部～(ヘルス)</a></strong>：4F・5F</li>
        <li><strong><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=130" style = "color: #333;">あなたの全てを包み込み　優しいひとづま（人妻専門）</a></strong>：2F</li>
        <li><strong><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=10" style = "color: #333;">バニラリップ（デリヘル）</a></strong>：1F</li>
        <li><strong><a href="https://459x.com/detail/top?genre_id=1&area_id=13&site_id=133" style = "color: #333;">ごほうびスパ　はちみつ（風俗エステ）</a></strong></li>
        <li><strong>道後歌舞伎通り案内所（無料案内所）</strong>：1F</li>
      </ul>
    </section>  
    <section>
      <h2>道後温泉と風俗文化の共存</h2>
      <p>
        観光地として栄える道後温泉と、地元密着のナイトカルチャーが共存する場所——それが「道後ヘルスビル」の魅力です。<br>
        温泉で癒され、<strong>夜は男の聖地でもうひとつの癒し</strong>を体験してみてはいかがでしょうか？
      </p>
    </section>  
    <section>
      <h2>そもそも「ヘルス」ってなに？語源と業界用語の由来</h2>
      <p>
        日本の風俗業界でよく聞く「ヘルス」という言葉。<br>
        実はこれは、<strong>英語の “Health（健康）” が語源</strong>です。本来は身体や心の健康を意味する言葉で、そこから派生して、<strong>“健康的な癒しを提供するサービス”</strong>として使われるようになったとされています。
      </p>
      <p>
        昭和〜平成初期、性風俗業の中でも比較的「ソフトサービス寄り」の業態に、この「ヘルス」という呼称がつけられ、<strong>“ファッションヘルス（通称：ヘルス）”</strong>というジャンルが定着しました。
      </p>
      <p>
        つまり、「ヘルス」とは単なる略語ではなく、<strong>「癒し」「健康回復」「精神的リフレッシュ」</strong>といったポジティブなイメージを反映した言葉なのです。
      </p>
      <p>
        道後ヘルスビルもその名に恥じぬ、“ちょっとオトナな健康ランド”かもしれませんね…？
      </p>
    </section>  
    <section>
      <h2>利用時のマナーと注意点</h2>
      <p>
        地元の人々や他の観光客への配慮を忘れず、静かに行動しましょう。<br>
        店舗前での撮影や大声での会話はNG。<br>
        初めて訪れる方は、まず1階の案内所で説明を受けるのがおすすめです。
      </p>
    </section>  
    <section>
      <h2>アクセス情報</h2>
      <p>
        道後温泉本館から徒歩約5分。<br>
        近くの「ファミリーマート松山道後多幸町店」横の道後歌舞伎通りに入ると、ネオンに照らされた「道後ヘルスビル」が姿を現します。<br>
        Googleマップで「道後ヘルスビル」と検索すれば、すぐに表示されます。
      </p>
      <iframe src="https://www.google.com/maps?q=道後ヘルスビル&output=embed" loading="lazy"></iframe>
    </section>  
    <section class="box">
      <h2>ここに来たら健康になれる！？</h2>
      <!--
      <img src="./img/kenkou.jpg" alt="健康アイコン" class="image-icon" />
      -->
      <p><strong>「道後ヘルスビル」</strong>——名前の通り、もしかしたら<strong>健康になれる場所</strong>かもしれません。<br />
          担当者が勝手に考えた健康ポイントをご紹介！
      </p>
      <ul>
        <li class="pa_w10"><strong>歩く距離で足腰強化！</strong><br>道後温泉からの裏道ウォーキングでちょっとした運動に。</li>
        <li class=".pa_w10"><strong>コミュ力アップ！</strong><br>案内所スタッフや嬢との会話で、笑顔と交流の力がUP！</li>
        <li class="pa_w10"><strong>ストレス解消！</strong><br>癒しとドキドキの融合空間で心のデトックス！？</li>
        <li class="pa_w10"><strong>デトックス効果！？</strong><br />何事も溜め込むのは良くない！出すもの出して心も体もスッキリ！</li>
        <li class="pa_w10"><strong>夜型生活にメリハリを！</strong><br>日中は温泉、夜はヘルスで24時間フル活用健康法。</li>
        <li class="pa_w10"><strong>笑いは健康の源！</strong><br>「ここ、本当に健康ビル！？」とツッコミながら元気に。</li>
      </ul>
      <p><em>※明らかな体調不良は医師の診断を受けることをおすすめします！</em></p>
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
