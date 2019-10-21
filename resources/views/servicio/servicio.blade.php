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
                <button id="servicio_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
            <div class="item ui colhidden">
                <h3>{{ $rsMascota->mascota_nombre }} | {{ $rsCliente->cliente_fullname }}</h3>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Vacunacion</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('ServicioController@save') }}" method="post" id="servicio_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="servicio_id" id="servicio_id"
                                                   @if (isset($rsServicio)) value="{{$rsServicio->servicio_id}}" @endif>

                                            <input type="hidden" name="servicio_historia_id" id="servicio_historia_id"
                                                   @if (isset($idHistoria)) value="{{ $idHistoria  }}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field servicio_servtip_id required">
                                                    @if (count($tipoServicio) > 0)
                                                        <label>Tipo Servicio</label>
                                                        <select class="ui search dropdown" id="servicio_servtip_id" name="servicio_servtip_id">
                                                            <option value="">Seleccione Tipo Servicio</option>
                                                            @foreach ($tipoServicio as $tipo)
                                                                <option value="{{$tipo->servtip_id}}" @if (isset($rsServicio)) @if ($tipo->servtip_id == $rsServicio->servicio_servtip_id ) selected  @endif @endif >{{ $tipo->servtip_nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field servicio_fecha required">
                                                    <label>Fecha</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="servicio_fecha" type="text" name="servicio_fecha"
                                                                @if (isset($rsServicio)) value="{{$rsServicio->servicio_fecha}}" @endif>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field servicio_observacion required">
                                                    <label>Observacion</label>
                                                    <textarea rows="2" id="servicio_observacion" name="servicio_observacion"
                                                    >@if (isset($rsServicio)) {{$rsServicio->servicio_observacion}}@endif</textarea>
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
                window.location.href = "{{ url('mascota/historia') }}/"+"{{ $rsMascota->mascota_id }}";
            });

            $('#servicio_fecha').flatpickr({
                maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $("#servicio_servtip_id").dropdown({
                fullTextSearch:true
            });

            $('#servicio_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('ServicioController@save') }}",
                    data: {
                        servicio_id: $("#servicio_id").val(),
                        servicio_servtip_id: $("#servicio_servtip_id").val(),
                        servicio_historia_id: $("#servicio_historia_id").val(),
                        servicio_fecha: $("#servicio_fecha").val(),
                        servicio_observacion: $("#servicio_observacion").val(),
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
                                    $('#servicio_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Servicio Guardado');
                            window.location.href = "{{ url('mascota/historia') }}/"+response.idMascota;
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