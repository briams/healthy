<?php

namespace App\Http\Controllers;

use App\Sexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $countRegs = Sexo::getCountSexo();
        $rows = Sexo::getList($take, $skip);

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
        if ($idSexo == '') {
            return view('sexos.sexo');
        }

        $sexo = Sexo::getSexo($idSexo);
        if (!$sexo) {
            return redirect()->action('SexoController@index');
        }
        return view('sexos.sexo', [
            'sexo' => $sexo,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'sexo_nombre' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('sexo_id')) {
            $sexo = Sexo::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $sexo->sexo_id]);
        }
        $sexo = Sexo::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $sexo->sexo_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        Sexo::deleteSexo($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
