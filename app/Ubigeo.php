<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $table = 'tbl_ubigeo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getList()
    {
        return Ubigeo::orderBy('id', 'asc')
            ->get();
    }
}
