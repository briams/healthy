<div class="ui long modal modulo_dialog">
    <div class="header"> Grafica de Eficiencia </div>
    <div class="content ">
        <form class="ui form">
            <div class="ui grid ">
                <div class="sixteen wide column">
                    <div class="ui fluid card">
                        <div class="content">
                            <div class="fields">
                                <div class="eight wide field">

                                    <div id="charts"></div>

                                </div>
                                <div class="eight wide field">

                                    <div id="chart"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="actions">
        <div id="btn-cancelar" class="compact ui button">Cancelar</div>
        {{--<div id="btn-aceptar" class="compact ui right labeled icon primary button">--}}
            {{--<i class="right check icon"></i>Guardar</div>--}}
    </div>
</div>

<script type="text/javascript">

    /*$("#btn-cancelar").click(function(e){
        e.preventDefault();
        $('.modulo_dialog').modal('hide');
        $("#content-model").html('');
        $(".ui.dimmer.modals.page.transition.hidden").html('');

    });*/

    $("#charts").kendoChart({
        chartArea: {
            width: 360,
            height: 360
        },
        title: {
            text: "Eficiencia"
        },
        legend: {
            position: "bottom" //top
        },
        seriesDefaults: {
            labels: {
                visible: true,
                // format: "{0}%",
                template: "#= category #"
            }
        },
        series: [{
            type: "pie",
            /*overlay: {
                gradient: "none"
            },*/
            data: [ {
                category: "Eficacia",
                value: '{{ $eficacia }}',
                explode: true

            }, {
                category: "Others",
                value:'{{ $others }}'
            } ]
        }],
        tooltip: {
            visible: true,
            // format: "{0}%"
            template: "${ category } - ${ value }%"
        }
    });
    $("#chart").kendoChart({
        chartArea: {
            width: 360,
            height: 360
        },
        title: {
            text: "Eficiencia"
        },
        legend: {
            position: "bottom" //top
        },
        seriesDefaults: {
            labels: {
                visible: true,
                // format: "{0}%",
                template: "#= category #"
            }
        },
        series: [{
            type: "pie",
            /*overlay: {
                gradient: "none"
            },*/
            data: [ {
                category: "Eficacia",
                value: '{{ $eficacia }}',
                explode: true

            }, {
                category: "Others",
                value:'{{ $others }}'
            } ]
        }],
        tooltip: {
            visible: true,
            // format: "{0}%"
            template: "${ category } - ${ value }%"
        }
    });

    $(document).ready(function()
    {
        $('.modulo_dialog').modal({
            closable: false,
        }).modal('show');
    });

</script>