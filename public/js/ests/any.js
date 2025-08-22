$(function() {
   /************
    * エステサイト用
    * 
    * mrt2025-07-19
    * laravel用設定
    */
   //ajax laravel用に初期設定
   $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $("[name='csrf-token']").attr("content") },
   })

   //ajax
   $(document).on('click', '.kirino' , function(){
      //ページ内のなにか取得したいことはここに書く
      var kiri_val = $(this).text();
      //通信開始
      $.ajax({
         //問い合わせURL
         url: "/ests/post/ctl",
         //問い合わせ方式
         method: "POST",
         data: {
            //ここには送信したいデータを配列で書く
             'kirino' : '勉強中'
            ,'test' : kiri_val 

         },
         //やりとりの形式
         dataType: "json",
      }).done(function(res){
         //送信取得完了
         console.log(res);
         //$('.est_top_ttl').html(res);
         //bodyの最後に追加
         $('body').append(res);
         //アニメーション
         $(".yokokara").animate({left: "0%"}, 500);
      }).fail(function(){
         //エラーが起きたとき
         alert('ざぁんねん通信失敗だよ');
      });
   });
   //close
   $(document).on('click', '#close' , function(){
      console.log('おされたし');
      //css 初期値に戻して
      $('.yokokara').css('left','');
      //要素を削除
      $('.yokokara').remove();
   });
});

//ハンバーガーメニュー
const hamburger = document.getElementById('hamburger');
const menu = document.getElementById('menu');
const closeBtn = document.getElementById('closebtn');

// ハンバーガー押したら開閉
hamburger.addEventListener('click', () => {
  menu.classList.toggle('active');
});

// ×ボタンで閉じる
closeBtn.addEventListener('click', () => {
  menu.classList.remove('active');
});

// 画面サイズが変わったら自動で閉じる（例: 769px以上になったら閉じる）
window.addEventListener('resize', () => {
  if (window.innerWidth > 768) {
    menu.classList.remove('active');
  }
});