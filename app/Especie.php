<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $table = 'tbl_especie';
    protected $primaryKey = 'especie_id';
    public $timestamps = false;
    protected $fillable = [
        'especie_id',
        'especie_nombre',
    ];

    public static function getList($take, $skip)
    {
        return Especie::orderBy('especie_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountEspecie()
    {
        return Especie::count();
    }

    public static function getAllList()
    {
        return Especie::orderBy('especie_id', 'asc')
            ->get();
    }

    public static function getEspecie($idEspecie)
    {
        return Especie::where('especie_id', '=', $idEspecie)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return Especie
     */
    public static function updateRow($request)
    {
        $especie = Especie::findOrFail($request->input('especie_id'));
        $especie->fill($request->all())->save();
        return $especie;
    }

    public static function deleteEspecie($idEspecie)
    {
        Especie::where('especie_id', $idEspecie)
            ->delete();
    }
}
