<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'tbl_perfil';
    protected $primaryKey = 'perfil_id';
    public $timestamps = false;
    protected $fillable = [
        'perfil_id',
        'perfil_nombre',
        'perfil_estado',
    ];


    public static function getList($take, $skip)
    {
        return self::getClone()
            ->orderBy('perfil_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountPerfil()
    {
        return self::getClone()
            ->count();
    }

    public static function getActivePerfil()
    {
        return self::getClone()
            ->where('perfil_estado', '=', ST_ACTIVO)
            ->get();
    }

    public static function getPerfil($idPerfil)
    {
        return Perfil::where('perfil_id', '=', $idPerfil)
            ->first();
    }

    private static function getClone()
    {
        return Perfil::where('perfil_estado', '!=', ST_ELIMINADO);
    }

    /**
     * @param $request : its a dataInsert
     * @return Perfil
     */
    public static function updateRow($request)
    {
        $perfil = Perfil::findOrFail($request['perfil_id']);
        $perfil->fill($request)->save();
        return $perfil->perfil_id;
    }

    public static function updateStatus($request)
    {
        $perfil = Perfil::findOrFail($request->input('perfil_id'));
        $perfil->fill($request->all())->save();
        return $perfil;
    }
}
