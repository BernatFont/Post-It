<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publicacion;

class PublicacionController extends Controller
{
    //OBTENEMOS LOS DATOS DE LA BD
    public function index(){
        $publicaciones = Publicacion::all()->toArray();
        return $publicaciones;
    }

    //CREA Y GUARDA EN LA BD
    public function store(Request $request){
        $request->validate([
            'id_usuario' => 'required',
            'texto' => 'required'
        ]);
        $publicacion = $request->all();
        $post = Publicacion::create($publicacion);

        return response()->json(['success'=> true,'data'=> $post]);
    }
}
