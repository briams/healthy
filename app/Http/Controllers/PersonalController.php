<?php

namespace App\Http\Controllers;

use App\Cargo;
use App\Personal;
use App\Rules\Emailvalidation;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    public function index()
    {
        return view('personal.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Personal::getCountPersonal();
        $rows = Personal::getList($take, $skip);

        foreach ($rows as $row) {
            if($row->personal_nacimiento != '')
                $row->personal_nacimiento = (new Carbon($row->personal_nacimiento))->format(UI_DATE_FORMAT);

            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idPersonal="' . $row->personal_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->personal_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idPersonal="' . $row->personal_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->personal_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';

                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idPersonal="' . $row->personal_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';

                if($row->personal_user_id == '')
                    $tool .= '
                            <div class="ui divider"></div>
                            <div class="item ajxGenerate" data-idPersonal="' . $row->personal_id . '">
                            <i class="green play icon"></i>
                            Generar User
                            </div>';

            } elseif ($row->personal_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idPersonal="' . $row->personal_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }
            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idPersonal="' . $row->personal_id . '">
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

    public function edit($idPersonal = '')
    {
        $cargo = Cargo::getAllList();
        if ($idPersonal == '') {
            return view('personal.personal', [
                'cargo' => $cargo,
            ]);
        }
        $rsPersonal = Personal::getPersonal($idPersonal);
        if (!$rsPersonal) {
            return redirect()->action('PersonalController@index');
        }
        if($rsPersonal->personal_nacimiento != '')
            $rsPersonal->personal_nacimiento = (new Carbon($rsPersonal->personal_nacimiento))->format(UI_DATE_FORMAT);
        return view('personal.personal', [
            'rsPersonal' => $rsPersonal,
            'cargo' => $cargo,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'personal_nombre' => 'required',
            'personal_apellido' => 'required',
            'personal_dni' => 'required',
            'personal_email' => ['required', new Emailvalidation],
            'personal_cargo' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }
        $rsPersonal = Personal::getPersonalEmail( $request->input('personal_email') );
        if ($rsPersonal and $rsPersonal->personal_id != $request->input('personal_id') ) {
            $error['personal_email'] = "Ya existe un personal usando este E-mail";
        }

        if (count($error) > 0) {
            return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo']);
        }

        if ($request->filled('personal_nacimiento')) {
            $parte = explode('/', $request->input('personal_nacimiento'));
            $request->merge(['personal_nacimiento' => (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d') ]);
        }

        if (!$request->filled('personal_id')) {
            $request->merge(['personal_estado' => ST_NUEVO]);
            $request->merge(['personal_fecha_registro' => Carbon::now() ]);
            $personal = Personal::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $personal->personal_id]);
        }
        $personal = Personal::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $personal->personal_id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['personal_id' => $request->input('id')]);
        $request->merge(['personal_estado' => ST_INACTIVO]);
        Personal::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['personal_id' => $request->input('id')]);
        $request->merge(['personal_estado' => ST_ACTIVO]);
        Personal::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['personal_id' => $request->input('id')]);
        $request->merge(['personal_estado' => ST_ELIMINADO]);
        Personal::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function generarUsuario(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $rsPersonal = Personal::getPersonal($request->input('id'));

        $rsUser = Usuario::getUSerEmail( $rsPersonal->personal_email );
        if ($rsUser) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error al intentar crear usuario. Correo ya existente']);
        }

        $request->merge(['email' => $rsPersonal->personal_email]);
        $request->merge(['password' => Hash::make($rsPersonal->personal_dni)]);
        $request->merge(['fecha_registro' => Carbon::now() ]);
        $request->merge(['referencia' => $request->input('id') ]);
        $request->merge(['tipo' => TIPO_PERSONAL]);
        $request->merge(['estado' => ST_NUEVO]);

        $user = Usuario::create($request->all());

        $request->merge(['personal_id' => $request->input('id')]);
        $request->merge(['personal_user_id' => $user->idUsuario ]);
        Personal::updateRow($request);

        return response()->json(['status' => STATUS_OK]);
    }
}
