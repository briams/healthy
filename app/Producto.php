<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'tbl_producto';
    protected $primaryKey = 'pro_id';
    public $timestamps = false;
    protected $fillable = [
        'pro_id',
        'pro_estado',
        'pro_codigo',
        'pro_nombre',
        'pro_laboratorio',
        'pro_unidad_medida',
    ];

    public static function getList($take,$skip)
    {
        return self::getClone()->orderBy('pro_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountProducto()
    {
        return self::getClone()->count();
    }

    public static function getListActive()
    {
        return Producto::where('pro_estado', '=', ST_ACTIVO)
            ->leftJoin('tbl_unidad_medida', 'tbl_producto.pro_unidad_medida', '=', 'tbl_unidad_medida.umd_id')
            ->orderBy('pro_id', 'asc')
            ->get();
    }

    public static function getProducto($idProducto)
    {
        return Producto::where('pro_id', '=', $idProducto)
            ->first();
    }

    private static function getClone()
    {
        return Producto::leftJoin('tbl_unidad_medida', 'tbl_producto.pro_unidad_medida', '=', 'tbl_unidad_medida.umd_id')
            ->where('pro_estado', '!=', ST_ELIMINADO);
    }

    /**
     * @param $request : its a request
     * @return Producto
     */
    public static function updateRow($request)
    {
        $producto = Producto::findOrFail($request->input('pro_id'));
        $producto->fill($request->all())->save();
        return $producto;
    }
}
