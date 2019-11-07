<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Historia;
use App\Mascota;
use App\Producto;
use App\Tratamiento;
use App\TratamientoDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TratamientoController extends Controller
{
    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $idHistoria = $request->input('tratamiento_historia_id');

        $countRegs = Tratamiento::getCountTratamiento($idHistoria);
        $rows = Tratamiento::getList($take, $skip,$idHistoria);

        foreach ($rows as $row) {

            $row->tratamiento_fecha_registro = (new Carbon($row->tratamiento_fecha_registro))->format('d/m/Y');

            if($row->tratamiento_tipo == 1) {
                $row->tratamiento_tipo = 'tratamiento interno';
            }elseif ($row->tratamiento_tipo == 2){
                $row->tratamiento_tipo = 'Receta';
            }

            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idTratamiento="' . $row->tratamiento_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idTratamiento="' . $row->tratamiento_id . '">
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

    public function edit($idHistoria,$idTratamiento = '')
    {
        Session::forget('listProductos');
        $rsHistoria = Historia::getHistoria($idHistoria);
        $rsMascota = Mascota::getMascota($rsHistoria->historia_mascota_id);
        $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
        $rsProductos = Producto::getListActive();
        if ($idTratamiento == '') {
            return view('tratamiento.tratamiento', [
                'idHistoria' => $idHistoria,
                'rsMascota' => $rsMascota,
                'rsCliente' => $rsCliente,
                'rsProductos' => $rsProductos,
            ]);
        }

        $rsTratamiento = Tratamiento::getTratamiento($idTratamiento);
        $rsTratamientoDetalle = TratamientoDetalle::getListDetalle($idTratamiento);
        $arrayListP = [];
        foreach ($rsTratamientoDetalle as $index=>$row){
            $arrayListP[$row->tratamientod_producto_id] = [
                'tratamientod_producto_id'      => $row->tratamientod_producto_id,
                'tratamientod_frecuencia'       => $row->tratamientod_frecuencia,
                'tratamientod_duracion'     => $row->tratamientod_duracion,
                'tratamientod_dosis'        => $row->tratamientod_dosis,
                'tratamientod_cantidad'        => $row->tratamientod_cantidad,
            ];
        }
        Session::put('listProductos', $arrayListP);

        if (!$rsTratamiento) {
            return redirect()->action('InternamientoController@index');
        }
        return view('tratamiento.tratamiento', [
            'rsTratamiento' => $rsTratamiento,
            'idHistoria' => $idHistoria,
            'rsMascota' => $rsMascota,
            'rsCliente' => $rsCliente,
            'rsProductos' => $rsProductos,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'tratamiento_historia_id' => 'required',
            'tratamiento_descripcion' => 'required',
            'tratamiento_tipo' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $productos = Session::get('listProductos');
        if($productos == ''){
            return response()->json(['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Debe tener detalle']);
        }

        $rsHistoria = Historia::getHistoria($request->input('tratamiento_historia_id'));

        $user = Session::get('usuario');
        $request->merge(['tratamiento_usuario' => $user->idUsuario]);

        if (!$request->filled('tratamiento_id')) {
            $request->merge(['tratamiento_estado' => ST_ACTIVO]);
            $request->merge(['tratamiento_fecha_registro' => Carbon::now() ]);
            $tratamiento = Tratamiento::create($request->all());
        }else{
            $tratamiento = Tratamiento::updateRow($request);
            TratamientoDetalle::deleteAllTratamientoDetalle($tratamiento->tratamiento_id);
        }

        foreach ($productos as $index=>$row){
            $row['tratamientod_tratamiento_id'] = $tratamiento->tratamiento_id;
            TratamientoDetalle::create($row);
        }
        Session::forget('listProductos');

        return response()->json(['status' => STATUS_OK, 'id' => $tratamiento->tratamiento_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['tratamiento_id' => $request->input('id')]);
        $request->merge(['tratamiento_estado' => ST_ELIMINADO]);
        Tratamiento::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function GetMainListDetalle(Request $request)
    {
        $productosTemporal = Session::get('listProductos');
        $arrayProductos = [];
        if($productosTemporal != '')
        if (count($productosTemporal) > 0)
        {
            $productosTemporal = (object)$productosTemporal;
            foreach($productosTemporal as $producto)
            {
                $producto = (object)$producto;
                $array = [];

//                $editar = '<a class="ui button mini circular ajxEditIngreso icon " data-almingid="'.$producto->idproducto.'" ><i class="icon write"></i></a>';
                $eliminar = '<div class="ui button medium red circular ajxDelete inverted icon" data-idProducto="' . $producto->tratamientod_producto_id . '"><i class="icon trash alternate"></i></div>';

                $array['eliminar'] = $eliminar;
                $rsProducto = Producto::getProducto($producto->tratamientod_producto_id);
                $array['tratamientod_producto_id'] = $rsProducto->pro_nombre;

                $array['tratamientod_frecuencia'] = $producto->tratamientod_frecuencia . ' horas';

                $array['tratamientod_duracion'] = $producto->tratamientod_duracion. ' dias';

                $array['tratamientod_dosis'] = $producto->tratamientod_dosis;

                $array['tratamientod_cantidad'] = $producto->tratamientod_cantidad;

                $arrayProductos[] = (object)$array;
            }
        }

        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $arrayProductos, 'count' => count($arrayProductos)]]);
    }

    public function addDetalle(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'tratamientod_producto_id' => 'required',
            'tratamientod_frecuencia' => 'required',
            'tratamientod_duracion' => 'required',
            'tratamientod_dosis' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $idProducto = $request->input('tratamientod_producto_id');

        $productos = Session::get('listProductos');

        $productos[$idProducto] = $request->all();

        Session::put('listProductos', $productos);

        return response()->json(['status' => STATUS_OK]);
    }

    public function removeDetalle(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $productos = Session::get('listProductos');
        $idProducto = $request->input('id');
        foreach($productos as $index =>$item)
        {
            if($item['tratamientod_producto_id'] == $idProducto)
            {
                unset($productos[$idProducto]);
            }
        }
        Session::put('listProductos', $productos);
        return response()->json(['status' => STATUS_OK]);
    }
}
