<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TratamientoDetalle extends Model
{
    protected $table = 'tbl_tratamiento_detalle';
    protected $primaryKey = 'internamiento_id';
    public $timestamps = false;
    protected $fillable = [
        'tratamientod_id',
        'tratamientod_tratamiento_id',
        'tratamientod_producto_id',
        'tratamientod_dosis',
        'tratamientod_frecuencia',
        'tratamientod_duracion',
    ];

    public static function getList($take,$skip,$idTratamiento)
    {
        return self::getClone($idTratamiento)->orderBy('tratamientod_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountInternamiento($idTratamiento)
    {
        return self::getClone($idTratamiento)->count();
    }

    public static function getListDetalle($idTratamiento)
    {
        return self::getClone($idTratamiento)->orderBy('tratamientod_id', 'desc')
            ->get();
    }

    private static function getClone($idTratamiento)
    {
        return TratamientoDetalle::where('tratamientod_tratamiento_id', '=', $idTratamiento);
    }

    /**
     * @param $request : its a request
     * @return TratamientoDetalle
     */
    public static function updateRow($request)
    {
        $tratamientoDetalle = TratamientoDetalle::findOrFail($request->input('tratamientod_id'));
        $tratamientoDetalle->fill($request->all())->save();
        return $tratamientoDetalle;
    }

    public static function deleteTratamientoDetalle($idTratamientoDetalle)
    {
        TratamientoDetalle::where('tratamientod_id', $idTratamientoDetalle)
            ->delete();
    }

    public static function deleteAllTratamientoDetalle($idTratamiento)
    {
        TratamientoDetalle::where('tratamientod_tratamiento_id', $idTratamiento)
            ->delete();
    }
}
