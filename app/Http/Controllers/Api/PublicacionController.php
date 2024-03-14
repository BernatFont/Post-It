<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publicacion;

class PublicacionController extends Controller
{
    //OBTENEMOS LOS DATOS DE LA BD
    public function index(){
        $publicaciones = Publicacion::orderBy('created_at', 'desc')->get()->toArray();
    return $publicaciones;
    }

    //CREA Y GUARDA EN LA BD
    public function store(Request $request){
        $request->validate([
            'texto' => 'required'
        ]);
        $publicacion = $request->all();
        $publicacion['id_usuario'] = auth()->id();
        $post = Publicacion::create($publicacion);

        return response()->json(['success'=> true,'data'=> $post]);
    }

    
}
