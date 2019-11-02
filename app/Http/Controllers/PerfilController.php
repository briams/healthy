<?php

namespace App\Http\Controllers;

use App\Modulo;
use App\Perfil;
use App\Privilegio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Perfil::getCountPerfil();
        $rows = Perfil::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idPer="' . $row->perfil_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->perfil_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idPer="' . $row->perfil_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->perfil_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idPer="' . $row->perfil_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->perfil_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idPer="' . $row->perfil_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }
            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idPer="' . $row->perfil_id . '">
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

    public function edit($idPerfil = '')
    {
        $modulosPadres = Modulo::getListModuleParent();
        foreach ($modulosPadres as $padre) {
            $hijos = Modulo::getListModuleChildren($padre->idModule);
            foreach ($hijos as $hijo) {
                $modulosNivel3 = Modulo::getListModuleChildren($hijo->idModule);
                foreach ($modulosNivel3 as $modN3) {
                    $privilegio = Privilegio::getPrivilegio($idPerfil, $modN3->idModule);
                    $modN3->privilegio = ($privilegio) ? "checked" : "";
                }
                $privilegio = Privilegio::getPrivilegio($idPerfil, $hijo->idModule);
                $hijo->privilegio = ($privilegio) ? "checked" : "";
                $hijo->hijos = $modulosNivel3;
            }
            $privilegio = Privilegio::getPrivilegio($idPerfil, $padre->idModule);
            $padre->privilegio = ($privilegio) ? "checked" : "";
            $padre->hijos = $hijos;
        }

        if ($idPerfil == '') {
            return view('perfil.perfil', [
                'modulosPadre' => $modulosPadres,
            ]);
        }

        $perfil = Perfil::getPerfil($idPerfil);
        if (!$perfil) {
            return redirect()->action('PerfilController@index');
        }
        return view('perfil.perfil', [
            'perfil' => $perfil,
            'modulosPadre' => $modulosPadres,
        ]);

    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'perfil_nombre' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }
        if (count($error) > 0) {
            return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo']);
        }
        if ($request->input('modulesPriv') === null) {
            return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Seleccione al menos un modulo para darle acceso']);
        }

        $dataInsert = [
            'perfil_nombre' => $request->input('perfil_nombre'),
        ];
        if (!$request->filled('perfil_id')) {
            $dataInsert['perfil_estado'] = ST_NUEVO;
            $perfil = Perfil::create($dataInsert);
            $id = $perfil->perfil_id;
        } else {
            $dataInsert['perfil_id'] = $request->input('perfil_id');
            $id = Perfil::updateRow($dataInsert);
            Privilegio::deletePrivilegio($id);
        }
        foreach ($request->input('modulesPriv') as $row) {
            $dataPrivilegio[] = [
                'priv_perfil_id' => $id,
                'priv_modulo_id' => $row,
            ];
        }
        Privilegio::insert($dataPrivilegio);

        return response()->json(['status' => STATUS_OK, 'id' => $id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['perfil_id' => $request->input('id')]);
        $request->merge(['perfil_estado' => ST_INACTIVO]);
        Perfil::updateStatus($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['perfil_id' => $request->input('id')]);
        $request->merge(['perfil_estado' => ST_ACTIVO]);
        Perfil::updateStatus($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['perfil_id' => $request->input('id')]);
        $request->merge(['perfil_estado' => ST_ELIMINADO]);
        Perfil::updateStatus($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
