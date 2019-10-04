<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EspecieController extends Controller
{
    public function index()
    {
        return view('especies.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_especie');

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('especie_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idEsp="' . $row->especie_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idEsp="' . $row->especie_id . '">
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

    public function edit($idEspecie = '')
    {
        $especie = DB::table('tbl_especie')
            ->where('especie_id', '=', $idEspecie)
            ->first();

        if ($idEspecie == '') {
            return view('especies.especie');
        } else {
            if ($especie) {
                return view('especies.especie', [
                    'especie' => $especie,
                ]);
            } else {
                return redirect()->action('EspecieController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('especie_nombre') == '') {
            $error['especie_nombre'] = "Debe ingresar nombre de la especie";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'especie_nombre'    => $request->input('especie_nombre'),
        ];

        if ($request->input('especie_id') == '') {
            $id = DB::table('tbl_especie')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_especie')
                ->where('especie_id', $request->input('especie_id'))
                ->update($dataInsert);
            $id = $request->input('especie_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_especie')
            ->where('especie_id', $request->input('id'))
            ->delete();

        return response()->json(['status'=>STATUS_OK]);
    }
}
