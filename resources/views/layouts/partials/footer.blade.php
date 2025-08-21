<footer>
    <div class="sp footer-btn">
      @if(request()->query('site_id'))
        @if($shops->tel)
        <div class="tel-content">
          <div class="text-center py-2">
            <a href="tel:{{$shops->tel}}" class="btn rounded-pill tel-btn"><span style="color:white"><i class="fas fa-phone-alt mr-1"></i>電話番号でお問い合わせ</span></a>
          </div>
        </div>
        @endif
      @endif
      <div class="d-flex bg-light  mb-3">
      @if(session()->has('user_id'))
        <!-- <a href="{{ route('site') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 btn-sm">
          <i class="fas fa-home"></i><small>ホーム</small>
        </a> -->
        <a href="{{ route('mypage.top') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 btn-sm">
          <i class="fas fa-user"></i><small>会員画面</small>
        </a>
        <a class="btn btn-app m-0 flex-fill pt-2 qr_btn">
          <i class="fas fa-qrcode"></i><small class="text-nowrap">QR</small>
        </a>
        @if(request()->query('site_id'))
          <a href="{{ route('mypage.reserve.course',['site_id' => request()->query('site_id')]) }}" class="btn btn-app m-0 flex-fill pt-2 px-0 login_btn btn-sm">
            <i class="fas fa-calendar-alt"></i><small class="text-nowrap">web予約</small>
          </a>
          <button class="btn btn-app m-0 flex-fill px-0 btn-sm like_btn">
            <i class="fas fa-heart"></i><small class="text-nowrap">お気に入り</small>
          </button>
        @endif
        <a href="{{ route('mypage.logout') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 btn-sm">
          <i class="fas fa-sign-out-alt"></i><small class="text-nowrap">ログアウト</small>
        </a>
      @else
        <a href="{{ route('site') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 btn-sm">
          <i class="fas fa-home"></i><small>ホーム</small>
        </a>
        <a href="{{ route('mypage.signup') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 sign_btn btn-sm">
          <i class="fas fa-user"></i><small>会員登録</small>
        </a>
        <a href="{{ route('mypage.login') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 login_btn btn-sm">
          <!-- <i class="fas fa-sign-in-alt"></i><small class="text-nowrap">ログイン</small> -->
          <i class="fas fa-sign-in-alt"></i><small >ログイン</small>
        </a>
      @endif
        <a href="{{ route('mypage.contact') }}" class="btn btn-app m-0 flex-fill pt-2 px-0 contact_btn btn-sm">
          <i class="fas fa-envelope"></i><small >お問い合わせ</small>
        </a>
      </div> 
      </div>
     <section class="footer_nav">
        <div class="container inner">
           <div class="box">
              @if('gnr' == $kio_gen)
              <nav class="sexycaba_nav">
                 <h3><a href="https://459x.com/detail?genre_id=4" title="愛媛県松山市で人気・おすすめのセクキャバ店をまとめてご紹介｜コスモ天国ネット">セクキャバで遊ぶならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=4&area_id=14" title="愛媛県松山市内で人気・おすすめのセクキャバ店をご紹介｜コスモ天国ネット">松山市内のセクキャバで楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="caba_nav">
                 <h3><a href="https://459x.com/detail?genre_id=3" title="愛媛県四国中央市で人気・おすすめのキャバクラ店をまとめてご紹介｜コスモ天国ネット">キャバクラで遊ぶならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=4&area_id=14" title="四国中央市で人気・おすすめのキャバクラ店をご紹介｜コスモ天国ネット">四国中央市のキャバクラで楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="menes_nav">
                 <h3><a href="https://459x.com/detail?genre_id=2" title="愛媛県で人気・おすすめのメンズエステ店をまとめてご紹介｜コスモ天国ネット">メンズエステで癒されるならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=2&area_id=14" title="愛媛県松山市内で人気・おすすめのメンズエステ店をご紹介｜コスモ天国ネット">松山市内のメンズエステで癒されよう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=2&area_id=5" title="愛媛県新居浜市で人気・おすすめのメンズエステ店をご紹介｜コスモ天国ネット">新居浜市のメンズエステで癒されよう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=2&area_id=6" title="愛媛県四国中央市で人気・おすすめのメンズエステ店をご紹介｜コスモ天国ネット">四国中央市のメンズエステで癒されよう</a></li>
                  </ul>
              </nav>
              <nav class="enkai_nav">
                 <h3><a href="https://459x.com/detail?genre_id=6" title="愛媛県松山市道後で人気・おすすめの宴会コンパニオン店をまとめてご紹介｜コスモ天国ネット">道後温泉で宴会をお探しの方はこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=6&area_id=13" title="松山市道後歌舞伎通りで人気・おすすめの宴会コンパニオン店をご紹介｜コスモ天国ネット">道後歌舞伎通りの宴会で楽しもう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=6&area_id=14" title="愛媛県松山市内で人気・おすすめの宴会コンパニオン店をご紹介｜コスモ天国ネット">松山市内の宴会で楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="eat_nav">
                 <h3><a href="https://459x.com/detail?genre_id=5" title="愛媛県松山市で人気・おすすめの飲食店をまとめてご紹介｜コスモ天国ネット">飲食店をお探しならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=5&area_id=14" title="愛媛県松山市内で人気・おすすめの飲食店をご紹介｜コスモ天国ネット">松山市内の飲食店でお腹を満たそう</a></li>
                  </ul>
              </nav>
              <nav class="eat_nav">
                 <h3><a href="https://459x.com/" title="四国最大級のおすすめ優良店まとめサイト|コスモ天国ネット">天国ネットで探そう！</a></h3>
              </nav>
              @else
              <nav class="fuzoku_nav">
                 <h3><a href="https://459x.com/detail?genre_id=1" title="愛媛県香川県で人気・おすすめの風俗店をまとめてご紹介｜コスモ天国ネット">ソープ・ファッションヘルスで遊ぶならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=1&area_id=13" title="松山市道後歌舞伎通りで人気・おすすめの風俗店をご紹介｜コスモ天国ネット">道後歌舞伎通りのソープ・ヘルスで楽しもう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=1&area_id=11" title="高松城東町で人気・おすすめの風俗店をご紹介｜コスモ天国ネット">高松市城東町のソープ・ヘルスで楽しもう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=1&area_id=10" title="善通寺・琴平で人気・おすすめの風俗店をご紹介｜コスモ天国ネット">善通寺・琴平町のソープ・ヘルスで楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="sexycaba_nav">
                 <h3><a href="https://459x.com/detail?genre_id=4" title="愛媛県松山市で人気・おすすめのセクキャバ店をまとめてご紹介｜コスモ天国ネット">セクキャバで遊ぶならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=4&area_id=14" title="愛媛県松山市内で人気・おすすめのセクキャバ店をご紹介｜コスモ天国ネット">松山市内のセクキャバで楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="caba_nav">
                 <h3><a href="https://459x.com/detail?genre_id=3" title="愛媛県四国中央市で人気・おすすめのキャバクラ店をまとめてご紹介｜コスモ天国ネット">キャバクラで遊ぶならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=4&area_id=14" title="四国中央市で人気・おすすめのキャバクラ店をご紹介｜コスモ天国ネット">四国中央市のキャバクラで楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="menes_nav">
                 <h3><a href="https://459x.com/detail?genre_id=2" title="愛媛県で人気・おすすめのメンズエステ店をまとめてご紹介｜コスモ天国ネット">メンズエステで癒されるならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=2&area_id=14" title="愛媛県松山市内で人気・おすすめのメンズエステ店をご紹介｜コスモ天国ネット">松山市内のメンズエステで癒されよう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=2&area_id=5" title="愛媛県新居浜市で人気・おすすめのメンズエステ店をご紹介｜コスモ天国ネット">新居浜市のメンズエステで癒されよう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=2&area_id=6" title="愛媛県四国中央市で人気・おすすめのメンズエステ店をご紹介｜コスモ天国ネット">四国中央市のメンズエステで癒されよう</a></li>
                  </ul>
              </nav>
              <nav class="enkai_nav">
                 <h3><a href="https://459x.com/detail?genre_id=6" title="愛媛県松山市道後で人気・おすすめの宴会コンパニオン店をまとめてご紹介｜コスモ天国ネット">道後温泉で宴会をお探しの方はこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=6&area_id=13" title="松山市道後歌舞伎通りで人気・おすすめの宴会コンパニオン店をご紹介｜コスモ天国ネット">道後歌舞伎通りの宴会で楽しもう</a></li>
                     <li><a href="https://459x.com/detail/area?genre_id=6&area_id=14" title="愛媛県松山市内で人気・おすすめの宴会コンパニオン店をご紹介｜コスモ天国ネット">松山市内の宴会で楽しもう</a></li>
                  </ul>
              </nav>
              <nav class="eat_nav">
                 <h3><a href="https://459x.com/detail?genre_id=5" title="愛媛県松山市で人気・おすすめの飲食店をまとめてご紹介｜コスモ天国ネット">飲食店をお探しならこちら</a></h3>
                  <ul>
                     <li><a href="https://459x.com/detail/area?genre_id=5&area_id=14" title="愛媛県松山市内で人気・おすすめの飲食店をご紹介｜コスモ天国ネット">松山市内の飲食店でお腹を満たそう</a></li>
                  </ul>
              </nav>
              <nav class="eat_nav">
                 <h3><a href="https://459x.com/" title="四国最大級のおすすめ優良店まとめサイト|コスモ天国ネット">天国ネットで探そう！</a></h3>
              </nav>
              @endif 
           </div>
        </div>
      </section>
      <p class="footer-copyright">
        Copyright(C)2023 <a href="https://459x.com/" style="color: #fff; text-decoration: none;" title="愛媛県と香川県で大人気・おすすめのソープ・ヘルス・メンズエステ・キャバクラ・飲食店をまとめてご紹介｜コスモ天国ネット">コスモ天国ネット</a> All Rights Reserved
      </p>
  </footer>