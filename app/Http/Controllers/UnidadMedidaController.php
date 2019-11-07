<?php

namespace App\Http\Controllers;

use App\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $countRegs = UnidadMedida::getCountUnidMedida();
        $rows = UnidadMedida::getList($take, $skip);

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
        if ($idUnidMedida == '') {
            return view('unidmedida.unidmedida');
        }

        $unidadMedida = UnidadMedida::getUnidMedida($idUnidMedida);
        if (!$unidadMedida) {
            return redirect()->action('UnidadMedidaController@index');
        }
        return view('unidmedida.unidmedida', [
            'unidadMedida' => $unidadMedida,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'umd_codigo' => 'required',
            'umd_descripcion' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('umd_id')) {
            $unidMedida = UnidadMedida::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $unidMedida->umd_id]);
        }
        $unidMedida = UnidadMedida::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $unidMedida->umd_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        UnidadMedida::deleteUnidMedida($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
