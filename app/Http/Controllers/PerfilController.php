<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $select = DB::table('tbl_perfil')
            ->where('perfil_estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('perfil_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

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
        $perfil = DB::table('tbl_perfil')
            ->where('perfil_id', '=', $idPerfil)
            ->first();

        $modulosPadres = DB::table('tbl_module')
            ->where('estado','=',1)
            ->where('is_parent','=',1)
            ->orderBy('orden', 'asc')
            ->get();

        foreach($modulosPadres as $padre)
        {
            $hijos =  DB::table('tbl_module')
                ->where('padre_id', $padre->idModule)
                ->orderBy('orden', 'asc')
                ->get();
            foreach($hijos as $hijo)
            {
                $privilegio =  DB::table('tbl_privilegio')
                    ->where('priv_perfil_id', $idPerfil)
                    ->where('priv_modulo_id', $hijo->idModule)
                    ->first();
                if ($privilegio){
                    $hijo->privilegio = "checked";
                }else{
                    $hijo->privilegio = "";
                }
            }
            $privilegio =  DB::table('tbl_privilegio')
                ->where('priv_perfil_id', $idPerfil)
                ->where('priv_modulo_id', $padre->idModule)
                ->first();
            if ($privilegio){
                $padre->privilegio = "checked";
            }else{
                $padre->privilegio = "";
            }
            $padre->hijos = $hijos;
        }


        if ($idPerfil == '') {
            return view('perfil.perfil', [
                'modulosPadre' => $modulosPadres,
            ]);
        } else {
            if ($perfil) {
                return view('perfil.perfil', [
                    'perfil' => $perfil,
                    'modulosPadre' => $modulosPadres,
                ]);
            } else {
                return redirect()->action('PerfilController@index');
            }
        }

    }

    public function save(Request $request)
    {

        $error = [];

        if ($request->input('perfil_nombre') == '') {
            $error['perfil_nombre'] = "Debe ingresar nombre del perfil";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }


        if ( $request->input('modulesPriv') === null ) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Seleccione al menos un modulo para darle acceso'];
            return response()->json($res);
        }

        $dataInsert = [
            'perfil_nombre'    => $request->input('perfil_nombre'),
        ];

        if ($request->input('perfil_id') == '') {
            $dataInsert['perfil_estado'] = ST_NUEVO;
            $id = DB::table('tbl_perfil')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_perfil')
                ->where('perfil_id', $request->input('perfil_id'))
                ->update($dataInsert);
            $id = $request->input('perfil_id');

            DB::table('tbl_privilegio')
                ->where('priv_perfil_id', '=', $id)
                ->delete();
        }

        foreach ($request->input('modulesPriv') as $row){
            $dataPrivilegio[] = [
                'priv_perfil_id'    => $id,
                'priv_modulo_id'    => $row,
            ];
        }
        DB::table('tbl_privilegio')->insert( $dataPrivilegio );

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_perfil')
            ->where('perfil_id', $request->input('id'))
            ->update([ 'perfil_estado' => ST_INACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_perfil')
            ->where('perfil_id', $request->input('id'))
            ->update([ 'perfil_estado' => ST_ACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_perfil')
            ->where('perfil_id', $request->input('id'))
            ->update([ 'perfil_estado' => ST_ELIMINADO ]);

        return response()->json(['status'=>STATUS_OK]);
    }
}
