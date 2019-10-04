<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $select = DB::table('tbl_producto')
            ->leftJoin('tbl_unidad_medida', 'tbl_producto.pro_unidad_medida', '=', 'tbl_unidad_medida.umd_id')
            ->where('pro_estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('pro_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

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
        $rsProducto = DB::table('tbl_producto')
            ->where('pro_id', '=', $idProducto)
            ->first();

        $UnidadMedidas = DB::table('tbl_unidad_medida')
            ->orderBy('umd_id', 'asc')
            ->get();

        if ($idProducto == '') {
            return view('producto.producto',[
                'UnidadMedidas'  => $UnidadMedidas,
            ]);
        } else {
            if ($rsProducto) {
                return view('producto.producto', [
                    'rsProducto' => $rsProducto,
                    'UnidadMedidas'  => $UnidadMedidas,
                ]);
            } else {
                return redirect()->action('ProductoController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('pro_codigo') == '') {
            $error['pro_codigo'] = "Debe ingresar codigo del producto";
        }

        if ($request->input('pro_nombre') == '') {
            $error['pro_nombre'] = "Debe ingresar nombre del producto";
        }

        if ($request->input('pro_unidad_medida') == '') {
            $error['pro_unidad_medida'] = "Debe seleccione unidad de medida del producto";
        }

        if ($request->input('pro_laboratorio') == '') {
            $error['pro_laboratorio'] = "Debe ingresar laboratorio del producto";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'pro_codigo'    => $request->input('pro_codigo'),
            'pro_nombre'    => $request->input('pro_nombre'),
            'pro_unidad_medida'    => $request->input('pro_unidad_medida'),
            'pro_laboratorio'    => $request->input('pro_laboratorio'),
        ];

        if ($request->input('pro_id') == '') {
            $dataInsert['pro_estado'] = ST_NUEVO;
            $id = DB::table('tbl_producto')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_producto')
                ->where('pro_id', $request->input('pro_id'))
                ->update($dataInsert);
            $id = $request->input('pro_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_producto')
            ->where('pro_id', $request->input('id'))
            ->update([ 'pro_estado' => ST_INACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_producto')
            ->where('pro_id', $request->input('id'))
            ->update([ 'pro_estado' => ST_ACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_producto')
            ->where('pro_id', $request->input('id'))
            ->update([ 'pro_estado' => ST_ELIMINADO ]);

        return response()->json(['status'=>STATUS_OK]);
    }
}
