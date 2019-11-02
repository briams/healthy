<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'tbl_module';
    protected $primaryKey = 'idModule';
    public $timestamps = false;
    protected $fillable = [
        'idModule',
        'nombre',
        'url',
        'icono',
        'orden',
        'padre_id',
        'is_parent',
        'estado',
    ];

    public static function getList($take, $skip)
    {
        return self::getClone()
            ->orderBy('idModule', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountModule()
    {
        return self::getClone()
            ->count();
    }

    public static function getListModuleParent()
    {
        return self::getClone()
            ->where('is_parent', '=', DB_TRUE)
            ->orderBy('orden', 'asc')
            ->get();
    }

    public static function getListModuleChildren($idPadre)
    {
        return self::getClone()
            ->where('padre_id', '=', $idPadre)
            ->orderBy('orden', 'asc')
            ->get();
    }

    public static function getModule($idModulo)
    {
        return Modulo::where('idModule', '=', $idModulo)
            ->first();
    }

    public static function getIdModule($url)
    {
        return Modulo::where('url', '=', $url)
            ->first()->idModule;
    }

    private static function getClone()
    {
        return Modulo::where('estado', '=', DB_TRUE);
    }

    /**
     * @param $request : its a request
     * @return Modulo
     */
    public static function updateRow($request)
    {
        $modulo = Modulo::findOrFail($request->input('idModule'));
        $modulo->fill($request->all())->save();
        return $modulo;
    }
}
