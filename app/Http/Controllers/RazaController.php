<?php

namespace App\Http\Controllers;

use App\Especie;
use App\Raza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $countRegs = Raza::getCountRaza();
        $rows = Raza::getList($take, $skip);

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
        $especies = Especie::getAllList();
        if ($idRaza == '') {
            return view('razas.raza', [
                'especies' => $especies,
            ]);
        }

        $raza = Raza::getRaza($idRaza);
        if (!$raza) {
            return redirect()->action('RazaController@index');
        }
        return view('razas.raza', [
            'raza' => $raza,
            'especies' => $especies,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'raza_nombre' => 'required',
            'raza_especie_id' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('raza_id')) {
            $raza = Raza::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $raza->raza_id]);
        }
        $raza = Raza::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $raza->raza_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        Raza::deleteRaza($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
