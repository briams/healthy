<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $table = 'tbl_raza';
    protected $primaryKey = 'raza_id';
    public $timestamps = false;
    protected $fillable = [
        'raza_id',
        'raza_nombre',
        'raza_especie_id',
    ];

    public static function getList($take,$skip)
    {
        return self::getClone()->orderBy('raza_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountRaza()
    {
        return self::getClone()->count();
    }

    public static function getRaza($idRaza)
    {
        return Raza::where('raza_id', '=', $idRaza)
            ->first();
    }

    public static function getListRazaXEspecie($idEspecie)
    {
        return Raza::where('raza_especie_id', '=', $idEspecie)
            ->get();
    }

    public static function getListAll()
    {
        return Raza::get();
    }

    private static function getClone()
    {
        return Raza::leftJoin('tbl_especie', 'tbl_raza.raza_especie_id', '=', 'tbl_especie.especie_id');
    }

    /**
     * @param $request : its a request
     * @return Raza
     */
    public static function updateRow($request)
    {
        $raza = Raza::findOrFail($request->input('raza_id'));
        $raza->fill($request->all())->save();
        return $raza;
    }

    public static function deleteRaza($idRaza)
    {
        Raza::where('raza_id', $idRaza)
            ->delete();
    }
}
