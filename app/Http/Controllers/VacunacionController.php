<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Historia;
use App\Mascota;
use App\Vacuna;
use App\Vacunacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VacunacionController extends Controller
{

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $idHistoria = $request->input('vacunacion_historia_id');

        $countRegs = Vacunacion::getCountVacunacion($idHistoria);
        $rows = Vacunacion::getList($take, $skip,$idHistoria);

        foreach ($rows as $row) {

            $row->vacunacion_fecha = (new Carbon($row->vacunacion_fecha))->format('d/m/Y');
            
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idVacunacion="' . $row->vacunacion_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idVacunacion="' . $row->vacunacion_id . '">
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

    public function edit($idHistoria,$idVacunacion = '')
    {
        $rsHistoria = Historia::getHistoria($idHistoria);
        $rsMascota = Mascota::getMascota($rsHistoria->historia_mascota_id);
        $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
        $vacunas = Vacuna::getListAll();
        if ($idVacunacion == '') {
            return view('vacunacion.vacunacion', [
                'vacunas' => $vacunas,
                'idHistoria' => $idHistoria,
                'rsMascota' => $rsMascota,
                'rsCliente' => $rsCliente,
            ]);
        }

        $rsVacunacion = Vacunacion::getVacunacion($idVacunacion);
        if (!$rsVacunacion) {
            return redirect()->action('VacunacionController@index');
        }
        if($rsVacunacion->vacunacion_fecha != '')
            $rsVacunacion->vacunacion_fecha = (new Carbon($rsVacunacion->vacunacion_fecha))->format('d/m/Y');
        return view('vacunacion.vacunacion', [
            'rsVacunacion' => $rsVacunacion,
            'vacunas' => $vacunas,
            'idHistoria' => $idHistoria,
            'rsMascota' => $rsMascota,
            'rsCliente' => $rsCliente,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'vacunacion_vacuna_id' => 'required',
            'vacunacion_historia_id' => 'required',
            'vacunacion_fecha' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->filled('vacunacion_fecha')) {
            $parte = explode('/', $request->input('vacunacion_fecha'));
            $request->merge(['vacunacion_fecha' => (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d') ]);
        }

        $rsHistoria = Historia::getHistoria($request->input('vacunacion_historia_id'));

        $user = Session::get('usuario');
        $request->merge(['vacunacion_usuario' => $user->idUsuario]);

        if (!$request->filled('vacunacion_id')) {
            $request->merge(['vacunacion_estado' => ST_ACTIVO]);
            $request->merge(['vacunacion_fecha_registro' => Carbon::now() ]);
            $vacunacion = Vacunacion::create($request->all());

            HistoriaController::generarCierre($rsHistoria->historia_id);

            return response()->json(['status' => STATUS_OK, 'id' => $vacunacion->vacunacion_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
        }
        $vacunacion = Vacunacion::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $vacunacion->vacunacion_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['vacunacion_id' => $request->input('id')]);
        $request->merge(['vacunacion_estado' => ST_ELIMINADO]);
        Vacunacion::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

}
