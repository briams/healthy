<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('usuarios.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_usuario')
            ->where('estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('idUsuario', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idUser="' . $row->idUsuario . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idUser="' . $row->idUsuario . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idUser="' . $row->idUsuario . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idUser="' . $row->idUsuario . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }
            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idUser="' . $row->idUsuario . '">
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

    public function edit($idUser = '')
    {
        $user = DB::table('tbl_usuario')
            ->where('idUsuario', '=', $idUser)
            ->first();

        $perfiles = DB::table('tbl_perfil')
            ->where('perfil_estado', '!=', ST_ELIMINADO)
            ->get();

        if ($idUser == '') {
            return view('usuarios.usuario', [
                'perfiles' => $perfiles,
            ]);
        } else {
            if ($user) {
                return view('usuarios.usuario', [
                    'user' => $user,
                    'perfiles' => $perfiles,
                ]);
            } else {
                return redirect()->action('UsuarioController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('nombre') == '') {
            $error['nombre'] = "Debe ingresar nombre del usuario";
        }

        if ($request->input('apellido') == '') {
            $error['apellido'] = "Debe ingresar apellido del usuario";
        }

        if ($request->input('numero_doc') == '') {
            $error['numero_doc'] = "Debe ingresar nro de documento del usuario";
        }

        if ($request->input('email') == '') {
            $error['email'] = "Debe ingresar email del usuario";
        }

        if ($request->input('usuario_perfil_id') == '') {
            $error['usuario_perfil_id'] = "Seleccione el perfil del usuario";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'nombre'    => $request->input('nombre'),
            'apellido'       => $request->input('apellido'),
            'numero_doc'     => $request->input('numero_doc'),
            'email'     => $request->input('email'),
            'telefono'  => $request->input('telefono'),
            'usuario_perfil_id' => $request->input('usuario_perfil_id'),
        ];
        if ($request->input('password') != '') {
            $dataInsert['password'] = $request->input('password');
//            $dataInsert['password'] = bcrypt($request->input('password'));
        }

        if ($request->input('idUsuario') == '') {
            $dataInsert['estado'] = ST_NUEVO;
//            $dataInsert['fecha_registro'] = (new DateTime())->format('Y-m-d H:i:s');
            $id = DB::table('tbl_usuario')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_usuario')
                ->where('idUsuario', $request->input('idUsuario'))
                ->update($dataInsert);
            $id = $request->input('idUsuario');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_usuario')
            ->where('idUsuario', $request->input('id'))
            ->update([ 'estado' => ST_INACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_usuario')
            ->where('idUsuario', $request->input('id'))
            ->update([ 'estado' => ST_ACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_usuario')
            ->where('idUsuario', $request->input('id'))
            ->update([ 'estado' => ST_ELIMINADO ]);

        return response()->json(['status'=>STATUS_OK]);
    }
}
