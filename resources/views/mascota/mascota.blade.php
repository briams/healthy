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
                <button id="mascota_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Mascota</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('MascotaController@save') }}" method="post" id="mascota_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="mascota_id" id="mascota_id"
                                                   @if (isset($rsMascota)) value="{{$rsMascota->mascota_id}}" @endif >
                                            <div class="fields">
                                                <div class="eight wide field mascota_cliente_id" >
                                                    @if (count($clientes) > 0)
                                                        <label>Dueño (cliente)</label>
                                                        <select class="ui search dropdown" id="mascota_cliente_id" name="mascota_cliente_id">
                                                            <option value="">Seleccione Dueño (Cliente)</option>
                                                            @foreach ($clientes as $cliente)
                                                                <option value="{{$cliente->cliente_id}}" @if (isset($rsMascota)) @if ($cliente->cliente_id == $rsMascota->mascota_cliente_id ) selected @endif @endif >{{ $cliente->cliente_fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field mascota_nombre required">
                                                    <label>Nombre</label>
                                                    <input id="mascota_nombre" type="text" name="mascota_nombre"
                                                           @if (isset($rsMascota)) value="{{$rsMascota->mascota_nombre}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field mascota_especie required">
                                                    @if (count($especies) > 0)
                                                        <label>Especie</label>
                                                        <select class="ui search dropdown" id="mascota_especie" name="mascota_especie">
                                                            <option value="">Seleccione Especie</option>
                                                            @foreach ($especies as $especie)
                                                                <option value="{{$especie->especie_id}}" @if (isset($rsMascota)) @if ($especie->especie_id == $rsMascota->mascota_especie ) selected @endif @endif >{{ $especie->especie_nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field mascota_raza required">
                                                    <label>Raza</label>
                                                    <select class="ui search dropdown" id="mascota_raza" name="mascota_raza">
                                                        <option value="">Seleccione Raza</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field mascota_sexo" >
                                                    @if (count($sexos) > 0)
                                                        <label>Sexo</label>
                                                        <select class="ui search dropdown" id="mascota_sexo" name="mascota_sexo">
                                                            <option value="">Seleccione Sexo</option>
                                                            @foreach ($sexos as $sexo)
                                                                <option value="{{$sexo->sexo_id}}" @if (isset($rsMascota)) @if ($sexo->sexo_id == $rsMascota->mascota_sexo ) selected @endif @endif >{{ $sexo->sexo_nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field mascota_peso required">
                                                    <label>Peso</label>
                                                    <input id="mascota_peso" type="text" name="mascota_peso"
                                                           @if (isset($rsMascota)) value="{{$rsMascota->mascota_peso}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field mascota_tamano required">
                                                    <label>Tamaño</label>
                                                    <input id="mascota_tamano" type="text" name="mascota_tamano"
                                                           @if (isset($rsMascota)) value="{{$rsMascota->mascota_tamano}}" @endif>
                                                </div>
                                                <div class="eight wide field mascota_pelaje required">
                                                    <label>Pelaje</label>
                                                    <input id="mascota_pelaje" type="text" name="mascota_pelaje"
                                                           @if (isset($rsMascota)) value="{{$rsMascota->mascota_pelaje}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field mascota_atributo required">
                                                    <label>Atributo</label>
                                                    <input id="mascota_atributo" type="text" name="mascota_atributo"
                                                           @if (isset($rsMascota)) value="{{$rsMascota->mascota_atributo}}" @endif>
                                                </div>
                                                <div class="eight wide field mascota_chip required">
                                                    <label>Chip</label>
                                                    <input id="mascota_chip" type="text" name="mascota_chip"
                                                           @if (isset($rsMascota)) value="{{$rsMascota->mascota_chip}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field mascota_nacimiento required">
                                                    <label>Fecha Nac.</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="mascota_nacimiento" type="text"  name="mascota_nacimiento" autocomplete="off" @if (isset($rsMascota)) value="{{$rsMascota->mascota_nacimiento}}" @endif>
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
                window.location.href = "{{ url('mascota/') }}";
            });

            $("#mascota_cliente_id").dropdown({
                fullTextSearch:true
            });

            $("#mascota_especie").dropdown({
                fullTextSearch:true
            });

            $("#mascota_raza").dropdown({
                fullTextSearch:true
            });

            $("#mascota_sexo").dropdown({
                fullTextSearch:true
            });

            $('#mascota_nacimiento').flatpickr({
                maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            // $("#mascota_especie").change(function(e){
            //     e.preventDefault();
            // });

            $('#mascota_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('MascotaController@save') }}",
                    data: {
                        mascota_id: $("#mascota_id").val(),
                        mascota_nombre: $("#mascota_nombre").val(),
                        mascota_sexo: $("#mascota_sexo").val(),
                        mascota_especie: $("#mascota_especie").val(),
                        mascota_raza: $("#mascota_raza").val(),
                        mascota_cliente_id: $("#mascota_cliente_id").val(),
                        mascota_peso: $("#mascota_peso").val(),
                        mascota_tamano: $("#mascota_tamano").val(),
                        mascota_pelaje: $("#mascota_pelaje").val(),
                        mascota_nacimiento: $("#mascota_nacimiento").val(),
                        mascota_atributo: $("#mascota_atributo").val(),
                        mascota_chip: $("#mascota_chip").val(),
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
                                    $('#mascota_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Mascota Guardada');
                            window.location.href = "{{ url('mascota/') }}";
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