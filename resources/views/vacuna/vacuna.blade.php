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
                <button id="vacuna_save" class="ui button primary compact">
                    <i class="icon save"></i>Guardar
                </button>
            </div>
        </div>
    </div>
    <div class="mainWrap navslide">
        <div class="ui padded grid">
            <div class="sixteen wide column">
                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Vacuna</a>
                </div>
                <div class="ui bottom attached active tab segment" data-tab="first">
                    <form action="{{ action('VacunaController@save') }}" method="post" id="vacuna_ficha_registro"
                          class="ui form">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="fields">
                                <div class="sixteen wide field">
                                    <div class="ui fluid card">
                                        <div class="content">
                                            <input type="hidden" name="vac_id" id="vac_id"
                                                   @if (isset($rsVacuna)) value="{{$rsVacuna->vac_id}}" @endif>
                                            <div class="fields">
                                                <div class="eight wide field vac_descripcion required">
                                                    <label>Nombre</label>
                                                    <input id="vac_descripcion" type="text" name="vac_descripcion"
                                                           @if (isset($rsVacuna)) value="{{$rsVacuna->vac_descripcion}}" @endif>
                                                </div>
                                                <div class="eight wide field vac_abreviatura required">
                                                    <label>Siglas</label>
                                                    <input id="vac_abreviatura" type="text" name="vac_abreviatura"
                                                           @if (isset($rsVacuna)) value="{{$rsVacuna->vac_abreviatura}}" @endif>
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
                window.location.href = "{{ url('vacuna/') }}";
            });

            $('#vacuna_save').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ action('VacunaController@save') }}",
                    data: {
                        vac_id: $("#vac_id").val(),
                        vac_descripcion: $("#vac_descripcion").val(),
                        vac_abreviatura: $("#vac_abreviatura").val(),
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
                                    $('#vacuna_ficha_registro .' + k).addClass('error');
                                    if (k == 'detalle') toast('error', 1500, v);
                                });
                            }
                        } else if (response.status == STATUS_OK) {
                            toast('success', 3000, 'Vacuna Guardada');
                            window.location.href = "{{ url('vacuna/') }}";
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