<?php

namespace App\Http\Controllers;

use App\ReportTest;
use App\Visita;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportClienteController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();
        $first = Carbon::now();
        $first->startOfMonth();

        return view('reportcliente.main',[
            'desde'  => (new Carbon($first))->format(UI_DATE_FORMAT),
            'hasta'  => (new Carbon($hoy))->format(UI_DATE_FORMAT),
        ]);
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $parte = explode('/', $request->input('desde'));
        $desde = (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d');

        $parte = explode('/', $request->input('hasta'));
        $hasta = (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d');
        $fecha = $desde;

        while($hasta >= $fecha){

            $rows [] = (object)[
                                    'fecha' => (new Carbon($fecha))->format('d/m/Y'),
                                    'clientesA' => Visita::countDuracionAtencion($fecha),
                                    'clientesF' => Visita::countHistoriasEncontradas($fecha),
                                    'clientesT' => Visita::countClientesVisita($fecha),
                                ];

            $fecha = new Carbon($fecha);
            $fecha = $fecha->addDays(1);
            $fecha = (new Carbon($fecha))->format('Y/m/d');
        }

        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => count($rows) ]]);
    }

    public function getGrafica(Request $request){

        $eficacia = 58;
        $others = 42;

        $view = view('reportcliente.grafica',[
            'eficacia'  => $eficacia,
            'others'  => $others,
        ])->render();

        return response()->json(['html'=>$view]);

    }
}
