<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Tratamiento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();
        $first = Carbon::now();
        $first->startOfMonth();
        return view('reporte.main',[
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

        $rows = Tratamiento::getCountProductFecha($desde, $hasta);

        foreach ($rows as $row) {

            if($row->tratamiento_tipo == 1) {
                $row->tratamiento_tipo = 'tratamiento interno';
            }elseif ($row->tratamiento_tipo == 2){
                $row->tratamiento_tipo = 'Receta';
            }

            $row->tratamientod_producto_id = (Producto::getProducto($row->tratamientod_producto_id))->pro_nombre;

        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => count($rows)]]);
    }
}
