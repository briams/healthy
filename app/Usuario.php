<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;

class Usuario extends Model
{
    protected $table = 'tbl_usuario';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;
    protected $fillable = [
        'idUsuario',
        'nombre',
        'apellido',
        'numero_doc',
        'email',
        'telefono',
        'usuario_perfil_id',
        'usuario',
        'email',
        'password',
        'estado',
    ];

    public static function geList($take, $skip, $filter = 'idUsuario')
    {
        return self::getClone()
            ->orderBy($filter, 'desc')
            ->limit($take)
            ->offset($skip)->get();
    }

    public static function getUSer($idUsuario)
    {
        return Usuario::where('idUsuario', '=', $idUsuario)->first();
    }

    public static function getCountUsers()
    {
        return Usuario::where('estado', '!=', ST_ELIMINADO)->count();
    }


    private static function getClone()
    {
        return Usuario::where('estado', '!=', ST_ELIMINADO);
    }

    /**
     * @param $request: its a request
     * @return Usuario
     */
    public static function updateRow($request) {
        $usuario = Usuario::findOrFail($request->input('idUsuario'));
        return $usuario->fill($request->all())->save();
    }
}
