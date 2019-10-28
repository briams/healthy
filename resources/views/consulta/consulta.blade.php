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
                <button id="consulta_save" class="ui button primary compact">
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
                    <a class="active item" data-tab="first">Consulta</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('ConsultaController@save') }}" method="post" id="consulta_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="consulta_id" id="consulta_id"
                                                   @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_id}}" @endif>

                                            <input type="hidden" name="consulta_historia_id" id="consulta_historia_id"
                                                   @if (isset($idHistoria)) value="{{ $idHistoria  }}" @endif>
                                            <div class="fields">
                                                <div class="six wide field consulta_peso required">
                                                    <label>Peso</label>
                                                    <input id="consulta_peso" type="text" name="consulta_peso"
                                                           @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_peso}}" @endif>
                                                </div>
                                                <div class="six wide field consulta_mucosa required">
                                                    <label>Mucosa</label>
                                                    <input id="consulta_mucosa" type="text" name="consulta_mucosa"
                                                           @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_mucosa}}" @endif>
                                                </div>
                                                <div class="six wide field consulta_temperatura required">
                                                    <label>Temperatura</label>
                                                    <input id="consulta_temperatura" type="text" name="consulta_temperatura"
                                                           @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_temperatura}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="six wide field consulta_frec_cardiaca required">
                                                    <label>Frecuencia Cardiaca</label>
                                                    <input id="consulta_frec_cardiaca" type="text" name="consulta_frec_cardiaca"
                                                           @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_frec_cardiaca}}" @endif>
                                                </div>
                                                <div class="six wide field consulta_frec_respiratoria required">
                                                    <label>Frecuencia Respiratoria</label>
                                                    <input id="consulta_frec_respiratoria" type="text" name="consulta_frec_respiratoria"
                                                           @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_frec_respiratoria}}" @endif>
                                                </div>
                                                <div class="six wide field consulta_observaciones required">
                                                    <label>Observaciones</label>
                                                    <input id="consulta_observaciones" type="text" name="consulta_observaciones"
                                                           @if (isset($rsConsulta)) value="{{$rsConsulta->consulta_observaciones}}" @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="eight wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <div class="header">Sintomas</div>
                                        </div>
                                        <div class="content">
                                            <div class="fields">
                                                <div class="sixteen wide field sintoma_nombre" >
                                                    <label>Nombre</label>
                                                    <input id="sintoma_nombre" type="text" name="sintoma_nombre">
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field sintoma_descripcion required">
                                                    <label>Descripcion</label>
                                                    <input id="sintoma_descripcion" type="text" name="sintoma_descripcion">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="fields">
                                                <div class="sixteen wide field ">
                                                    <div id="grid_sintoma"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="eight wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <div class="header">Examenes</div>
                                        </div>
                                        <div class="content">
                                            <div class="fields">
                                                <div class="sixteen wide field examen_exament_id" >
                                                    @if (count($rsTipoExamen) > 0)
                                                        <label>Examenes</label>
                                                        <select class="ui search dropdown" id="examen_exament_id" name="examen_exament_id">
                                                            <option value="">Seleccione Examenes</option>
                                                            @foreach ($rsTipoExamen as $examen)
                                                                <option value="{{$examen->exament_id}}">{{ $examen->exament_nombre }} </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="sixteen wide field examen_observaciones required">
                                                    <label>Observaciones</label>
                                                    <input id="examen_observaciones" type="text" name="examen_observaciones">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="fields">
                                                <div class="sixteen wide field ">
                                                    <div id="grid_examen"></div>
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

            $("#examen_exament_id").dropdown({
                fullTextSearch:true
            });

            $('#consulta_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('ConsultaController@save') }}",
                    data: {
                        consulta_id: $("#consulta_id").val(),
                        consulta_historia_id: $("#consulta_historia_id").val(),
                        consulta_peso: $("#consulta_peso").val(),
                        consulta_mucosa: $("#consulta_mucosa").val(),
                        consulta_temperatura: $("#consulta_temperatura").val(),
                        consulta_frec_cardiaca: $("#consulta_frec_cardiaca").val(),
                        consulta_frec_respiratoria: $("#consulta_frec_respiratoria").val(),
                        consulta_observaciones: $("#consulta_observaciones").val(),
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
                                    $('#consulta_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Consulta Guardado');
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

            $("#examen_observaciones").keydown(function(e){
                var enter = 13;
                if(e.which==enter){
                    e.preventDefault();
                    agregar();
                }
            });

            $("#sintoma_descripcion").keydown(function(e){
                var enter = 13;
                if(e.which==enter){
                    e.preventDefault();
                    agregarSintomas();
                }
            });

            var mainDataSource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        dataSourceBinding(options, "{{ url('consulta/get-main-list-examen') }}")
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

                        id: "examen_id"
                    }
                }
            });

            var grid = $("#grid_examen").kendoGrid({
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
                        template: "#= eliminar #",
                        sortable: false,
                        attributes: {"class": "grid__cell_tool_menu"}
                    },

                    // {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                    {field: "examen_exament_id", title: 'EXAMEN', width: '80px'},
                    {field: "examen_observaciones", title: 'OBSERVACIONES', width: '80px'},

                ],

                sortable: true,
                dataBound: function (sender, args) {

                    $('.ajxDelete').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idExamen');
                        $.ajax({
                            url : "{{ action('ConsultaController@removeExamen') }}",
                            data : { id : id },
                            type : 'POST',
                            success : function(response){
                                if (response.status == STATUS_FAIL) {
                                    toast('error', 1500, response.msg );
                                }else if (response.status == STATUS_OK) {
                                    toast('success',3000,'Item Eliminado');
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

            var mainDataSource2 = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        dataSourceBinding(options, "{{ url('consulta/get-main-list-detalle') }}")
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

                        id: "sint_id"
                    }
                }
            });

            var grid = $("#grid_sintoma").kendoGrid({
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
                        template: "#= eliminar #",
                        sortable: false,
                        attributes: {"class": "grid__cell_tool_menu"}
                    },

                    // {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                    {field: "sintoma_nombre", title: 'SINTOMA', width: '80px'},
                    {field: "sintoma_descripcion", title: 'DESCRIPCION', width: '80px'},

                ],

                sortable: true,
                dataBound: function (sender, args) {

                    $('.ajxDelete').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idSintoma');
                        $.ajax({
                            url : "{{ action('ConsultaController@removeDetalle') }}",
                            data : { id : id },
                            type : 'POST',
                            success : function(response){
                                if (response.status == STATUS_FAIL) {
                                    toast('error', 1500, response.msg );
                                }else if (response.status == STATUS_OK) {
                                    toast('success',3000,'Item Eliminado');
                                    mainDataSource2.read();
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

            function agregar() {
                $.ajax({
                    url: "{{ action('ConsultaController@addExamen') }}",
                    data: {
                        examen_exament_id: $("#examen_exament_id").val(),
                        examen_observaciones: $("#examen_observaciones").val(),
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
                                    $('#consulta_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Detalle Agregado');
                            mainDataSource.read();
                            $('#examen_exament_id').dropdown('clear');
                            $("#examen_observaciones").val('');
                        }
                    },
                    statusCode: {
                        404: function () {
                            alert('Web not found');
                        }
                    }
                });
            }

            function agregarSintomas() {
                $.ajax({
                    url: "{{ action('ConsultaController@addDetalle') }}",
                    data: {
                        sintoma_nombre: $("#sintoma_nombre").val(),
                        sintoma_descripcion: $("#sintoma_descripcion").val(),
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
                                    $('#consulta_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Detalle Agregado');
                            mainDataSource2.read();
                            $("#sintoma_nombre").val('');
                            $("#sintoma_descripcion").val('');
                        }
                    },
                    statusCode: {
                        404: function () {
                            alert('Web not found');
                        }
                    }
                });
            }

        });

    </script>
@stop