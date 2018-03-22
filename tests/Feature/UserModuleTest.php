<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Joel'
        ]);

        factory(User::class)->create([
            'name' => 'Ellie'
        ]);
        //$this->withoutExceptionHandling();
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de Usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test  */
    public function it_shows_a_default_message_if_the_users_list_is_empty()
    {
        // DB::table('users')->truncate();
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('No hay Usuarios Registrados');
    }

    /** @test  */
    public function it_displays_the_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Sender Flores'
        ]);

        $this->get('/usuarios/' . $user->id)
            ->assertStatus(200)
            ->assertSee('Sender Flores');
    }

    /** @test  */
    public function it_displays_a_404_error_if_the_user_is_not_found()
    {
        $this->get('/usuarios/999')
            ->assertStatus(404)
            ->assertSee('Página no Econtrada!!');
    }

    /** @test  */
    public function it_loads_the_new_users_page()
    {
        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear Nuevo Usuario');
    }

    /** @test  */
    public function it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '1234567'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '1234567'
        ]);

        /** Cuando no se maneja helper bcrypt */
        /* $this->assertDatabaseHas('users', [
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '123456'
         ]);*/
    }

    /** @test  */
    public function the_name_is_required()
    {
        //$this->withoutExceptionHandling();
        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => '',
            'email' => 'safcrace@gmail.com',
            'password' => '123456'
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());

        //Verificacion en Base de Datos
        /* $this->assertDatabaseMissing('users', [
            'email' => 'safcrace@gmail.com'
        ]); */
    }

    /** @test  */
    public function the_email_is_required()
    {
        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => '',
            'password' => '123456'
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['email']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'name' => 'Sender Flores'
        ]);
    }

    /** @test  */
    public function the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => 'correo-no-valido',
            'password' => '123456'
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */
    public function the_email_must_be_unique()
    {
        factory(User::class)->create([
            'email' => 'safcrace@gmail.com'
        ]);

        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '123456'
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());
    }

    /** @test  */
    public function the_password_is_required()
    {
        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => ''
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['password']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'name' => 'Sender Flores'
        ]);
    }

    /** @test  */
    public function the_password_must_be_six_character()
    {
        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '12345'
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['password']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */
    public function it_loads_the_edit_users_page()
    {
        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar Usuario')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id == $user->id;
            });
    }

    /** @test  */
    public function it_updates_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->put("/usuarios/{$user->id}", [
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '1234567'
        ])->assertRedirect("/usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '1234567'
        ]);
    }

    /** @test */
    public function the_name_is_required_when_updating_a_user()
    {
        $user = factory(User::class)->create();

        //$this->withoutExceptionHandling();
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => '',
                'email' => 'safcrace@gmail.com',
                'password' => '1234567'
        ])->assertRedirect("usuarios/{$user->id}/editar")
          ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', [
            'email' => 'safcrace@gmail.com'
        ]);
    }

    public function the_email_is_required_when_updating_the_user()
    {
        $this->from('usuarios/nuevo')->post('/usuarios/', [
            'name' => 'Sender Flores',
            'email' => '',
            'password' => '123456'
        ])->assertRedirect('usuarios/nuevo')
        ->assertSessionHasErrors(['email']);

        // $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'name' => 'Sender Flores'
        ]);
    }

    /** @test  */
    public function the_email_must_be_valid_when_updating_the_user()
    {
        $user = factory(User::class)->create(['name' => 'Nombre Inicial']);

        //$this->withoutExceptionHandling();
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Nombre Actualizado',
                'email' => 'correo-no-valido',
                'password' => '1234567'
        ])->assertRedirect("usuarios/{$user->id}/editar")
          ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', [
            'name' => 'Nombre Actualizado'
        ]);
    }

    /** @test  */
    public function the_email_must_be_unique_when_updating_the_user()
    {
        self::markTestIncomplete();
        return;

        $user = factory(User::class)->create([
            'email' => 'safcrace@gmail.com'
        ]);

        //$this->withoutExceptionHandling();
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Sender Flores',
                'email' => 'safcrace@gmail.com',
                'password' => '1234567'
        ])->assertRedirect("usuarios/{$user->id}/editar")
          ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', [
            'email' => 'safcrace@gmail.com'
        ]);
    }

    /** @test  */
    public function the_password_is_optional_when_updating_the_user()
    {
        $oldPassword = 'CLAVE_ANTERIOR';
        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword)
        ]);

        //$this->withoutExceptionHandling();
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Sender Flores',
                'email' => 'safcrace@gmail.com',
                'password' => ''
        ])->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => $oldPassword
        ]);
    }

    /** @test  */
    public function the_password_must_be_six_character_when_updating_the_user()
    {
        $user = factory(User::class)->create();

        //$this->withoutExceptionHandling();
        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Sender Flores',
                'email' => 'safcrace@gmail.com',
                'password' => '123456'
        ])->assertRedirect("usuarios/{$user->id}/editar")
          ->assertSessionHasErrors(['password']);

        $this->assertDatabaseMissing('users', [
            'email' => 'safcrace@gmail.com'
        ]);
    }
}
