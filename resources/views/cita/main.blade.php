@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless " data-color="inverted white" style="height:43px;">
            <div class="item ui colhidden">
                <button id="new_cita" class="ui button compact"><i class="icon plus"></i>Nueva Cita
                </button>
            </div>
            <div class="item ui colhidden">
                {{--<button id="down_excel" class="ui button compact"><i class="icon download"></i>Down Cita--}}
                {{--</button>--}}
                <a href="{{ url('cita/down-excel') }}/{{ $desde }}/{{ $hasta }}" id="down_excel1" class="ui button compact"><i class="icon download"></i> Download Excel </a>
            </div>
            <div class="item ui colhidden">
                <div class="header">
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input id="desde" type="text"  name="desde" autocomplete="off" value="{{ $desde }}">
                    </div>
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input id="hasta" type="text"  name="hasta" autocomplete="off" value="{{ $hasta }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mainWrap navslide">
        <div id="grid"></div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">

        var desde = "{{ $desde }}";
        var hasta = "{{ $hasta }}";

        $('#desde').flatpickr({
            maxDate: new Date(),
            // locale:'es',
            dateFormat:'d/m/Y',
            'onChange':function(){
                desde = $("#desde").val();
                mainDataSource.read();
                $('#down_excel1').attr('href', "{{ url('cita/down-excel') }}"+'/'+desde+'/'+hasta );
            }
        });

        $('#hasta').flatpickr({
            maxDate: new Date(),
            // locale:'es',
            dateFormat:'d/m/Y',
            'onChange':function(){
                hasta = $("#hasta").val();
                mainDataSource.read();
                $('#down_excel1').attr('href', "{{ url('cita/down-excel') }}"+'/'+desde+'/'+hasta );
            }
        });

        var mainDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    options.data.desde = function () { return $("#desde").val(); };
                    options.data.hasta = function () { return $("#hasta").val(); };
                    dataSourceBinding(options, "{{ url('cita/get-main-list') }}")
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

                    id: "cita_id"
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
                {field: "mascota_nombre", title: 'MASCOTA', width: '80px'},
                {field: "cita_fecha", title: 'FECHA', width: '80px'},
                {field: "cita_motivo", title: 'MOTIVO', width: '80px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

                $('.ui.dropdown').dropdown({
                    context: '.k-grid-content'
                });

                $('.ajxEdit').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idCita');
                    window.location.href="{{ url('cita/editar') }}/"+id;
                });

                $('.ajxDown').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idCita');
                    $.ajax({
                        url : "{{ action('CitaController@bloquear') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'cita Bloqueada');
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
                    var id = $(this).attr('data-idCita');
                    $.ajax({
                        url : "{{ action('CitaController@activar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'cita Activada');
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
                    var id = $(this).attr('data-idCita');
                    $.ajax({
                        url : "{{ action('CitaController@eliminar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'cita Eliminada');
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

            $('#new_cita').click(function (e) {
                window.location.href = "{{ url('cita/editar') }}";
            });

            $('#down_excel').click(function(e){
                e.preventDefault();
                var desde = $("#desde").val();
                var hasta = $("#hasta").val();

                {{--$.fileDownload("{{ action('CitaController@downExcel') }}", {--}}
                    {{--httpMethod: 'POST',--}}
                    {{--data : { desde : desde, hasta : hasta },--}}
                    {{--prepareCallback: function (url) {--}}
                    {{--},--}}
                    {{--failCallback:function(html,error){--}}
                    {{--}--}}
                {{--});--}}

                $.ajax({
                    url : "{{ action('CitaController@downExcel') }}",
                    data : { desde : desde, hasta : hasta },
                    type : 'POST',
                    success : function(response){
                        toast('success',3000,'Descarga Exitosa');
                    },
                    statusCode : {
                        404 : function(){
                            alert('Web not found');
                        }
                    }
                });
            });
        });


    </script>

@stop