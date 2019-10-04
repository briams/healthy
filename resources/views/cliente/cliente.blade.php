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
                <button id="cliente_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Cliente</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('ClienteController@save') }}" method="post" id="cliente_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="cliente_id" id="cliente_id"
                                                   @if (isset($rsCliente)) value="{{$rsCliente->cliente_id}}" @endif >
                                            <div class="fields">
                                                <div class="eight wide field cliente_tipo_documento" >
                                                    @if (count($tipDocs) > 0)
                                                        <label>Tipo Documento</label>
                                                        <select class="ui search dropdown" id="cliente_tipo_documento" name="cliente_tipo_documento">
                                                            <option value="">Seleccione Tipo Documento</option>
                                                            @foreach ($tipDocs as $tipDoc)
                                                                <option sigla="{{$tipDoc->tdc_sigla}}" value="{{$tipDoc->tdc_id}}" @if (isset($rsCliente)) @if ($tipDoc->tdc_id == $rsCliente->cliente_tipo_documento ) selected @endif @endif >{{ $tipDoc->tdc_descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field cliente_nro_documento required">
                                                    <label>Nro Doc</label>
                                                    <input id="cliente_nro_documento" type="text" name="cliente_nro_documento"
                                                           @if (isset($rsCliente)) value="{{$rsCliente->cliente_nro_documento}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field cliente_nombres required">
                                                    <label>Nombres</label>
                                                    <input id="cliente_nombres" type="text" name="cliente_nombres"
                                                           @if (isset($rsCliente)) value="{{$rsCliente->cliente_nombres}}" @endif>
                                                </div>
                                                <div class="eight wide field cliente_apellidos required">
                                                    <label>Apellidos</label>
                                                    <input id="cliente_apellidos" type="text" name="cliente_apellidos"
                                                           @if (isset($rsCliente)) value="{{$rsCliente->cliente_apellidos}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field cliente_ubigeo" >
                                                    @if (count($ubigeo) > 0)
                                                        <label>Distrito</label>
                                                        <select class="ui search dropdown" id="cliente_ubigeo" name="cliente_ubigeo">
                                                            <option value="">Seleccione Distrito</option>
                                                            @foreach ($ubigeo as $ubicacion)
                                                                <option value="{{$ubicacion->ubigeo}}" @if (isset($rsCliente)) @if ($ubicacion->ubigeo == $rsCliente->cliente_ubigeo ) selected @endif @endif >{{ $ubicacion->dist }} - {{ $ubicacion->prov }} - {{ $ubicacion->depa }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field cliente_direccion required">
                                                    <label>Direccion</label>
                                                    <input id="cliente_direccion" type="text" name="cliente_direccion"
                                                           @if (isset($rsCliente)) value="{{$rsCliente->cliente_direccion}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field cliente_telefono required">
                                                    <label>Telefono</label>
                                                    <input id="cliente_telefono" type="text" name="cliente_telefono"
                                                           @if (isset($rsCliente)) value="{{$rsCliente->cliente_telefono}}" @endif>
                                                </div>
                                                <div class="eight wide field cliente_email required">
                                                    <label>E-mail</label>
                                                    <input id="cliente_email" type="text" name="cliente_email"
                                                           @if (isset($rsCliente)) value="{{$rsCliente->cliente_email}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field cliente_fecha_nacimiento required">
                                                    <label>Fecha Nac.</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="cliente_fecha_nacimiento" type="text"  name="cliente_fecha_nacimiento" autocomplete="off" @if (isset($rsCliente)) value="{{$rsCliente->cliente_fecha_nacimiento}}" @endif>
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
                window.location.href = "{{ url('cliente/') }}";
            });

            $("#cliente_tipo_documento").dropdown({
                fullTextSearch:true
            });

            $("#cliente_ubigeo").dropdown({
                fullTextSearch:true
            });

            $('#cliente_fecha_nacimiento').flatpickr({
                maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $("#cliente_tipo_documento").change(function(e)
            {
                e.preventDefault();
                var sigla =  $('#cliente_tipo_documento option:selected').attr('sigla');
                var nroDoc = $("#cliente_nro_documento").val();
                if(sigla == 'DNI'){
                    $("#cliente_nro_documento").attr("maxlength","8");
                    if(nroDoc.length > 8){
                        $("#cliente_nro_documento").val('');
                    }
                }else if(sigla == 'RUC'){
                    $("#cliente_nro_documento").attr("maxlength","11");
                    if(nroDoc.length > 11){
                        $("#cliente_nro_documento").val('');
                    }
                }else{
                    $("#cliente_nro_documento").attr("maxlength","");
                }
            });

            $("#cliente_nro_documento").keyup(function(e){
                e.preventDefault();
                var enter = 13;
                if(e.which == enter)
                {
                    var sigla =  $('#cliente_tipo_documento option:selected').attr('sigla');
                    var nroDoc = $("#cliente_nro_documento").val();
                    if(sigla == 'RUC' || sigla == 'DNI'){
                        $.ajax({
                            url : "{{ action('ClienteController@searchDoc') }}",
                            data : { sigla : sigla , nroDoc : nroDoc },
                            type : 'POST',
                            success : function(response){
                                if (response.status == STATUS_FAIL) {
                                    toast('error', 1500, response.msg );
                                }else if (response.status == STATUS_OK) {
                                    toast('success',3000,response.msg);
                                    $("#cliente_nombres").val(response.nombre);
                                    $("#cliente_apellidos").val(response.apellido);
                                    $("#cliente_direccion").val(response.direccion);
                                }
                            },
                            statusCode : {
                                404 : function(){
                                    alert('Web not found');
                                }
                            }
                        });
                    }
                }
            });

            $('#cliente_save').click(function (e) {
                e.preventDefault();
                var sigla =  $('#cliente_tipo_documento option:selected').attr('sigla');
                $.ajax({
                    url: "{{ action('ClienteController@save') }}",
                    data: {
                        cliente_id: $("#cliente_id").val(),
                        cliente_tipo_documento: $("#cliente_tipo_documento").val(),
                        cliente_nro_documento: $("#cliente_nro_documento").val(),
                        cliente_nombres: $("#cliente_nombres").val(),
                        cliente_apellidos: $("#cliente_apellidos").val(),
                        cliente_direccion: $("#cliente_direccion").val(),
                        cliente_ubigeo: $("#cliente_ubigeo").val(),
                        cliente_telefono: $("#cliente_telefono").val(),
                        cliente_email: $("#cliente_email").val(),
                        cliente_fecha_nacimiento: $("#cliente_fecha_nacimiento").val(),
                        sigla: sigla,
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
                                    $('#cliente_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Cliente Guardado');
                            window.location.href = "{{ url('cliente/') }}";
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