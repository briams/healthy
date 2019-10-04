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
                <button id="module_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
      {{--      {% if registro != '' %}
            <div class="item ui colhidden">
                <h3 style="text-transform: uppercase;">{{registro.fv_documento}} | {{registro.fv_full_name}}</h3>
            </div>
            {% endif %}--}}
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first"> Modulo </a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('ModuleController@save') }}" method="post" id="module_ficha_registro" class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        {{--<div class="content">--}}
                                            {{--<div class="header">Modulo</div>--}}
                                        {{--</div>--}}
                                        <div class="content">
                                            <input type="hidden" name="idModule" id="idModule"  @if (isset($modulo)) value="{{$modulo->idModule}}" @endif>
                                            <div class="fields">
                                                <div class="six wide field required nombre">
                                                    <label>Nombre</label>
                                                    <input type="text" name="nombre" id="nombre" placeholder="Nombre"  @if (isset($modulo)) value="{{$modulo->nombre}}" @endif>
                                                </div>
                                                <div class="six wide field required url">
                                                    <label>Url</label>
                                                    <input type="text" name="url" id="url" placeholder="Url"  @if (isset($modulo)) value="{{$modulo->url}}" disabled @endif>
                                                </div>
                                                <div class="six wide field required icono">
                                                    <label>Icono</label>
                                                    <input type="text" name="icono" id="icono" placeholder="Icono"  @if (isset($modulo)) value="{{$modulo->icono}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="six wide field required orden">
                                                    <label>Orden</label>
                                                    <input type="number" name="orden" id="orden" placeholder="Orden" maxlength="9" @if (isset($modulo)) value="{{$modulo->orden}}" @endif>
                                                </div>
                                                <div class="six wide field is_parent">
                                                    <label> &nbsp; </label>
                                                    <div class="ui checkbox is_parent">
                                                        <input value="1" type="checkbox" id="is_parent" name="is_parent" @if (isset($modulo)) @if ($modulo->is_parent == 1) checked @endif @endif>
                                                        <label for="is_parent"><strong>Is Parent</strong></label>
                                                    </div>
                                                </div>
                                                <div class="six wide field padre_id" id="div_drop_padre">
                                                    @if (count($moduloPadre) > 0)
                                                    <label>Modulo Padre</label>
                                                    <select class="ui search dropdown" id="padre_id" name="padre_id">
                                                        <option value="">Seleccione Padre</option>
                                                        @foreach ($moduloPadre as $padre)
                                                        <option value="{{$padre->idModule}}" @if (isset($modulo)) @if ($modulo->padre_id == $padre->idModule ) selected @endif @endif >{{ $padre->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                </div>
                                                <div class="six wide field" style="display: none" id="div_visual">
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
                window.location.href = "{{ url('module/') }}";
            });

            $("#padre_id").dropdown({
                fullTextSearch:true
            });

            is_parent();

            $('#is_parent').click(function(e){
                is_parent();
            });

            $('#module_save').click(function(e){
                e.preventDefault();
                if($('#is_parent').is(':checked'))
                    is_parent=1;
                else
                    is_parent=0;
                $.ajax({
                    url : "{{ action('ModuleController@save') }}",
                    data : {
                        idModule : $("#idModule").val(),
                        nombre : $("#nombre").val(),
                        url : $("#url").val(),
                        icono : $("#icono").val(),
                        orden : $("#orden").val(),
                        padre_id : $("#padre_id").val(),
                        is_parent : is_parent,
                    },
                    type : 'POST',
                    success : function(response){
                        var data = response;
                        $('.field').removeClass('error');
                        if (response.status == STATUS_FAIL) {
                            toast('error', 1500, data.msg );
                            msg = data.data;
                            if (msg) {
                                $.each(msg, function (k, v) {
                                    $('#module_ficha_registro .' + k).addClass('error');
                                    if(k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        }else if (response.status == STATUS_OK) {
                            toast('success',3000,'Modulo Guardado');
                            window.location.href = "{{ url('module/') }}";
                        }
                    },
                    statusCode : {
                        404 : function(){
                            alert('Web not found');
                        }
                    }
                });
            });

        });

        function is_parent(){
            if($('#is_parent').is(':checked')){
                $('#div_drop_padre').hide();
                $('#padre_id').dropdown('clear');
                $('#div_visual').show();
            }
            else{
                $('#div_drop_padre').show();
                $('#div_visual').hide();
            }
        }

    </script>

@stop