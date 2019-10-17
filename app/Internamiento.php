<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Internamiento extends Model
{
    protected $table = 'tbl_internamiento';
    protected $primaryKey = 'internamiento_id';
    public $timestamps = false;
    protected $fillable = [
        'internamiento_id',
        'internamiento_historia_id',
        'internamiento_fecha_inicio',
        'internamiento_fecha_salida',
        'internamiento_dias',
        'internamiento_motivo',
        'internamiento_fecha_registro',
        'internamiento_estado',
    ];

    public static function getList($take,$skip,$idHistoria)
    {
        return self::getClone($idHistoria)->orderBy('internamiento_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountInternamiento($idHistoria)
    {
        return self::getClone($idHistoria)->count();
    }

    public static function getInternamiento($idInternamiento)
    {
        return Internamiento::where('internamiento_id', '=', $idInternamiento)
            ->first();
    }

    private static function getClone($idHistoria)
    {
        return Internamiento::where('internamiento_estado', '=', ST_ACTIVO)
            ->where('internamiento_historia_id', '=', $idHistoria);
    }

    /**
     * @param $request : its a request
     * @return Internamiento
     */
    public static function updateRow($request)
    {
        $internamiento = Internamiento::findOrFail($request->input('internamiento_id'));
        $internamiento->fill($request->all())->save();
        return $internamiento;
    }
}
