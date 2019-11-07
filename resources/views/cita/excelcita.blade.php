<?php
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-type: application/xls; charset=utf-8");
header("Content-Disposition: attachment; filename=excel-cita.xls");
header("Content-Type: application/download");
header("Content-Transfer-Encoding: binary ");
?>

<style>
    .backTD{
        background-color:#ECF9FF;
        border: 1px solid #cccccc;
    }
    .TDBorder1{

        border: 1px solid #cccccc;
    }
    h3{
        border: 0;
    }

</style>

<div id="exportar" class="exportar">
    <div  class="table-responsive" tabindex="3" style="overflow: hidden; outline: none;">
        <table class="table table-striped" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td colspan="8" style="border: 0"></td>
            </tr>
            <tr style="border: 0">
                <td colspan="8" style="text-align: center; border:0"><h3>Reporte de citas</h3></td>
            </tr>
            <tr>
                <td colspan="8" style="border: 0"></td>
            </tr>
            <tr>
                <td class=backTD>N</td>
                <td style="text-align: right;" class=backTD>Mascota</td>
                <td style="text-align: right;" class=backTD>Motivo</td>
                <td style="text-align: right;" class=backTD>Fecha</td>
                <td style="text-align: right;" class=backTD>Hora</td>
                <td style="text-align: right;" class=backTD>Cliente</td>
                <td style="text-align: right;" class=backTD>Telefono</td>
                <td style="text-align: right;" class=backTD>E-mail</td>
            </tr>

            <?php
            $cont = 1;
            ?>

            @foreach($citas as $row )
                <tr>
                    <td class="TDBorder1">{{$cont}}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->mascota_nombre }}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->cita_motivo }}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->cita_fecha }}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->cita_hora }}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->cliente_fullname }}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->cliente_telefono }}</td>
                    <td style="text-align: right" class="TDBorder1">{{ $row->cliente_email }}</td>
                </tr>

                <?php //Sumatoria
                $cont = $cont + 1
                ?>

            @endforeach

            </tbody>
        </table>
    </div>
</div>

<?php
exit();