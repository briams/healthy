<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SexoController extends Controller
{
    public function index()
    {
        return view('sexos.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_sexo');

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('sexo_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idSexo="' . $row->sexo_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idSexo="' . $row->sexo_id . '">
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

    public function edit($idSexo = '')
    {
        $sexo = DB::table('tbl_sexo')
            ->where('sexo_id', '=', $idSexo)
            ->first();

        if ($idSexo == '') {
            return view('sexos.sexo');
        } else {
            if ($sexo) {
                return view('sexos.sexo', [
                    'sexo' => $sexo,
                ]);
            } else {
                return redirect()->action('SexoController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('sexo_nombre') == '') {
            $error['sexo_nombre'] = "Debe ingresar nombre del sexo";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'sexo_nombre'    => $request->input('sexo_nombre'),
        ];

        if ($request->input('sexo_id') == '') {
            $id = DB::table('tbl_sexo')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_sexo')
                ->where('sexo_id', $request->input('sexo_id'))
                ->update($dataInsert);
            $id = $request->input('sexo_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_sexo')
            ->where('sexo_id', $request->input('id'))
            ->delete();

        return response()->json(['status'=>STATUS_OK]);
    }
}
