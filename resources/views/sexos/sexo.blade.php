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
                <button id="sexo_save" class="ui button primary compact">
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
                    <a class="active item" data-tab="first"> Sexos </a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('SexoController@save') }}" method="post" id="sexo_ficha_registro" class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        {{--<div class="content">--}}
                                            {{--<div class="header">Usuarios</div>--}}
                                        {{--</div>--}}
                                        <div class="content">
                                            <input type="hidden" name="sexo_id" id="sexo_id"  @if (isset($sexo)) value="{{$sexo->sexo_id}}" @endif>
                                            <div class="fields">
                                                <div class="six wide field required sexo_nombre">
                                                    <label>Nombre</label>
                                                    <input type="text" name="sexo_nombre" id="sexo_nombre" placeholder="Nombre"  @if (isset($sexo)) value="{{$sexo->sexo_nombre}}" @endif>
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
                window.location.href = "{{ url('sexo/') }}";
            });

            $('#sexo_save').click(function(e){
                e.preventDefault();
                $.ajax({
                    url : "{{ action('SexoController@save') }}",
                    data : {
                        sexo_id : $("#sexo_id").val(),
                        sexo_nombre : $("#sexo_nombre").val(),
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
                                    $('#sexo_ficha_registro .' + k).addClass('error');
                                    if(k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        }else if (response.status == STATUS_OK) {
                            toast('success',3000,'Sexo Guardada');
                            window.location.href = "{{ url('sexo/') }}";
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