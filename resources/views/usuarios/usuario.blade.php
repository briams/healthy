@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless" data-color="inverted white">
            <div class="item ui colhidden">
                <button id="button_back" class="ui button compact icon">
                    <i class="icon arrow left"></i>
                </button>
            </div>
            <div class="item ui colhidden">
                <button id="user_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
            <div class="item ui colhidden">
                <h3 style="text-transform: uppercase;">@if (isset($nombre)) {{ $nombre }} @endif @if (isset($strtipo)) | {{ $strtipo }} @endif</h3>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first"> Usuarios </a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('UsuarioController@save') }}" method="post" id="usuario_ficha_registro" class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        {{--<div class="content">--}}
                                            {{--<div class="header">Usuarios</div>--}}
                                        {{--</div>--}}
                                        <div class="content">
                                            <input type="hidden" name="idUsuario" id="idUsuario"  @if (isset($user)) value="{{$user->idUsuario}}" @endif>
                                            <input type="hidden" name="tipo" id="tipo"  @if (isset($tipo)) value="{{$tipo}}" @endif>
                                            <div class="fields">
                                                <div class="six wide field required email">
                                                    <label>E-mail</label>
                                                    <input type="email" name="email" id="email" placeholder="Email" @if (isset($user)) value="{{$user->email}}" @endif>
                                                </div>
                                                <div class="six wide field usuario_perfil_id" >
                                                    @if (count($perfiles) > 0)
                                                    <label>Perfil</label>
                                                    <select class="ui search dropdown" id="usuario_perfil_id" name="usuario_perfil_id">
                                                        <option value="">Seleccione Perfil</option>
                                                        @foreach ($perfiles as $perfil)
                                                        <option value="{{$perfil->perfil_id}}" @if (isset($user)) @if ($perfil->perfil_id == $user->usuario_perfil_id ) selected @endif @endif >{{ $perfil->perfil_nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="six wide field required password">
                                                    <label>New Password</label>
                                                    <input type="password" name="password" id="password" placeholder="New Password" >
                                                </div>
                                                <div class="six wide field required password_validate">
                                                    <label>Duplicate New Password</label>
                                                    <input type="password" name="password_validate" id="password_validate" placeholder="Duplicate New Password" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#button_back').click(function (e) {
                window.location.href = "{{ url('usuarios/') }}";
            });

            $("#usuario_perfil_id").dropdown({
                fullTextSearch:true
            });

            $('#user_save').click(function(e){
                e.preventDefault();
                $.ajax({
                    url : "{{ action('UsuarioController@save') }}",
                    data : {
                        idUsuario : $("#idUsuario").val(),
                        tipo : $("#tipo").val(),
                        email : $("#email").val(),
                        usuario_perfil_id : $("#usuario_perfil_id").val(),
                        password : $("#password").val(),
                        password_validate : $("#password_validate").val(),
                    },
                    type : 'POST',
                    success : function(response){
                        var data = response;
                        $('.field').removeClass('error');
                        if (response.status == STATUS_FAIL) {
                            toast('error', 1500, data.msg );
                            msg = data.data;
                            if (msg) {
                                $.each(msg, function (k, v) {
                                    $('#usuario_ficha_registro .' + k).addClass('error');
                                    if(k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        }else if (response.status == STATUS_OK) {
                            toast('success',3000,'Usuario Guardado');
                            window.location.href = "{{ url('usuarios/') }}";
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

@stop