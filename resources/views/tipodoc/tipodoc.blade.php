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
                <button id="tipodoc_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Tipo Documento</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('TipoDocumentoController@save') }}" method="post" id="tipodoc_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="tdc_id" id="tdc_id"
                                                   @if (isset($tipDocumento)) value="{{$tipDocumento->tdc_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field tdc_codigo required">
                                                    <label>Codigo</label>
                                                    <input id="tdc_codigo" type="text" name="tdc_codigo"
                                                           @if (isset($tipDocumento)) value="{{$tipDocumento->tdc_codigo}}" @endif>
                                                </div>
                                                <div class="eight wide field tdc_descripcion required">
                                                    <label>Descripcion</label>
                                                    <input id="tdc_descripcion" type="text" name="tdc_descripcion"
                                                           @if (isset($tipDocumento)) value="{{$tipDocumento->tdc_descripcion}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field tdc_sigla required">
                                                    <label>Sigla</label>
                                                    <input id="tdc_sigla" type="text" name="tdc_sigla"
                                                           @if (isset($tipDocumento)) value="{{$tipDocumento->tdc_sigla}}" @endif>
                                                </div>
                                                <div class="eight wide field tdc_orden required">
                                                    <label>Orden</label>
                                                    <input id="tdc_orden" type="number" name="tdc_orden"
                                                           @if (isset($tipDocumento)) value="{{$tipDocumento->tdc_orden}}" @endif>
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
                window.location.href = "{{ url('tipo-documento/') }}";
            });

            $('#tipodoc_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('TipoDocumentoController@save') }}",
                    data: {
                        tdc_id: $("#tdc_id").val(),
                        tdc_codigo: $("#tdc_codigo").val(),
                        tdc_descripcion: $("#tdc_descripcion").val(),
                        tdc_orden: $("#tdc_orden").val(),
                        tdc_sigla: $("#tdc_sigla").val(),
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
                                    $('#tipodoc_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Tipo Documento Guardado');
                            window.location.href = "{{ url('tipo-documento/') }}";
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