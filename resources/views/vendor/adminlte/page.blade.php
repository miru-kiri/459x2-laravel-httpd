@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation --}}
        @if($layoutHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="mailCreateModal" tabindex="-1" role="dialog" aria-labelledby="mailCreateModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="mailCreateModalLabel">メールアドレスの発行</h5>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body">
            システム変更につき、メールアドレスを再発行する必要があります。<br>
            「メールアドレスを発行する」というボタンをクリックし、メールアドレスを発行してください。
        </div>
        <div class="modal-footer">
            <form method="post" action="{{ route('cast.mail.create') }}">
                @csrf
                <input type="hidden" name="cast_id" value="{{ session('id') }}">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="submit" class="btn btn-primary" id="create_mail_submit">メールアドレスを発行する</button>
            </form>
        </div>
        </div>
    </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        const is_mail_create = '{{ session("is_mail_create") }}';
        $(document).ready(function() {
            if(is_mail_create == 0) {
                $('#mailCreateModal').modal('show')
            }
        })
        $('#create_mail_submit').on('click',function() {
            $('#mailCreateModal').modal('hide')
            $("#overlay").fadeIn(300);  
        })
        $('#back-to-top').on('click',function() {
            window.scrollTo(0, 0);
        })
    </script>
@stop
