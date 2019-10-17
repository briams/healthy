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
}
