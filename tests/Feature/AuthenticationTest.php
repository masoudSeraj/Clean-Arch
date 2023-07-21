<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $password = 'random';
        $data = ['name' => 'masoud', 'email' => 'masoud.seraj.1991@gmail.com', 'password' => $password, 'password_confirmation' => $password];

        $response = $this->postJson(route('register'), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'masoud']);
    }

    public function test_user_can_login()
    {
        $password = 'random';
        $user = User::factory()->state(['password' => $password])->create();
        // dd($user);
        $data = ['password' => $password, 'name' => $user->name];
        $response = $this->postJson(route('login'), $data);
        $response->assertOk();
        $this->assertAuthenticated();
    }
}
