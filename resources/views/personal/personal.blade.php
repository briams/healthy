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
                <button id="personal_save" class="ui button primary compact">
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
                    <a class="active item" data-tab="first"> Personal </a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('PersonalController@save') }}" method="post" id="personal_ficha_registro" class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        {{--<div class="content">--}}
                                            {{--<div class="header">Usuarios</div>--}}
                                        {{--</div>--}}
                                        <div class="content">
                                            <input type="hidden" name="personal_id" id="personal_id"  @if (isset($rsPersonal)) value="{{$rsPersonal->personal_id}}" @endif>
                                            <div class="fields">
                                                <div class="six wide field required personal_nombre">
                                                    <label>Nombre</label>
                                                    <input type="text" name="personal_nombre" id="personal_nombre" placeholder="Nombre"  @if (isset($rsPersonal)) value="{{$rsPersonal->personal_nombre}}" @endif>
                                                </div>
                                                <div class="six wide field required personal_apellido">
                                                    <label>Apellido</label>
                                                    <input type="text" name="personal_apellido" id="personal_apellido" placeholder="Apellido"  @if (isset($rsPersonal)) value="{{$rsPersonal->personal_apellido}}" @endif>
                                                </div>
                                                <div class="six wide field required personal_dni">
                                                    <label>DNI</label>
                                                    <input type="text" name="personal_dni" id="personal_dni" placeholder="NroDoc"  @if (isset($rsPersonal)) value="{{$rsPersonal->personal_dni}}" @endif>
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="six wide field required personal_email">
                                                    <label>E-mail</label>
                                                    <input type="email" name="personal_email" id="personal_email" placeholder="Email" @if (isset($rsPersonal)) value="{{$rsPersonal->personal_email}}" @endif>
                                                </div>
                                                <div class="six wide field required personal_telefono">
                                                    <label>Telefono</label>
                                                    <input type="text" name="personal_telefono" id="personal_telefono" placeholder="Telefono" @if (isset($rsPersonal)) value="{{$rsPersonal->personal_telefono}}" @endif>
                                                </div>
                                                <div class="six wide field personal_cargo" >
                                                    @if (count($cargo) > 0)
                                                    <label>Perfil</label>
                                                    <select class="ui search dropdown" id="personal_cargo" name="personal_cargo">
                                                        <option value="">Seleccione Perfil</option>
                                                        @foreach ($cargo as $row)
                                                        <option value="{{$row->cargo_id}}" @if (isset($rsPersonal)) @if ($row->cargo_id == $rsPersonal->personal_cargo ) selected @endif @endif >{{ $row->cargo_nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="fields">
                                                <div class="six wide field personal_nacimiento required">
                                                    <label>Fecha Nacimiento</label>
                                                    <div class="ui input left icon">
                                                        <i class="calendar icon"></i>
                                                        <input id="personal_nacimiento" type="text" name="personal_nacimiento"
                                                               @if (isset($rsPersonal)) value="{{$rsPersonal->personal_nacimiento}}" @endif>
                                                    </div>
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
                window.location.href = "{{ url('personal/') }}";
            });

            $("#personal_cargo").dropdown({
                fullTextSearch:true
            });

            $('#personal_nacimiento').flatpickr({
                maxDate: new Date(),
                // locale:'es',
                dateFormat:'d/m/Y'
            });

            $('#personal_save').click(function(e){
                e.preventDefault();
                $.ajax({
                    url : "{{ action('PersonalController@save') }}",
                    data : {
                        personal_id : $("#personal_id").val(),
                        personal_nombre : $("#personal_nombre").val(),
                        personal_apellido : $("#personal_apellido").val(),
                        personal_dni : $("#personal_dni").val(),
                        personal_email : $("#personal_email").val(),
                        personal_telefono : $("#personal_telefono").val(),
                        personal_nacimiento : $("#personal_nacimiento").val(),
                        personal_cargo : $("#personal_cargo").val(),
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
                                    $('#personal_ficha_registro .' + k).addClass('error');
                                    if(k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        }else if (response.status == STATUS_OK) {
                            toast('success',3000,'Personal Guardado');
                            window.location.href = "{{ url('personal/') }}";
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

    </script>

@stop