<div class="ui small modal modulo_dialog">
    <div class="header"> Grafica de Eficiencia </div>
    <div class="content">
        <form class="ui form">
            <div class="eight wide field">
                <div id="charts"></div>
            </div>
            <div class="eight wide field">
                <div id="chart" style="background: center no-repeat;"></div>
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

    $("#btn-cancelar").click(function(e){
        e.preventDefault();
        $('.modulo_dialog').modal('hide');
    });

    /*$("#charts").kendoChart({
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
            /!*overlay: {
                gradient: "none"
            },*!/
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
    });*/
    $("#chart").kendoChart({
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
        $('.modulo_dialog').modal('show');
    });

</script>