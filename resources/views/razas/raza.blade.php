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
                <button id="raza_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
      {{--      {% if registro != '' %}
            <div class="item ui colhidden">
                <h3 style="text-transform: uppercase;">{{registro.fv_documento}} | {{registro.fv_full_name}}</h3>
            </div>
            {% endif %}--}}
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first"> Razas </a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('RazaController@save') }}" method="post" id="raza_ficha_registro" class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        {{--<div class="content">--}}
                                            {{--<div class="header">Usuarios</div>--}}
                                        {{--</div>--}}
                                        <div class="content">
                                            <input type="hidden" name="raza_id" id="raza_id"  @if (isset($raza)) value="{{ $raza->raza_id }}" @endif>
                                            <div class="fields">
                                                <div class="six wide field required raza_nombre">
                                                    <label>Nombre</label>
                                                    <input type="text" name="raza_nombre" id="raza_nombre" placeholder="Nombre"  @if (isset($raza)) value="{{$raza->raza_nombre}}" @endif>
                                                </div>
                                                <div class="six wide field raza_especie_id" >
                                                    @if (count($especies) > 0)
                                                    <label>Modulo Padre</label>
                                                    <select class="ui search dropdown" id="raza_especie_id" name="raza_especie_id">
                                                        <option value="">Seleccione Especie</option>
                                                        @foreach ($especies as $especie)
                                                        <option value="{{$especie->especie_id}}" @if (isset($raza)) @if ($especie->especie_id == $raza->raza_especie_id ) selected @endif @endif >{{ $especie->especie_nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
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
                window.location.href = "{{ url('raza/') }}";
            });

            $("#raza_especie_id").dropdown({
                fullTextSearch:true
            });

            $('#raza_save').click(function(e){
                e.preventDefault();
                $.ajax({
                    url : "{{ action('RazaController@save') }}",
                    data : {
                        raza_id	 : $("#raza_id").val(),
                        raza_nombre : $("#raza_nombre").val(),
                        raza_especie_id : $("#raza_especie_id").val(),
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
                                    $('#raza_ficha_registro .' + k).addClass('error');
                                    if(k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        }else if (response.status == STATUS_OK) {
                            toast('success',3000,'Raza Guardada');
                            window.location.href = "{{ url('raza/') }}";
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