<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $users = User::
        when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%'.request('search_title').'%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%'.request('search_global').'%');

                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(50);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return UserResource
     */
    
    public function store(StoreUserRequest $request)
{
    $role = Role::find($request->role_id);
    $user = new User();
    $user->username = $request->username;
    $user->name = $request->name;
    $user->surname = null;
    $user->biography = null;
    $user->image = null;
    $user->birth_date = null;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);

    try {
        $user->save();

        if ($role) {
            $user->assignRole($role);
        }

        return new UserResource($user);
    } catch (\Exception $e) {
        // Manejar el error, por ejemplo, registrar el error y devolver una respuesta adecuada
        \Log::error('Error al crear el usuario: ' . $e->getMessage());
        return response()->json(['error' => 'Error al crear el usuario. Por favor, inténtelo de nuevo más tarde.'], 500);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */
    public function show(User $user)
    {
        $user->load('roles');
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $role = Role::find($request->role_id);

        $user->name = $request->name;
        $user->email = $request->email;
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password) ?? $user->password;
        }

        if ($user->save()) {
            if ($role) {
                $user->syncRoles($role);
            }
            return new UserResource($user);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('user-delete');
        $user->delete();

        return response()->noContent();
    }

    // Funcion para obtener usuario por id, se declara el contenido json aqui por no utilizar el UserResource ya que genera errores con la fecha created_at.
    public function obtenerUsuario($username) {
        try {
            return $user = User::where('username', $username)
            ->with(['seguidores','roles', 'seguidores.media', 'media', 'seguidos','seguidos.media', 'bloqueados', 'bloqueado', 'publicaciones'=> function ($query) {$query->orderBy('created_at', 'desc');}, 'publicaciones.likes', 'publicaciones.comentarios', 'publicaciones.media'])
            ->withCount('seguidores', 'seguidos')
            ->firstOrFail();

        } catch (\Exception $e) {
            \Log::error('Error al obtener el usuario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener el usuario. Por favor, inténtelo de nuevo más tarde.'], 500);
        }
    }

    public function obtenerTodosUsuarios(){
        return $usuarios = User::with('media')->get();;
    }

    public function modificarUsuario(Request $request){

        // Valida los datos de entrada
        $validatedData = $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string',
            'surname' => 'nullable|string',
            'birth_date' => 'nullable|date|date_format:Y-m-d', // La fecha puede ser nula o debe ser una fecha válida
            'biography' => 'nullable|string',
        ]);

        // Encuentra al usuario
        $user = User::findOrFail($validatedData['id']);
    
            // Actualiza los datos del usuario
            $user->name = $validatedData['name'];
            $user->surname = $request->get('surname');
            $user->birth_date = $request->get('birth_date');
            $user->biography = $request->get('biography');
    
            // Guarda los cambios en el usuario
            $user->save();
    
            return new UserResource($user);
        
    }

    public function seguir($usuario){
        $userId = auth()->id();
    
        $user = User::find($userId);
        $seguido = User::find($usuario);
    
        // Verificar si el usuario ya sigue al usuario especificado
        if ($user->seguidos()->where('id_usuario_seguido', $seguido->id)->exists()) {
            // Si ya lo sigue, realizar un detach
            $user->seguidos()->detach($seguido->id);
            return "Ya no sigues a este usuario.";
        } else {
            // Si no lo sigue, realizar un attach
            $user->seguidos()->attach($seguido->id, ['fecha_seguido' => now()]);
            return "Ahora estás siguiendo a este usuario.";
        }
    }

    public function bloquear($usuario){
        $userId = auth()->id();
    
        $user = User::find($userId);
        $bloqueado = User::find($usuario);
    
        // Verificar si el usuario ya sigue al usuario especificado
        if ($user->bloqueados()->where('id_usuario_bloqueado', $bloqueado->id)->exists()) {
            // Si ya lo sigue, realizar un detach
            $user->bloqueados()->detach($bloqueado->id);
            return "Ya no sigues a este usuario.";
        } else {
            // Si no lo sigue, realizar un attach
            $user->bloqueados()->attach($bloqueado->id);
            return "Ahora estás siguiendo a este usuario.";
        }
    }
    

    public function modificarImagenUsuario(Request $request, $username){
        try {
            $user = User::where('username', $username)->firstOrFail();
    
            // Elimina la imagen anterior del usuario, si existe
            $user->media()->delete();
    
            if ($request->hasFile('imagen')) {
                $user->addMediaFromRequest('imagen')
                    ->preservingOriginal()
                    ->toMediaCollection('images-user');
    
                return response()->json(['message' => 'Imagen del usuario almacenada correctamente'], 200);
            } else {
                // Si no se proporciona una imagen, devuelve un mensaje de error
                return response()->json(['error' => 'No se ha proporcionado una imagen'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al modificar la imagen: ' . $e->getMessage()], 500);
        }
    }
    
    public function colorPost($color,$username){
        $user = User::where('username', $username)->firstOrFail();
        // Encuentra al usuario
        $user->style = $color;
        $user->save();
    
        return new UserResource($user);
    }
}
