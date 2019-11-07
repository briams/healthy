<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login</title>

  <link rel="stylesheet" type="text/css" href="{{ asset('semantic/dist/semantic.min.css') }}">
  <!-- Fonts -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&subset=latin" rel="stylesheet"> -->
  <style type="text/css">
    body {
      background-color: #eee;
      -webkit-font-smoothing: antialiased;
      -moz-font-smoothing: grayscale;
    }

    .ui.container {
      margin-top: 6rem;
      max-width: 22rem !important;
    }

    .ui.large.form > .field:first-child {
      margin-bottom: 0;
    }

    .prompt.label.transition{
      display: none !important;
    }
    .prompt.label.transition.visible{
      display: inline-block !important;
    }
  </style>
</head>
<body id="root">

<div class="ui center aligned grid">
  <div class="ui container">
    <h1 class="ui huge header">Log-in</h1>
    <div class="ui large form" >
      {{--{{ csrf_field() }}--}}
      <div class="field">
        <div class="ui input">
          <input name="email" id="email" placeholder="Email address" type="text" />
        </div>
        <div class="ui basic red pointing prompt label transition email">Usuario Invalida</div>
      </div>
      <div class="field">
        <div class="ui input">
          <input name="password" id="password" placeholder="Password" type="password" />
        </div>
        <div class="ui basic red pointing prompt label password transition">Contraseña Invalida</div>
      </div>
      {{--<div class="ui error message" id="login__alert">--}}
      {{--</div>--}}
      <button class="ui fluid large primary button" type="submit" id="btn_login">
        Sign in
      </button>
    </div>
  </div>
</div>
<div class="ui center aligned grid">
    <img src={{ url('/img/perro.png') }} width="" height="" alt="" style="width: 500px;"/>
</div>
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

    $("#email").keyup(function(e){
      e.preventDefault();
      var enter = 13;
      if(e.which == enter)
      {
        $("#password").focus();
      }
    });

    $("#password").keyup(function(e){
      e.preventDefault();
      var enter = 13;
      if(e.which == enter)
      {
        login()
      }
    });

    $('#btn_login').click(function(e){
      e.preventDefault();
      login();
    });

    function login() {
      $('.prompt.label.transition.visible').removeClass('visible');
      $('.field.error').removeClass('error');
      $.ajax({
        url : "{{ url('login') }}",
        // url : "{{ action('MainController@login') }}",
        data : {
          email : $("#email").val(),
          password : $("#password").val()
        },
        type : 'POST',
        success : function(response){
          console.log(response)
          if (response.status == STATUS_OK)
          {
            window.location.reload();
          }
          else if (response.status == STATUS_FAIL)
          {
            if(response.error == 1){
              $("#email").val('');
              $("#email").parent().parent().addClass('error');
              $('.email.prompt.label.transition').addClass('visible');
              $("#email").focus();
            }else{
              $("#password").focus();
            }
            $("#password").val('');
            $("#password").parent().parent().addClass('error');
            $('.password.prompt.label.transition').addClass('visible');
            $(".ui.form").form();
          }
        },
        statusCode : {
          404 : function(){
            alert('Web not found');
          }
        }
      });
    }
  });

</script>
</body>

</html>
