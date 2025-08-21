<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        @if(is_array(session('site_control')) && !empty(session('site_control')))
        <button class="btn preview-pc-btn btn-primary">
            <span class="preview-pc-btn-text">プレビュー(PC)</span>
        </button>
        <button class="btn preview-sp-btn btn-primary">
            <span class="preview-sp-btn-text">プレビュー(スマホ)</span>
        </button>
		@endif
        <!-- 予約のタイマー -->
		@if(session('is_admin') == 1)
        <button class="btn reserve-btn">
            <span class="reserve-btn-text">予約管理</span>
            <span class="reserve_fukidashi">
                <span class="reservation_unread_title">仮予約</span>
                <span class="blinking reservation_unread_count"></span>
            </span>
        </button>
		@endif
        <p class="navbar-expand navbar-nav nav-link">{{ session('name') }}様</p>
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
        <a href="{{ url('/admin/logout') }}" class="btn btn-info" role="button">ログアウト</a>
    </ul>

</nav>

<style>
    .reserve-btn {
        background-color: #FF4127;
        color:white;
        /* height: 30px !important; */
        /* margin-top: 8px; */
        padding: 0.35rem 0.3rem !important;
    }
    .reserve-btn-text {
        font-size: 0.8rem;
    }
    span.reserve_fukidashi {
        /* display: inline-block; */
        display: none;
        color: #FF4127;
        background: #fff;
        border-radius: 5px;
        /* width: 74px; */
        width: 94px;
        line-height: 17px;
        text-align: center;
        font-size: 12px;
        margin-left: 1px;
    }
    span.reserve_fukidashi span.reservation_unread_title:not(:first-child){
        margin-left: 5px;
    }
    span.reservation_unread_count {
        display: inline-block;
        font-size: 10px;
        color: white;
        z-index: 1;
        width: 16px;
        height: 16px;
        background-image: url('{{ asset("/img/circle.png?imgopt=y")}}');
        background-repeat: no-repeat;
        background-size: cover;
        margin-left: -2px;
        font-weight: normal;
    }
    .blinking {
        -webkit-animation: blink 0.8s ease-in-out infinite alternate;
        -moz-animation: blink 0.8s ease-in-out infinite alternate;
        animation: blink 0.8s ease-in-out infinite alternate;
    }
    .blinking {
        -webkit-animation: blink 0.8s ease-in-out infinite alternate;
        -moz-animation: blink 0.8s ease-in-out infinite alternate;
        animation: blink 0.8s ease-in-out infinite alternate;
    }
    .preview-pc-btn {
        margin-right: 8px;
        /* height: 30px !important; */
        /* margin-top: 8px; */
        padding: 0.35rem 0.3rem !important;
    }
    .preview-pc-btn-text {
        font-size: 0.8rem;
    }
    .preview-sp-btn {
        margin-right: 8px;
        /* height: 30px !important; */
        /* margin-top: 8px; */
        padding: 0.35rem 0.3rem !important;
    }
    .preview-sp-btn-text {
        font-size: 0.8rem;
    }

    @-webkit-keyframes blink {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }

    @-moz-keyframes blink {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }

    @keyframes blink {
        0% { opacity: 1; }
        100% { opacity: 0; }
    }
</style>


@push('js')
<script>
//定期的に回して予約があったらなんかしらする。
const is_admin = "{{ session('is_admin') }}";
const delay = 10000; //10秒
let current_site_id = 0;
let current_date = "{{ date('Y/m/d') }}";
$(document).ready(function() {
    if(is_admin == 1) {
        getReserveData();
        setInterval(function() {
            getReserveData();
        }, delay);
    }
});
$('.reserve-btn').on('click', function() {
    let redirectUrl = "{{ route('reserve') }}";
    if(current_site_id > 0) {
        redirectUrl = "{!! route('reserve', ['site_id' => '__site_id__','date' => '__date__']) !!}".replace('__site_id__', current_site_id).replace('__date__', current_date);
    }
    location.href = redirectUrl;
});
$('.preview-pc-btn').on('click', function(event) {
    let siteId = "{{ is_array(session('site_control')) && !empty(session('site_control')) ? session('site_control')[0] : 0 }}"
    var url = "{!! route('site.detail.top',['site_id' => '__site_id__' ]) !!}".replace('__site_id__', siteId);
    var width = 1200; // PCの幅
    var height = 667;

    var leftPosition = (screen.width) ? (screen.width - width) : 0;
    var topPosition = (screen.height) ? (screen.height - height) / 4 : 0;

    var windowFeatures = "width=" + width + ",height=" + height + ",top=" + topPosition + ",left=" + leftPosition + ",location=no,toolbar=no,menubar=no";

    window.open(url, "_blank", windowFeatures);
});
$('.preview-sp-btn').on('click', function(event) {
    let siteId = "{{ is_array(session('site_control')) && !empty(session('site_control')) ? session('site_control')[0] : 0 }}"
    var url = "{!! route('site.detail.top',['site_id' => '__site_id__' ]) !!}".replace('__site_id__', siteId);
    var width = 375; // スマートフォンの幅
    var height = 667; // スマートフォンの高さ

    var leftPosition = (screen.width) ? (screen.width - width) / 2 : 0;
    var topPosition = (screen.height) ? (screen.height - height) / 2 : 0;

    var windowFeatures = "width=" + width + ",height=" + height + ",top=" + topPosition + ",left=" + leftPosition + ",location=no,toolbar=no,menubar=no";

    window.open(url, "_blank", windowFeatures);
});
function getReserveData() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        url: "{{ route('reserve.count') }}",
    }).done(function(res) {
        const data = res.data;
        let count = data.length;
        $('.reserve_fukidashi').css('display','none')
        if(count > 0) {
            for (var i = 0; i < count; i++) {
                current_site_id = data[i].site_id_reserve;
                current_date = data[i].date
            }
            $('.reserve_fukidashi').css('display','inline-block')
        }
        $('.blinking').text(count)
    }).fail(function(jqXHR, textStatus, errorThrown) {
        ShowToast('処理結果',"<i class='fas fa-exclamation-triangle'></i>　処理に失敗しました。",'toast_danger');
    });
}
</script>
@endpush