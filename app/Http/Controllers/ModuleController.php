<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
//        $q = $request->input('q');

        $select = DB::table('tbl_module')
            ->where('estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('idModule', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

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
//            } elseif ($row->estado == ST_ACTIVO) {
//                $row->estado = '<div class="ui label mini green" > Activo</div > ';
            } elseif ($row->estado == 0 ) {
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
        $modulo = DB::table('tbl_module')
            ->where('idModule', '=', $idModulo)
            ->first();

        $moduloPadre = DB::table('tbl_module')
            ->where('is_parent', '=', DB_TRUE)
            ->get();

//        dd($moduloPadre);
        if ($idModulo == '') {
            return view('module.modulo', [
                'moduloPadre' => $moduloPadre,
            ]);
        } else {
            if ($modulo) {
                return view('module.modulo', [
                    'modulo' => $modulo,
                    'moduloPadre' => $moduloPadre,
                ]);
            } else {
//                return view('module.main');
                return redirect()->action('ModuleController@index');
            }
        }

    }

    public function save(Request $request)
    {

        $error = [];

        if ($request->input('nombre') == '') {
            $error['nombre'] = "Debe ingresar nombre del modulo";
        }

        if ($request->input('url') == '') {
            $error['url'] = "Debe ingresar nombre del modulo";
        }

        if ($request->input('orden') == '') {
            $error['orden'] = "Debe ingresar nombre del modulo";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->input('padre_id') == '' and $request->input('is_parent') == 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Indique si este modulo sera padre o Seleccione un padre'];
            return response()->json($res);
        }

        $dataInsert = [
            'nombre'    => $request->input('nombre'),
            'url'       => $request->input('url'),
            'icono'     => $request->input('icono'),
            'orden'     => $request->input('orden'),
            'padre_id'  => $request->input('padre_id'),
            'is_parent' => $request->input('is_parent'),
        ];

        if ($request->input('idModule') == '') {
            $dataInsert['estado'] = ST_NUEVO;
            $id = DB::table('tbl_module')
                    ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_module')
                ->where('idModule', $request->input('idModule'))
                ->update($dataInsert);
            $id = $request->input('idModule');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_module')
            ->where('idModule', $request->input('id'))
            ->update([ 'estado' => 0 ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_module')
            ->where('idModule', $request->input('id'))
            ->update([ 'estado' => ST_NUEVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

}
