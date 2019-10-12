<?php

namespace App\Http\Controllers;

use App\Producto;
use App\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        return view('producto.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Producto::getCountProducto();
        $rows = Producto::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idProd="' . $row->pro_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->pro_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idProd="' . $row->pro_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->pro_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idProd="' . $row->pro_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->pro_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idProd="' . $row->pro_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idProd="' . $row->pro_id . '">
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

    public function edit($idProducto = '')
    {
        $UnidadMedidas = UnidadMedida::getAllList();
        if ($idProducto == '') {
            return view('producto.producto', [
                'UnidadMedidas' => $UnidadMedidas,
            ]);
        }

        $rsProducto = Producto::getProducto($idProducto);
        if (!$rsProducto) {
            return redirect()->action('ProductoController@index');
        }
        return view('producto.producto', [
            'rsProducto' => $rsProducto,
            'UnidadMedidas' => $UnidadMedidas,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'pro_codigo' => 'required',
            'pro_nombre' => 'required',
            'pro_unidad_medida' => 'required',
            'pro_laboratorio' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('pro_id')) {
            $request->merge(['pro_estado' => ST_NUEVO]);
            $producto = Producto::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $producto->pro_id]);
        }
        $producto = Producto::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $producto->pro_id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['pro_id' => $request->input('id')]);
        $request->merge(['pro_estado' => ST_INACTIVO]);
        Producto::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['pro_id' => $request->input('id')]);
        $request->merge(['pro_estado' => ST_ACTIVO]);
        Producto::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['pro_id' => $request->input('id')]);
        $request->merge(['pro_estado' => ST_ELIMINADO]);
        Producto::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
