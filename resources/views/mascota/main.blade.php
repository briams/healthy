@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless" data-color="inverted white" style="height:43px;">
            <div class="item ui colhidden">
                <button id="new_mascota" class="ui button compact"><i class="icon plus"></i>Nuevo Mascota
                </button>
            </div>
            <div class="item ui colhidden">
                @if (count($especies) > 0)
                    <select class="ui dropdown" id="especie" name="especie">
                        <option value="">Seleccione Especie</option>
                        <option value="0">Todas</option>
                        @foreach ($especies as $especie)
                            <option value="{{$especie->especie_id}}">{{ $especie->especie_nombre }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="item ui colhidden">
                <select class="ui dropdown" id="raza" name="raza">

                </select>
            </div>
            <div class="item ui colhidden">
                @if (count($sexos) > 0)
                    <select class="ui dropdown" id="sexo" name="sexo">
                        <option value="">Seleccione Sexo</option>
                        <option value="0">Todas</option>
                        @foreach ($sexos as $sexo)
                            <option value="{{$sexo->sexo_id}}">{{ $sexo->sexo_nombre }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="item right ui colhidden" @if ( $visita != '' ) @if ( $visita->vsta_estado != 1 ) style="display: none;" @endif @else style="display: none;" @endif>
                <button id="historia_asignar" class="ui button compact"><i class="icon x icon"></i> Sin Historia Registrada
                </button>
            </div>
            {{--<div class="item right ui colhidden">--}}
                {{--<div class="ui input inline">--}}
                    {{--<input type="text" placeholder="Buscar..." id="search">--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

    <div class="mainWrap navslide">
        <div id="grid"></div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">

        var mainDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    options.data.especie = function () { return $("#especie").val(); };
                    options.data.raza = function () { return $("#raza").val(); };
                    options.data.sexo = function () { return $("#sexo").val(); };
                    dataSourceBinding(options, "{{ url('mascota/get-main-list') }}")
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

                    id: "mascota_id"
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

                {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                {field: "mascota_nombre", title: 'NOMBRE', width: '80px'},
                {field: "cliente_fullname", title: 'DUEÑO(CLIENTE)', width: '80px'},
                {field: "especie_nombre", title: 'ESPECIE', width: '80px'},
                {field: "raza_nombre", title: 'RAZA', width: '80px'},
                {field: "sexo_nombre", title: 'SEXO', width: '80px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

                $('.ui.dropdown').dropdown({
                    context: '.k-grid-content'
                });

                $('.ajxEdit').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idMasc');
                    window.location.href="{{ url('mascota/editar') }}/"+id;
                });

                $('.ajxHistoria').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idMasc');
                    window.location.href="{{ url('mascota/historia') }}/"+id;
                });

                $('.ajxDown').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idMasc');
                    $.ajax({
                        url : "{{ action('MascotaController@bloquear') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, response.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Mascota Bloqueada');
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

                $('.ajxUp').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idMasc');
                    $.ajax({
                        url : "{{ action('MascotaController@activar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, response.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Mascota Activada');
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

                $('.ajxDelete').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idMasc');
                    $.ajax({
                        url : "{{ action('MascotaController@eliminar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, response.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Mascota Eliminada');
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


        $(document).ready(function () {

            mainDataSource.read();

            $('#historia_asignar').click(function(e){
                e.preventDefault();
                $.ajax({
                    url : "{{ action('VisitaController@confirmarAsignacion') }}",
                    data : {  },
                    type : 'POST',
                    success : function(response){
                        if (response.status == STATUS_FAIL) {
                            toast('error', 1500, response.msg );
                        }else if (response.status == STATUS_OK) {
                            toast('success',3000,'Sin historia asignada');
                            window.location.href = "{{ url('visita/') }}";
                            // mainDataSource.read();
                        }
                    },
                    statusCode : {
                        404 : function(){
                            alert('Web not found');
                        }
                    }
                });
            });

            $('#new_mascota').click(function (e) {
                window.location.href = "{{ url('mascota/editar') }}";
            });

            $("#sexo").change(function(e){
                e.preventDefault();
                mainDataSource.read();
            });

            $("#raza").change(function(e){
                e.preventDefault();
                mainDataSource.read();
            });

            $("#especie").change(function(e){
                e.preventDefault();
                $('#raza').dropdown('clear');
                accion = 3;
                cargarRaza(accion);
                mainDataSource.read();
            });

            cargarRaza(3);

            // $("#search_cliente").keyup(function(e){
            //     e.preventDefault();
            //     var enter = 13;
            //     if(e.which == enter)
            //     {
            //         // mainDataSource.page(0);
            //         mainDataSource.read();
            //     }
            // });
        });

        function cargarRaza(accion){
            var idMascota = 0;
            var idEspecie = $('#especie').val();
            idEspecie = ( idEspecie > 0 ) ? idEspecie : 0;
            $.post("{{ action('MascotaController@cargarRaza') }}", { idMascota: idMascota, idEspecie: idEspecie, accion: accion } , function(data) {
                $('#raza').html('<option value="0">Todas</option>'+data.raza);
            });
        }

    </script>

@stop