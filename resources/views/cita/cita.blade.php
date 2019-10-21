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
                <button id="cita_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Producto</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('CitaController@save') }}" method="post" id="cita_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="cita_id" id="cita_id"
                                                   @if (isset($rsCita)) value="{{$rsCita->cita_id}}" @endif>
                                            <div class="fields">
                                                <div class="six wide field cita_cliente_id" >
                                                    @if (count($clientes) > 0)
                                                        <label>Dueño (cliente)</label>
                                                        <select class="ui search dropdown" id="cita_cliente_id" name="cita_cliente_id">
                                                            <option value="">Seleccione Dueño (Cliente)</option>
                                                            @foreach ($clientes as $cliente)
                                                                <option value="{{$cliente->cliente_id}}" @if (isset($rsCita)) @if ($cliente->cliente_id == $rsCita->cita_cliente_id ) selected @endif @endif >{{ $cliente->cliente_fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="six wide field cita_mascota_id required">
                                                    <label>Mascota</label>
                                                    <select class="ui search dropdown" id="cita_mascota_id" name="cita_mascota_id">
                                                        <option value="">Seleccione Mascota</option>
                                                    </select>
                                                </div>
                                                <div class="six wide field cita_fecha required">
                                                    <label>Fecha de Cita</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="cita_fecha" type="text" name="cita_fecha"
                                                               @if (isset($rsCita)) value="{{$rsCita->cita_fecha}}" @endif>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field cita_motivo required">
                                                    <label>Motivo</label>
                                                    <textarea rows="2" id="cita_motivo" name="cita_motivo"
                                                    >@if (isset($rsCita)) {{$rsCita->cita_motivo}}@endif</textarea>
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
                window.location.href = "{{ url('cita/') }}";
            });

            $("#cita_cliente_id").dropdown({
                fullTextSearch:true
            });

            $("#cita_mascota_id").dropdown({
                fullTextSearch:true
            });

            $('#cita_fecha').flatpickr({
                minDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $('#cita_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('CitaController@save') }}",
                    data: {
                        cita_id: $("#cita_id").val(),
                        cita_cliente_id: $("#cita_cliente_id").val(),
                        cita_mascota_id: $("#cita_mascota_id").val(),
                        cita_fecha: $("#cita_fecha").val(),
                        cita_motivo: $("#cita_motivo").val(),
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
                                    $('#cita_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'cita Guardada');
                            window.location.href = "{{ url('cita/') }}";
                        }
                    },
                    statusCode: {
                        404: function () {
                            alert('Web not found');
                        }
                    }
                });
            });

            var cliente = $("#cita_cliente_id").val();

            if(cliente != ''){
                accion = 2;
                cargarMascota(accion);
            }

            $("#cita_cliente_id").change(function(e){
                e.preventDefault();
                $('#cita_mascota_id').dropdown('clear');
                accion = 1;
                cargarMascota(accion);
            });

        });

        function cargarMascota(accion){
            var idCita = $('#cita_id').val();
            var idCliente = $('#cita_cliente_id').val();
            $.post("{{ action('CitaController@cargarMascota') }}", { idCita: idCita, idCliente: idCliente, accion: accion } , function(data) {
                $('#cita_mascota_id').html(data.mascota);
            });
        }

    </script>
@stop