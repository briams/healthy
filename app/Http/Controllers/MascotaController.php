<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class MascotaController extends Controller
{
    public function index()
    {
        return view('mascota.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_mascota')
            ->leftJoin('tbl_cliente', 'tbl_mascota.mascota_cliente_id', '=', 'tbl_cliente.cliente_id')
            ->leftJoin('tbl_especie', 'tbl_mascota.mascota_especie', '=', 'tbl_especie.especie_id')
            ->leftJoin('tbl_raza', 'tbl_mascota.mascota_raza', '=', 'tbl_raza.raza_id')
            ->leftJoin('tbl_sexo', 'tbl_mascota.mascota_sexo', '=', 'tbl_sexo.sexo_id')
            ->where('mascota_estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('mascota_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idMasc="' . $row->mascota_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->mascota_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idMasc="' . $row->mascota_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->mascota_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idMasc="' . $row->mascota_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->mascota_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idMasc="' . $row->mascota_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idMasc="' . $row->mascota_id . '">
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

    public function edit($idMascota = '')
    {
        $rsMascota = DB::table('tbl_mascota')
            ->where('mascota_id', '=', $idMascota)
            ->first();
        $rsMascota->mascota_nacimiento = (new Carbon($rsMascota->mascota_nacimiento))->format('d/m/Y');

        $clientes = DB::table('tbl_cliente')
            ->where('cliente_estado', '=', ST_ACTIVO)
            ->get();

        $especies = DB::table('tbl_especie')
            ->get();

        $sexos = DB::table('tbl_sexo')
            ->get();

        if ($idMascota == '') {
            return view('mascota.mascota',[
                'clientes'  => $clientes,
                'especies'  => $especies,
                'sexos'  => $sexos,
            ]);
        } else {
            if ($rsMascota) {
                return view('mascota.mascota', [
                    'rsMascota' => $rsMascota,
                    'clientes'  => $clientes,
                    'especies'  => $especies,
                    'sexos'  => $sexos,
                ]);
            } else {
                return redirect()->action('MascotaController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('mascota_nombre') == '') {
            $error['mascota_nombre'] = "Debe ingresar nombre del cliente";
        }

        if ($request->input('mascota_sexo') == '') {
            $error['mascota_sexo'] = "Debe ingresar direccion del cliente";
        }

        if ($request->input('mascota_especie') == '') {
            $error['mascota_especie'] = "Debe ingresar telefono del cliente";
        }

//        if ($request->input('mascota_raza') == '') {
//            $error['mascota_raza'] = "Debe ingresar telefono del cliente";
//        }

        if ($request->input('mascota_cliente_id') == '') {
            $error['mascota_cliente_id'] = "Debe ingresar telefono del cliente";
        }

        if ($request->input('mascota_nacimiento') == '') {
            $error['mascota_nacimiento'] = "Debe ingresar telefono del cliente";
        }else{
            $parte = explode('/',$request->input('mascota_nacimiento'));
            $fecha = (new Carbon($parte[2].'-'.$parte[1].'-'.$parte[0]))->format('Y/m/d');
        }


        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'mascota_nombre'    => $request->input('mascota_nombre'),
            'mascota_sexo'    => $request->input('mascota_sexo'),
            'mascota_especie'    => $request->input('mascota_especie'),
            'mascota_raza'    => $request->input('mascota_raza'),
            'mascota_cliente_id'    => $request->input('mascota_cliente_id'),
            'mascota_peso'    => $request->input('mascota_peso'),
            'mascota_tamano'    => $request->input('mascota_tamano'),
            'mascota_pelaje'    => $request->input('mascota_pelaje'),
            'mascota_nacimiento'    => $fecha,
            'mascota_atributo'    => $request->input('mascota_atributo'),
            'mascota_chip'    => $request->input('mascota_chip'),
        ];

        if ($request->input('mascota_id') == '') {
            $dataInsert['mascota_estado'] = ST_NUEVO;
            $dataInsert['mascota_fecha_registro'] = Carbon::now();
            $id = DB::table('tbl_mascota')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_mascota')
                ->where('mascota_id', $request->input('mascota_id'))
                ->update($dataInsert);
            $id = $request->input('mascota_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_mascota')
            ->where('mascota_id', $request->input('id'))
            ->update([ 'mascota_estado' => ST_INACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_mascota')
            ->where('mascota_id', $request->input('id'))
            ->update([ 'mascota_estado' => ST_ACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_mascota')
            ->where('mascota_id', $request->input('id'))
            ->update([ 'mascota_estado' => ST_ELIMINADO ]);

        return response()->json(['status'=>STATUS_OK]);
    }
}
