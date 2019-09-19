<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function getAllClients(Request $request)
    {
//        dd(Cliente::getAllClients());

        $result = ClienteController::getAllClients();
        $cliente = (Cliente::getfirstClient());
        return view('single-client', compact('cliente'));
    }
}
