@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless" data-color="inverted white">
            <div class="item ui colhidden">
                <button id="button_back" class="ui button compact icon">
                    <i class="icon arrow left"></i>
                </button>
            </div>
            @if($editar)
            <div class="item ui colhidden">
                <button id="historia_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Historia</a>
                    @if (isset($rsHistoria))
                    <a class=" item" data-tab="second">Vacunacion</a>

                    {{--<a class=" item" data-tab="third">Servicio Estetico</a>--}}
                    <a class=" item" data-tab="fourth">Tratamiento</a>
                    <a class=" item" data-tab="fifth">Internamiento</a>
                    {{--<a class=" item" data-tab="sixth">Receta</a>--}}
                    {{--<a class=" item" data-tab="seventh">Examenes</a>--}}
                    {{--<a class=" item" data-tab="eighth">Intervencion Quirurgica</a>--}}
                    @endif


                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('MascotaController@save') }}" method="post" id="historia_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">

                                            @if($editar)

                                            <input type="hidden" name="historia_id" id="historia_id"
                                                   @if (isset($rsHistoria)) value="{{$rsHistoria->historia_id}}" @endif >
                                            <div class="fields">
                                                <div class="eight wide field historia_mascota_id" >
                                                    @if (count($mascotas) > 0)
                                                        <label>Mascota</label>
                                                        <select class="ui search dropdown" id="historia_mascota_id" name="historia_mascota_id" disabled>
                                                            <option value="">Seleccione Mascota</option>
                                                            @foreach ($mascotas as $mascota)
                                                                <option value="{{$mascota->mascota_id}}" @if (isset($idMascota)) @if ($mascota->mascota_id == $idMascota ) selected  @endif @endif >{{ $mascota->mascota_nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field historia_peso required">
                                                    <label>Peso</label>
                                                    <input id="historia_peso" type="text" name="historia_peso"
                                                           @if (isset($rsHistoria)) value="{{$rsHistoria->historia_peso}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field historia_mucosa required">
                                                    <label>Mucosa</label>
                                                    <input id="historia_mucosa" type="text" name="historia_mucosa"
                                                           @if (isset($rsHistoria)) value="{{$rsHistoria->historia_mucosa}}" @endif>
                                                </div>
                                                <div class="eight wide field historia_temperatura required">
                                                    <label>Temperatura</label>
                                                    <input id="historia_temperatura" type="text" name="historia_temperatura"
                                                           @if (isset($rsHistoria)) value="{{$rsHistoria->historia_temperatura}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field historia_frec_cardiaca required">
                                                    <label>Frecuencia Cardiaca</label>
                                                    <input id="historia_frec_cardiaca" type="text" name="historia_frec_cardiaca"
                                                           @if (isset($rsHistoria)) value="{{$rsHistoria->historia_frec_cardiaca}}" @endif>
                                                </div>
                                                <div class="eight wide field historia_frec_respiratoria required">
                                                    <label>Frecuencia Respiratoria</label>
                                                    <input id="historia_frec_respiratoria" type="text" name="historia_frec_respiratoria"
                                                           @if (isset($rsHistoria)) value="{{$rsHistoria->historia_frec_respiratoria}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field historia_alergias required">
                                                    <label>Alergias</label>
                                                    <textarea rows="2" id="historia_alergias" name="historia_alergias"
                                                    >@if (isset($rsHistoria)) {{$rsHistoria->historia_alergias}}@endif</textarea>
                                                </div>
                                                <div class="eight wide field historia_sintomatologia required">
                                                    <label>Sintomatologia</label>
                                                    <textarea rows="2" id="historia_sintomatologia" name="historia_sintomatologia"
                                                    >@if (isset($rsHistoria)) {{$rsHistoria->historia_sintomatologia}}@endif</textarea>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field historia_oservaciones required">
                                                    <label>Observaciones</label>
                                                    <textarea rows="2" id="historia_oservaciones" name="historia_oservaciones"
                                                    >@if (isset($rsHistoria)) {{$rsHistoria->historia_oservaciones}}@endif</textarea>
                                                </div>
                                            </div>
                                            @else
                                                @foreach ($html as $index => $value)
                                                    <b>{{ $index }} </b> {{ $value }} </br>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="ui bottom attached tab segment" data-tab="second">
                    <form action="" method="post" id="vacinacion_ficha"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">

                                            <input type="hidden" name="vacunacion_historia_id" id="vacunacion_historia_id"
                                                   @if (isset($rsHistoria)) value="{{$rsHistoria->historia_id}}" @endif >

                                            <div class="fields">
                                                {{--<div class="item ui colhidden">--}}
                                                {{--</div>--}}
                                                <div class="four wide field ">
                                                    <button id="new_vacunacion" class="ui button compact"><i class="icon plus"></i>Nueva Vacunacion
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field ">
                                                    <div id="grid"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="ui bottom attached tab segment" data-tab="fourth">
                    <form action="" method="post" id="tratamiento_ficha"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">

                                            <input type="hidden" name="tratamiento_historia_id" id="tratamiento_historia_id"
                                                   @if (isset($rsHistoria)) value="{{$rsHistoria->historia_id}}" @endif >

                                            <div class="fields">
                                                {{--<div class="item ui colhidden">--}}
                                                {{--</div>--}}
                                                <div class="four wide field ">
                                                    <button id="new_tratamiento" class="ui button compact"><i class="icon plus"></i>Nuevo Tratamiento
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field ">
                                                    <div id="grid_tratamiento"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="ui bottom attached tab segment" data-tab="fifth">
                    <form action="" method="post" id="internamiento_ficha"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">

                                            <input type="hidden" name="internamiento_historia_id" id="internamiento_historia_id"
                                                   @if (isset($rsHistoria)) value="{{$rsHistoria->historia_id}}" @endif >

                                            <div class="fields">
                                                {{--<div class="item ui colhidden">--}}
                                                {{--</div>--}}
                                                <div class="four wide field ">
                                                    <button id="new_internamiento" class="ui button compact"><i class="icon plus"></i>Nuevo Internamiento
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field ">
                                                    <div id="grid_internamiento"></div>
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

            $('.tabular.menu .item').tab();

            $('#button_back').click(function (e) {
                window.location.href = "{{ url('mascota/') }}";
            });

            $("#historia_mascota_id").dropdown({
                fullTextSearch:true
            });

            $('#historia_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('HistoriaController@save') }}",
                    data: {
                        historia_id: $("#historia_id").val(),
                        historia_mascota_id: $("#historia_mascota_id").val(),
                        historia_peso: $("#historia_peso").val(),
                        historia_mucosa: $("#historia_mucosa").val(),
                        historia_temperatura: $("#historia_temperatura").val(),
                        historia_frec_cardiaca: $("#historia_frec_cardiaca").val(),
                        historia_frec_respiratoria: $("#historia_frec_respiratoria").val(),
                        historia_alergias: $("#historia_alergias").val(),
                        historia_sintomatologia: $("#historia_sintomatologia").val(),
                        historia_oservaciones: $("#historia_oservaciones").val(),
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
                                    $('#historia_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Historia Guardada');
                            window.location.reload();
                        }
                    },
                    statusCode: {
                        404: function () {
                            alert('Web not found');
                        }
                    }
                });
            });

            $('#new_vacunacion').click(function (e) {
                e.preventDefault();
                var idHistoria = $("#vacunacion_historia_id").val();
                window.location.href = "{{ url('vacunacion/editar') }}/" + idHistoria;
            });

            var mainDataSource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        options.data.vacunacion_historia_id = $("#vacunacion_historia_id").val();
                        dataSourceBinding(options, "{{ url('vacunacion/get-main-list') }}")
                    }
                },
                serverFiltering: true,
                serverSorting: true,
                serverPaging: true,
                autoSync: true,
                pageSize: 20,
                schema: {
                    data: 'data',
                    total: 'count',
                    model: {

                        id: "vacunacion_id"
                    }
                }
            });

            var grid = $("#grid").kendoGrid({
                dataSource: mainDataSource,
                pageable: {
                    refresh: true,
                    buttonCount: 5,
                    messages: {
                        display: "Listando {0}-{1} de {2} registros"
                    }
                },
                autoBind: false,
                columns: [
                    {
                        field: "&nbsp;",
                        width: 50,
                        template: "#= tool #",
                        sortable: false,
                        attributes: {"class": "grid__cell_tool_menu"}
                    },

                    // {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                    {field: "vac_descripcion", title: 'VACUNA', width: '80px'},
                    {field: "vacunacion_fecha", title: 'FECHA', width: '80px'},

                ],

                sortable: true,
                dataBound: function (sender, args) {

                    $('#grid .ui.dropdown').dropdown({
                        context: '#grid .k-grid-content'
                    });

                    $('.ajxEdit').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idVacunacion');
                        var idHistoria = $("#vacunacion_historia_id").val();
                        window.location.href="{{ url('vacunacion/editar') }}/"+ idHistoria+'/'+id;
                    });

                    $('.ajxDelete').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idVacunacion');
                        $.ajax({
                            url : "{{ action('VacunacionController@eliminar') }}",
                            data : { id : id },
                            type : 'POST',
                            success : function(response){
                                if (response.status == STATUS_FAIL) {
                                    toast('error', 1500, response.msg );
                                }else if (response.status == STATUS_OK) {
                                    toast('success',3000,'Vacunaciòn Eliminada');
                                    mainDataSource.read();
                                }
                            },
                            statusCode : {
                                404 : function(){
                                    alert('Web not found');
                                }
                            }
                        });
                    });

                }

            }).data("kendoGrid");

            mainDataSource.read();

            $('#new_internamiento').click(function (e) {
                e.preventDefault();
                var idHistoria = $("#internamiento_historia_id").val();
                window.location.href = "{{ url('internamiento/editar') }}/" + idHistoria;
            });

            var mainDataSource2 = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        options.data.internamiento_historia_id = $("#internamiento_historia_id").val();
                        dataSourceBinding(options, "{{ url('internamiento/get-main-list') }}")
                    }
                },
                serverFiltering: true,
                serverSorting: true,
                serverPaging: true,
                autoSync: true,
                pageSize: 20,
                schema: {
                    data: 'data',
                    total: 'count',
                    model: {

                        id: "internamiento_id"
                    }
                }
            });

            var grid = $("#grid_internamiento").kendoGrid({
                dataSource: mainDataSource2,
                pageable: {
                    refresh: true,
                    buttonCount: 5,
                    messages: {
                        display: "Listando {0}-{1} de {2} registros"
                    }
                },
                autoBind: false,
                columns: [
                    {
                        field: "&nbsp;",
                        width: 50,
                        template: "#= tool #",
                        sortable: false,
                        attributes: {"class": "grid__cell_tool_menu"}
                    },

                    // {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                    {field: "internamiento_fecha_inicio", title: 'FECHA INICIO', width: '80px'},
                    {field: "internamiento_fecha_salida", title: 'FECHA FIN', width: '80px'},
                    {field: "internamiento_dias", title: 'DIAS TOTALES', width: '80px'},
                    {field: "internamiento_motivo", title: 'MOTIVO INTERNAMIENTO', width: '80px'},

                ],

                sortable: true,
                dataBound: function (sender, args) {

                    $('#grid_internamiento .ui.dropdown').dropdown({
                        context: '#grid_internamiento .k-grid-content'
                    });

                    $('.ajxEdit').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idInternamiento');
                        var idHistoria = $("#internamiento_historia_id").val();
                        window.location.href="{{ url('internamiento/editar') }}/"+ idHistoria+'/'+id;
                    });

                    $('.ajxDelete').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idInternamiento');
                        $.ajax({
                            url : "{{ action('InternamientoController@eliminar') }}",
                            data : { id : id },
                            type : 'POST',
                            success : function(response){
                                if (response.status == STATUS_FAIL) {
                                    toast('error', 1500, response.msg );
                                }else if (response.status == STATUS_OK) {
                                    toast('success',3000,'Registro Eliminado');
                                    mainDataSource.read();
                                }
                            },
                            statusCode : {
                                404 : function(){
                                    alert('Web not found');
                                }
                            }
                        });
                    });
                }

            }).data("kendoGrid");

            mainDataSource2.read();

            $('#new_tratamiento').click(function (e) {
                e.preventDefault();
                var idHistoria = $("#tratamiento_historia_id").val();
                window.location.href = "{{ url('tratamiento/editar') }}/" + idHistoria;
            });

            var mainDataSource3 = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        options.data.tratamiento_historia_id = $("#tratamiento_historia_id").val();
                        dataSourceBinding(options, "{{ url('tratamiento/get-main-list') }}")
                    }
                },
                serverFiltering: true,
                serverSorting: true,
                serverPaging: true,
                autoSync: true,
                pageSize: 20,
                schema: {
                    data: 'data',
                    total: 'count',
                    model: {

                        id: "internamiento_id"
                    }
                }
            });

            var grid = $("#grid_tratamiento").kendoGrid({
                dataSource: mainDataSource3,
                pageable: {
                    refresh: true,
                    buttonCount: 5,
                    messages: {
                        display: "Listando {0}-{1} de {2} registros"
                    }
                },
                autoBind: false,
                columns: [
                    {
                        field: "&nbsp;",
                        width: 50,
                        template: "#= tool #",
                        sortable: false,
                        attributes: {"class": "grid__cell_tool_menu"}
                    },

                    // {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                    {field: "tratamiento_descripcion", title: 'DESCRIPCION', width: '80px'},
                    {field: "tratamiento_tipo", title: 'TIPO', width: '80px'},
                    {field: "tratamiento_fecha_registro", title: 'FECHA', width: '80px'},

                ],

                sortable: true,
                dataBound: function (sender, args) {

                    $('#grid_tratamiento .ui.dropdown').dropdown({
                        context: '#grid_tratamiento .k-grid-content'
                    });

                    $('.ajxEdit').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idTratamiento');
                        var idHistoria = $("#tratamiento_historia_id").val();
                        window.location.href="{{ url('tratamiento/editar') }}/"+ idHistoria+'/'+id;
                    });

                    $('.ajxDelete').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idTratamiento');
                        $.ajax({
                            url : "{{ action('TratamientoController@eliminar') }}",
                            data : { id : id },
                            type : 'POST',
                            success : function(response){
                                if (response.status == STATUS_FAIL) {
                                    toast('error', 1500, response.msg );
                                }else if (response.status == STATUS_OK) {
                                    toast('success',3000,'Registro Eliminado');
                                    mainDataSource.read();
                                }
                            },
                            statusCode : {
                                404 : function(){
                                    alert('Web not found');
                                }
                            }
                        });
                    });
                }

            }).data("kendoGrid");

            mainDataSource3.read();

        });

    </script>
@stop