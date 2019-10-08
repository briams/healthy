<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'tbl_perfil';
    protected $primaryKey = 'perfil_id';
    public $timestamps = false;

    public static function getList($take = '', $skip = '', $filter = 'perfil_id')
    {
        $select = self::getClone();
        $select->orderBy($filter, 'desc');
        if ($take != '') {
            $select->limit($take);
            $select->offset($skip);
        }
        return $select->get();
    }

    private static function getClone()
    {
        return Perfil::where('perfil_estado', '!=', ST_ELIMINADO);
    }
}
