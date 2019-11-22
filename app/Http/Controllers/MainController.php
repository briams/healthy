<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

// use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index()
    {
        if (Session::get('usuario') != '') {
            return view('layouts.main');
        } else {
            return view('login');
        }
        // Session::get('usuario'); //obtiene un registro de la session
        // Session::all(); //imprime toda la session
        // Session::forget('usuario'); //borra especific registro de la session
        // Session::flush(); //borra toda la session
    }

    public function login(Request $request)
    {
        $usuario = $request->input('email');
        $pass = $request->input('password');

        $rsUser = Usuario::getUSerEmail($usuario);
        if (!$rsUser) {
            return response()->json(['status' => STATUS_FAIL, 'error' => 1, 'msg' => 'Usuario no válido']);
        }

        if ($rsUser->estado != ST_ACTIVO) {
            return response()->json(['status' => STATUS_FAIL, 'error' => 1, 'msg' => 'Usuario no activo']);
        }

        if (Hash::check($pass, $rsUser->password)) {
            Session::put('usuario', $rsUser);
            return response()->json(['status' => STATUS_OK]);
        } else {
            return response()->json(['status' => STATUS_FAIL, 'error' => 2, 'msg' => 'Contraseña erronea']);
        }
        // dd($rsUser);
        // print_r($rsUser);
        // die();
        // $encrypted = Crypt::encryptString('Hello world.');
        // $decrypted = Crypt::decryptString($encrypted);
        // return redirect()->intended('/');
        // return redirect()->action('MainController@index')->with('USUARIO',$rsUser);//session flat

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Session::flush();
        return response()->json(['status' => STATUS_OK, 'msg' => 'Sesion Finalizada, Gracias']);
    }
}
