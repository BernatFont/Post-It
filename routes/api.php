<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicacionController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ComentarioController;
use App\Http\Controllers\Api\SeguidoController;
use App\Http\Controllers\Api\BloqueadoController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MensajeController;
use App\Http\Controllers\Api\NotificacionController;

use App\Http\Controllers\Auth\ResetPasswordController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ForgotPasswordController;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::get('publicacions', [PublicacionController::class, 'index']); // Devuelve todas las publicaciones
Route::post('publicacions/',[PublicacionController::class,'store']); // Crea una publicacion
Route::get('publicacions/{id}',[PublicacionController::class, 'mostrarPublicacion']); // Muestra una publicacion por id
Route::put('publicacions/update/{id}', [PublicacionController::class,'update']); //Editar publicacion
Route::delete('publicacions/delete/{id}', [PublicacionController::class, 'destroy']); // Eliminar publicacion
Route::get('publicacions/filter/{filter}', [PublicacionController::class, 'filterPosts']); // Eliminar publicacion

Route::post('/like/add/{id}', [LikeController::class, 'store']); // Ejecuta la funcion de like

Route::get('comentarios', [ComentarioController::class, 'index']); // Obtener comentarios
Route::post('/comentario/add/{id}', [ComentarioController::class, 'store']); // Añadir comentario
Route::delete('comentario/delete/{id}', [ComentarioController::class, 'destroy']); // Eliminar comentario

Route::get('/usuario/{username}', [UserController::class, 'obtenerUsuario']); // Obtiene un usuario por username
Route::get('/usuarios', [UserController::class, 'obtenerTodosUsuarios']); // Obtiene todos los usuarios
Route::post('/usuarios/modificar', [UserController::class, 'modificarUsuario']); // Modifica un usuario
Route::post('/usuarios/{username}/modificarImagen', [UserController::class, 'modificarImagenUsuario']); // Modifica la imagen de un usuario
Route::post('/usuarios/colorPost/{color}/{username}', [UserController::class, 'colorPost']); // Modifica el color de los post de el usuario logeado
Route::delete('usuarios/{user}', [UserController::class, 'destroy']);// Elimina a un usuario con todas las dependencias
Route::post('usuarios/seguir/{user}', [UserController::class, 'seguir']);// Elimina a un usuario con todas las dependencias
Route::post('usuarios/bloquear/{user}', [UserController::class, 'bloquear']);// Elimina a un usuario con todas las dependencias

Route::get('/chats', [ChatController::class, 'index']); // Obtener todos los chat
Route::get('/chats/{id}', [ChatController::class, 'obtenerChat']); // Obtener conversacion deu n chat
// Route::post('/follow/{id}', [SeguidoController::class, 'store']); // Seguir usuario
// Route::post('/block/{id}', [BloqueadoController::class, 'store']); // Bloquear usuario
Route::post('/chat/{id}', [ChatController::class, 'store']); // Crear chat
Route::get('/chat/mensajes/{id}', [MensajeController::class, 'index']); // Obtener mensajes de un chat
Route::post('/chat/mensajes/{id}', [MensajeController::class, 'store']); // Añadir mensaje al chat

Route::get('/notificaciones', [NotificacionController::class, 'index']); // Obtener notificaciones
Route::get('/notificaciones/contador',[NotificacionController::class, 'numNotificaciones']); // Obtener numero de notificaciones
Route::post('/notificacion', [NotificacionController::class, 'store']); // Crear notificacion
Route::delete('/notificacion/delete/{id}', [NotificacionController::class, 'destroy']); // Eliminar notificacion
Route::delete('/notificacion/deleteAll/{id}', [NotificacionController::class, 'eliminarNotificacionesUsuario']); // Eliminar notificacion

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    //Route::apiResource('exercises', ExerciseController::class);
    Route::post('exercises/', [ExerciseController::class,'store']); //Guardar
    Route::get('exercises', [ExerciseController::class,'index']); //Listar
    Route::get('exercises/{exercise}', [ExerciseController::class,'show']); //Mostrar
    Route::post('exercises/update/{id}', [ExerciseController::class,'update']); //Editar

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    Route::get('category-list', [CategoryController::class, 'getList']);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});


Route::get('category-list', [CategoryController::class, 'getList']);
Route::get('get-posts', [PostController::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostController::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostController::class, 'getPost']);
