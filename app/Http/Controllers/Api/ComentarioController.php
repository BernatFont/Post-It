<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    //
    public function index(){
        $comentarios = Comentario::all()->toArray();
        return $comentarios;
    }
}
