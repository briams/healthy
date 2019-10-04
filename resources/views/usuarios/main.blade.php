@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless grid" data-color="inverted white">
            <div class="item ui colhidden">
                <button id="new_user" class="ui button compact"><i class="icon plus"></i>Nuevo Usuario
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
                    // options.data.q = function () {
                    //     return $("#search_cliente").val();
                    // };
                    dataSourceBinding(options, "{{ url('usuarios/get-main-list') }}")
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

                    id: "idUsuario"
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
                {field: "nombre", title: 'NOMBRE', width: '80px'},
                {field: "apellido", title: 'APELLIDO', width: '120px'},
                {field: "numero_doc", title: 'NRO DOCUMENTO', width: '120px'},
                {field: "email", title: 'EMAIL', width: '120px'},
                {field: "telefono", title: 'TELEFONO', width: '120px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

                $('.ui.dropdown').dropdown({
                    context: '.k-grid-content'
                });

                $('.ajxEdit').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idUser');
                    window.location.href="{{ url('usuarios/editar') }}/"+id;
                });

                $('.ajxDown').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idUser');
                    $.ajax({
                        url : "{{ action('UsuarioController@bloquear') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Usuario Bloqueado');
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
                    var id = $(this).attr('data-idUser');
                    $.ajax({
                        url : "{{ action('UsuarioController@activar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Usuario Activado');
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
                    var id = $(this).attr('data-idUser');
                    $.ajax({
                        url : "{{ action('UsuarioController@eliminar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Usuario Eliminado');
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

            $('#new_user').click(function (e) {
                window.location.href = "{{ url('usuarios/editar') }}";
            });

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


    </script>

@stop