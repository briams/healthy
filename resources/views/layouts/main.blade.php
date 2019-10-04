{{--<!DOCTYPE html>--}}
<html lang="en">
<head>
    <title>HEALTHY ADMIN</title>
    <meta charset="utf-8"/>
    <meta name="google" content="notranslate">
    <meta name=viewport content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('kendoui/styles/kendo.common-material.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('toast/jquery.toast.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/layout.css') }}">

    {{--<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/main.css?217e6ea996') }}">--}}

    <style>
        #app__container_body {
            margin-left: 270px;
        }
    </style>
</head>
<body style="width:100%;">
<div id="contextWrap">
    <div id="app_header" class="ui menu">
        <div class="menu colhidden">
            <H1>Healthy</H1>
        </div>
        <div class="right menu colhidden">
            <a class="item labeled rightsidebar computer only" id="app_logoff">
                <i class="sign-out white-text large icon"></i>
            </a>
        </div>
    </div>
    <!--sidebar-->
    <div class="ui sidebar vertical left menu overlay  borderless visible sidemenu inverted"
         style="-webkit-transition-duration: 0.1s; transition-duration: 0.1s; margin-top: 48px !important;"
         data-color="grey">
        <!-- MENU -->
        <div class="ui accordion inverted">
            <div class="content">
                <a class="item logo titleIcon" href="{{ url("/") }}"><i class="home icon"></i>Veterinaria</a>
            </div>
        @foreach ($modulos as $modulo)
            @if (count($modulo->hijos) > 0)
                <!-- menu con dropdown-->
                    <div class="title item logo ui left icon">
                        <i class="{{$modulo->icono}} titleIcon icon"></i>
                        <i class="dropdown icon"></i> {{$modulo->nombre}}
                    </div>
                    <div class="content">
                        @foreach ($modulo->hijos as $hijo)
                            <a class="item app_nav__module_item" href="{{ url($hijo->url) }}">{{$hijo->nombre}}</a>
                        @endforeach
                    </div>
            @else
                <!-- menu sin dropdown-->
                    <div class="title item">
                        <a href=" /{{ url($modulo->url) }}">
                            <i class="{{$modulo->icono}} titleIcon icon"></i>
                            <span class="colhidden">{{ $modulo->nombre }}</span>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="pusher" id="app__container_body">
        @yield('content')
    </div>
</div>
<!-- jQuery -->
<script src="{{ URL::asset('js/jquery.js') }}" charset="utf-8"></script>
<script src="{{ asset('semantic/dist/semantic.min.js') }}"></script>
<script src="{{ asset('kendoui/js/kendo.all.min.js') }}"></script>
<script src="{{ asset('flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('toast/jquery.toast.min.js') }}"></script>

<!-- application -->
<script src="{{ URL::asset('js/app.js') }}" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.ui.accordion').accordion();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name = csrf-token]').attr('content')
            }
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
