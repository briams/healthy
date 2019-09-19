<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //protected $connection = 'mysql';
    protected $table = 'cliente';
    protected $primaryKey = 'cliente_id';
    public $timestamps = false;

    public static function getAllClients()
    {
        return Cliente::select('*')->get();
//        return Cliente::select('*')->toSql();
//        return Cliente::select('*')->get();
    }

    public static function getfirstClient()
    {
        return Cliente::select('*')->first();
//        return Cliente::select('*')->toSql();
//        return Cliente::select('*')->get();
    }

}
