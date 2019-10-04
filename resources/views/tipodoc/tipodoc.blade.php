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
                <button id="perfil_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Perfil</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('PerfilController@save') }}" method="post" id="perfil_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="perfil_id" id="perfil_id"
                                                   @if (isset($perfil)) value="{{$perfil->perfil_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field perfil_nombre required">
                                                    <label>Nombre</label>
                                                    <input id="perfil_nombre" type="text" name="perfil_nombre"
                                                           @if (isset($perfil)) value="{{$perfil->perfil_nombre}}" @endif>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <strong><p style="font-size:1.5em">Modulos</p></strong>
                                                {{--<span class="error modulo_id"></span>--}}
                                                @foreach ($modulosPadre as $modulo)
                                                    <div class="grouped fields">
                                                        @if (count($modulo->hijos) > 0)
                                                            <label>{{$modulo->nombre}}</label>
                                                            @foreach ($modulo->hijos as $hijo)
                                                                <div class="field" style="margin-left:20px;">
                                                                    <div class="ui checkbox">
                                                                        <input type="checkbox"
                                                                               name="modulo_{{ $hijo->idModule }}"
                                                                               {{ $hijo->privilegio }} value="{{ $hijo->idModule }}"
                                                                               id="modulo_{{ $hijo->idModule }}">
                                                                        <label for="modulo_{{ $hijo->idModule }}">{{ $hijo->nombre }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="field">
                                                                <div class="ui checkbox">
                                                                    <input type="checkbox"
                                                                           name="modulo_{{ $modulo->idModule }}"
                                                                           {{ $modulo->privilegio }} value="{{ $modulo->idModule }}"
                                                                           id="modulo_{{ $modulo->idModule }}">
                                                                    <label for="modulo_{{ $modulo->idModule }}">{{ $modulo->nombre }}</label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
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

            var modulesPriv = [];

            @foreach ($modulosPadre as $modulo)
            @if (count($modulo->hijos) > 0)
            @foreach ($modulo->hijos as $hijo)
            @if ($hijo->privilegio != '')
            modulesPriv.push({{ $hijo->idModule }}+'');
            @endif
            $('#modulo_{{ $hijo->idModule }}').click(function (e) {
                id = $("#modulo_{{ $hijo->idModule }}").val();
                if ($('#modulo_{{ $hijo->idModule }}').is(':checked')) {
                    modulesPriv.push(id);
                } else {
                    var pos = modulesPriv.indexOf(id);
                    modulesPriv.splice(pos, 1);
                }
                console.log(modulesPriv)
            });
            @endforeach
            @else
            @if ($modulo->privilegio != '')
            modulesPriv.push({{ $modulo->idModule }}+'');
            @endif
            $('#modulo_{{ $modulo->idModule }}').click(function (e) {
                id = $("#modulo_{{ $modulo->idModule }}").val();
                if ($('#modulo_{{ $modulo->idModule }}').is(':checked')) {
                    modulesPriv.push(id);
                } else {
                    var pos = modulesPriv.indexOf(id);
                    modulesPriv.splice(pos, 1);
                }
                console.log(modulesPriv)
            });
            @endif
            @endforeach

            $('#button_back').click(function (e) {
                window.location.href = "{{ url('perfil/') }}";
            });

            $('#perfil_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('PerfilController@save') }}",
                    data: {
                        perfil_id: $("#perfil_id").val(),
                        perfil_nombre: $("#perfil_nombre").val(),
                        modulesPriv: (modulesPriv.length > 0) ? modulesPriv : null,
                    },
                    type: 'POST',
                    success: function (response) {
                        var data = response;
                        $('.field').removeClass('error');
                        if (response.status == STATUS_FAIL) {
                            toast('error', 1500, data.msg);
                            msg = data.data;
                            if (msg) {
                                $.each(msg, function (k, v) {
                                    $('#perfil_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Perfil y Privilegios Guardados');
                            window.location.href = "{{ url('perfil/') }}";
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
@stop