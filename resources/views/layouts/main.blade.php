
<!DOCTYPE html>
<html lang="en">
<head>
    <title>HEALTHY ADMIN</title>
    <meta charset="utf-8" />
    <meta name="google" content="notranslate">
    <meta name=viewport content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css') }}">

    <style>
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
style="-webkit-transition-duration: 0.1s; transition-duration: 0.1s;" data-color="grey">

  <a class="item logo" href="/">ERP</a>
  <!-- MENU -->
  <div class="ui accordion inverted">
    <!-- <div class="title item">
      <a href="/#dashboard">
      <i class="ion-speedometer titleIcon icon"></i> <span class="colhidden">DASHBOARD</span>
      </a>
    </div> -->
    @foreach ($modulos as $modulo)
    <!-- {% for modulo in modulosPadre %} -->
    @if (count($modulo->hijos) > 0)
    <!-- {% if modulo.mod_is_parent == 1 %} -->
    <!-- menu con dropdown-->
    <div class="title item">
      <i class="{{$modulo->icono}} titleIcon icon"></i>
      <i class="dropdown icon"></i> {{$modulo->nombre}}
    </div>
    <div class="content">
      <!-- {% for modh in modulo.hijos %} -->
      @foreach ($modulo->hijos as $hijo)
      <a class="item app_nav__module_item" href="/{{$hijo->url}}">{{$hijo->nombre}}</a>
      <!-- {% endfor %} -->
      @endforeach
    </div>
    @else
    <!-- {% else %} -->
    <!-- menu sin dropdown-->
    <div class="title item">
      <a href="/{{$modulo->url}}">
      <i class="{{$modulo->icono}} titleIcon icon"></i> <span class="colhidden">{{ $modulo->nombre }}</span>
      </a>
    </div>
    @endif
    <!-- {%endif%} -->
    <!-- {%endfor%} -->
    @endforeach
  </div>
  </div>
  </div>
  <!-- jQuery -->
  <script src="{{ URL::asset('js/jquery.js') }}" charset="utf-8"></script>
  <script src="{{ asset('semantic/dist/semantic.min.js') }}"></script>
    <!-- application -->
    <script src="{{ URL::asset('js/app.js') }}" charset="utf-8"></script>

  <script type="text/javascript">
    $(document).ready(function(){

  $.ajaxSetup({
      headers : {
          'X-CSRF-TOKEN' : $('meta[name = csrf-token]').attr('content')
      }
  });

      $('#app_logoff').click(function(e){
        e.preventDefault();
        $.ajax({
          url : "{{ url('logout') }}",
            data : {
            },
            type : 'POST',
            success : function(response){
              console.log(response)
                if (response.status == STATUS_OK)
                {
                    // window.location.reload();
                    window.location.href="{{ url('/') }}";
                }
            },
            statusCode : {
                404 : function(){
                    alert('Web not found');
                }
            }
        });
      });
    });

  </script>


</body>
</html>
