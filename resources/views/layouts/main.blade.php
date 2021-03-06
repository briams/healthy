{{--<!DOCTYPE html>--}}
<html lang="en">
<head>
    <title>HEALTHY PETS</title>
    <meta charset="utf-8"/>
    <meta name="google" content="notranslate">
    <meta name=viewport content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ url('/img/favicon.png') }}" sizes="40x40">

    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('kendoui/styles/kendo.common-material.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('toast/jquery.toast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('perfect-scrollbar/css/perfect-scrollbar.css') }}">

    {{--<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/main.css?217e6ea996') }}">--}}

</head>
<body style="width:100%; overflow-y: hidden;">
<div id="contextWrap">
    <div id="app_header" class="ui menu">
        <div class="item ui colhidden" style="
             padding-right: 5px;
             padding-left: 5px;
             ">
            <button id="btn_menu" class="ui button medium basic white inverted icon" style=""><i class="icon sidebar"></i>
            </button>
        </div>
        <div class="item ui colhidden" style="background-position: center;place-content: center;">
            <img src={{ url('/img/logo.png') }} width="" height="" alt="" style="width: 120px; height: 50px;"/>
        </div>
        <div class="right menu colhidden">
            <a class="item labeled rightsidebar computer only" id="app_logoff">
                <i class="sign-out white-text large icon"></i>
            </a>
        </div>
        {{--<div class="right item ui colhidden">--}}
            {{--<button id="app_logoff" class="ui button big white inverted icon" style=""><i class="icon  sign-out"></i>--}}
            {{--</button>--}}
        {{--</div>--}}
    </div>
    <!--sidebar-->
    <div class="ui sidebar vertical left menu overlay  borderless visible sidemenu inverted"
         style="-webkit-transition-duration: 0.1s; transition-duration: 0.1s; top: 50px !important;transition: width .5s ease 0s;"
         data-color="grey">
        <!-- MENU -->
        <div class="ui accordion inverted">
            <div class="content">
                <a class="item logo titleIcon" href="{{ url("/") }}" style="height: 39.28px;"><i class="home icon"></i><span class="itemmenu">Veterinaria</span></a>
            </div>
        @foreach ($modulos as $modulo)
            @if (count($modulo->hijos) > 0)
                <!-- menu con dropdown-->
                    <div class="title item logo ui left icon" style="height: 39.28px;">
                        <i class="{{$modulo->icono}} titleIcon icon"></i>
                        <i class="dropdown icon itemmenu"></i> <span class="itemmenu">{{$modulo->nombre}}</span>
                    </div>
                    <div class="content">
                        @foreach ($modulo->hijos as $hijo)
                            <a class="item app_nav__module_item" href="{{ url($hijo->url) }}">{{$hijo->nombre}}</a>
                        @endforeach
                    </div>
            @else
                <!-- menu sin dropdown-->
                    <div class="title item">
                        <a href=" {{ url($modulo->url) }}">
                            <i class="{{$modulo->icono}} titleIcon icon"></i>
                            <span class="colhidden">{{ $modulo->nombre }}</span>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div id="div_dad_container" style="margin-left: 192px;height: 100vh;transition: margin-left .5s ease 0s;">
        <div class="pusher" id="app__container_body">
            <div style="overflow-x: scroll;height: 100vh;">
                @yield('content')
            </div>
        </div>
        <div id="content-model">

        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ URL::asset('js/jquery.js') }}" charset="utf-8"></script>
<script src="{{ URL::asset('js/jquery-print-area.js') }}"></script>
{{--<script src="{{ URL::asset('js/jquery.fileDownload.js') }}"></script>--}}
<script src="{{ URL::asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('semantic/dist/semantic.min.js') }}"></script>
<script src="{{ asset('perfect-scrollbar/js/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('kendoui/js/kendo.all.min.js') }}"></script>
<script src="{{ asset('flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('toast/jquery.toast.min.js') }}"></script>

<!-- application -->
<script src="{{ URL::asset('js/app.js') }}" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function () {

        resizeScreen();

        $('.ui.accordion').accordion();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name = csrf-token]').attr('content')
            }
        });

        $('#btn_menu').click(function (e) {
            menuDinamico();
        });

        $('#app_logoff').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('logout') }}",
                data: {},
                type: 'POST',
                success: function (response) {
                    console.log(response)
                    if (response.status == STATUS_OK) {
                        // window.location.reload();
                        window.location.href = "{{ url('/') }}";
                    }
                },
                statusCode: {
                    404: function () {
                        alert('Web not found');
                    }
                }
            });
        });



    });
</script>
@yield('scripts')

<div class="ui active inverted dimmer app__loader" style="display:none;">
    <div class="ui massive text loader">...</div>
</div>

</body>
</html>
