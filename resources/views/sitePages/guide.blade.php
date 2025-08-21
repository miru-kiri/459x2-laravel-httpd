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

<link rel="stylesheet" href="{{ asset('css/site/top.css') }}">
<link rel="stylesheet" href="{{ asset('css/site/guide.css') }}">

@section('content')
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
  <section>
    <div class="container">
       <div id="member">
         <div class="member_inner">
           <div class="member_header">
             <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_header_1118.jpg" alt="無料会員になってコスモ天国ネットを楽しく遊ぼう！">
             <div class="member login_wrap">
               <div class="member_in">
                 <ul class="unauth_wrap">
                   <li><a href="" class="register_link">会員登録（無料）</a></li>
                   <li><a href="" class="login_link">会員の方はコチラ</a></li>
                 </ul>
               </div>
             </div>
           </div>
           <div class="member_content">
             <div class="member_content_ttl">
               <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_content_ttl_1118.png" alt="無料会員だけの特典！">
             </div>
             <ul>
               <li>
                 <div class="image">
                   <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_tokuten6_1118.png" alt="遊んで貯まる♪貯まって遊ぶ♪超お得な「ポイントシステム」">
                 </div>
                 <div class="text">
                   <div class="main">
                     <span>遊んで貯まる♪貯まって遊ぶ♪</span><br />
                     超お得な<span>「ポイントシステム」</span>
                   </div>
                   <div class="sub">
                     コスモグループ全店で利用できる「貯めて、使えう」ことができるポイントシステムです。利用金額に応じてポイントが付与され、いつでも好きなタイミングで使用できちゃうんです。マイページから残高や利用履歴も確認OK！
                   </div>
                 </div>
               </li>
               <li>
                 <div class="image">
                   <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_tokuten1_1118.png" alt="「マイページ」でお気に入りのお店女の子をいつでもチェック♪">
                 </div>
                 <div class="text">
                   <div class="main">
                     <span>「マイページ」</span>でお気に入りのお店<br />
                       女の子をいつでもチェック♪
                   </div>
                   <div class="sub">
                     会員になると利用できる｢マイページ｣でお気に入り登録した女の子やお店を確認できるほか、女の子の出勤情報、即ヒメ、写メ日記、ニュースなどの情報がいつでもチェックできちゃうんです。
                   </div>
                 </div>
               </li>
               <li>
                 <div class="image">
                   <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_tokuten2_1118.png" alt="便利でお得なネット予約が簡単にできます！">
                 </div>
                 <div class="text">
                   <div class="main">
                     便利でお得な<span>ネット予約</span>が<br />
                     簡単にできます！
                   </div>
                   <div class="sub">
                     超簡単手順で電話よりお得に予約できるんです。どこでも手軽に予約ができるので、とっても便利！！女の子の空き状況もひと目で分かっちゃいます♪
                   </div>
                 </div>
               </li>
               <li>
                 <div class="image">
                   <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_tokuten3_1118.png" alt="お気に入りの女の子にメッセージが送れちゃう♥">
                 </div>
                 <div class="text">
                   <div class="main">
                     お気に入りの<span>女の子にメッセージ</span>が<br />
                     送れちゃう♥
                   </div>
                   <div class="sub">
                     なんといっても一番の特典として、お気に入りの女の子にメッセージを送ることができちゃうんです！女の子にとっても反応があるのはやっぱり嬉しいものですよね！女の子から返信が来るなんてことも…❤
                   </div>
                 </div>
               </li>
               <li>
                 <div class="image">
                   <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_tokuten4_1118.png" alt="あなたの生の声が届く口コミ投稿ができます♪">
                 </div>
                 <div class="text">
                   <div class="main">
                     あなたの生の声が届く<br />
                    <span>口コミ投稿</span>ができます♪
                   </div>
                   <div class="sub">
                     口コミ投稿ができるのも会員様だけの限定の特典です。実際に利用したあなたの声でお店が変わるなんてことも！？ユーザー同士の情報交換でさらに充実した利用もできます♪
                   </div>
                 </div>
               </li>
               <li>
                 <div class="image">
                   <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_tokuten5_1118.png" alt="ちょっとした問い合わせなど、気軽にお店へメッセージが送れます！">
                 </div>
                 <div class="text">
                   <div class="main">
                     ちょっとした問い合わせなど、<br />
                     気軽に<span>お店へメッセージ</span>が送れます！
                   </div>
                   <div class="sub">
                     ちょっとしたことや知りたいことがたくさんある時など、電話で聞くのにはちょっと…という方にはオススメです！周りに人がいるシチュエーションでも気軽に聞けちゃいますね。
                   </div>
                 </div>
               </li>
             </ul>
             <div class="member_content_gate">
               <div class="member_content_gate_image">
                 <img src="https://develop.459x.com/storage/guide_img/kaiin_lp_content_gate_1118.png" alt="さぁ、今すぐ無料登録を！！">
               </div>
               <div class="member login_wrap">
                 <div class="member_in">
                   <ul class="unauth_wrap">
                     <li><a href="" class="register_link">会員登録（無料）</a></li>
                     <li><a href="" class="login_link">会員の方はコチラ</a></li>
                   </ul>
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
    </div>
  </section>
@endsection