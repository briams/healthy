<?php

namespace App\Http\Controllers;

use App\Especie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $countRegs = Especie::getCountEspecie();
        $rows = Especie::getList($take, $skip);

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
        if ($idEspecie == '') {
            return view('especies.especie');
        }

        $especie = Especie::getEspecie($idEspecie);
        if (!$especie) {
            return redirect()->action('EspecieController@index');
        }
        return view('especies.especie', [
            'especie' => $especie,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'especie_nombre' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('especie_id')) {
            $especie = Especie::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $especie->especie_id]);
        }
        $especie = Especie::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $especie->especie_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        Especie::deleteEspecie($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
