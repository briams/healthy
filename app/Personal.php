<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'tbl_personal';
    protected $primaryKey = 'personal_id';
    public $timestamps = false;
    protected $fillable = [
        'personal_id',
        'personal_nombre',
        'personal_apellido',
        'personal_dni',
        'personal_email',
        'personal_telefono',
        'personal_nacimiento',
        'personal_cargo',
        'personal_fecha_registro',
        'personal_estado',
        'personal_user_id',
    ];

    public static function getList($take, $skip)
    {
        return self::getClone()
            ->orderBy('personal_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountPersonal()
    {
        return self::getClone()
            ->count();
    }

    public static function getPersonal($idPersonal)
    {
        return Personal::where('personal_id', '=', $idPersonal)
            ->first();
    }

    public static function getPersonalEmail($email)
    {
        return Personal::where('personal_email', '=', $email)
            ->first();
    }

    private static function getClone()
    {
        return Personal::where('personal_estado', '!=', ST_ELIMINADO)
            ->leftJoin('tbl_cargo', 'tbl_personal.personal_cargo', '=', 'tbl_cargo.cargo_id');
    }

    /**
     * @param $request : its a request
     * @return Personal
     */
    public static function updateRow($request)
    {
        $personal = Personal::findOrFail($request->input('personal_id'));
        $personal->fill($request->all())->save();
        return $personal;
    }
}
