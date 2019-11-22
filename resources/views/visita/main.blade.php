@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless " data-color="inverted white" style="height:43px;">
            <div class="item ui colhidden">
                <button id="new_visita" class="ui button compact"><i class="icon plus"></i>Nueva Visita
                </button>
            </div>
            {{--<div class="item ui colhidden">--}}
                {{--<a href="{{ url('cita/down-excel') }}/{{ $desde }}/{{ $hasta }}" id="down_excel1" class="ui button compact"><i class="icon download"></i> Download Excel </a>--}}
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
                    // options.data.desde = function () { return $("#desde").val(); };
                    // options.data.hasta = function () { return $("#hasta").val(); };
                    dataSourceBinding(options, "{{ url('visita/get-main-list') }}")
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

                    id: "vsta_id"
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
                {field: "cliente_fullname", title: 'CLIENTE', width: '80px'},
                {field: "vsta_motivo", title: 'MOTIVO', width: '80px'},
                {field: "vsta_ticket_correlativo", title: 'TICKET', width: '80px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

                $('.ui.dropdown').dropdown({
                    context: '.k-grid-content'
                });

                /*$('.ajxEdit').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idVisita');
                    {{--window.location.href="{{ url('visita/editar') }}/"+id;--}}
                });*/

                $('.ajxUpAsignar').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idVisita');
                    $.ajax({
                        url : "{{ action('VisitaController@iniciarAsignacion') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Iniciar Asignacion');
                                window.location.href = "{{ url('mascota/') }}";
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

                $('.ajxUpAtender').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idVisita');
                    $.ajax({
                        url : "{{ action('VisitaController@iniciarAtencion') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Inicio de la Atencion');
                                if(response.idHistoria > 0){
                                    window.location.href="{{ url('/mascota/historia') }}/"+response.idHistoria;
                                }else{
                                    window.location.href="{{ url('mascota/editar') }}/";
                                }

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

                $('.ajxViewHistoria').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idVisita');
                    $.ajax({
                        url : "{{ action('VisitaController@viewHistoria') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Inicio de la Atencion');
                                if(response.idHistoria > 0){
                                    window.location.href="{{ url('/mascota/historia') }}/"+response.idHistoria;
                                }else{
                                    window.location.href="{{ url('mascota/editar') }}/";
                                }

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

                $('.ajxDelete').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idVisita');
                    $.ajax({
                        url : "{{ action('VisitaController@eliminar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Visita Eliminada');
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

            $('#new_visita').click(function (e) {
                window.location.href = "{{ url('visita/editar') }}";
            });

        });


    </script>

@stop