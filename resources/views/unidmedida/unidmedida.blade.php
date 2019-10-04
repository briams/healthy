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
                <button id="unidmed_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Unidad Medida</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('TipoDocumentoController@save') }}" method="post" id="unidmed_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="umd_id" id="umd_id"
                                                   @if (isset($unidadMedida)) value="{{$unidadMedida->umd_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field umd_codigo required">
                                                    <label>Codigo</label>
                                                    <input id="umd_codigo" type="text" name="umd_codigo"
                                                           @if (isset($unidadMedida)) value="{{$unidadMedida->umd_codigo}}" @endif>
                                                </div>
                                                <div class="eight wide field umd_descripcion required">
                                                    <label>Descripcion</label>
                                                    <input id="umd_descripcion" type="text" name="umd_descripcion"
                                                           @if (isset($unidadMedida)) value="{{$unidadMedida->umd_descripcion}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field umd_orden required">
                                                    <label>Orden</label>
                                                    <input id="umd_orden" type="number" name="umd_orden"
                                                           @if (isset($unidadMedida)) value="{{$unidadMedida->umd_orden}}" @endif>
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
                window.location.href = "{{ url('unidad-medida/') }}";
            });

            $('#unidmed_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('UnidadMedidaController@save') }}",
                    data: {
                        umd_id: $("#umd_id").val(),
                        umd_codigo: $("#umd_codigo").val(),
                        umd_descripcion: $("#umd_descripcion").val(),
                        umd_orden: $("#umd_orden").val(),
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
                                    $('#unidmed_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Unidad Medida Guardado');
                            window.location.href = "{{ url('unidad-medida/') }}";
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