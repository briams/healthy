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
                <button id="vacuna_save" class="ui button primary compact">
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
                    <form action="{{ action('VacunaController@save') }}" method="post" id="vacunacion_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="vacunacion_id" id="vacunacion_id"
                                                   @if (isset($rsVacunacion)) value="{{$rsVacunacion->vacunacion_id}}" @endif>

                                            <input type="hidden" name="vacunacion_historia_id" id="vacunacion_historia_id"
                                                   @if (isset($idHistoria)) value="{{ $idHistoria  }}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field vacunacion_vacuna_id required">
                                                    @if (count($vacunas) > 0)
                                                        <label>Vacuna</label>
                                                        <select class="ui search dropdown" id="vacunacion_vacuna_id" name="vacunacion_vacuna_id">
                                                            <option value="">Seleccione Vacuna</option>
                                                            @foreach ($vacunas as $vacuna)
                                                                <option value="{{$vacuna->vac_id}}" @if (isset($rsVacunacion)) @if ($vacuna->vac_id == $rsVacunacion->vacunacion_vacuna_id ) selected  @endif @endif >{{ $vacuna->vac_descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field vacunacion_fecha required">
                                                    <label>Fecha</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="vacunacion_fecha" type="text" name="vacunacion_fecha"
                                                                @if (isset($rsVacunacion)) value="{{$rsVacunacion->vacunacion_fecha}}" @endif>
                                                    </div>
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
                window.location.href = "{{ url('vacuna/') }}";
            });

            $('#vacunacion_fecha').flatpickr({
                maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $("#vacunacion_vacuna_id").dropdown({
                fullTextSearch:true
            });

            $('#vacuna_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('VacunacionController@save') }}",
                    data: {
                        vacunacion_id: $("#vacunacion_id").val(),
                        vacunacion_vacuna_id: $("#vacunacion_vacuna_id").val(),
                        vacunacion_historia_id: $("#vacunacion_historia_id").val(),
                        vacunacion_fecha: $("#vacunacion_fecha").val(),
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
                                    $('#vacunacion_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Vacunacion Guardada');
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