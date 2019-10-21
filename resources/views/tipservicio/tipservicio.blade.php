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
                <button id="tipserv_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Tipo Servicio</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('TipoServicioController@save') }}" method="post" id="servtip_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="servtip_id" id="servtip_id"
                                                   @if (isset($tipoServicio)) value="{{$tipoServicio->servtip_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field servtip_nombre required">
                                                    <label>Nombre</label>
                                                    <input id="servtip_nombre" type="text" name="servtip_nombre"
                                                           @if (isset($tipoServicio)) value="{{$tipoServicio->servtip_nombre}}" @endif>
                                                </div>
                                                <div class="eight wide field servtip_descripcion required">
                                                    <label>Descripcion</label>
                                                    <input id="servtip_descripcion" type="text" name="servtip_descripcion"
                                                           @if (isset($tipoServicio)) value="{{$tipoServicio->servtip_descripcion}}" @endif>
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
                window.location.href = "{{ url('tipo-servicio/') }}";
            });

            $('#tipserv_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('TipoServicioController@save') }}",
                    data: {
                        servtip_id: $("#servtip_id").val(),
                        servtip_nombre: $("#servtip_nombre").val(),
                        servtip_descripcion: $("#servtip_descripcion").val(),
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
                                    $('#servtip_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Tipo Servicio Guardado');
                            window.location.href = "{{ url('tipo-servicio/') }}";
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