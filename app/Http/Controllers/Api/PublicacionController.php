<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publicacion;
//Importamos modelo usuario para poder hacer la relacion con el post (como un join)
use App\Models\User;
use App\Models\Like;
use App\Models\Comentario;

class PublicacionController extends Controller
{
    //OBTENEMOS LOS DATOS DE LA BD
    public function index(){
        // 'With' de 'user' para importar sus datos y 'orderby' para mostrar los post por fecha de mas reciente a mas antiguo
        $publicaciones = Publicacion::with('user')->withCount('likes','comentarios')->orderBy('created_at', 'desc')->get()->toArray();
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

       // Verifica si la solicitud HTTP contiene un archivo con el nombre 'imagen'
        if ($request->hasFile('imagen')) {
            // Si hay un archivo en la solicitud, añádelo al modelo de publicación ($post)
            // Utilizando el método addMediaFromRequest() proporcionado por Laravel Media Library
            // 'imagen' se refiere al nombre del campo en el formulario HTML que contiene el archivo de imagen
            $post->addMediaFromRequest('imagen')
                // Garantiza que se conserve el archivo original sin procesar que se ha cargado
                // Útil cuando se desea conservar la imagen sin procesar además de realizar manipulaciones adicionales en la imagen
                ->preservingOriginal()
                // Guarda la imagen en una colección de medios llamada 'images-publicacion'
                // Esto significa que la imagen se almacenará y se asociará con el modelo de publicación ($post) bajo esta colección de medios
                ->toMediaCollection('images-publicacion');
        }
        


        return response()->json(['success'=> true,'data'=> $post]);
    }

    // Funcion para devolver una publicacion por id
    public function mostrarPublicacion($id, Request $request) {
        $publicacion = Publicacion::find($id);
    
        // Verifica si la publicación existe
        if (!$publicacion) {
            return response()->json(['error' => 'Publicación no encontrada'], 404);
        } else {
            $publicacion = $publicacion->load('user','comentarios.user')->loadCount('likes','comentarios')->toArray();
        }
        // Devuelve solo la publicación encontrada por su ID
        return response()->json($publicacion);
    }

    
}

