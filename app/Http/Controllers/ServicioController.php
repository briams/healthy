<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Historia;
use App\Mascota;
use App\Servicio;
use App\TipoServicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ServicioController extends Controller
{
    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $idHistoria = $request->input('servicio_historia_id');

        $countRegs = Servicio::getCountServicio($idHistoria);
        $rows = Servicio::getList($take, $skip,$idHistoria);

        foreach ($rows as $row) {

            $row->servicio_fecha = (new Carbon($row->servicio_fecha))->format('d/m/Y');
            $tipoServicio = TipoServicio::getTipoServicio($row->servicio_servtip_id);
            $row->servicio_servtip_id = $tipoServicio->servtip_nombre;
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idServicio="' . $row->servicio_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idServicio="' . $row->servicio_id . '">
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

    public function edit($idHistoria,$idServicio = '')
    {
        $rsHistoria = Historia::getHistoria($idHistoria);
        $rsMascota = Mascota::getMascota($rsHistoria->historia_mascota_id);
        $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
        $tipoServicio = TipoServicio::getAllList();
        if ($idServicio == '') {
            return view('servicio.servicio', [
                'tipoServicio' => $tipoServicio,
                'idHistoria' => $idHistoria,
                'rsMascota' => $rsMascota,
                'rsCliente' => $rsCliente,
            ]);
        }

        $rsServicio = Servicio::getServicio($idServicio);
        if (!$rsServicio) {
            return redirect()->action('ServicioController@index');
        }
        if($rsServicio->servicio_fecha != '')
            $rsServicio->servicio_fecha = (new Carbon($rsServicio->servicio_fecha))->format('d/m/Y');
        return view('servicio.servicio', [
            'rsServicio' => $rsServicio,
            'tipoServicio' => $tipoServicio,
            'idHistoria' => $idHistoria,
            'rsMascota' => $rsMascota,
            'rsCliente' => $rsCliente,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'servicio_servtip_id' => 'required',
            'servicio_historia_id' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $rsHistoria = Historia::getHistoria($request->input('servicio_historia_id'));

        $user = Session::get('usuario');
        $request->merge(['servicio_usuario' => $user->idUsuario]);

        if (!$request->filled('servicio_id')) {
            $request->merge(['servicio_estado' => ST_ACTIVO]);
            $request->merge(['servicio_fecha' => Carbon::now() ]);
            $servicio = Servicio::create($request->all());
            HistoriaController::generarCierre($rsHistoria->historia_id);
            return response()->json(['status' => STATUS_OK, 'id' => $servicio->servicio_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
        }
        $servicio = Servicio::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $servicio->servicio_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['servicio_id' => $request->input('id')]);
        $request->merge(['servicio_estado' => ST_ELIMINADO]);
        Servicio::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
