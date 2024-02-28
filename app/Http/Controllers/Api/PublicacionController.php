<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publicacion;

class PublicacionController extends Controller
{
    //
    //
    public function index(){
        $publicaciones = Publicacion::all()->toArray();
        return $publicaciones;
    }
}
