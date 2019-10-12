<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $table = 'tbl_vacuna';
    protected $primaryKey = 'vac_id';
    public $timestamps = false;
    protected $fillable = [
        'vac_id',
        'vac_abreviatura',
        'vac_descripcion',
        'vac_estado',
    ];

    public static function getList($take, $skip)
    {
        return self::getClone()
            ->orderBy('vac_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountVacuna()
    {
        return self::getClone()
            ->count();
    }

    public static function getListAll()
    {
        return  Vacuna::where('vac_estado', '=', ST_ACTIVO)
            ->orderBy('vac_id', 'desc')
            ->get();
    }

    public static function getVacuna($idVacuna)
    {
        return Vacuna::where('vac_id', '=', $idVacuna)
            ->first();
    }

    private static function getClone()
    {
        return Vacuna::where('vac_estado', '!=', ST_ELIMINADO);
    }

    /**
     * @param $request : its a request
     * @return Vacuna
     */
    public static function updateRow($request)
    {
        $vacuna = Vacuna::findOrFail($request->input('vac_id'));
        $vacuna->fill($request->all())->save();
        return $vacuna;
    }
}
