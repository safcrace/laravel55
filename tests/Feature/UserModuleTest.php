<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserModuleTest extends TestCase
{
    /** @test  */
    public function it_shows_the_users_list()
    {
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
        $this->get('/usuarios?empty')
            ->assertStatus(200)
            ->assertSee('No hay Usuarios Registrados');
    }

    /** @test  */
    public function it_loads_the_users_details_page()
    {
        $this->get('/usuarios/5')
            ->assertStatus(200)
            ->assertSee('Mostrando detalle del usuario: 5');
    }

    /** @test  */
    public function it_loads_the_new_users_page()
    {
        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Creando Nuevo Usuario');
    }
}
