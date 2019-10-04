<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Peru\Http\ContextClient;
use Peru\Jne\{Dni, DniParser};
use Peru\Sunat\{HtmlParser, Ruc, RucParser};

use Carbon\Carbon;

//require 'vendor/autoload.php';

class ClienteController extends Controller
{
    public function index()
    {
        return view('cliente.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_cliente')
            ->leftJoin('tbl_ubigeo', 'tbl_cliente.cliente_ubigeo', '=', 'tbl_ubigeo.ubigeo')
            ->leftJoin('tbl_tipo_documento', 'tbl_cliente.cliente_tipo_documento', '=', 'tbl_tipo_documento.tdc_id')
            ->where('cliente_estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('cliente_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idCli="' . $row->cliente_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->cliente_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idCli="' . $row->cliente_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->cliente_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idCli="' . $row->cliente_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->cliente_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idCli="' . $row->cliente_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idCli="' . $row->cliente_id . '">
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

    public function edit($idCliente = '')
    {
        $rsCliente = DB::table('tbl_cliente')
            ->where('cliente_id', '=', $idCliente)
            ->first();
        $rsCliente->cliente_fecha_nacimiento = (new Carbon($rsCliente->cliente_fecha_nacimiento))->format('d/m/Y');

        $ubigeo = DB::table('tbl_ubigeo')
            ->orderBy('id', 'asc')
            ->get();

        $tipDocs = DB::table('tbl_tipo_documento')
            ->orderBy('tdc_id', 'asc')
            ->get();

        if ($idCliente == '') {
            return view('cliente.cliente',[
                'ubigeo'  => $ubigeo,
                'tipDocs'  => $tipDocs,
            ]);
        } else {
            if ($rsCliente) {
                return view('cliente.cliente', [
                    'rsCliente' => $rsCliente,
                    'ubigeo'  => $ubigeo,
                    'tipDocs'  => $tipDocs,
                ]);
            } else {
                return redirect()->action('ClienteController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('cliente_nombres') == '') {
            $error['cliente_nombres'] = "Debe ingresar nombre del cliente";
        }

        if ($request->input('cliente_direccion') == '') {
            $error['cliente_direccion'] = "Debe ingresar direccion del cliente";
        }

        if ($request->input('cliente_telefono') == '') {
            $error['cliente_telefono'] = "Debe ingresar telefono del cliente";
        }

        if ($request->input('cliente_email') == '') {
            $error['cliente_email'] = "Debe ingresar email del cliente";
        }else{
            if (!filter_var($request->input('cliente_email'), FILTER_VALIDATE_EMAIL)) {
                $error['cliente_email'] = 'Es necesario que agregues un E-mail valido';
            }
        }

        if ($request->input('cliente_tipo_documento') == '') {
            $error['cliente_tipo_documento'] = "Debe seleccione tipo de documento del cliente";
        }

        if ($request->input('cliente_nro_documento') == '') {
            $error['cliente_nro_documento'] = "Debe ingresar nro de documento del cliente";
        }

        if ($request->input('cliente_ubigeo') == '') {
            $error['cliente_ubigeo'] = "Debe ingresar ubigeo del cliente";
        }

        if ($request->input('cliente_fecha_nacimiento') == '') {
//            $error['cliente_fecha_nacimiento'] = "Debe ingresar fecha de nacimiento del cliente";
            $fecha=null;
        }else{
            $parte = explode('/',$request->input('cliente_fecha_nacimiento'));
            $fecha = (new Carbon($parte[2].'-'.$parte[1].'-'.$parte[0]))->format('Y/m/d');
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }
        if ($request->input('sigla') == 'RUC'){
            if ( ! (strlen($request->input('cliente_nro_documento')) == 11) ) {
                $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'El nro de RUC debe contar con 11 digitos'];
                return response()->json($res);
            }
        }

        if ($request->input('sigla') == 'DNI' ){
            if ( ! (strlen($request->input('cliente_nro_documento')) == 8 )) {
                $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'El nro de DNI debe contar con 8 digitos'];
                return response()->json($res);
            }
        }

        $cliente_fullname = $request->input('cliente_nombres').(($request->input('cliente_apellidos') != '') ? ' '.$request->input('cliente_apellidos') : '');

        $dataInsert = [
            'cliente_nombres'    => $request->input('cliente_nombres'),
            'cliente_apellidos'    => $request->input('cliente_apellidos'),
            'cliente_fullname'    => $cliente_fullname,
            'cliente_direccion'    => $request->input('cliente_direccion'),
            'cliente_telefono'    => $request->input('cliente_telefono'),
            'cliente_email'    => $request->input('cliente_email'),
            'cliente_tipo_documento'    => $request->input('cliente_tipo_documento'),
            'cliente_nro_documento'    => $request->input('cliente_nro_documento'),
            'cliente_ubigeo'    => $request->input('cliente_ubigeo'),
            'cliente_sexo'    => $request->input('cliente_sexo'),
            'cliente_fecha_nacimiento'    => $fecha,
        ];

        if ($request->input('cliente_id') == '') {
            $dataInsert['cliente_estado'] = ST_NUEVO;
            $dataInsert['cliente_registro_ts'] = Carbon::now();
            $id = DB::table('tbl_cliente')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_cliente')
                ->where('cliente_id', $request->input('cliente_id'))
                ->update($dataInsert);
            $id = $request->input('cliente_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_cliente')
            ->where('cliente_id', $request->input('id'))
            ->update([ 'cliente_estado' => ST_INACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_cliente')
            ->where('cliente_id', $request->input('id'))
            ->update([ 'cliente_estado' => ST_ACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_cliente')
            ->where('cliente_id', $request->input('id'))
            ->update([ 'cliente_estado' => ST_ELIMINADO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function searchDoc(Request $request){

        $sigla = $request->input('sigla');
        $nroDoc = $request->input('nroDoc');
        if($sigla == 'DNI'){
            $cs = new Dni(new ContextClient(), new DniParser());
            $person = $cs->get($nroDoc);
            if (!$person) {
                $res = ['status' => STATUS_FAIL, 'msg' => 'Dni invalido'];
                return response()->json($res);
            }else{
                $res = ['status' => STATUS_OK, 'msg' => 'Dni Encontrado', 'nombre' => $person->nombres,'apellido' => $person->apellidoPaterno.' '.$person->apellidoMaterno ,'direccion' => ''];
                return response()->json($res);
            }
        }else if($sigla == 'RUC'){
            $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));

            $company = $cs->get($nroDoc);
            if (!$company) {
                $res = ['status' => STATUS_FAIL, 'msg' => 'Ruc invalido'];
                return response()->json($res);
            }else{
                $res = ['status' => STATUS_OK, 'msg' => 'Razon Social Encontrada', 'nombre' => $company->razonSocial,'apellido' =>'' ,'direccion' => $company->direccion];
                return response()->json($res);
            }
        }
    }
}
