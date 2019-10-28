<?php

namespace App\Http\Controllers;

use App\TipoIntervencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoIntervencionController extends Controller
{
    public function index()
    {
        return view('tipintervencion.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = TipoIntervencion::getCountTipoIntervencion();
        $rows = TipoIntervencion::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idTipIntervencion="' . $row->intervenciont_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idTipIntervencion="' . $row->intervenciont_id . '">
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

    public function edit($idTipoIntervencion = '')
    {
        if ($idTipoIntervencion == '') {
            return view('tipintervencion.tipintervencion');
        }

        $tipoIntervencion = TipoIntervencion::getTipoIntervencion($idTipoIntervencion);
        if (!$tipoIntervencion) {
            return redirect()->action('TipoIntervencionController@index');
        }
        return view('tipintervencion.tipintervencion', [
            'tipoIntervencion' => $tipoIntervencion,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'intervenciont_nombre' => 'required',
            'intervenciont_descripcion' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('intervenciont_id')) {
            $tipoIntervencion = TipoIntervencion::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $tipoIntervencion->intervenciont_id]);
        }
        $tipoIntervencion = TipoIntervencion::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $tipoIntervencion->intervenciont_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        TipoIntervencion::deleteTipoIntervencion($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
