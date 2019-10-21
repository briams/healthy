<?php

namespace App\Http\Controllers;

use App\Cita;
use App\Cliente;
use App\Mascota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    public function index()
    {
        return view('cita.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Cita::getCountCita();
        $rows = Cita::getList($take, $skip);

        foreach ($rows as $row) {

            $row->cita_fecha = (new Carbon($row->cita_fecha))->format(UI_DATE_FORMAT);

            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idCita="' . $row->cita_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->cita_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idCita="' . $row->cita_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->cita_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idCita="' . $row->cita_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->cita_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idCita="' . $row->cita_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idCita="' . $row->cita_id . '">
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

    public function edit($idCita = '')
    {
        $clientes = Cliente::getListClientesActive();
        if ($idCita == '') {
            return view('cita.cita', [
                'clientes' => $clientes,
            ]);
        }

        $rsCita = Cita::getCita($idCita);
        if (!$rsCita) {
            return redirect()->action('CitaController@index');
        }
        $rsCita->cita_fecha = (new Carbon($rsCita->cita_fecha))->format(UI_DATE_FORMAT);
        return view('cita.cita', [
            'rsCita' => $rsCita,
            'clientes' => $clientes,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'cita_mascota_id' => 'required',
            'cita_cliente_id' => 'required',
            'cita_fecha' => 'required',
            'cita_motivo' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->filled('cita_fecha')) {
            $parte = explode('/', $request->input('cita_fecha'));
            $request->merge(['cita_fecha' => (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d') ]);
        }

        if (!$request->filled('cita_id')) {
            $request->merge(['cita_estado' => ST_NUEVO]);
            $request->merge(['cita_fecha_registro' => Carbon::now() ]);
            $cita = Cita::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $cita->cita_id]);
        }
        $cita = Cita::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $cita->cita_id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['cita_id' => $request->input('id')]);
        $request->merge(['cita_estado' => ST_INACTIVO]);
        Cita::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['cita_id' => $request->input('id')]);
        $request->merge(['cita_estado' => ST_ACTIVO]);
        Cita::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['cita_id' => $request->input('id')]);
        $request->merge(['cita_estado' => ST_ELIMINADO]);
        Cita::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function cargarMascota(Request $request)
    {
        $idCita = $request->input('idCita');
        $idCliente = $request->input('idCliente');
        $accion = $request->input('accion');

        if ($idCliente == '' or $accion == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        $rsCita = false;
        if ($idCita != '') {
            $rsCita = Cita::getCita($idCita);
        }
        $idMascota = ($rsCita) ? $rsCita->cita_mascota_id : '';

        $html = '<option value="">Selecciona Mascota</option>';
        $Mascotas = Mascota::getListMascotaXCliente($idCliente);
        if ($Mascotas) {
            foreach ($Mascotas as $var) {
                $html .= '<option ' . (($idMascota == $var->mascota_id and $accion == 2) ? 'selected' : '') . ' value="' . $var->mascota_id . '">' . $var->mascota_nombre . '</option>';
            }
        }

        return response()->json(['status' => STATUS_OK, 'mascota' => $html]);
    }
}
