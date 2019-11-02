<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'tbl_tratamiento';
    protected $primaryKey = 'tratamiento_id';
    public $timestamps = false;
    protected $fillable = [
        'tratamiento_id',
        'tratamiento_historia_id',
        'tratamiento_descripcion',
        'tratamiento_tipo',
        'tratamiento_fecha_registro',
        'tratamiento_estado',
        'tratamiento_usuario',
    ];

    public static function getList($take,$skip,$idHistoria)
    {
        return self::getClone($idHistoria)->orderBy('tratamiento_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountTratamiento($idHistoria)
    {
        return self::getClone($idHistoria)->count();
    }

    public static function getTratamiento($idTratamiento)
    {
        return Tratamiento::where('tratamiento_id', '=', $idTratamiento)
            ->first();
    }

    private static function getClone($idHistoria)
    {
        return Tratamiento::where('tratamiento_estado', '=', ST_ACTIVO)
            ->where('tratamiento_historia_id', '=', $idHistoria);
    }

    /**
     * @param $request : its a request
     * @return Tratamiento
     */
    public static function updateRow($request)
    {
        $tratamiento = Tratamiento::findOrFail($request->input('tratamiento_id'));
        $tratamiento->fill($request->all())->save();
        return $tratamiento;
    }

    public static function getCountProductFecha($inicio,$fin)
    {
        return Tratamiento::selectRaw('SUM(tratamientod_cantidad) as total, tratamiento_tipo , tratamientod_producto_id')
            ->join('tbl_tratamiento_detalle', 'tbl_tratamiento.tratamiento_id', '=', 'tbl_tratamiento_detalle.tratamientod_tratamiento_id')
            ->whereDate('tratamiento_fecha_registro', '>=', $inicio)
            ->whereDate('tratamiento_fecha_registro', '<=', $fin)
            ->where('tratamiento_estado', '=', ST_ACTIVO)
            ->groupBy('tratamiento_tipo')
            ->groupBy('tratamientod_producto_id')
            ->get();
//            ->tosql();
    }
}
