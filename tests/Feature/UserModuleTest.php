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
            'password' => '123456'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Sender Flores',
            'email' => 'safcrace@gmail.com',
            'password' => '123456'
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
}
