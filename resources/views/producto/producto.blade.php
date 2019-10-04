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
                <button id="producto_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Producto</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('ProductoController@save') }}" method="post" id="producto_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="pro_id" id="pro_id"
                                                   @if (isset($rsProducto)) value="{{$rsProducto->pro_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field pro_codigo required">
                                                    <label>Codigo</label>
                                                    <input id="pro_codigo" type="text" name="pro_codigo"
                                                           @if (isset($rsProducto)) value="{{$rsProducto->pro_codigo}}" @endif>
                                                </div>
                                                <div class="eight wide field pro_nombre required">
                                                    <label>Nombre</label>
                                                    <input id="pro_nombre" type="text" name="pro_nombre"
                                                           @if (isset($rsProducto)) value="{{$rsProducto->pro_nombre}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="eight wide field pro_unidad_medida" >
                                                    @if (count($UnidadMedidas) > 0)
                                                        <label>Modulo Padre</label>
                                                        <select class="ui search dropdown" id="pro_unidad_medida" name="pro_unidad_medida">
                                                            <option value="">Seleccione Unidad Medida</option>
                                                            @foreach ($UnidadMedidas as $unidad)
                                                                <option value="{{$unidad->umd_id}}" @if (isset($rsProducto)) @if ($unidad->umd_id == $rsProducto->pro_unidad_medida ) selected @endif @endif >{{ $unidad->umd_descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                                <div class="eight wide field pro_laboratorio required">
                                                    <label>Laboratorio</label>
                                                    <input id="pro_laboratorio" type="text" name="pro_laboratorio"
                                                           @if (isset($rsProducto)) value="{{$rsProducto->pro_laboratorio}}" @endif>
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
                window.location.href = "{{ url('producto/') }}";
            });

            $("#pro_unidad_medida").dropdown({
                fullTextSearch:true
            });

            $('#producto_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('ProductoController@save') }}",
                    data: {
                        pro_id: $("#pro_id").val(),
                        pro_codigo: $("#pro_codigo").val(),
                        pro_nombre: $("#pro_nombre").val(),
                        pro_unidad_medida: $("#pro_unidad_medida").val(),
                        pro_laboratorio: $("#pro_laboratorio").val(),
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
                                    $('#producto_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Producto Guardado');
                            window.location.href = "{{ url('producto/') }}";
                        }
                    },
                    statusCode: {
                        404: function () {
                            alert('Web not found');
                        }
                    }
                });
            });

        });

    </script>
@stop