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
                <button id="visita_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Visita</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('CitaController@save') }}" method="post" id="visita_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="vsta_id" id="vsta_id"
                                                   @if (isset($rsVisita)) value="{{$rsVisita->vsta_id}}" @endif>
                                            <div class="fields">
                                                <div class="six wide field vsta_cliente_id" >
                                                    @if (count($clientes) > 0)
                                                        <label>Dueño (cliente)</label>
                                                        <select class="ui search dropdown" id="vsta_cliente_id" name="vsta_cliente_id">
                                                            <option value="">Seleccione Dueño (Cliente)</option>
                                                            @foreach ($clientes as $cliente)
                                                                <option value="{{$cliente->cliente_id}}" @if (isset($rsVisita)) @if ($cliente->cliente_id == $rsVisita->vsta_cliente_id ) selected @endif @endif >{{ $cliente->cliente_fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field vsta_motivo required">
                                                    <label>Motivo</label>
                                                    <textarea rows="2" id="vsta_motivo" name="vsta_motivo"
                                                    >@if (isset($rsVisita)) {{$rsVisita->vsta_motivo}}@endif</textarea>
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
                window.location.href = "{{ url('visita/') }}";
            });

            $("#vsta_cliente_id").dropdown({
                fullTextSearch:true
            });

            $('#visita_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('VisitaController@save') }}",
                    data: {
                        vsta_id: $("#vsta_id").val(),
                        vsta_cliente_id: $("#vsta_cliente_id").val(),
                        vsta_motivo: $("#vsta_motivo").val(),
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
                                    $('#visita_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'visita Guardada');
                            window.location.href = "{{ url('visita/') }}";
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