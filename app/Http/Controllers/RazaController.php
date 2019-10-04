<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RazaController extends Controller
{
    public function index()
    {
        return view('razas.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_raza')
            ->leftJoin('tbl_especie', 'tbl_raza.raza_especie_id', '=', 'tbl_especie.especie_id');

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('raza_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idRaza="' . $row->raza_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idRaza="' . $row->raza_id . '">
                        <i class="black trash alternate icon"></i>
		                Eliminar
		                </div>';
            $tool .= '
		                </div >
		                </div > ';

            $row->tool = $tool;
        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $countRegs]]);
    }

    public function edit($idRaza = '')
    {
        $raza = DB::table('tbl_raza')
            ->where('raza_id', '=', $idRaza)
            ->first();

        $especies = DB::table('tbl_especie')
            ->get();

        if ($idRaza == '') {
            return view('razas.raza', [
                'especies' => $especies,
            ]);
        } else {
            if ($raza) {
                return view('razas.raza', [
                    'raza' => $raza,
                    'especies' => $especies,
                ]);
            } else {
                return redirect()->action('RazaController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('raza_nombre') == '') {
            $error['raza_nombre'] = "Debe ingresar nombre de la raza";
        }

        if ($request->input('raza_especie_id') == '') {
            $error['raza_especie_id'] = "Seleccione la especie a la q pertenece";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'raza_nombre'    => $request->input('raza_nombre'),
            'raza_especie_id'       => $request->input('raza_especie_id'),
        ];

        if ($request->input('raza_id') == '') {
            $id = DB::table('tbl_raza')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_raza')
                ->where('raza_id', $request->input('raza_id'))
                ->update($dataInsert);
            $id = $request->input('raza_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_raza')
            ->where('raza_id', $request->input('id'))
            ->delete();

        return response()->json(['status'=>STATUS_OK]);
    }
}
