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
                <button id="tratamiento_save" class="ui button primary compact">
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
                    <a class="active item" data-tab="first">Tratamiento</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('TratamientoController@save') }}" method="post" id="tratamiento_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="tratamiento_id" id="tratamiento_id"
                                                   @if (isset($rsTratamiento)) value="{{$rsTratamiento->tratamiento_id}}" @endif>

                                            <input type="hidden" name="tratamiento_historia_id" id="tratamiento_historia_id"
                                                   @if (isset($idHistoria)) value="{{ $idHistoria  }}" @endif>
                                            <div class="fields">
                                                <div class="sixteen wide field tratamiento_descripcion required">
                                                    <label>Descripcion</label>
                                                    <input id="tratamiento_descripcion" type="text" name="tratamiento_descripcion"
                                                           @if (isset($rsTratamiento)) value="{{$rsTratamiento->tratamiento_descripcion}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field tratamiento_tipo required">
                                                    <label>Tipo</label>
                                                    <select class="ui search dropdown" id="tratamiento_tipo" name="tratamiento_tipo">
                                                        <option value="">Seleccione Tipo</option>
                                                        <option value="1" @if (isset($rsTratamiento)) @if ( 1 == $rsTratamiento->tratamiento_tipo ) selected @endif @endif > Tratamiento Interno </option>
                                                        <option value="2" @if (isset($rsTratamiento)) @if ( 2 == $rsTratamiento->tratamiento_tipo ) selected @endif @endif > Receta </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <div class="header">Detalle</div>
                                        </div>
                                        <div class="content">
                                            <div class="fields">
                                                <div class="six wide field tratamientod_producto_id" >
                                                    @if (count($rsProductos) > 0)
                                                        <label>Producto</label>
                                                        <select class="ui search dropdown" id="tratamientod_producto_id" name="tratamientod_producto_id">
                                                            <option value="">Seleccione Producto</option>
                                                            @foreach ($rsProductos as $producto)
                                                                <option value="{{$producto->pro_id}}">{{ $producto->pro_nombre }} ( {{ $producto->umd_descripcion }} )</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="six wide field tratamientod_frecuencia required">
                                                    <label>Frecuencia (horas)</label>
                                                    <input id="tratamientod_frecuencia" type="number" min="1" name="tratamientod_frecuencia">
                                                </div>
                                                <div class="six wide field tratamientod_duracion required">
                                                    <label>Duracion (dias)</label>
                                                    <input id="tratamientod_duracion" type="number" min="1" name="tratamientod_duracion">
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field tratamientod_dosis required">
                                                    <label>Dosis</label>
                                                    <input id="tratamientod_dosis" type="text" name="tratamientod_dosis">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
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

            $("#tratamiento_tipo").dropdown({
                fullTextSearch:true
            });

            $("#tratamientod_producto_id").dropdown({
                fullTextSearch:true
            });

            $('#tratamiento_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('TratamientoController@save') }}",
                    data: {
                        tratamiento_id: $("#tratamiento_id").val(),
                        tratamiento_historia_id: $("#tratamiento_historia_id").val(),
                        tratamiento_descripcion: $("#tratamiento_descripcion").val(),
                        tratamiento_tipo: $("#tratamiento_tipo").val(),
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
                                    $('#tratamiento_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Tratamiento Guardado');
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

            $("#tratamientod_dosis").keydown(function(e){
                var enter = 13;
                if(e.which==enter){
                    e.preventDefault();
                    agregar();
                }
            });

            var mainDataSource = new kendo.data.DataSource({
                transport: {
                    read: function (options) {
                        dataSourceBinding(options, "{{ url('tratamiento/get-main-list-detalle') }}")
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
                        template: "#= eliminar #",
                        sortable: false,
                        attributes: {"class": "grid__cell_tool_menu"}
                    },

                    // {field: "&nbsp;", title: 'ESTADO', width: "60px", template: "#= estado #"},
                    {field: "tratamientod_producto_id", title: 'PRODUCTO', width: '80px'},
                    {field: "tratamientod_frecuencia", title: 'FRECUENDIA', width: '80px'},
                    {field: "tratamientod_duracion", title: 'DURACION', width: '80px'},
                    {field: "tratamientod_dosis", title: 'DOSIS', width: '80px'},

                ],

                sortable: true,
                dataBound: function (sender, args) {

                    $('.ajxDelete').click(function(e){
                        e.preventDefault();
                        var id = $(this).attr('data-idProducto');
                        $.ajax({
                            url : "{{ action('TratamientoController@removeDetalle') }}",
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
            
            function agregar() {
                $.ajax({
                    url: "{{ action('TratamientoController@addDetalle') }}",
                    data: {
                        tratamientod_producto_id: $("#tratamientod_producto_id").val(),
                        tratamientod_frecuencia: $("#tratamientod_frecuencia").val(),
                        tratamientod_duracion: $("#tratamientod_duracion").val(),
                        tratamientod_dosis: $("#tratamientod_dosis").val(),
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
                                    $('#tratamiento_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Detalle Agregado');
                            mainDataSource.read();
                            $('#tratamientod_producto_id').dropdown('clear');
                            $("#tratamientod_frecuencia").val('');
                            $("#tratamientod_duracion").val('');
                            $("#tratamientod_dosis").val('');
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