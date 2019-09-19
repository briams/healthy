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

</head>
<body>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui teal image header">
      <!-- <img src="assets/images/logo.png" class="image"> -->
      <div class="content">
        Log-in to your account
      </div>
    </h2>
    <form class="ui large form" action="{{ action('MainController@login') }}" method="POST" style="text-align: -webkit-center;">
      {{ csrf_field() }}
      <div class="ui stacked segment" style="width: 40%;">
        <div class="field email">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" id="email" placeholder="E-mail address">
          </div>
        </div>
        <div class="field password">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" id="password" placeholder="Password">
          </div>
        </div>
        <button class="ui fluid large teal submit button" id="btn_login">Login</button>
      </div>

      <div class="ui error message" id="login__alert" style="width: 40%;">
      </div>

    </form>

    <!-- <div class="ui message">
      New to us? <a href="#">Sign Up</a>
    </div> -->
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

    $('#login__alert').hide();

    $('#btn_login').click(function(e){
      e.preventDefault();
      $('#login__alert').hide();
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
                $("#login__alert").html(response.msg);
                if(response.error == 1){
                  $("#email").val('');
                  $("#email").focus();
                }else{
                  $("#password").focus();
                }
                $("#password").val('');
                $('#login__alert').show();
              }
          },
          statusCode : {
              404 : function(){
                  alert('Web not found');
              }
          }
      });
      // e.preventDefault();
      // var email = $("#email").val();
      // var password = $("#password").val();
      // $.post("{{ action('MainController@login') }}", { email: email, password: password } , function(data) {
      //   	console.log(data)
      // });
    });
  });

</script>

</body>

</html>
