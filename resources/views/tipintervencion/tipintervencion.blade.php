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
                <button id="tipintervencion_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Tipo Intervencion</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('TipoIntervencionController@save') }}" method="post" id="tipintervencion_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="intervenciont_id" id="intervenciont_id"
                                                   @if (isset($tipoIntervencion)) value="{{$tipoIntervencion->intervenciont_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field intervenciont_nombre required">
                                                    <label>Nombre</label>
                                                    <input id="intervenciont_nombre" type="text" name="intervenciont_nombre"
                                                           @if (isset($tipoIntervencion)) value="{{$tipoIntervencion->intervenciont_nombre}}" @endif>
                                                </div>
                                                <div class="eight wide field intervenciont_descripcion required">
                                                    <label>Descripcion</label>
                                                    <input id="intervenciont_descripcion" type="text" name="intervenciont_descripcion"
                                                           @if (isset($tipoIntervencion)) value="{{$tipoIntervencion->intervenciont_descripcion}}" @endif>
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
                window.location.href = "{{ url('tipo-intervencion/') }}";
            });

            $('#tipintervencion_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('TipoIntervencionController@save') }}",
                    data: {
                        intervenciont_id: $("#intervenciont_id").val(),
                        intervenciont_nombre: $("#intervenciont_nombre").val(),
                        intervenciont_descripcion: $("#intervenciont_descripcion").val(),
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
                                    $('#tipintervencion_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Tipo Intervencion Guardado');
                            window.location.href = "{{ url('tipo-intervencion/') }}";
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