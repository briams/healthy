<?php

namespace App\Http\Controllers;

use App\Modulo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function index()
    {
        return view('module.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Modulo::getCountModule();
        $rows = Modulo::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idMod="' . $row->idModule . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idMod="' . $row->idModule . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->estado == 0) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idMod="' . $row->idModule . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }
            $tool .= '
		                </div >
		                </div > ';

            $row->tool = $tool;
        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $countRegs]]);
    }

    public function edit($idModulo = '')
    {
        $moduloPadre = Modulo::getListModuleParent();

        if ($idModulo == '') {
            return view('module.modulo', [
                'moduloPadre' => $moduloPadre,
            ]);
        }
        $modulo = Modulo::getModule($idModulo);

        if (!$modulo) {
            return redirect()->action('ModuleController@index');
        }
        return view('module.modulo', [
            'modulo' => $modulo,
            'moduloPadre' => $moduloPadre,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'url' => 'required',
            'orden' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (  (!$request->filled('padre_id')) and $request->input('is_parent') == 0) {
            return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Indique si este modulo sera padre o Seleccione un padre']);
        }

        if (!$request->filled('idModule')) {
            $request->merge(['estado' => DB_TRUE]);
            $modulo = Modulo::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $modulo->idModule]);
        }
        $modulo = Modulo::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $modulo->idModule]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['idModule' => $request->input('id')]);
        $request->merge(['estado' => DB_FALSE]);
        Modulo::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['idModule' => $request->input('id')]);
        $request->merge(['estado' => DB_TRUE]);
        Modulo::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

}
