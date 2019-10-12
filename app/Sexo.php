<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sexo extends Model
{
    protected $table = 'tbl_sexo';
    protected $primaryKey = 'sexo_id';
    public $timestamps = false;
    protected $fillable = [
        'sexo_id',
        'sexo_nombre',
    ];

    public static function getList($take, $skip)
    {
        return Sexo::orderBy('sexo_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountSexo()
    {
        return Sexo::count();
    }

    public static function getListAll()
    {
        return Sexo::orderBy('sexo_id', 'asc')
            ->get();
    }

    public static function getSexo($idSexo)
    {
        return Sexo::where('sexo_id', '=', $idSexo)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return Sexo
     */
    public static function updateRow($request)
    {
        $especie = Sexo::findOrFail($request->input('sexo_id'));
        $especie->fill($request->all())->save();
        return $especie;
    }

    public static function deleteSexo($idSexo)
    {
        Sexo::where('sexo_id', $idSexo)
            ->delete();
    }
}
