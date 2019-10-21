<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'tbl_cargo';
    protected $primaryKey = 'cargo_id';
    public $timestamps = false;
    protected $fillable = [
        'cargo_id',
        'cargo_nombre',
        'cargo_descripcion',
    ];

    public static function getList($take,$skip)
    {
        return Cargo::orderBy('cargo_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountCargo()
    {
        return Cargo::count();
    }

    public static function getAllList()
    {
        return Cargo::orderBy('cargo_id', 'asc')
            ->get();
    }

    public static function getCargo($idCargo)
    {
        return Cargo::where('cargo_id', '=', $idCargo)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return Cargo
     */
    public static function updateRow($request)
    {
        $cargo = Cargo::findOrFail($request->input('cargo_id'));
        $cargo->fill($request->all())->save();
        return $cargo;
    }

    public static function deleteCargo($idCargo)
    {
        Cargo::where('cargo_id', $idCargo)
            ->delete();
    }
}
