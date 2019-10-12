<?php

namespace App\Http\Controllers;

use App\Vacuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VacunaController extends Controller
{
    public function index()
    {
        return view('vacuna.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Vacuna::getCountVacuna();
        $rows = Vacuna::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idVac="' . $row->vac_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->vac_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idVac="' . $row->vac_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->vac_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idVac="' . $row->vac_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->vac_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idVac="' . $row->vac_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idVac="' . $row->vac_id . '">
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

    public function edit($idVacuna = '')
    {
        if ($idVacuna == '') {
            return view('vacuna.vacuna');
        }

        $rsVacuna = Vacuna::getVacuna($idVacuna);

        if (!$rsVacuna) {
            return redirect()->action('VacunaController@index');
        }
        return view('vacuna.vacuna', [
            'rsVacuna' => $rsVacuna,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'vac_abreviatura' => 'required',
            'vac_descripcion' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('vac_id')) {
            $request->merge(['vac_estado' => ST_NUEVO]);
            $vacuna = Vacuna::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $vacuna->vac_id]);
        }
        $vacuna = Vacuna::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $vacuna->vac_id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['vac_id' => $request->input('id')]);
        $request->merge(['vac_estado' => ST_INACTIVO]);
        Vacuna::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['vac_id' => $request->input('id')]);
        $request->merge(['vac_estado' => ST_ACTIVO]);
        Vacuna::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['vac_id' => $request->input('id')]);
        $request->merge(['vac_estado' => ST_ELIMINADO]);
        Vacuna::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
