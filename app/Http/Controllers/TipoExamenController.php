<?php

namespace App\Http\Controllers;

use App\TipoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoExamenController extends Controller
{
    public function index()
    {
        return view('tipexamen.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = TipoExamen::getCountTipoExamen();
        $rows = TipoExamen::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idTipExamen="' . $row->exament_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idTipExamen="' . $row->exament_id . '">
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

    public function edit($idTipoExamen = '')
    {
        if ($idTipoExamen == '') {
            return view('tipexamen.tipexamen');
        }

        $tipoExamen = TipoExamen::getTipoExamen($idTipoExamen);
        if (!$tipoExamen) {
            return redirect()->action('TipoExamenController@index');
        }
        return view('tipexamen.tipexamen', [
            'tipoExamen' => $tipoExamen,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'exament_nombre' => 'required',
            'exament_descripcion' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('exament_id')) {
            $tipoExamen = TipoExamen::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $tipoExamen->exament_id]);
        }
        $tipoExamen = TipoExamen::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $tipoExamen->exament_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        TipoExamen::deleteTipoExamen($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
