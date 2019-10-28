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
                <button id="intervencion_save" class="ui button primary compact">
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
                    <a class="active item" data-tab="first">Servicio</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('IntervencionController@save') }}" method="post" id="intervencion_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="intervencion_id" id="intervencion_id"
                                                   @if (isset($rsIntervencion)) value="{{$rsIntervencion->intervencion_id}}" @endif>

                                            <input type="hidden" name="intervencion_historia_id" id="intervencion_historia_id"
                                                   @if (isset($idHistoria)) value="{{ $idHistoria  }}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field intervencion_interventip_id required">
                                                    @if (count($tipoIntervencion) > 0)
                                                        <label>Tipo Intervencion</label>
                                                        <select class="ui search dropdown" id="intervencion_interventip_id" name="intervencion_interventip_id">
                                                            <option value="">Seleccione Tipo Intervencion</option>
                                                            @foreach ($tipoIntervencion as $tipo)
                                                                <option value="{{$tipo->intervenciont_id}}" @if (isset($rsIntervencion)) @if ($tipo->intervenciont_id == $rsIntervencion->intervencion_interventip_id ) selected  @endif @endif >{{ $tipo->intervenciont_nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field intervencion_fecha required">
                                                    <label>Fecha</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="intervencion_fecha" type="text" name="intervencion_fecha"
                                                                @if (isset($rsIntervencion)) value="{{$rsIntervencion->intervencion_fecha}}" @endif>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field intervencion_descripcion required">
                                                    <label>Descripcion</label>
                                                    <textarea rows="2" id="intervencion_descripcion" name="intervencion_descripcion"
                                                    >@if (isset($rsIntervencion)) {{$rsIntervencion->intervencion_descripcion}}@endif</textarea>
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

            $('#intervencion_fecha').flatpickr({
                maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $("#intervencion_interventip_id").dropdown({
                fullTextSearch:true
            });

            $('#intervencion_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('IntervencionController@save') }}",
                    data: {
                        intervencion_id: $("#intervencion_id").val(),
                        intervencion_interventip_id: $("#intervencion_interventip_id").val(),
                        intervencion_historia_id: $("#intervencion_historia_id").val(),
                        intervencion_fecha: $("#intervencion_fecha").val(),
                        intervencion_descripcion: $("#intervencion_descripcion").val(),
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
                                    $('#intervencion_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Intervencion Guardado');
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