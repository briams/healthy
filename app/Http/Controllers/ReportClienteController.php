<?php

namespace App\Http\Controllers;

use App\ReportTest;
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
        $rows = ReportTest::getCountClienteFecha($take, $skip, $desde, $hasta);
        $count = count(ReportTest::CountClienteFecha($desde, $hasta));

//        dd($rows);
        foreach ($rows as $row) {

            $row->fecha = (new Carbon($row->fecha))->format('d/m/Y');



        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $count ]]);
    }
}
