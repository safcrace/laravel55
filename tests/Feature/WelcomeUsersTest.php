<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /** @test */
    function it_welcomes_users_with_nickname()
    {
        $this->get('saludo/sender/safcrace')
            ->assertStatus(200)
            ->assertSee('Bienvenido Sender, tu apodo es safcrace');
    }
    
    /** @test */
    function it_welcomes_users_without_nickname()
    {
        $this->get('saludo/sender')
            ->assertStatus(200)
            ->assertSee('Bienvenido Sender');
    }
}
