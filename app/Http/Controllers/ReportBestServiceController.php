<?php

namespace App\Http\Controllers;

use App\Personal;
use App\ReportTest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportBestServiceController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();
        $first = Carbon::now();
        $first->startOfMonth();

        $rsPersonal = Personal::getListAll();

        return view('reportbestservice.main',[
            'desde'  => (new Carbon($first))->format(UI_DATE_FORMAT),
            'hasta'  => (new Carbon($hoy))->format(UI_DATE_FORMAT),
            'rsPersonal'  => $rsPersonal,
        ]);
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $personal = $request->input('personal');

        $parte = explode('/', $request->input('desde'));
        $desde = (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d');

        $parte = explode('/', $request->input('hasta'));
        $hasta = (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d');
        if (!$request->filled('personal')) {
            $rows = ReportTest::getCountServiceFecha($take, $skip, $desde, $hasta);
            $count = count(ReportTest::CountServiceFecha($desde, $hasta));
        }else{
            $rows = ReportTest::getCountServiceFechaPersonal($take, $skip, $desde, $hasta,$personal);
            $count = count(ReportTest::CountServiceFechaPersonal($desde, $hasta,$personal));
        }

//        foreach ($rows as $row) {
//        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $count ]]);
    }
}
