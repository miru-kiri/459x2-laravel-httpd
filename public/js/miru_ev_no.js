window.addEventListener('pageshow', function () {
  document.querySelectorAll('a.none').forEach(link => {
    // 無効化されたリンクを初期化
    if (link.classList.contains('point_none')) {
      link.classList.remove('point_none');
      //link.style.backgroundColor = '';//テスト用に表示
    }

    // すでにイベントがついている場合もあるので、重複を避けるには一度 remove してもいい
    link.addEventListener('click', function handleClick(event) {
      this.classList.add('point_none');
      //this.style.backgroundColor = '#fcc';//テスト用に表示

      // 飛ばないようにする  ※テスト用 効いてるか試す
      //event.preventDefault();
    }, { once: true }); // ← これで何度も追加されない
  });
});

//求人エリアタイトル スクロール固定
  document.addEventListener('DOMContentLoaded', function () {
  let target = document.querySelector('.scroll_target');
  let offsetTop = target ? target.offsetTop : 0;

  function updateTarget() {
    target = document.querySelector('.scroll_target');
    offsetTop = target ? target.offsetTop : 0;
  }

  window.addEventListener('scroll', function () {
    if (!target) return;

    if (window.scrollY >= offsetTop) {
      target.classList.add('scroll_fixed');
    } else {
      target.classList.remove('scroll_fixed');
    }
  });
});
//求人のエリア選択ボタン固定　2025_7_5krn
document.addEventListener('DOMContentLoaded', function () {
  const ala = document.querySelector('.ala');
  const alaLine = document.getElementById('ala_line');

  let lastScrollY = window.scrollY;

  function handleScroll() {
    const currentScrollY = window.scrollY;
    const alaLineTop = alaLine.getBoundingClientRect().top + window.scrollY;
    const windowHeight = window.innerHeight;

    const scrollingDown = currentScrollY > lastScrollY;
    const scrollingUp = currentScrollY < lastScrollY;

    // スクロール位置がページトップなら非表示
    if (currentScrollY === 0) {
      ala.classList.remove('fixed-bottom', 'hidden');
      return;
    }

    // ala_line を画面下で通過したら固定解除・元の位置で表示
    if (currentScrollY + windowHeight > alaLineTop) {
      ala.classList.remove('fixed-bottom', 'hidden');
    } else {
      if (scrollingDown) {
        ala.classList.add('fixed-bottom');
        ala.classList.remove('hidden');
      } else if (scrollingUp) {
        // 上にスクロールしても ala_line より上なら再表示
        if (currentScrollY + windowHeight < alaLineTop) {
          ala.classList.add('fixed-bottom');
          ala.classList.remove('hidden');
        }
      }
    }

    lastScrollY = currentScrollY;
  }

  window.addEventListener('scroll', handleScroll);
});

//求人画像ポップアップ
window.addEventListener('scroll', function () {
    const popup = document.getElementById('recruitPopup');
    const triggerPoint = 10; // スクロール量で調整（px）
    if (window.scrollY > triggerPoint) {
        popup.classList.add('show');
    } else {
        popup.classList.remove('show');
    }
});