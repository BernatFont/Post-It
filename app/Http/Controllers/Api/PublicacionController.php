<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publicacion;
//Importamos modelo usuario para poder hacer la relacion con el post (como un join)
use App\Models\User;
use App\Models\Like;

class PublicacionController extends Controller
{
    //OBTENEMOS LOS DATOS DE LA BD
    public function index(){
        // 'With' de 'user' para importar sus datos y 'orderby' para mostrar los post por fecha de mas reciente a mas antiguo
        $publicaciones = Publicacion::with('user')->withCount('likes')->orderBy('created_at', 'desc')->get()->toArray();
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

