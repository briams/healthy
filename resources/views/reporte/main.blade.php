@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless " data-color="inverted white" style="height:43px;">
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
            <div class="item right ui colhidden">

            </div>
        </div>
    </div>

    <div class="mainWrap navslide">
        <div id="grid"></div>
    </div>
@stop

@extends('layouts.stylepdf')

@section('titule','Reporte de Productos')

@section('scripts')

    <script type="text/javascript">

        $('#desde').flatpickr({
            // maxDate: new Date(),
            // locale:'es',
            dateFormat:'d/m/Y',
            'onChange':function(){
                mainDataSource.read();
            }
        });

        $('#hasta').flatpickr({
            // maxDate: new Date(),
            // locale:'es',
            dateFormat:'d/m/Y',
            'onChange':function(){
                mainDataSource.read();
            }
        });

        var mainDataSource = new kendo.data.DataSource({
            transport: {
                read: function (options) {
                    options.data.desde = function () { return $("#desde").val(); };
                    options.data.hasta = function () { return $("#hasta").val(); };
                    dataSourceBinding(options, "{{ url('reporte-producto/get-main-list') }}")
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

                    id: "asig_id"
                }
            }
        });

        var grid = $("#grid").kendoGrid({
            toolbar: [
                // "excel",
                // "pdf"
                { name: "excel", text: "Excel"},
                { name: "pdf", text: "Pdf"}
            ],
            excel: {
                fileName: "Products Report.xlsx",
                proxyURL: "https://demos.telerik.com/kendo-ui/service/export",
                allPages: true,
                filterable: true
            },
            pdf: {
                title: "Products Report",
                fileName: "Products Report.pdf",
                allPages: true,
                avoidLinks: true,
                paperSize: "A4",
                margin: { top: "2cm", left: "1cm", right: "1cm", bottom: "1cm" },
                landscape: false,//true = horizontal   false = vertical
                repeatHeaders: true,
                template: $("#page-template").html(),
                scale: 0.8
            },
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
                // {
                //     field: "&nbsp;",
                //     width: 50,
                //     template: "#= tool #",
                //     sortable: false,
                //     attributes: {"class": "grid__cell_tool_menu"}
                // },

                {field: "tratamientod_producto_id", title: 'PRODUCTO', width: '80px'},
                {field: "total", title: 'TOTAL', width: '80px'},
                {field: "tratamiento_tipo", title: 'TIPO', width: '80px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

            }

        }).data("kendoGrid");


        $(document).ready(function () {
            mainDataSource.read();

            // $('#desde').click(function (e) {
            //     mainDataSource.read();
            // });
            // $('#hasta').click(function (e) {
            //     mainDataSource.read();
            // });

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