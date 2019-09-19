<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
  public function index()
  {
    $user = DB::table('tbl_usuario')->get();
    return view('usuarios.index',['users'=>$user]);
  }
}
