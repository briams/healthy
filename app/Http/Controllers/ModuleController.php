<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    public function index()
    {
        return view('module.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
//        $q = $request->input('q');

        $select = DB::table('tbl_module')
                        ->where('estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('idModule', 'desc')
                ->limit($take)
                ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-catpid="' . $row->idModule . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
            } elseif ($row->estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
            } elseif ($row->estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
            }
            $tool .= '
		                </div >
		                </div > ';

            $row->tool = $tool;
        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $countRegs]]);
    }
}
