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
        'email',
        'password',
        'fecha_registro',
        'usuario_perfil_id',
        'referencia',
        'tipo',
        'estado',
    ];

    public static function getList($take, $skip)
    {
        return self::getClone()
            ->orderBy('idUsuario', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountUsers()
    {
        return self::getClone()
            ->count();
    }

    public static function getUSer($idUsuario)
    {
        return Usuario::where('idUsuario', '=', $idUsuario)
            ->first();
    }

    public static function getUSerEmail($usuario)
    {
        return Usuario::where('email', '=', $usuario)
            ->first();
    }

    private static function getClone()
    {
        return Usuario::where('estado', '!=', ST_ELIMINADO)
            ->leftJoin('tbl_personal', 'tbl_usuario.referencia', '=', 'tbl_personal.personal_id')
            ->leftJoin('tbl_cliente', 'tbl_usuario.referencia', '=', 'tbl_cliente.cliente_id')
            ->leftJoin('tbl_perfil', 'tbl_usuario.usuario_perfil_id', '=', 'tbl_perfil.perfil_id');
    }

    /**
     * @param $request : its a request
     * @return Usuario
     */
    public static function updateRow($request)
    {
        $usuario = Usuario::findOrFail($request->input('idUsuario'));
        $usuario->fill($request->all())->save();
        return $usuario;
    }
}
