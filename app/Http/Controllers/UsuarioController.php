<?php

namespace App\Http\Controllers;

use App\Perfil;
use App\Rules\Emailvalidation;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        $countRegs = Usuario::getCountUsers();
        $rows = Usuario::geList($take, $skip);
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
        $perfiles = Perfil::getList();
        if ($idUser == '') {
            return view('usuarios.usuario', [
                'perfiles' => $perfiles,
            ]);
        }
        $user = Usuario::getUSer($idUser);
        if (!$user) {
            return redirect()->action('UsuarioController@index');
        }
        return view('usuarios.usuario', [
            'user' => $user,
            'perfiles' => $perfiles,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'numero_doc' => 'required',
            'email' => ['required', new Emailvalidation],
            'usuario_perfil_id' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('password')) {
            $request->request->remove('password');
        } else {
            $request->merge(['password' => Hash::make($request->input('password'))]);
        }

        if (!$request->filled('idUsuario')) {
            $request->merge(['estado' => ST_NUEVO]);
            $usuario = Usuario::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $usuario->idUsuario]);
        }
        $usuario = Usuario::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $usuario->idUsuario]);
    }

    public function bloquear(Request $request)
    {
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_usuario')
            ->where('idUsuario', $request->input('id'))
            ->update(['estado' => ST_INACTIVO]);

        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['idUsuario' => $request->input('id')]);
        $request->merge(['estado' => ST_ACTIVO]);
        Usuario::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_usuario')
            ->where('idUsuario', $request->input('id'))
            ->update(['estado' => ST_ELIMINADO]);

        return response()->json(['status' => STATUS_OK]);
    }
}
