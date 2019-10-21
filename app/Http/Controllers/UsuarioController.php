<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Perfil;
use App\Personal;
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
        $rows = Usuario::getList($take, $skip);

        foreach ($rows as $row) {
            if($row->tipo == TIPO_PERSONAL){
                $row->nombre = $row->personal_nombre;
                $row->tipo = 'Personal';
            }elseif ($row->tipo == TIPO_CLIENTE){
                $row->nombre = $row->cliente_fullname;
                $row->tipo = 'Cliente';
            }elseif ($row->tipo == TIPO_SISTEMA){
                $row->tipo = 'Sistema';
                $row->nombre = 'User de Sistema';
            }

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
        $perfiles = Perfil::getActivePerfil();
        if ($idUser == '') {
            return view('usuarios.usuario', [
                'perfiles' => $perfiles,
                'tipo' => TIPO_SISTEMA,
                'strtipo' => TIPO_USUARIO[TIPO_SISTEMA],
            ]);
        }
        $user = Usuario::getUSer($idUser);
        if (!$user) {
            return redirect()->action('UsuarioController@index');
        }
        if($user->tipo == TIPO_PERSONAL){
            $personal = Personal::getPersonal($user->referencia);
            $nombre = $personal->personal_apellido.', '.$personal->personal_nombre;
        }elseif ($user->tipo == TIPO_CLIENTE){
            $cliente = Cliente::getCliente($user->referencia);
            $nombre = $cliente->cliente_fullname;
        }elseif ($user->tipo == TIPO_SISTEMA){
            $nombre = 'User de Sistema';
        }
        return view('usuarios.usuario', [
            'user' => $user,
            'perfiles' => $perfiles,
            'tipo' => $user->tipo,
            'strtipo' => TIPO_USUARIO[$user->tipo],
            'nombre' => $nombre,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'tipo' => 'required',
            'email' => ['required', new Emailvalidation],
            'usuario_perfil_id' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }
        $rsUser = Usuario::getUSerEmail( $request->input('email') );
        if ($rsUser and $rsUser->idUsuario != $request->input('idUsuario') ) {
            $error['email'] = "Ya existe un usuario usando este E-mail";
        }

        if (count($error) > 0) {
            return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo']);
        }

        if (!$request->filled('password')) {
            $request->request->remove('password');
        } else {
            if ($request->input('password') != $request->input('password_validate') ) {
                return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Verifique que las dos contraseñas ingresadas sean iguales']);
            }
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
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['idUsuario' => $request->input('id')]);
        $request->merge(['estado' => ST_INACTIVO]);
        Usuario::updateRow($request);
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
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['idUsuario' => $request->input('id')]);
        $request->merge(['estado' => ST_ELIMINADO]);
        Usuario::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
