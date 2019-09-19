@extends('layouts.main')

@section('content')
    {{--<div class="navslide navwrap" id="app_content_toolbar">--}}
    {{--<div class="ui menu icon borderless" data-color="inverted white">--}}
    {{--<div class="item ui colhidden">--}}
    {{--<button id="cliente-button-atras" class="ui button compact icon"><i class="icon arrow left"></i></button>--}}
    {{--</div>--}}
    {{--<div class="item ui colhidden">--}}
    {{--<button id="cliente-button-guardar" class="ui button primary compact"><i class="icon save"></i>Guardar</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless grid" data-color="inverted white">
            <div class="item ui colhidden">
                <button id="bmg-clientes-button-create" class="ui button compact"><i class="icon plus"></i>Nuevo Modulo
                </button>
            </div>
            <div class="item right ui colhidden">
                <div class="ui input inline">
                    <input type="text" placeholder="Buscar..." id="search">
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


        var mainDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    options.data.q = function () {
                        return $("#search_cliente").val();
                    };
                    dataSourceBinding(options, "{{ url('module/get-main-list') }}")
                }
            },
            serverFiltering: true,
            serverSorting: true,
            serverPaging: true,
            autoSync: true,
            pageSize: 1,
            schema: {
                data: 'data',
                total: 'count',
                model: {

                    id: "mod_id"
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
                {field: "url", title: 'URL', width: '120px'},
                {field: "orden", title: 'ORDEN', width: '120px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

                $('.ui.dropdown').dropdown({
                    context: '.k-grid-content'
                });

            }

        }).data("kendoGrid");


        $(document).ready(function () {
            mainDataSource.read();

            // $('#bmg-clientes-button-create').click(function(e){
            //         window.location.href="/#clientes/local-nuevo";
            // });
            //
            // $("#search_cliente").keyup(function(e){
            //     e.preventDefault();
            //     var enter = 13;
            //     if(e.which == enter)
            //     {
            //         // mainDataSource.page(0);
            //         mainDataSource.read();
            //     }
            // });
            //
            // $('#actualizar_cliente').click(function(e){
            //     loadDialogUi('/clientes/svc/ajx-ficha-excel');
            // });
        });


    </script>

@stop