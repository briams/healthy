<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Historia;
use App\Internamiento;
use App\Mascota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternamientoController extends Controller
{
    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $idHistoria = $request->input('internamiento_historia_id');

        $countRegs = Internamiento::getCountInternamiento($idHistoria);
        $rows = Internamiento::getList($take, $skip,$idHistoria);

        foreach ($rows as $row) {

            $row->internamiento_fecha_inicio = (new Carbon($row->internamiento_fecha_inicio))->format('d/m/Y');
            $row->internamiento_fecha_salida = (new Carbon($row->internamiento_fecha_salida))->format('d/m/Y');

            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idInternamiento="' . $row->internamiento_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idInternamiento="' . $row->internamiento_id . '">
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

    public function edit($idHistoria,$idInternamiento = '')
    {
        $rsHistoria = Historia::getHistoria($idHistoria);
        $rsMascota = Mascota::getMascota($rsHistoria->historia_mascota_id);
        $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
        if ($idInternamiento == '') {
            return view('internamiento.internamiento', [
                'idHistoria' => $idHistoria,
                'rsMascota' => $rsMascota,
                'rsCliente' => $rsCliente,
            ]);
        }

        $rsInternamiento = Internamiento::getInternamiento($idInternamiento);
        if (!$rsInternamiento) {
            return redirect()->action('InternamientoController@index');
        }
        if($rsInternamiento->internamiento_fecha_inicio != '')
            $rsInternamiento->internamiento_fecha_inicio = (new Carbon($rsInternamiento->internamiento_fecha_inicio))->format('d/m/Y');
        if($rsInternamiento->internamiento_fecha_salida != '')
            $rsInternamiento->internamiento_fecha_salida = (new Carbon($rsInternamiento->internamiento_fecha_salida))->format('d/m/Y');
        return view('internamiento.internamiento', [
            'rsInternamiento' => $rsInternamiento,
            'idHistoria' => $idHistoria,
            'rsMascota' => $rsMascota,
            'rsCliente' => $rsCliente,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'internamiento_historia_id' => 'required',
            'internamiento_fecha_inicio' => 'required',
            'internamiento_dias' => 'required',
            'internamiento_motivo' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->filled('internamiento_fecha_inicio')) {
            $parte = explode('/', $request->input('internamiento_fecha_inicio'));
            $fecha = new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]);
            $request->merge(['internamiento_fecha_inicio' => $fecha->format('Y/m/d') ]);
            $fecha = $fecha->addDays($request->input('internamiento_dias'));
            $request->merge(['internamiento_fecha_salida' => $fecha->format('Y/m/d') ]);
        }


        $rsHistoria = Historia::getHistoria($request->input('internamiento_historia_id'));
        if (!$request->filled('internamiento_id')) {
            $request->merge(['internamiento_estado' => ST_ACTIVO]);
            $request->merge(['internamiento_fecha_registro' => Carbon::now() ]);
            $internamiento = Internamiento::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $internamiento->internamiento_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
        }
        $internamiento = Internamiento::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $internamiento->internamiento_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['internamiento_id' => $request->input('id')]);
        $request->merge(['internamiento_estado' => ST_ELIMINADO]);
        Internamiento::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
