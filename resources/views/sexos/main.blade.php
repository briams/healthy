@extends('layouts.main')

@section('content')
    <div class="navslide navwrap" id="app_content_toolbar">
        <div class="ui menu icon borderless grid" data-color="inverted white">
            <div class="item ui colhidden">
                <button id="new_sexo" class="ui button compact"><i class="icon plus"></i>Nuevo Sexo
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
                    dataSourceBinding(options, "{{ url('sexo/get-main-list') }}")
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

                    id: "idSexo"
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

                {field: "sexo_nombre", title: 'NOMBRE', width: '80px'},

            ],

            sortable: true,
            dataBound: function (sender, args) {

                $('.ui.dropdown').dropdown({
                    context: '.k-grid-content'
                });

                $('.ajxEdit').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idSexo');
                    window.location.href="{{ url('sexo/editar') }}/"+id;
                });

                $('.ajxDelete').click(function(e){
                    e.preventDefault();
                    var id = $(this).attr('data-idSexo');
                    $.ajax({
                        url : "{{ action('SexoController@eliminar') }}",
                        data : { id : id },
                        type : 'POST',
                        success : function(response){
                            if (response.status == STATUS_FAIL) {
                                toast('error', 1500, data.msg );
                            }else if (response.status == STATUS_OK) {
                                toast('success',3000,'Sexo Eliminado');
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

            $('#new_sexo').click(function (e) {
                window.location.href = "{{ url('sexo/editar') }}";
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