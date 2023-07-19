<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $response = $this->postJson(route('register', $data));
        // $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'masoud']);
    }

    public function test_user_can_login()
    {
        $password = Hash::make('random');
        $user = User::factory()->state(['password' => $password])->create();
        // dd($user);
        $data = ['password' => $password, 'email' => $user->email];
        $response = $this->postJson(route('login'), $data);
        $response->assertOk();
        $this->assertAuthenticated();
    }
}
