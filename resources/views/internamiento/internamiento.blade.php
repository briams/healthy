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
                <button id="internamiento_save" class="ui button primary compact">
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
                    <a class="active item" data-tab="first">Internamiento</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('InternamientoController@save') }}" method="post" id="internamiento_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="internamiento_id" id="internamiento_id"
                                                   @if (isset($rsInternamiento)) value="{{$rsInternamiento->internamiento_id}}" @endif>

                                            <input type="hidden" name="internamiento_historia_id" id="internamiento_historia_id"
                                                   @if (isset($idHistoria)) value="{{ $idHistoria  }}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field internamiento_fecha_inicio required">
                                                    <label>Fecha Inicio</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="internamiento_fecha_inicio" type="text" name="internamiento_fecha_inicio"
                                                                @if (isset($rsInternamiento)) value="{{$rsInternamiento->internamiento_fecha_inicio}}" @endif>
                                                    </div>
                                                </div>
                                                <div class="eight wide field internamiento_dias required">
                                                    <label>Cantidad de dias de internamiento</label>
                                                    <input id="internamiento_dias" type="number" min="1" name="internamiento_dias"
                                                           @if (isset($rsInternamiento)) value="{{$rsInternamiento->internamiento_dias}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field internamiento_motivo required">
                                                    <label>Motivo de Internamiento</label>
                                                    <textarea rows="2" id="internamiento_motivo" name="internamiento_motivo"
                                                    >@if (isset($rsInternamiento)) {{$rsInternamiento->internamiento_motivo}}@endif</textarea>
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

            $('#internamiento_fecha_inicio').flatpickr({
                // maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $('#internamiento_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('InternamientoController@save') }}",
                    data: {
                        internamiento_id: $("#internamiento_id").val(),
                        internamiento_historia_id: $("#internamiento_historia_id").val(),
                        internamiento_fecha_inicio: $("#internamiento_fecha_inicio").val(),
                        internamiento_dias: $("#internamiento_dias").val(),
                        internamiento_motivo: $("#internamiento_motivo").val(),
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
                                    $('#internamiento_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Internamiento Guardado');
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