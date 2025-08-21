<header>
    <!-- USER_HEADER: {{ session('user_id') }} -->
    @if(!empty($recruit_title ?? null))
      <h1 class="recruit_title">{{$recruit_title}}</h1>
    @endif
     <div class="pc mx-3">
         <a href="{{ route('site') }}" class="logo_umi">
           <img src="{{ asset('img/2_tenkochan_summer_0.png') }}"  class="wave_img">
           <img class="logo" src="{{ asset('img/07_0630.png') }}" alt="コスモ天国ネット" style="margin-left: 20px;" />
         </a>
         <div style="text-align: center;">
           <a href="https://459x.dogo459.com/recruit_top">
              <img src="{{ asset('img/cast_recruit_tyouhou.jpg') }}" style="width: 100%; max-width:500px;">
           </a>
         </div>
         <div>
         <!-- <button type="button" class="btn btn-secondary rounded-pill serch-button"><small>条件で検索</small> <i class="fas fa-search" style="color: #ffffff"></i></button> -->
         @if(session()->has('user_id'))
           <button class="btn menu-button rounded-0 mypage_btn" style="background: #016DB7">
             <i class="fas fa-home" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">会員ページ</p>
           </button>
           <button class="btn menu-button rounded-0 logout_btn" style="background: #EC1070">
             <i class="fas fa-sign-out-alt" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">ログアウト</p>
           </button>
           @if(request()->query('site_id'))
           <button class="btn menu-button rounded-0 reserve_btn" style="background: #F1747E">
             <i class="fas fa-calendar-alt" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">Web予約</p>
           </button>
           <button class="btn menu-button rounded-0 like_btn" style="background: #F7BD2D">
             <i class="fas fa-heart" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">お気に入り</p>
           </button>
           <input type="hidden" id="csrf_token_hidden" value="{{ csrf_token() }}">
           @endif
         @else
           <button class="btn menu-button rounded-0 sign_btn" style="background: #EE5318">
             <i class="fas fa-home" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">会員登録</p>
           </button>
           <button class="btn menu-button rounded-0 login_btn" style="background: #EC1070">
             <i class="fas fa-sign-in-alt" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">ログイン</p>
           </button>
         @endif
           <button class="btn menu-button rounded-0 contact_btn" style="background: #BF0D28">
             <i class="fas fa-envelope" style="color: #ffffff"></i> <!-- Font Awesomeのアイコン -->
             <p class="text-white menu-text">お問い合わせ</p>
           </button>
         </div>
         <!-- <nav>
           <ul>
             <li><a href="#">メニュー2</a></li>
             <li><a href="#">メニュー3</a></li>
           </ul>
         </nav> -->
     </div>
     <div class="sp  mb-3 dis_flex">
       <a href="{{ route('site') }}">
         <img src="{{ asset('img/2_tenkochan_summer_0.png') }}" class="wave_img" style="max-width: 80px;">
         <img class="logo mt-3" src="{{ asset('img/07_0630.png') }}" alt="ロゴ" style="margin-left: 20px;">
       </a>
         <div style="text-align: center; padding-right: 60px;">
           <a href="https://459x.dogo459.com/recruit_top">
              <img src="{{ asset('img/cast_recruit_sikaku.jpg') }}" style="width: 80%; max-width:100px;">
           </a>
         </div>
       <div class="hamburger">
         <span></span>
         <span></span>
         <span></span>
       </div>
       <nav class="globalMenuSp">
         <div style="padding-top: 50px; text-align: center;">
           <img src="{{ asset('img/cast_recruit_tyouhou.jpg') }}" style="width: 80%;">
         </div>
         @yield('sp-nav')
       </nav>
     </div>
     <!-- <div class="hamburger">☰</div> -->
   </header>