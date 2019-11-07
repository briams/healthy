<?php

namespace App\Http\Controllers;

use App\Historia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HistoriaController extends Controller
{
    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'historia_mascota_id' => 'required',
            'historia_peso' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('historia_id')) {

            $user = Session::get('usuario');
            $request->merge(['historia_Usuario' => $user->idUsuario]);

            $request->merge(['historia_fecha_registro' => Carbon::now() ]);
            $historia = Historia::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $historia->historia_id]);
        }
        $historia = Historia::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $historia->historia_id]);
    }
}
