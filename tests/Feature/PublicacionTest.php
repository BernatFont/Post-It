<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Publicacion;
use App\Models\Comentario;
use App\Models\Like;
use Illuminate\Support\Facades\Hash;

class PublicacionTest extends TestCase
{

    //use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature CORRECT test example.
     */
    public function test_create_user(){
        // Crear usuario usando la fábrica y especificar un nombre para coincidir con la aserción
        $user = User::factory()->create([
            'username' => 'PruebasUnitarias',
            'email' => 'unitarias@gmail.com',
            'password' => Hash::make(12345678)
        ]);

        // Asegurarse de que el usuario se haya creado correctamente en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'PruebasUnitarias'
        ]);
    }

    public function test_create_publicacion(){
        // Crear un usuario para la prueba
        $user = User::factory()->create([
            'username' => 'UsuarioCrearPublicacion',
            'email' => 'publicacion@gmail.com',
            'password' => Hash::make(12345678)
        ]);

        // Simular un usuario autenticado
        $this->actingAs($user);

        // Datos de la publicación a almacenar
        $publicacionData = [
            'texto' => 'Contenido de prueba',
            'id_usuario' => $user->id, // Asignar el ID del usuario a la publicación
        ];

        // Hacer una solicitud POST para almacenar la publicación
        $response = $this->postJson(route('publicaciones.store'), $publicacionData);

        // Verificar que la publicación se haya almacenado correctamente
        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
        $this->assertDatabaseHas('publicacions', ['texto' => 'Contenido de prueba']);
    }

    public function test_delete_publicacion(){
        // Crear una publicación para la prueba
        $publicacion = Publicacion::factory()->create([
            'texto' => $this->faker->sentence,
            'id_usuario' => User::factory()->create([
            'username' => 'UsuarioPublicacionDestroy'
            ])->id, // Asociar la publicación con un usuario al crearla
        ]);

        // Hacer una solicitud DELETE para eliminar la publicación
        $response = $this->deleteJson(route('publicaciones.destroy', $publicacion->id));


        // Verificar que la publicación se haya eliminado correctamente
        $response->assertNoContent();
        $this->assertDatabaseMissing('publicacions', ['id' => $publicacion->id]);
    }

    public function test_like_publicacion(){
        // Crear un usuario para la prueba
        $user = User::factory()->create([
            'username' => 'UsuarioDarLike',
            'email' => 'like@gmail.com',
            'password' => Hash::make(12345678)
        ]);

        // Crear una publicación para la prueba
        $publicacion = Publicacion::factory()->create([
            'texto' => $this->faker->sentence,
            'id_usuario' => User::factory()->create([
            'username' => 'UsuarioObtieneLike'
            ])->id, // Asociar la publicación con un usuario al crearla
            'rotation' => 2,
            'position' => 1
        ]);

        // Simular un usuario autenticado
        $this->actingAs($user);

        // Hacer una solicitud POST para dar like a la publicación
        $response = $this->postJson(route('publicaciones.like', $publicacion->id));

        // Verificar que el like se haya agregado correctamente
        $response->assertStatus(200)
                 ->assertJson(['liked' => true]);
        $this->assertTrue($publicacion->likes->contains($user));
    }



    // ERRORES -------------------------------------------------------------------------------

        /**
     * A basic feature FAILED test example.
     */
    public function test_create_failed_user()
    {
        $user = User::factory()->create([
            'username' => $this->faker->unique()->userName() ?? 'default_username' // Si no se proporciona ningún nombre de usuario, utiliza 'default_username'
        ]);
    
        // Autenticar al usuario
        $this->actingAs($user);
    
        // Intentar crear un usuario sin especificar el nombre de usuario
        $response = $this->postJson(route('users.store'), [
            'username' => '', // Valor vacío para provocar un fallo de validación
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
    
        // Verificar que la respuesta sea 422 Unprocessable Entity, indicando una falla de validación
        $response->assertStatus(422);
    
        // Verificar que la respuesta contenga el mensaje de error esperado
        $response->assertJson([
            'message' => 'The name field is required.'
        ]);
    }

    // public function test_create_failed_publicacion(){
    //     // Crear un usuario para la prueba
    //     $user = User::factory()->create([
    //         'username' => $this->faker->unique()->userName() ?? 'default_username' // Si no se proporciona ningún nombre de usuario, utiliza 'default_username'
    //     ]);

    //     // Simular un usuario autenticado
    //     $this->actingAs($user);

    //     // Intentar crear una publicación sin especificar el texto
    //     $response = $this->postJson(route('publicaciones.store'), [
    //         //'texto' => 'Texto pruebas', // Valor vacío para provocar un fallo de validación
    //     ]);

    //     // Verificar que la respuesta sea 422 Unprocessable Entity, indicando una falla de validación
    //     $response->assertStatus(422);

    //     // Verificar que la respuesta contenga el mensaje de error esperado
    //     $response->assertJson([
    //         'message' => 'The texto field is required.'
    //     ]);
    // }

    
    


    
    

    
}
