<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected $table = 'tbl_privilegio';
    protected $primaryKey = '';
    public $timestamps = false;

    public static function getPrivilegio($idPerfil,$idModule)
    {
        return Privilegio::where('priv_perfil_id', $idPerfil)
            ->where('priv_modulo_id', $idModule)
            ->first();
    }

    public static function deletePrivilegio($idPerfil)
    {
        Privilegio::where('priv_perfil_id', $idPerfil)
            ->delete();
    }

}
