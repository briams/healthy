<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadMedidaController extends Controller
{
    public function index()
    {
        return view('unidmedida.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_unidad_medida');

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('umd_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idUnidMed="' . $row->umd_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idUnidMed="' . $row->umd_id . '">
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

    public function edit($idUnidMedida = '')
    {
        $unidadMedida = DB::table('tbl_unidad_medida')
            ->where('umd_id', '=', $idUnidMedida)
            ->first();

        if ($idUnidMedida == '') {
            return view('unidmedida.unidmedida');
        } else {
            if ($unidadMedida) {
                return view('unidmedida.unidmedida', [
                    'unidadMedida' => $unidadMedida,
                ]);
            } else {
                return redirect()->action('UnidadMedidaController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('umd_codigo') == '') {
            $error['umd_codigo'] = "Debe ingresar codigo de la unidad de medida";
        }

        if ($request->input('umd_descripcion') == '') {
            $error['umd_descripcion'] = "Debe ingresar descripcion de la unidad de medida";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'umd_codigo'    => $request->input('umd_codigo'),
            'umd_descripcion'    => $request->input('umd_descripcion'),
            'umd_orden'    => $request->input('umd_orden'),
        ];

        if ($request->input('umd_id') == '') {
            $id = DB::table('tbl_unidad_medida')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_unidad_medida')
                ->where('umd_id', $request->input('umd_id'))
                ->update($dataInsert);
            $id = $request->input('umd_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_unidad_medida')
            ->where('umd_id', $request->input('id'))
            ->delete();

        return response()->json(['status'=>STATUS_OK]);
    }
}
